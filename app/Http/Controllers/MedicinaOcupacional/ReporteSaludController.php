<?php

namespace App\Http\Controllers\MedicinaOcupacional;

use App\Http\Controllers\Controller;
use App\Models\MedicinaOcupacional\Consulta;
use App\Models\MedicinaOcupacional\Accidente;
use App\Models\MedicinaOcupacional\Paciente;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;

class ReporteSaludController extends Controller
{
    // Reporte 1: Morbilidad Mensual (Por CIE-10)
    public function morbilidadMensual(Request $request)
    {
        $mes = $request->mes ?? now()->month;
        $anio = $request->anio ?? now()->year;

        $data = Consulta::select('diagnostico_cie10')
            ->selectRaw('COUNT(*) as total')
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->groupBy('diagnostico_cie10')
            ->orderByDesc('total')
            ->get();
            
        $pdf = Pdf::loadView('MedicinaOcupacional.reportes.pdf_morbilidad', [
            'data' => $data,
            'mes' => $mes,
            'anio' => $anio
        ]);

        return $pdf->stream('morbilidad-mensual.pdf');
    }


    public function reporteAccidentalidad()
    {
        // Accidentes por lugar en el año actual
        $data = Accidente::select('lugar_exacto')
            ->selectRaw('COUNT(*) as total')
            ->whereYear('fecha_hora_accidente', now()->year)
            ->groupBy('lugar_exacto')
            ->orderByDesc('total')
            ->get();

        $pdf = Pdf::loadView('MedicinaOcupacional.reportes.pdf_accidentalidad', compact('data'));
        return $pdf->stream('indice-accidentalidad.pdf');
    }


    public function reporteVigilancia()
    {
        $anioActual = now()->year;

        // 1. Distribución por Género
        $porGenero = Paciente::select('sexo')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('sexo')
            ->get();

        // 2. Distribución por Sistemas (Ejemplo de agrupación por las primeras letras del CIE-10)
        // En vigilancia epidemiológica se agrupan por patologías similares
        $porSistemas = Consulta::selectRaw("
                CASE 
                    WHEN diagnostico_cie10 LIKE 'M%' THEN 'Osteomuscular (Espalda/Huesos)'
                    WHEN diagnostico_cie10 LIKE 'J%' THEN 'Respiratorio (Gripe/Pulmón)'
                    WHEN diagnostico_cie10 LIKE 'I%' THEN 'Circulatorio (Hipertensión)'
                    WHEN diagnostico_cie10 LIKE 'S%' OR diagnostico_cie10 LIKE 'T%' THEN 'Traumatismos (Heridas/Fracturas)'
                    ELSE 'Otros Diagnósticos'
                END as sistema
            ")
            ->selectRaw('COUNT(*) as total')
            ->whereYear('created_at', $anioActual)
            ->groupByRaw("
                CASE 
                    WHEN diagnostico_cie10 LIKE 'M%' THEN 'Osteomuscular (Espalda/Huesos)'
                    WHEN diagnostico_cie10 LIKE 'J%' THEN 'Respiratorio (Gripe/Pulmón)'
                    WHEN diagnostico_cie10 LIKE 'I%' THEN 'Circulatorio (Hipertensión)'
                    WHEN diagnostico_cie10 LIKE 'S%' OR diagnostico_cie10 LIKE 'T%' THEN 'Traumatismos (Heridas/Fracturas)'
                    ELSE 'Otros Diagnósticos'
                END
            ")
            ->orderByDesc('total')
            ->get();

        $pdf = Pdf::loadView('MedicinaOcupacional.reportes.pdf_vigilancia', compact('porGenero', 'porSistemas', 'anioActual'));
        
        return $pdf->stream('vigilancia-epidemiologica-' . $anioActual . '.pdf');
    }
}