<?php

namespace App\Http\Controllers\Produccion\Areas;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Areas\Sector;
use App\Models\Produccion\Areas\Tablon;
use App\Models\Produccion\Areas\Lote;   
use App\Models\Produccion\Agro\Variedad; // <--- NUEVO: Importar el Modelo Variedad
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use Illuminate\Support\Facades\DB;


class TablonController extends Controller
{
    
    // ... index() se mantiene igual, salvo que se carga la relación 'variedad'
    public function index()
    {
        // Carga las relaciones 'lote', 'lote.sector' y AHORA 'variedad'
        $tablones = Tablon::with('lote.sector', 'variedad')->orderBy('codigo_completo')->get();
        return view('produccion.areas.tablones.index', compact('tablones'));
    }

    /**
     * Muestra el formulario para crear un nuevo tablón.
     */
    public function create()
    {   
        // Sectores (Se mantiene igual)
        $sectores = Sector::select('id', 'nombre', 'codigo_sector')
            ->addSelect(DB::raw('geometria.STAsText() as geometria_wkt'))
            ->get()
            ->map(function($s) {
                return [
                    'id' => $s->id,
                    'nombre' => $s->nombre,
                    'geometria' => $s->geometria_wkt ? Polygon::fromWkt($s->geometria_wkt)->toJson() : null
                ];
            });

        // Lotes y sus tablones vecinos
        $lotes = Lote::with([
            'sector' => function($q) {
                $q->addSelect('*')->addSelect(DB::raw('geometria.STAsText() as geometria_wkt'));
            },
            'sector.lotes.tablones' => function($q) {
                // Usamos table names para evitar ambigüedad en SQL Server
                $q->select('tablones.*')->addSelect(DB::raw('tablones.geometria.STAsText() as geometria_wkt'));
            }
        ])->get();

        $estados = ['Activo' => 'Activo', 'Inactivo' => 'Inactivo', 'Preparacion' => 'Preparación'];
        $variedades = Variedad::pluck('nombre', 'id'); 
        
       $lotes = $lotes->map(function($lote) {
            // 1. Procesar geometría del Sector
            if ($lote->sector) {
                $lote->sector->geometria_json = $lote->sector->geometria_wkt 
                    ? Polygon::fromWkt($lote->sector->geometria_wkt)->toJson() 
                    : null;
                
                // ELIMINAR EL BINARIO para evitar el error "Invalid spatial value"
                unset($lote->sector->geometria);

                // 2. Procesar tablones hermanos
                foreach ($lote->sector->lotes as $loteHermano) {
                    foreach ($loteHermano->tablones as $tablon) {
                        $tablon->geometria_json = $tablon->geometria_wkt 
                            ? Polygon::fromWkt($tablon->geometria_wkt)->toJson() 
                            : null;
                        
                        // ELIMINAR EL BINARIO aquí también
                        unset($tablon->geometria);
                    }
                    unset($loteHermano->geometria); // por si acaso
                }
            }
            unset($lote->geometria); // Limpiar el lote mismo
            return $lote;
        });

        return view('produccion.areas.tablones.create', compact('sectores', 'lotes', 'estados', 'variedades'));
    }



    // ... store() se mantiene igual en la lógica, solo se actualiza la validación
    public function store(Request $request)
    {
        $data = $request->all();

        $request->validate([
            'lote_id' => 'required|exists:lotes,id',
            'codigo_tablon_interno' => [
                'required',
                'string',
                'min:1', 
                'max:5', 
                'regex:/^[a-zA-Z0-9]+$/', 
                // Asegurar unicidad de codigo_interno dentro del lote seleccionado
                Rule::unique('tablones')->where(function ($query) use ($request) {
                    return $query->where('lote_id', $request->lote_id);
                }),
            ],
            'nombre' => 'required|string|max:100',
            'hectareas_documento' => 'required|numeric|min:0.01|max:99999.99', // CAMBIO DE NOMBRE: hectareas -> hectareas_documento
            'variedad_id' => 'nullable|exists:variedades,id', // NUEVO
            'meta_ton_ha' => 'nullable|numeric|min:0|max:99999.99', // NUEVO
            'tipo_suelo' => 'nullable|string|max:50',
            'estado' => 'required|in:Preparacion,Crecimiento,Maduro, Cosecha, Inactivo',
            'descripcion' => 'nullable|string',
        ], [
            'codigo_tablon_interno.unique' => 'Ya existe un tablón con ese código interno dentro del lote seleccionado.',
            'hectareas_documento.required' => 'El campo de Área en Hectáreas es obligatorio.',
        ]);

       if ($request->filled('geometria')) {
              $jsonGeom = $request->geometria; // Esto viene del mapa
              $polygon = Polygon::fromJson($jsonGeom);
              $wkt = $polygon->toWkt(); // Convierte a "POLYGON((...))"

              // Usamos DB::raw para que SQL Server reciba su función nativa
              // El 4326 es el SRID estándar para coordenadas WGS84 (GPS)
              $data['geometria'] = DB::raw("geometry::STGeomFromText('{$wkt}', 4326)");
          }

          Tablon::create($data);

        return redirect()->route('produccion.areas.tablones.index')
                         ->with('success', 'Tablón creado exitosamente, listo para sembrar!');
    }

