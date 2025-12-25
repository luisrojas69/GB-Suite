<?php

namespace App\Http\Controllers\MedicinaOcupacional;

use App\Http\Controllers\Controller;
use App\Models\MedicinaOcupacional\Paciente;
use App\Models\MedicinaOcupacional\Consulta;
use App\Models\MedicinaOcupacional\Accidente;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;

class CertificadoController extends Controller
{
    // 1. Certificado de Aptitud Física
    public function aptitud($paciente_id)
    {
        $paciente = Paciente::findOrFail($paciente_id);
        // Traemos la última consulta de tipo 'Examen Físico' o 'Pre-empleo'
        $ultimaConsulta = Consulta::where('paciente_id', $paciente_id)->latest()->first();

        $pdf = PDF::loadView('MedicinaOcupacional.certificados.aptitud', compact('paciente', 'ultimaConsulta'))
                  ->setPaper('letter')
                  ->setOption('margin-top', 10);
        
        return $pdf->inline('Certificado_Aptitud_'.$paciente->cedula.'.pdf');
    }

    // 2. Constancia de Asistencia (Sin diagnóstico por confidencialidad)
    public function constancia($consulta_id)
    {
        $consulta = Consulta::with('paciente')->findOrFail($consulta_id);
        
        $pdf = PDF::loadView('MedicinaOcupacional.certificados.constancia', compact('consulta'))
                  ->setPaper('a5', 'landscape'); // Formato pequeño tipo receta
        
        return $pdf->inline('Constancia_Asistencia.pdf');
    }

    // 3. Historial Epidemiológico Individual
    public function historial($paciente_id)
    {
        $paciente = Paciente::with(['consultas', 'accidentes'])->findOrFail($paciente_id);
        
        $pdf = PDF::loadView('MedicinaOcupacional.certificados.historial', compact('paciente'))
                  ->setPaper('letter');
        
        return $pdf->inline('Historial_Clinico_'.$paciente->cedula.'.pdf');
    }

    // 4. Entrega de EPP (Equipos de Protección Personal)
    public function entregaEpp($paciente_id)
    {
        $paciente = Paciente::findOrFail($paciente_id);
        
        $pdf = PDF::loadView('MedicinaOcupacional.certificados.entrega_epp', compact('paciente'));
        
        return $pdf->inline('Entrega_EPP.pdf');
    }
}