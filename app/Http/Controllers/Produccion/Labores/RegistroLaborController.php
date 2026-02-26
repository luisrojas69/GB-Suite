<?php

namespace App\Http\Controllers\Produccion\Labores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produccion\Labores\RegistroLabor;
use App\Models\Logistica\Taller\Activo;
use App\Models\Produccion\Areas\Tablon;
use App\Models\Produccion\Areas\Sector;
use App\Models\Produccion\Agro\Zafra;
use App\Models\Produccion\Labores\LaborCritica;
use App\Models\MedicinaOcupacional\Paciente;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use Illuminate\Support\Facades\DB;

class RegistroLaborController extends Controller
{

    public function index(Request $request)
    {
        // Query principal cargando la jerarquía: Registro -> Tablones (pivote) -> Lote -> Sector
        $query = RegistroLabor::with(['labor', 'tablones.lote.sector'])
            ->when($request->sector_id, function($q) use ($request) {
                return $q->whereHas('tablones.lote', function($sq) use ($request) {
                    $sq->where('sector_id', $request->sector_id);
                });
            })
            ->when($request->fecha_inicio, function($q) use ($request) {
                return $q->whereDate('fecha_ejecucion', '>=', $request->fecha_inicio);
            });

        // KPI: Suma de hectáreas logradas en el mes (desde la tabla pivote)
        $kpiHectareas = DB::table('labor_tablon_detalle')
            ->join('registro_labores', 'labor_tablon_detalle.registro_labor_id', '=', 'registro_labores.id')
            ->whereMonth('registro_labores.fecha_ejecucion', now()->month)
            ->whereYear('registro_labores.fecha_ejecucion', now()->year)
            ->sum('hectareas_logradas');

        // Datos Gráfico: Agrupamos por el nombre del Sector
        $datosGrafico = DB::table('labor_tablon_detalle')
            ->join('tablones', 'labor_tablon_detalle.tablon_id', '=', 'tablones.id')
            ->join('lotes', 'tablones.lote_id', '=', 'lotes.id')
            ->join('sectores', 'lotes.sector_id', '=', 'sectores.id')
            ->select('sectores.nombre as sector', DB::raw('SUM(labor_tablon_detalle.hectareas_logradas) as total'))
            ->groupBy('sectores.nombre')
            ->get();

        $registros = $query->orderBy('fecha_ejecucion', 'desc')->paginate(15);
        $sectores = Sector::all(['id', 'nombre']);

        return view('produccion.labores.index', compact('registros', 'sectores', 'kpiHectareas', 'datosGrafico'));
    }

    public function create()
    {
        $zafraActiva = Zafra::where('estado', 'Activa')->first();
        // 1. Traemos sectores con el select de su propia geometría + relaciones
        $sectores = Sector::addSelect(['*', DB::raw('geometria.STAsText() as geometria_wkt')])
            ->with(['lotes.tablones' => function($q) {
                $q->select('tablones.*')
                  ->addSelect(DB::raw('geometria.STAsText() as geometria_wkt'))
                  ->whereNotNull('geometria');
            }])->get();

        // 2. Procesamos la colección
        $sectores->each(function($sector) {
            // PROCESAR GEOMETRÍA DEL SECTOR
            if ($sector->geometria_wkt) {
                try {
                    $sector->geometria_json = Polygon::fromWkt($sector->geometria_wkt)->toJson();
                } catch (\Exception $e) {
                    $sector->geometria_json = null;
                }
            }
            unset($sector->geometria); // Limpiar binario

            // PROCESAR GEOMETRÍA DE LOS TABLONES
            foreach ($sector->lotes as $lote) {
                foreach ($lote->tablones as $tablon) {
                    if ($tablon->geometria_wkt) {
                        try {
                            $tablon->geometria_json = Polygon::fromWkt($tablon->geometria_wkt)->toJson();
                        } catch (\Exception $e) {
                            $tablon->geometria_json = null;
                        }
                    }
                    unset($tablon->geometria); // Limpiar binario
                }
            }
        });

        $labores = LaborCritica::all();
        
        $operadores = Paciente::where('status', 'A')
            ->where(function($q) {
                $q->where('des_depart', 'like', '%DPTO DE COSECHA%')
                  ->orWhere('des_depart', 'like', '%CAMPO%')
                  ->orWhere('des_cargo', 'like', '%OPERADOR%')
                  ->orWhere('des_cargo', 'like', '%TRACTORISTA%');
            })
            ->orderBy('nombre_completo')->get();

        $activos = Activo::whereIn('tipo', ['Tractor', 'Cosechadora', 'Implemento', 'Camión'])
            ->select('id', 'nombre', 'lectura_actual', 'codigo') 
            ->get();

        return view('produccion.labores.create', compact('sectores', 'labores', 'operadores', 'activos','zafraActiva'));
    }

