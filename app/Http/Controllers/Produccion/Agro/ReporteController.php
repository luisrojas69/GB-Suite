<?php

namespace App\Http\Controllers\Produccion\Agro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF; 
use App\Exports\Produccion\Agro\LaboresCriticasExport;
use App\Models\Produccion\Labores\LaborTablonDetalle;
use DB;
use App\Exports\Produccion\Agro\PlanVsRealExport;

class ReporteController extends Controller
{
    /**
     * Muestra la vista principal (Catálogo)
     */
    public function index()
    {
        return view('produccion.agro.reportes.index');
    }

    /**
     * Enrutador principal de descargas
     */
    public function exportar(Request $request, $reporte)
    {
        // 1. Recolectar Filtros
        $filtros = [
            'zafra_id'  => $request->input('zafra_id'),
            'sector_id' => $request->input('sector_id'),
            'desde'     => $request->input('fecha_desde'),
            'hasta'     => $request->input('fecha_hasta'),
        ];
        $tipo = $request->input('tipo_exportacion'); // 'pdf' o 'excel'

        // 2. Switch o Match para enrutar según el reporte solicitado
        switch ($reporte) {
            case 'labores_criticas':
                return $this->generarLaboresCriticas($filtros, $tipo);
                break;
                case 'molienda_comparativo':
                    if ($tipo === 'excel') {
                        return Excel::download(new PlanVsRealExport($filtros), 'Plan_vs_Real_Molienda.xlsx');
                    }
                    
                    $data = $this->obtenerDataComparativa($filtros);
                    $pdf = PDF::loadView('produccion.agro.reportes.pdf.plan_vs_real', compact('data', 'filtros'))
                              ->setPaper('a4', 'portrait');
                    return $pdf->stream('Plan_vs_Real.pdf');
                break;
            
            // Aquí agregarás los demás casos: 'molienda_comparativo', 'horas_maquina', etc.
            
            default:
                abort(404, 'Reporte no encontrado');
        }
    }

    /**
     * Lógica específica para el reporte de Labores Post-Cosecha
     */
    private function generarLaboresCriticas($filtros, $tipo)
    {
        if ($tipo === 'excel') {
            // Laravel-Excel maneja la consulta internamente en la clase Export
            return Excel::download(new LaboresCriticasExport($filtros), 'Labores_Post_Cosecha_' . date('Ymd_Hi') . '.xlsx');
        } 
        
        if ($tipo === 'pdf') {
            // Para el PDF, resolvemos la data aquí y se la pasamos a la vista
            $data = $this->obtenerDataLabores($filtros);


            
            // Opciones de Snappy para un acabado Agro Premium
            $pdf = PDF::loadView('produccion.agro.reportes.pdf.labores_criticas', compact('data', 'filtros'))
                      ->setPaper('a4')
                      ->setOrientation('landscape')
                      ->setOption('margin-bottom', 15)
                      ->setOption('footer-right', 'Página [page] de [topage]')
                      ->setOption('footer-left', 'Generado desde GB-SUITE el ' . now()->format('d/m/Y H:i'));

            return $pdf->stream('Labores_Post_Cosecha.pdf'); // Stream abre en pestaña nueva (target="_blank")
        }
    }

    /**
     * Query base reutilizable para PDF (El Export de Excel usa su propio Query builder)
     */
    private function obtenerDataLabores($filtros)
    {
        // Consultamos desde la tabla detalle para tener una fila por tablón
        return LaborTablonDetalle::with([
                'registro.labor', 
                'registro.maquinarias.activo',
                'registro.contratista',
                'tablon.lote.sector'
            ])
            ->whereHas('registro', function($q) use ($filtros) {
                $q->where('zafra_id', $filtros['zafra_id']);
                if ($filtros['desde'] && $filtros['hasta']) {
                    $q->whereBetween('fecha_ejecucion', [$filtros['desde'], $filtros['hasta']]);
                }
            })
            ->when($filtros['sector_id'] !== 'todos', function($q) use ($filtros) {
                $q->whereHas('tablon.lote', function($sq) use ($filtros) {
                    $sq->where('sector_id', $filtros['sector_id']);
                });
            })
            ->get();
    }


   private function obtenerDataComparativa($filtros) {
        return DB::table('rol_molienda as p')
            // Unimos con tablones, lotes y sectores para obtener los nombres
            ->join('tablones as t', 'p.tablon_id', '=', 't.id')
            ->join('lotes as l', 't.lote_id', '=', 'l.id')
            ->join('sectores as s', 'l.sector_id', '=', 's.id')
            // Unimos con el real (consolidado)
            ->leftJoin('molienda_ejecutada as r', function($join) {
                $join->on('p.tablon_id', '=', 'r.tablon_id')
                     ->on('p.zafra_id', '=', 'r.zafra_id');
            })
            ->select([
                's.nombre as sector_nombre',    // Ahora sí viene de la tabla sectores
                't.codigo_tablon_interno as tablon_codigo',    // Viene de tablones
                'p.toneladas_estimadas',
                'p.fecha_corte_proyectada',
                'p.rendimiento_esperado',
                'r.toneladas_reales',           // Viene de molienda_ejecutada
                'r.rendimiento_real_avg',
                'r.estado_cosecha'
            ])
            ->where('p.zafra_id', $filtros['zafra_id'])
            ->when($filtros['sector_id'] !== 'todos', function($q) use ($filtros) {
                return $q->where('s.id', $filtros['sector_id']);
            })
            ->get();
    }
}