<?php

namespace App\Http\Controllers\Logistica\Taller;

use App\Http\Controllers\Controller;
use App\Models\Logistica\Taller\Activo;
use App\Models\Logistica\Taller\OrdenServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteGerencialController extends Controller
{
    /**
     * Muestra el panel de KPIs y reportes gerenciales.
     */
    public function index(Request $request)
    {
        // Solo consideramos órdenes finalizadas o facturadas para costos reales
        $servicios = OrdenServicio::whereIn('status', ['Cerrada','Finalizada', 'Facturada'])->get();
       // dd($servicios);
        $totalActivos = Activo::count();

        if ($servicios->isEmpty()) {
            // Si no hay data, informamos al usuario
            return view('taller.reportes.gerencial', [
                'kpis' => ['message' => 'No hay Órdenes de Servicio finalizadas para generar el reporte de KPIs.'],
            ]);
        }

        // --- CÁLCULO DE COSTOS Y PROPORCIONES ---
        
        $costoTotal = $servicios->sum('costo_total_servicio');
       // dd($servicios);
        $costoMP = $servicios->where('tipo_servicio', 'Preventivo')->sum('costo_total_servicio');
        $costoMC = $servicios->where('tipo_servicio', 'Correctivo')->sum('costo_total_servicio');

        $porcentajeMPvsMC = ($costoTotal > 0) ? round(($costoMP / $costoTotal) * 100, 2) : 0;
        $porcentajeMCvsMP = 100 - $porcentajeMPvsMC;

        // --- CÁLCULO DE COSTO POR UNIDAD DE USO ---
        
        // Sumamos el uso total registrado entre el inicio y el fin de cada servicio
        $usoTotalRegistrado = $servicios->sum(function ($orden) {
            // Asegura que lectura_final >= lectura_inicial
            if ($orden->lectura_final && $orden->lectura_inicial) {
                return max(0, $orden->lectura_final - $orden->lectura_inicial);
            }
            return 0;
        });
        
        $costoPorUso = ($usoTotalRegistrado > 0) ? round($costoTotal / $usoTotalRegistrado, 2) : 0;
        
        // --- CÁLCULO DE DISPONIBILIDAD (PROXY) ---
        
        $activosOperativos = Activo::where('estado_operativo', 'Operativo')->count();
        $disponibilidad = ($totalActivos > 0) ? round(($activosOperativos / $totalActivos) * 100, 2) : 0;

        // --- CÁLCULO DE MTTR (TIEMPO MEDIO DE REPARACIÓN) ---

        $correctivos = $servicios->where('tipo_servicio', 'Correctivo')->filter(function ($orden) {
            // Solo se usa si tiene fecha de inicio y fin para calcular la duración.
            return $orden->fecha_cierre_servicio && $orden->fecha_creacion;
        });
        
        $mttr = null;
        if ($correctivos->count() > 0) {
            $tiempoTotalReparacion = $correctivos->sum(function ($orden) {
                $inicio = Carbon::parse($orden->fecha_creacion);
                $fin = Carbon::parse($orden->fecha_cierre_servicio);
                return $fin->diffInHours($inicio); // Suma las horas que duró el servicio
            });
            $mttr = round($tiempoTotalReparacion / $correctivos->count(), 2);
        }


        $kpis = [
            'total_activos' => $totalActivos,
            'costo_total_flota' => $costoTotal,
            'costo_mp' => $costoMP,
            'costo_mc' => $costoMC,
            'porcentaje_mp_vs_mc' => $porcentajeMPvsMC,
            'porcentaje_mc_vs_mp' => $porcentajeMCvsMP,
            'uso_total_registrado' => $usoTotalRegistrado,
            'costo_por_uso_unidad' => $costoPorUso,
            'disponibilidad_flota' => $disponibilidad,
            'mttr_horas' => $mttr,
        ];

        return view('taller.reportes.gerencial', compact('kpis'));
    }
}