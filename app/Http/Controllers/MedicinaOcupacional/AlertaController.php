<?php

namespace App\Http\Controllers\MedicinaOcupacional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicinaOcupacional\Consulta;
use App\Models\MedicinaOcupacional\Paciente;

class AlertaController extends Controller
{
    public function index()
    {
        $hoy = now()->format('Y-m-d');

        // 1. Pacientes que regresan hoy de reposo
        // Calculamos la fecha de consulta + días de reposo
        $retornoReposo = Consulta::with('paciente')
            ->where('genera_reposo', 1)
            ->whereRaw("CAST(DATEADD(day, dias_reposo, created_at) AS DATE) = ?", [$hoy])
            ->get();

        // 2. Pacientes para chequeo Post-Vacacional
        // (Asumiendo que agregamos fecha_retorno_vacaciones al paciente o en una tabla de eventos)
        $retornoVacaciones = Paciente::whereDate('fecha_retorno_vacaciones', $hoy)->get();

        return view('MedicinaOcupacional.alertas.index', compact('retornoReposo', 'retornoVacaciones', 'hoy'));
    }
}
