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
        $proximos7Dias = [];

        for ($i = 0; $i < 7; $i++) {
            $fecha = now()->addDays($i);
            $proximos7Dias[] = [
                'dia' => $fecha->translatedFormat('D'), // Lun, Mar...
                'fecha' => $fecha->format('d/m'),
                'is_today' => $i == 0,
                'cant' => Consulta::where('genera_reposo', 1)
                          ->where('reincorporado', 0)
                          ->whereRaw("CAST(DATEADD(day, dias_reposo, fecha_consulta) AS DATE) = ?", [$fecha->format('Y-m-d')])
                          ->count() + 
                          Paciente::where('de_vacaciones', 1)
                             ->whereDate('fecha_retorno_vacaciones', $fecha->format('Y-m-d'))->count()
            ];
        }

        // 1. Datos para la Barra de Progreso

        // 1. Pacientes que regresan hoy de reposo
        // Calculamos la fecha de consulta + días de reposo
        $retornoReposo = Consulta::with('paciente')
            ->where('genera_reposo', 1)
            ->where('reincorporado', 0)
            ->whereRaw("CAST(DATEADD(day, dias_reposo, fecha_consulta) AS DATE) <= ?", [$hoy])
            ->get();

        // 2. Pacientes para chequeo Post-Vacacional
        $retornoVacaciones = Paciente::where('de_vacaciones', 1)
            ->whereDate('fecha_retorno_vacaciones', '<=' ,$hoy)
            ->get();

        // 1. Datos para la Barra de Progreso
        $totalPendientes = $retornoReposo->count() + $retornoVacaciones->count();
        // Ojo: Aquí debemos contar los que ya se hicieron hoy (fast track o normal)
        $procesadosHoy = Consulta::whereDate('fecha_consulta', now())->whereIn('diagnostico_cie10', ['Z02.7'])->count(); 
        $totalHoy = $totalPendientes + $procesadosHoy;
        $porcentajeAvance = $totalHoy > 0 ? ($procesadosHoy / $totalHoy) * 100 : 0;

        return view('MedicinaOcupacional.alertas.index', compact('retornoReposo', 'retornoVacaciones', 'hoy', 'proximos7Dias','porcentajeAvance', 'procesadosHoy', 'totalHoy' ));
    }
}
