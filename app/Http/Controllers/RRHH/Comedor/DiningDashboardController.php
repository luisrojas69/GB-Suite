<?php

namespace App\Http\Controllers\RRHH\Comedor;

use App\Http\Controllers\Controller;
use App\Models\RRHH\Comedor\DiningRecord;
use App\Models\RRHH\Comedor\MealType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class DiningDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // 1. Estadísticas Rápidas (Cards)
        $stats = [
            'today_count' => DiningRecord::whereDate('punch_time', $today)->count(),
            'today_cost'  => DiningRecord::whereDate('punch_time', $today)->sum('cost'),
            'month_count' => DiningRecord::whereMonth('punch_time', $today->month)->count(),
            'month_cost'  => DiningRecord::whereMonth('punch_time', $today->month)->sum('cost'),
        ];

        // 2. Datos para Gráfico de Pastel (Distribución por Tipo de Comida - Mes Actual)
        $mealDistribution = DiningRecord::join('meal_types', 'dining_records.meal_type_id', '=', 'meal_types.id')
            ->whereMonth('punch_time', $today->month)
            ->select('meal_types.name', DB::raw('count(*) as total'))
            ->groupBy('meal_types.name')
            ->get();

        // 3. Datos para Gráfico de Líneas (Tendencia últimos 7 días)
        $lastSevenDays = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = DiningRecord::whereDate('punch_time', $date)->count();
            $lastSevenDays->push([
                'day' => $date->format('d/m'),
                'total' => $count
            ]);
        }

        // 4. Empleados vs Invitados (IDs > 99000 son invitados según acuerdo)
        $userTypeDist = [
            'empleados' => DiningRecord::where('employee_id', '<', 999)->whereMonth('punch_time', $today->month)->count(),
            'invitados' => DiningRecord::where('employee_id', '>=', 1000)->whereMonth('punch_time', $today->month)->count(),
        ];

        // 5. Consumo por Departamento (Mes Actual)
        $deptDistribution = DiningRecord::join('dining_employees', 'dining_records.employee_id', '=', 'dining_employees.biometric_id')
            ->whereMonth('punch_time', Carbon::now()->month)
            ->select('dining_employees.department', DB::raw('SUM(dining_records.cost) as total_cost'))
            ->groupBy('dining_employees.department')
            ->get();

        return view('RRHH.Comedor.dashboard.index', compact('stats', 'mealDistribution', 'lastSevenDays', 'userTypeDist', 'deptDistribution'));

    }
}