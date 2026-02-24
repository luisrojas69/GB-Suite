<?php

namespace App\Http\Controllers\Produccion\Agro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produccion\Agro\PlanZafraDetalle;
use App\Models\Produccion\Areas\Tablon;
use App\Models\Produccion\Areas\Sector;
use App\Models\Produccion\Agro\Variedad;
use App\Models\Produccion\Agro\Zafra;
use Carbon\Carbon;

class RolMoliendaController extends Controller
{
    /**
     * Muestra el listado del plan de zafra actual (Dashboard)
     */
    public function index()
    {
        $zafraActiva = Zafra::where('estado', 'Activa')->first();

        if (!$zafraActiva) {
            return redirect()->back()->with('error', 'No hay una zafra activa.');
        }

        $planes = PlanZafraDetalle::where('zafra_id', $zafraActiva->id)
                    ->with(['tablon.lote.sector', 'variedad'])
                    ->get();

        $kpis = [
            'total_has' => $planes->sum('area_estimada_has'),
            'total_tons' => $planes->sum('toneladas_estimadas'),
            'rendimiento_avg' => $planes->avg('rendimiento_esperado') ?? 7.00,
            'tablones_planificados' => $planes->count()
        ];

        return view('produccion.agro.rol_molienda.index', compact('planes', 'kpis', 'zafraActiva'));
    }

    /**
     * Muestra el formulario de subida de archivo
     */
    public function importar()
    {
        return view('produccion.agro.rol_molienda.importar');
    }