   public function store(Request $request)
    {
        //dd($request);

        // 1. Validación dinámica
        $rules = [
            'labor_id' => 'required|exists:cat_labores_criticas,id',
            'zafra_id' => 'required|exists:zafras,id',
            'fecha' => 'required|date',
            'tablon_ids' => 'required|array|min:1',
            'observaciones' => 'nullable|string',
        ];

        // Si vienen maquinarias, validarlas
        if ($request->has('maquinarias')) {
            $rules['maquinarias'] = 'required|array';
            $rules['maquinarias.*.id'] = 'required|exists:activos,id';
            $rules['maquinarias.*.h_ini'] = 'required|numeric';
            $rules['maquinarias.*.h_fin'] = 'required|numeric|gte:maquinarias.*.h_ini';
        }

        $request->validate($rules);

        try {
            return \DB::transaction(function () use ($request) {
                
                // 2. Crear la Cabecera
                $registro = RegistroLabor::create([
                    'labor_id'           => $request->labor_id,
                    'contratista_id'     => $request->zafra_id,
                    'zafra_id'           => $request->zafra_id,
                    'fecha_ejecucion'    => $request->fecha,
                    'tipo_ejecutor'      => ($request->origen_personal == 'outsourcing') ? 'Contratista' : 'Propio',
                    'contratista_nombre' => $request->contratista_nombre,
                    'observaciones' => $request->observaciones,
                ]);

                // 3. Registrar Maquinarias (Solo si existen en el request)
                if ($request->has('maquinarias')) {
                    foreach ($request->maquinarias as $maqData) {
                        // 1. Obtener el activo para saber su lectura actual en DB
                        $activo = Activo::findOrFail($maqData['id']);
                        $lecturaAnteriorDB = $activo->lectura_actual; // Ej: 1500

                        // 2. Calcular desfase (Lo que marcó el operador - Lo que decía la DB)
                        // Ej: 1502 - 1500 = 2 horas de traslado o uso no registrado
                        $desfase = $maqData['h_ini'] - $lecturaAnteriorDB;


                        // 3. Crear el detalle de la maquinaria
                        $registro->maquinarias()->create([
                            'activo_id'         => $maqData['id'],
                            'operador_id'       => $maqData['operador_id'],
                            'horometro_inicial' => $maqData['h_ini'],
                            'horometro_final'   => $maqData['h_fin'],
                            'horas_desfase_uso' => $desfase > 0 ? $desfase : 0, // Solo si es positivo
                        ]);

                         // 4. REGISTRO DE TRAZABILIDAD (Nueva entrada en lectura_activos)
                        // Esto asegura que el Jefe de Taller pueda ver qué pasó en el campo
                        \DB::table('lectura_activos')->insert([
                            'activo_id'      => $maqData['id'],
                            'fecha_lectura'  => $request->fecha,
                            'valor_lectura'  => $maqData['h_fin'],
                            'unidad_medida'  => $activo->unidad_medida ?? 'HRS', // KM o HRS
                            'registrador_id' => auth()->id(),
                            'observaciones'  => "Lectura por Labor: " . $registro->labor->nombre . " (Jornada #{$registro->id})",
                            'created_at'     => now(),
                            'updated_at'     => now(),
                        ]);

                        // 5. Actualizar el valor actual en el activo (Sincronización)
                        $activo->update(['lectura_actual' => $maqData['h_fin']]);
                    }
                }

                // 6. Registrar Tablones y Lógica de Negocio
                $laborCritica = LaborCritica::findOrFail($request->labor_id);

                foreach ($request->tablon_ids as $tablonId) {
                    // Usamos find para evitar que explote si hay basura en el request
                    $tablon = Tablon::find($tablonId);
                    if(!$tablon) continue;

                    // Guardar en la tabla detalle (Many-to-Many)
                    $registro->tablones()->attach($tablonId, [
                        'hectareas_logradas' => $tablon->hectareas_documento,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Lógica de estados del Tablón
                    if ($laborCritica->reinicia_ciclo) {
                        $tablon->estado = 'Preparacion';
                        $tablon->fecha_inicio_ciclo = $request->fecha;
                        if ($laborCritica->nombre == 'Cosecha') {
                            $tablon->numero_soca = $tablon->numero_soca + 1;
                        }
                        $tablon->save(); // Aquí es donde se dispara el boot/Lote find
                    } elseif ($tablon->estado == 'Preparacion') {
                        $tablon->estado = 'Crecimiento';
                        $tablon->save();
                    }
                }

            // Enviamos el ID de la consulta para que la vista sepa cuál imprimir
            return redirect()->route('produccion.labores.show', $registro->id)
                             ->with('success', 'Labor registrada exitosamente.');

            });

        } catch (\Exception $e) {
            //dd($e);
            DB::rollback();
            return back()->with('error', 'Error al guardar: ' . $e->getMessage())->withInput();
        }
    }

    public function getTablonesGeoJson()
    {
        // Usamos tu modelo tal cual, con sus relaciones
        $tablones = \App\Models\Produccion\Areas\Tablones::whereNotNull('geometria')->with(['lote.sector'])->get();

        $features = $tablones->map(function ($tablon) {
            $config = match ($tablon->estado) {
                'Preparacion' => ['color' => '#36b9cc', 'label' => 'Preparación'],
                'Crecimiento' => ['color' => '#1cc88a', 'label' => 'Crecimiento'],
                'Maduro'      => ['color' => '#f6c23e', 'label' => 'Maduro'],
                'Cosecha'     => ['color' => '#e74a3b', 'label' => 'Cosecha'],
                default       => ['color' => '#858796', 'label' => 'Inactivo'],
            };

            return [
                'type' => 'Feature',
                // AQUÍ LA COMPATIBILIDAD: Convertimos el objeto Polygon a un array de GeoJSON
                'geometry' => json_decode($tablon->geometria->toJson()), 
                'properties' => [
                    'id'           => $tablon->id,
                    'nombre'       => $tablon->nombre,
                    'codigo'       => $tablon->codigo_completo,
                    'estado'       => $config['label'],
                    'color_estado' => $config['color'],
                    'area'         => number_format($tablon->hectareas_documento, 2) . ' Ha',
                    'sector'       => $tablon->lote->sector->nombre,
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }


    public function show($id)
    {
        $registro = RegistroLabor::with(['labor', 'maquinarias.activo', 'maquinarias.operador', 'tablones' => function($query) {
            // Seleccionamos los campos del tablón y convertimos la geometría a texto (WKT)
            // Nota: Asegúrate de incluir 'tablones.id' y los campos necesarios para las relaciones
            $query->addSelect('*')
                  ->addSelect(\DB::raw('geometria.STAsText() as geometria_wkt'));
        }, 'tablones.lote.sector'])->findOrFail($id);

        // Procesamos cada tablón para convertir su WKT en un objeto Geometry que Leaflet entienda
        $registro->tablones->transform(function ($tablon) {
            if ($tablon->geometria_wkt) {
                try {
                    // Convertimos el WKT de SQL Server a un objeto Polygon
                    $tablon->geometria_render = \MatanYadaev\EloquentSpatial\Objects\Polygon::fromWkt($tablon->geometria_wkt);
                } catch (\Exception $e) {
                    \Log::error("Error en WKT de Tablón {$tablon->id}: " . $e->getMessage());
                    $tablon->geometria_render = null;
                }
            }
            return $tablon;
        });

        return view('produccion.labores.show', compact('registro'));
    }

}
