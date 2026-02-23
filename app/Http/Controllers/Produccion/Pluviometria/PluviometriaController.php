<?php

namespace App\Http\Controllers\Produccion\Pluviometria;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Pluviometria\RegistroPluviometrico;
use App\Models\Produccion\Areas\Sector;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\Produccion\Pluviometria\PluviometriaMatrizExport;
use App\Exports\Produccion\Pluviometria\PluviometriaPlanoExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;


class PluviometriaController extends Controller
{
   
    public function matrizIndex(Request $request)
    {
        Gate::authorize('produccion.pluviometria.ver');
        \Carbon\Carbon::setLocale('es');
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);
        $fechaInicio = \Carbon\Carbon::createFromDate($anio, $mes, 1);
        $diasDelMes = $fechaInicio->daysInMonth;
        $hoy = now();

        $sectores = Sector::orderBy('nombre', 'asc')->get();
        $totalSectores = $sectores->count();
        
        $queryRegistros = RegistroPluviometrico::whereMonth('fecha', $mes)
                            ->whereYear('fecha', $anio)
                            ->get();

        // --- LÓGICA DE LOS 4 CARDS ---
        
        // 1. Acumulado Mes
        $acumuladoMes = $queryRegistros->sum('cantidad_mm');

        // 2. Máximo Diario
        $maximaLluvia = $queryRegistros->max('cantidad_mm') ?? 0;
        $sectorMax = $queryRegistros->where('cantidad_mm', $maximaLluvia)->first();
        $nombreSectorMax = $sectorMax ? $sectorMax->sector->nombre : '---';

        // 3. Días con Lluvia (Frecuencia)
        // Contamos días únicos donde al menos un sector registró lluvia > 0
        $diasConLluvia = $queryRegistros->where('cantidad_mm', '>', 0)
                            ->groupBy(function($reg) {
                                return \Carbon\Carbon::parse($reg->fecha)->format('d');
                            })->count();

        // 4. % de Carga (Cumplimiento)
        // Determinamos cuántos días deben haberse cargado hasta hoy
        $diasAControlar = ($mes == $hoy->month && $anio == $hoy->year) ? $hoy->day : $diasDelMes;
        $totalEsperado = $totalSectores * $diasAControlar;
        $totalCargado = $queryRegistros->count();
        $porcentajeCarga = $totalEsperado > 0 ? round(($totalCargado / $totalEsperado) * 100) : 0;

        // Organizar registros para la matriz
        $registros = $queryRegistros->groupBy('id_sector')
                        ->map(function ($item) {
                            return $item->keyBy(function($reg) {
                                return \Carbon\Carbon::parse($reg->fecha)->day;
                            });
                        });

