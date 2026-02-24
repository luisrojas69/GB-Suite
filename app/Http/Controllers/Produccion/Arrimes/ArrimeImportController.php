<?php

namespace App\Http\Controllers\Produccion\Arrimes;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Produccion\Areas\Tablon;
use App\Models\Logistica\Taller\Activo;
use App\Models\Produccion\Arrimes\BoletoArrime;
use App\Models\Produccion\Agro\Zafra;

class ArrimeImportController extends Controller
{
    
    public function index()
    {
        // 1. Obtenemos la zafra activa
        $zafraActiva = Zafra::where('estado', 'Activa')->first();
        
        if (!$zafraActiva) {
            return redirect()->back()->with('error', 'No hay una zafra activa configurada.');
        }

        // 2. Consultamos los boletos de la zafra actual
        $query = BoletoArrime::where('zafra_id', $zafraActiva->id)
                    ->with(['tablon.lote.sector', 'jaiba'])
                    ->orderBy('fecha_arrime', 'desc');

        $boletos = $query->get();

        // 3. Calculamos KPIs para las Cards
        $kpis = [
            'total_ton' => $boletos->sum('toneladas_netas'),
            'total_boletos' => $boletos->count(),
            'rendimiento_avg' => $boletos->avg('rendimiento_real') ?? 0,
            'ton_hoy' => $boletos->where('fecha_arrime', '>=', now()->startOfDay())->sum('toneladas_netas'),
        ];

        return view('produccion.arrimes.index', compact('boletos', 'kpis', 'zafraActiva'));
    }

    /**
     * Paso 1: Muestra la vista con el botón de subir archivo
     */
    public function importar()
    {
        return view('produccion.arrimes.importar');
    }