    public function show($id)
    {
        $tablon = Tablon::with(['lote.sector', 'variedad'])
            ->select('*')
            ->addSelect(\DB::raw('geometria.STAsText() as geometria_wkt'))
            ->findOrFail($id);

        // Creamos una propiedad dinámica para no chocar con el accesor
        $tablon->geometria_objeto = null;

        if ($tablon->geometria_wkt) {
            try {
                $tablon->geometria_objeto = \MatanYadaev\EloquentSpatial\Objects\Polygon::fromWkt($tablon->geometria_wkt);
            } catch (\Exception $e) {
                \Log::error("Error al convertir WKT en Tablón {$id}: " . $e->getMessage());
            }
        }

        return view('produccion.areas.tablones.show', compact('tablon'));
    }


    /**
     * Muestra el formulario para editar un tablón existente.
     */
    public function edit($id)
    {
        // 1. Cargar el tablón actual con sus datos espaciales
        $tablon = Tablon::with(['lote.sector', 'variedad'])
            ->select('tablones.*')
            ->addSelect(DB::raw('geometria.STAsText() as geometria_wkt'))
            ->findOrFail($id);

        // 2. Cargar Lotes y sus tablones vecinos (Igual que en Create)
        $lotes = Lote::with([
            'sector' => function($q) {
                $q->addSelect('*')->addSelect(DB::raw('geometria.STAsText() as geometria_wkt'));
            },
            'sector.lotes.tablones' => function($q) use ($id) {
                // Traemos los vecinos pero EXCLUIMOS el tablón que estamos editando
                $q->where('id', '!=', $id)
                  ->select('tablones.*')
                  ->addSelect(DB::raw('tablones.geometria.STAsText() as geometria_wkt'));
            }
        ])->get()->map(function($lote) {
            if ($lote->sector) {
                $lote->sector->geometria_json = $lote->sector->geometria_wkt 
                    ? \MatanYadaev\EloquentSpatial\Objects\Polygon::fromWkt($lote->sector->geometria_wkt)->toJson() 
                    : null;
                unset($lote->sector->geometria);

                foreach ($lote->sector->lotes as $loteHermano) {
                    foreach ($loteHermano->tablones as $t) {
                        $t->geometria_json = $t->geometria_wkt 
                            ? \MatanYadaev\EloquentSpatial\Objects\Polygon::fromWkt($t->geometria_wkt)->toJson() 
                            : null;
                        unset($t->geometria);
                    }
                }
            }
            unset($lote->geometria);
            return $lote;
        });

        $estados = ['Activo' => 'Activo', 'Inactivo' => 'Inactivo', 'Preparacion' => 'Preparación'];
        $variedades = Variedad::pluck('nombre', 'id');

        return view('produccion.areas.tablones.edit', compact('tablon', 'lotes', 'variedades', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $tablon = Tablon::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $tablon->fill($request->except('geometria'));

        if ($request->has('geometria')) {
            if ($request->geometria) {
                $polygon = Polygon::fromJson($request->geometria);
                $wkt = $polygon->toWkt();
                // Usamos 4326 que es el estándar para mapas web (GPS)
                $tablon->geometria = DB::raw("geometry::STGeomFromText('{$wkt}', 4326).MakeValid()");
            } else {
                $tablon->geometria = null;
            }
        }

        $tablon->save();
        return redirect()->route('produccion.areas.tablones.show', $tablon->id)
                         ->with('success', 'Tablón actualizado correctamente.');
    }
    // ... destroy() se mantiene igual
    
}