        return view('produccion.pluviometria.matriz', compact(
            'sectores', 'registros', 'diasDelMes', 'mes', 'anio', 'hoy', 'fechaInicio',
            'acumuladoMes', 'maximaLluvia', 'nombreSectorMax', 'diasConLluvia', 'porcentajeCarga'
        ));
    }

    public function guardarMasivo(Request $request)
    {
         Gate::authorize('produccion.pluviometria.crear');
        // 1. Validar que vengan datos
        $request->validate([
            'registros' => 'required|array',
            'registros.*.id_sector' => 'required|exists:sectores,id',
            'registros.*.fecha' => 'required|date',
            'registros.*.cantidad_mm' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->registros as $data) {
                // Usamos updateOrInsert para SQL Server (Lógica Upsert)
                DB::table('registros_pluviometricos')->updateOrInsert(
                    [
                        'id_sector' => $data['id_sector'],
                        'fecha'     => $data['fecha']
                    ],
                    [
                        'cantidad_mm'         => $data['cantidad_mm'],
                        'intensidad'          => $data['intensidad'] ?? $this->calcularIntensidad($data['cantidad_mm']),
                        'id_usuario_registro' => Auth::id(),
                        'updated_at'          => now(),
                        'created_at'          => now(), // Se ignora si es update en algunas versiones, pero es buena práctica
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Se han procesado ' . count($request->registros) . ' registros correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Error al procesar la carga: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lógica de negocio para determinar la intensidad basada en mm
     */
    private function calcularIntensidad($mm)
    {
        if ($mm == 0) return 'NULA';
        if ($mm < 10) return 'LIGERA';
        if ($mm < 30) return 'MODERADA';
        if ($mm < 60) return 'FUERTE';
        return 'TORRENCIAL';
    }


    //Funcion para exportar a excel

    public function exportar(Request $request) 
    {
        Gate::authorize('produccion.pluviometria.reportes');
        $request->validate([
            'desde' => 'required|date',
            'hasta' => 'required|date',
            'formato' => 'required|in:matriz,plano'
        ]);

        $desde = $request->desde;
        $hasta = $request->hasta;
        
        $nombreArchivo = "Reporte_Lluvia_" . Carbon::parse($desde)->format('d-m-Y') . "_al_" . Carbon::parse($hasta)->format('d-m-Y') . ".xlsx";

        if ($request->formato == 'matriz') {
            return Excel::download(new PluviometriaMatrizExport($desde, $hasta), $nombreArchivo);
        } 

        return Excel::download(new PluviometriaPlanoExport($desde, $hasta), $nombreArchivo);
    }

    //Dashboard
    public function dashboardOLD()
    {
        Gate::authorize('pluviometria.dashboard');
        \Carbon\Carbon::setLocale('es');
        $hoy = now();
        $mesActual = $hoy->month;
        $anioActual = $hoy->year;
        $anioActual = $hoy->year;
        $anioAnterior = $anioActual - 1;

        // 1. Datos para Gráfico de Tendencia (Días del mes actual)
        $diasMes = [];
        $totalesDia = [];
        $registrosMes = RegistroPluviometrico::whereMonth('fecha', $mesActual)
                        ->whereYear('fecha', $anioActual)
                        ->orderBy('fecha')
                        ->get()
                        ->groupBy('fecha');

        foreach($registrosMes as $fecha => $regs) {
            $diasMes[] = \Carbon\Carbon::parse($fecha)->format('d/m');
            $totalesDia[] = $regs->sum('cantidad_mm');
        }

        // 2. Datos para Gráfico por Sector (Acumulado Mes)
        $sectoresNombres = [];
        $sectoresValores = [];
        $porSector = RegistroPluviometrico::with('sector')
                        ->whereMonth('fecha', $mesActual)
                        ->whereYear('fecha', $anioActual)
                        ->get()
                        ->groupBy('id_sector');

        foreach($porSector as $id => $regs) {
            $sectoresNombres[] = $regs->first()->sector->nombre;
            $sectoresValores[] = $regs->sum('cantidad_mm');
        }

        // --- GRÁFICO 3: COMPARATIVO MENSUAL (Año Actual vs Anterior) ---
        $mesesLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $datosAnioActual = array_fill(0, 12, 0);
        $datosAnioAnterior = array_fill(0, 12, 0);

        // Registros año actual
        $regActual = RegistroPluviometrico::whereYear('fecha', $anioActual)->get();
        foreach($regActual as $r) {
            $mesIndex = \Carbon\Carbon::parse($r->fecha)->month - 1;
            $datosAnioActual[$mesIndex] += $r->cantidad_mm;
        }

        // Registros año anterior
        $regAnterior = RegistroPluviometrico::whereYear('fecha', $anioAnterior)->get();
        foreach($regAnterior as $r) {
            $mesIndex = \Carbon\Carbon::parse($r->fecha)->month - 1;
            $datosAnioAnterior[$mesIndex] += $r->cantidad_mm;
        }

        // --- GRÁFICO 4: FRECUENCIA DE PRECIPITACIÓN (Mes Actual) ---
        // Días con lluvia (>0.5mm para considerar lluvia efectiva) vs Días sin lluvia
        $diasMesActual = $hoy->daysInMonth;
        $diasConLluvia = RegistroPluviometrico::whereMonth('fecha', $hoy->month)
                            ->whereYear('fecha', $anioActual)
                            ->where('cantidad_mm', '>', 0.5)
                            ->distinct('fecha')
                            ->count();
        $diasSecos = $hoy->day - $diasConLluvia; // Basado en el día actual del mes

        // (Mantenemos la lógica de los gráficos anteriores: Tendencia y Sectores...)
        // ...

        return view('produccion.pluviometria.dashboardold', compact(
            'diasMes', 'totalesDia', 'sectoresNombres', 'sectoresValores',
            'mesesLabels', 'datosAnioActual', 'datosAnioAnterior',
            'diasConLluvia', 'diasSecos', 'anioActual', 'anioAnterior'
        ));
    }

    public function dashboard(Request $request)
    {
        Gate::authorize('pluviometria.dashboard');
        \Carbon\Carbon::setLocale('es');
        
        // 1. Manejo del Tiempo (Navegación)
        $anio = $request->input('anio', date('Y'));
        $mes = $request->input('mes', date('m'));
        
        $fechaConsulta = \Carbon\Carbon::create($anio, $mes, 1);
        $hoy = now();
        $anioActual = $fechaConsulta->year;
        $anioAnterior = $anioActual - 1;

        // 2. Cálculo de KPIs Superiores
        $acumuladoMes = RegistroPluviometrico::whereMonth('fecha', $mes)
                                             ->whereYear('fecha', $anioActual)
                                             ->sum('cantidad_mm');
                                             
        $maxRegistro = RegistroPluviometrico::with('sector')
                                            ->whereMonth('fecha', $mes)
                                            ->whereYear('fecha', $anioActual)
                                            ->orderByDesc('cantidad_mm')
                                            ->first();
                                            
        $maximaLluvia = $maxRegistro ? $maxRegistro->cantidad_mm : 0;
        $nombreSectorMax = $maxRegistro ? $maxRegistro->sector->nombre : 'Sin registros';

        $diasConLluvia = RegistroPluviometrico::whereMonth('fecha', $mes)
                                              ->whereYear('fecha', $anioActual)
                                              ->where('cantidad_mm', '>', 0.5)
                                              ->distinct('fecha')
                                              ->count();

        // Días secos: Si es el mes actual, restamos los días hasta hoy. Si es mes pasado, los días del mes.
        $diasEvaluados = ($mes == $hoy->month && $anioActual == $hoy->year) ? $hoy->day : $fechaConsulta->daysInMonth;
        $diasSecos = $diasEvaluados - $diasConLluvia;

        // Promedio por evento de lluvia
        $promedioLluvia = $diasConLluvia > 0 ? ($acumuladoMes / $diasConLluvia) : 0;

        // 3. Datos para Gráfico de Tendencia
        $diasMesLabels = [];
        $totalesDia = [];
        $registrosMes = RegistroPluviometrico::whereMonth('fecha', $mes)
                                             ->whereYear('fecha', $anioActual)
                                             ->orderBy('fecha')
                                             ->get()
                                             ->groupBy('fecha');

        foreach($registrosMes as $fecha => $regs) {
            $diasMesLabels[] = \Carbon\Carbon::parse($fecha)->format('d/m');
            $totalesDia[] = $regs->sum('cantidad_mm');
        }



        // 4. Datos para Gráfico por Sector
        $sectoresNombres = [];
        $sectoresValores = [];
        $porSector = RegistroPluviometrico::with('sector')
                                          ->whereMonth('fecha', $mes)
                                          ->whereYear('fecha', $anioActual)
                                          ->get()
                                          ->groupBy('id_sector');

        foreach($porSector as $id => $regs) {
            $sectoresNombres[] = $regs->first()->sector->nombre;
            $sectoresValores[] = $regs->sum('cantidad_mm');
        }

        // 5. Comparativo Mensual (Año Actual vs Anterior)
        $mesesLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $datosAnioActual = array_fill(0, 12, 0);
        $datosAnioAnterior = array_fill(0, 12, 0);

        $regActual = RegistroPluviometrico::whereYear('fecha', $anioActual)->get();
        foreach($regActual as $r) {
            $mesIndex = \Carbon\Carbon::parse($r->fecha)->month - 1;
            $datosAnioActual[$mesIndex] += $r->cantidad_mm;
        }

        $regAnterior = RegistroPluviometrico::whereYear('fecha', $anioAnterior)->get();
        foreach($regAnterior as $r) {
            $mesIndex = \Carbon\Carbon::parse($r->fecha)->month - 1;
            $datosAnioAnterior[$mesIndex] += $r->cantidad_mm;
        }

        return view('produccion.pluviometria.dashboard', compact(
            'fechaConsulta', 'acumuladoMes', 'maximaLluvia', 'nombreSectorMax', 'diasConLluvia', 'diasSecos', 'promedioLluvia',
            'diasMesLabels', 'totalesDia', 'sectoresNombres', 'sectoresValores',
            'mesesLabels', 'datosAnioActual', 'datosAnioAnterior', 'anioActual', 'anioAnterior'
        ));
    }
}