    /**
     * Paso 2: Lee el CSV, hace el cruce de datos y devuelve el "Purgatorio"
     */
    public function preview(Request $request)
    {
        $request->validate([
            'archivo_csv' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('archivo_csv');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        $headers = array_shift($csvData); // Extrae la primera fila (encabezados)

        $purgatorio = [];
        $zafraActiva = Zafra::where('estado', 'Activa')->first();

        foreach ($csvData as $index => $row) {
            //para que los dropdowns de corrección en el "purgatorio" funcionen.
            $todosLosTablones = Tablon::with('lote.sector')->get();
            // Combinamos los encabezados con los valores de la fila
            $data = array_combine($headers, $row);

            // 1. Lógica del Sector: Extraer el "06" de "00008-06"
            $rawSector = $data['Cod_Hacienda'] ?? '';
            $partesSector = explode('-', $rawSector);
            $codigoSectorLimpio = isset($partesSector[1]) ? $partesSector[1] : $rawSector;

            // 2. Búsqueda Inteligente del Tablón (Validación por Sector + Código Interno)
            $tablon = Tablon::where('codigo_tablon_interno', $data['Tablon'])
                ->whereHas('lote.sector', function($query) use ($codigoSectorLimpio) {
                    $query->where('codigo_sector', $codigoSectorLimpio);
                })->first();

            // 3. Búsqueda de Maquinaria (Jaiba)
            $jaiba = Activo::where('codigo', $data['Cod_Jaiba'])->first();

            // 4. Verificación de Duplicados
            $boletoExistente = BoletoArrime::where('boleto', $data['Boleto'])->first();

            // 5. Asignación de Colores (Status)
            $status = 'verde'; // Por defecto, todo bien
            $errores = [];

            if ($boletoExistente) {
                $status = 'amarillo';
                $errores[] = 'El boleto ya existe. Se actualizará el rendimiento (Liquidación).';
            }

            if (!$tablon) {
                $status = 'rojo';
                $errores[] = "No se encontró el Tablón {$data['Tablon']} en el Sector {$codigoSectorLimpio}.";
            }

            // Armamos el objeto para la tabla de pre-visualización
            $purgatorio[] = [
                'fila_excel' => $index + 2, // +2 por el index 0 y el encabezado
                'boleto' => $data['Boleto'],
                'remesa' => $data['Remesa'],
                'cod_hacienda_original' => $rawSector,
                'codigo_sector_limpio' => $codigoSectorLimpio,
                'tablon_csv' => $data['Tablon'],
                'tablon_id' => $tablon ? $tablon->id : null,
                'tablon_nombre_completo' => $tablon ? $tablon->codigo_completo : 'NO ENCONTRADO',
                'activo_jaiba_id' => $jaiba ? $jaiba->id : null,
                'fecha_quema' => $data['Fecha_Quema'],
                'fecha_arrime' => $data['Fecha_Arrime'],
                'toneladas_netas' => $data['Toneladas_Netas'],
                'rendimiento_real' => $data['Rendimiento'],
                'trash_porcentaje' => $data['Trash_Porc'],
                'id_chofer' => $data['ID_Chofer'] ?? null,
                'dia_zafra' => $data['Dia_Zafra'] ?? null,
                'status_color' => $status,
                'mensajes_error' => implode(' | ', $errores),
            ];
        }

        // Retornamos a la vista del purgatorio pasándole el array procesado
        return view('produccion.arrimes.purgatorio', compact('purgatorio', 'zafraActiva', 'todosLosTablones'));
    }

    /**
     * Paso 3: El Commit. Recibe los datos confirmados del frontend y guarda en BD.
     */

    public function process(Request $request)
{

    // Validamos que vengan datos
    if (!$request->has('data')) {
        return redirect()->route('arrimes.importar')->with('error', 'No hay datos para procesar.');
    }

    $insertados = 0;
    $actualizados = 0;
    $dataReporte = $request->input('data');
    $tablonesAsignados = $request->input('tablon_id', []);
    $tablonesCorregidos = $request->input('correccion_tablon', []);

    foreach ($dataReporte as $index => $item) {
        // Determinamos el ID del tablón: 
        // 1. Si el usuario corrigió manualmente, usamos ese.
        // 2. Si no, usamos el que el sistema detectó (si existe).
        $tablonId = $tablonesCorregidos[$index] ?? ($tablonesAsignados[$index] ?? null);

        // Si el registro es rojo y no se corrigió, lo saltamos
        if ($item['status_color'] == 'rojo' && empty($tablonesCorregidos[$index])) {
            continue;
        }

        // Guardado con updateOrCreate para evitar duplicados por número de boleto
        BoletoArrime::updateOrCreate(
            ['boleto' => $item['boleto']], // Llave única
            [
                'remesa' => $item['remesa'],
                    'cod_sector' => $item['cod_hacienda_original'],
                    'zafra_id' => $request->zafra_id, // Viene del formulario
                    'tablon_id' => $item['tablon_id'],
                    'central_id' => 1, // ID fijo temporal (Pastora) o dinámico desde la vista
                    'dia_zafra' => $item['dia_zafra'],
                    'activo_jaiba_id' => $item['activo_jaiba_id'],
                    'id_chofer' => $item['id_chofer'],
                    'toneladas_netas' => $item['toneladas_netas'],
                    'rendimiento_real' => $item['rendimiento_real'],
                    'trash_porcentaje' => $item['trash_porcentaje'],
                    'fecha_quema' => $item['fecha_quema'],
                    'fecha_arrime' => $item['fecha_arrime'],
                    // Si el boleto ya existía, pasará a Liquidado. Si es nuevo, Procesado.
                    'estado' => $item['status_color'] == 'amarillo' ? 'Liquidado' : 'Procesado',
            ]
        );

        if ($item['status_color'] == 'amarillo') {
            $actualizados++;
        } else {
            $insertados++;
        }
    }

    return redirect()->route('produccion.arrimes.index')->with('success', "¡Éxito! Se crearon $insertados registros y se actualizaron $actualizados.");
}



    public function processOld(Request $request)
    {
        dd($request->input());

        $datosConfirmados = json_decode($request->input('datos_purgatorio'), true);

        $insertados = 0;
        $actualizados = 0;

        foreach ($datosConfirmados as $item) {
            // Solo procesamos los que el usuario haya dejado en verde o amarillo
            if ($item['status_color'] == 'rojo') {
                continue; // Omitimos los errores graves no resueltos
            }

            BoletoArrime::updateOrCreate(
                ['boleto' => $item['boleto']], // Condición de búsqueda (Evita duplicados)
                [
                    'remesa' => $item['remesa'],
                    'cod_sector' => $item['cod_hacienda_original'],
                    'zafra_id' => $request->zafra_id, // Viene del formulario
                    'tablon_id' => $item['tablon_id'],
                    'central_id' => 1, // ID fijo temporal (Pastora) o dinámico desde la vista
                    'dia_zafra' => $item['dia_zafra'],
                    'activo_jaiba_id' => $item['activo_jaiba_id'],
                    'id_chofer' => $item['id_chofer'],
                    'toneladas_netas' => $item['toneladas_netas'],
                    'rendimiento_real' => $item['rendimiento_real'],
                    'trash_porcentaje' => $item['trash_porcentaje'],
                    'fecha_quema' => $item['fecha_quema'],
                    'fecha_arrime' => $item['fecha_arrime'],
                    // Si el boleto ya existía, pasará a Liquidado. Si es nuevo, Procesado.
                    'estado' => $item['status_color'] == 'amarillo' ? 'Liquidado' : 'Procesado',
                ]
            );

            if ($item['status_color'] == 'amarillo') {
                $actualizados++;
            } else {
                $insertados++;
            }
        }

        return redirect()->route('produccion.arrimes.index')->with('success', "Proceso completado: $insertados arrimes nuevos, $actualizados boletos liquidados/actualizados.");
    }
}