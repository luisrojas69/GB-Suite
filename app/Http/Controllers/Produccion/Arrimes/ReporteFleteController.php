<?php

namespace App\Http\Controllers\Produccion\Arrimes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produccion\Arrimes\BoletoArrime;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Produccion\Arrimes\Fletes\PreLiquidacionExport;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF; 

class ReporteFleteController extends Controller
{
    public function preLiquidacion(Request $request)
    {
        $desde = $request->desde;
        $hasta = $request->hasta;
        $contratista_id = $request->contratista_id;

        // Query Maestro: Agrupamos por Contratista y Sector
        $data = BoletoArrime::query()
            ->join('tablones', 'boletos_arrime.tablon_id', '=', 'tablones.id')
            ->join('lotes', 'tablones.lote_id', '=', 'lotes.id')
            ->join('sectores', 'lotes.sector_id', '=', 'sectores.id')
            ->join('contratistas', 'boletos_arrime.contratista_id', '=', 'contratistas.id')
            ->select(
                'contratistas.nombre as contratista',
                'sectores.nombre as sector',
                'sectores.tarifa_flete as tarifa',
                \DB::raw('COUNT(boletos_arrime.id) as total_viajes'),
                \DB::raw('SUM(boletos_arrime.peso_neto) as total_toneladas'),
                \DB::raw('SUM(boletos_arrime.peso_neto * sectores.tarifa_flete) as subtotal_pagar')
            )
            ->whereBetween('boletos_arrime.fecha', [$desde, $hasta]);

        if ($contratista_id) {
            $data->where('boletos_arrime.contratista_id', $contratista_id);
        }

        $resultados = $data->groupBy('contratistas.nombre', 'sectores.nombre', 'sectores.tarifa_flete')
                           ->orderBy('contratistas.nombre')
                           ->get();

        // Si la petición es para descargar...
        if ($request->format == 'excel') {
            return Excel::download(new PreLiquidacionExport($resultados, $desde, $hasta), 'PreLiquidacion_Fletes.xlsx');
        }

        if ($request->format == 'pdf') {
            $pdf = Pdf::loadView('produccion.fletes.reportes.pdf_preliquidacion', compact('resultados', 'desde', 'hasta'));
            return $pdf->download('PreLiquidacion_Fletes.pdf');
        }

        return view('reportes.fletes.index_pre_liquidacion', compact('resultados'));
    }
}