    /**
     * Procesa el CSV, cruza los datos y envía al Purgatorio
     */
    public function preview(Request $request)
    {
        $request->validate([
            'archivo_csv' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('archivo_csv');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        $headers = array_shift($csvData);

        $purgatorio = [];
        $zafraActiva = Zafra::where('estado', 'Activa')->first();
        
        // Cargamos todas las variedades y tablones para los dropdowns de corrección en la vista
        $todasLasVariedades = Variedad::orderBy('nombre')->get();
        $todosLosTablones = Tablon::with('lote.sector')->get();

        foreach ($csvData as $index => $row) {
            if(count($headers) !== count($row)) continue; // Evita filas en blanco o mal formadas
            $data = array_combine($headers, $row);

            // 1. Limpieza de datos clave
            $nombreSectorCsv = trim($data['Sector'] ?? $data['Hacienda']);
            $codigoTablonCsv = trim($data['Tablon']);
            $nombreVariedadCsv = trim($data['Variedad']);

            // 2. Búsqueda de Sector (Por nombre, asumiendo que el Excel dice "Palo a Pique")
            $sector = Sector::where('nombre', 'LIKE', "%{$nombreSectorCsv}%")->first();

            // 3. Búsqueda de Tablón (Validando que pertenezca a ese sector)
            $tablon = null;
            if ($sector) {
                $tablon = Tablon::where('codigo_tablon_interno', $codigoTablonCsv)
                    ->whereHas('lote', function($q) use ($sector) {
                        $q->where('sector_id', $sector->id);
                    })->first();
            }

            // 4. Búsqueda de Variedad (Match exacto requerido)
            $variedad = Variedad::where('nombre', $nombreVariedadCsv)->first();

            // 5. Verificación de si ya existe un plan para este tablón en esta zafra
            $planExistente = null;
            if ($tablon) {
                $planExistente = PlanZafraDetalle::where('zafra_id', $zafraActiva->id)
                                    ->where('tablon_id', $tablon->id)->first();
            }

            // 6. Cálculos y asignación de Estado
            $hasEstimadas = floatval($data['Has'] ?? 0);
            $tonHaEstimadas = floatval($data['Tons Has'] ?? 0);
            $toneladasTotales = $hasEstimadas * $tonHaEstimadas;

            $errores = [];
            $status = 'verde';

            if ($planExistente) {
                $status = 'amarillo';
                $errores[] = 'El tablón ya tiene un plan. Se actualizarán los datos.';
            }

            if (!$tablon) {
                $status = 'rojo';
                $errores[] = "Tablón [{$codigoTablonCsv}] no encontrado en el sector [{$nombreSectorCsv}].";
            }

            if (!$variedad) {
                $status = 'rojo';
                $errores[] = "La variedad [{$nombreVariedadCsv}] no existe en la base de datos.";
            }

            // Formateo de fecha (Si viene en el CSV)
            $fechaCorte = !empty($data['Fecha Corte']) ? Carbon::parse($data['Fecha Corte'])->format('Y-m-d') : null;

            $purgatorio[] = [
                'fila_excel' => $index + 2,
                'sector_csv' => $nombreSectorCsv,
                'tablon_csv' => $codigoTablonCsv,
                'variedad_csv' => $nombreVariedadCsv,
                
                'tablon_id' => $tablon->id ?? null,
                'tablon_nombre_completo' => $tablon->codigo_completo ?? 'N/A',
                'variedad_id' => $variedad->id ?? null,
                'variedad_nombre' => $variedad->nombre ?? 'N/A',
                
                'clase_ciclo' => $data['Clase'] ?? 'Plantilla',
                'area_estimada_has' => $hasEstimadas,
                'ton_ha_estimadas' => $tonHaEstimadas,
                'toneladas_estimadas' => $toneladasTotales,
                'rendimiento_esperado' => floatval($data['Rend'] ?? 7.00),
                'fecha_corte_proyectada' => $fechaCorte,
                
                'status_color' => $status,
                'mensajes_error' => implode(' | ', $errores),
            ];
        }

        return view('produccion.agro.rol_molienda.purgatorio', compact('purgatorio', 'zafraActiva', 'todosLosTablones', 'todasLasVariedades'));
    }

    /**
     * Guarda la data definitiva desde el purgatorio
     */
    public function process(Request $request)
    {
        if (!$request->has('data')) {
            return redirect()->route('rol_molienda.importar')->with('error', 'No hay datos para procesar.');
        }

        $insertados = 0;
        $actualizados = 0;
        $dataReporte = $request->input('data');
        
        // Arrays de correcciones manuales hechas en la vista
        $tablonesCorregidos = $request->input('correccion_tablon', []);
        $variedadesCorregidas = $request->input('correccion_variedad', []);

        foreach ($dataReporte as $index => $item) {
            
            // Resolvemos los IDs definitivos
            $tablonId = $tablonesCorregidos[$index] ?? $item['tablon_id'];
            $variedadId = $variedadesCorregidas[$index] ?? $item['variedad_id'];

            // Si el status es rojo y el usuario no corrigió los campos faltantes, lo saltamos
            if ($item['status_color'] == 'rojo' && (empty($tablonId) || empty($variedadId))) {
                continue;
            }

            // updateOrCreate para evitar duplicidad de planificación del mismo tablón en la misma zafra
            PlanZafraDetalle::updateOrCreate(
                [
                    'zafra_id' => $request->zafra_id,
                    'tablon_id' => $tablonId
                ],
                [
                    'variedad_id' => $variedadId,
                    'clase_ciclo' => $item['clase_ciclo'],
                    'area_estimada_has' => $item['area_estimada_has'],
                    'ton_ha_estimadas' => $item['ton_ha_estimadas'],
                    'toneladas_estimadas' => $item['toneladas_estimadas'],
                    'rendimiento_esperado' => $item['rendimiento_esperado'],
                    'fecha_corte_proyectada' => $item['fecha_corte_proyectada'] ?: null,
                ]
            );

            if ($item['status_color'] == 'amarillo') {
                $actualizados++;
            } else {
                $insertados++;
            }
        }

        return redirect()->route('rol_molienda.index')
            ->with('success', "Rol de Molienda procesado: $insertados tablones planificados y $actualizados actualizados.");
    }
}