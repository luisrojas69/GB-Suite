<?php

namespace App\Http\Controllers\MedicinaOcupacional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicinaOcupacional\Consulta;
use App\Models\MedicinaOcupacional\Paciente;
use App\Models\MedicinaOcupacional\Accidente;
use App\Models\MedicinaOcupacional\Dotacion;

class DashboardMedicinaController extends Controller
{
    public function index() {
        $hoy = now()->format('Y-m-d');
        $mes_actual = now()->month;
        $anio_actual = now()->year;
    
        // 1. Top 5 Diagnósticos (Contar ocurrencias de diagnostico_cie10)
        $topDiagnosticos = Consulta::select('diagnostico_cie10')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('diagnostico_cie10')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 2. Tendencia de Consultas (Últimos 6 meses)
        // Usamos una colección para asegurar que los meses tengan nombres en español
        $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $tendenciaRaw = Consulta::selectRaw("MONTH(created_at) as mes, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw("MONTH(created_at)")
            ->orderBy('mes')
            ->get();

        $labelsMeses = [];
        $dataValores = [];

        foreach ($tendenciaRaw as $t) {
            $labelsMeses[] = $mesesNombres[$t->mes - 1];
            $dataValores[] = $t->total;
        }


        // 3. KPIs Rápidos
        $data['total_personal'] = Paciente::count();
        $data['consultas_mes'] = Consulta::whereMonth('created_at', $mes_actual)->count();
        $data['accidentes_mes'] = Accidente::whereMonth('fecha_hora_accidente', $mes_actual)->count();
        $data['dotaciones_mes'] = Dotacion::whereMonth('fecha_entrega', $mes_actual)->count();
        
        // 4. Alertas (Sintaxis SQL Server)
        $data['alertas_reposo'] = Consulta::where('genera_reposo', 1)
            ->whereRaw("CAST(DATEADD(day, dias_reposo, created_at) AS DATE) = ?", [$hoy])->count();
        $data['alertas_vacas'] = Paciente::whereDate('fecha_retorno_vacaciones', $hoy)->count();

        // 5. Tendencia de Consultas (Últimos 6 meses para la gráfica)
        $data['tendencia'] = Consulta::selectRaw("MONTH(created_at) as mes, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw("MONTH(created_at)")
            ->orderBy('mes')
            ->get();

        // 6. Top 5 Pacientes con más consultas en el mes
        $topPacientes = Consulta::with('paciente')
            ->select('paciente_id')
            ->selectRaw('COUNT(*) as total')
            ->whereMonth('created_at', $mes_actual)
            ->whereYear('created_at', $anio_actual)
            ->groupBy('paciente_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 7. Top 5 Lugares con más accidentes en el mes
        // Nota: Usamos 'lugar_exacto' o el campo que definiste para la ubicación
        $topLugares = Accidente::select('lugar_exacto')
            ->selectRaw('COUNT(*) as total')
            ->whereMonth('fecha_hora_accidente', $mes_actual)
            ->whereYear('fecha_hora_accidente', $anio_actual)
            ->groupBy('lugar_exacto')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('dashboards.medicina', $data , compact('topDiagnosticos', 'labelsMeses', 'dataValores', 'topPacientes', 'topLugares' ));
    }

    /**
     * Procesa la lógica para el Jefe de Taller / Mecánicos
     */
    private function dashboardTaller()
    {
        // Aquí irá la lógica de maquinaria (Tractores activos, horómetros próximos a servicio, etc.)
        
    }
}
