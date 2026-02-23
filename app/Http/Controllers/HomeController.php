<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MedicinaOcupacional\Paciente;
use App\Models\MedicinaOcupacional\Consulta;
use App\Models\MedicinaOcupacional\Accidente;
use App\Models\MedicinaOcupacional\Dotacion;

//Dashboradcomedor
use App\Models\RRHH\Comedor\DiningRecord;
use App\Models\RRHH\Comedor\MealType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

//DashboardPluviometria
use App\Models\Produccion\Pluviometria\RegistroPluviometrico;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexdefault()
    {
        $users = User::count();

        $widget = [
            'users' => $users,
            //...
        ];

        return view('home', compact('widget'));
    }

    public function index()
    {
        $user = Auth::user();

        // 1. Prioridad: Dashboard de Medicina y Seguridad Laboral
        if ($user->can('pluviometria.dashboard')) {
            return $this->dashboardPluviometria();
        }
        // 1. Prioridad: Dashboard de Medicina y Seguridad Laboral
        if ($user->can('medicina.dashboard')) {
            return $this->dashboardMedicina();
        }

        // 2. Prioridad: Dashboard de Taller / Maquinaria
        if ($user->can('comedor.dashboard')) {
            return $this->DashboardComedor();
        }

        // 3. Prioridad: Dashboard de Seguridad Laboral
        if ($user->can('pozos.dashboard')) {
            return $this->dashboardPozos();
        }

        // 3. Prioridad: Dashboard de Seguridad Laboral
        if ($user->can('taller.dashboard')) {
            return $this->dashboardMedicina();
        }

        $users = User::count();
        $pacientes = Paciente::count();
        $widget = [
            'users' => $users,
            'pacientes' => $pacientes,
            //...
        ];

        // 3. Dashboard por defecto (General o RRHH)
        return view('home', compact('widget'));
    }

    public function DashboardMedicina() {
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
        $tendenciaRaw = Consulta::selectRaw("MONTH(fecha_consulta) as mes, COUNT(*) as total")
            ->where('fecha_consulta', '>=', now()->subMonths(6))
            ->groupByRaw("MONTH(fecha_consulta)")
            ->orderBy('mes')
            ->get();

        $labelsMeses = [];
        $dataValores = [];

        foreach ($tendenciaRaw as $t) {
            $labelsMeses[] = $mesesNombres[$t->mes - 1];
            $dataValores[] = $t->total;
        }


        // 3. KPIs Rápidos
        $data['total_personal'] = Paciente::where('status', 'A')->count();
        $data['consultas_mes'] = Consulta::whereMonth('created_at', $mes_actual)->count();
        $data['accidentes_mes'] = Accidente::whereMonth('fecha_hora_accidente', $mes_actual)->count();
        $data['dotaciones_mes'] = Dotacion::whereMonth('fecha_entrega', $mes_actual)->count();
        
        // 4. Alertas (Sintaxis SQL Server)
        $data['alertas_reposo'] = Consulta::where('genera_reposo', 1)
            ->whereRaw("CAST(DATEADD(day, dias_reposo, created_at) AS DATE) = ?", [$hoy])->count();
        $data['alertas_vacas'] = Paciente::where('de_vacaciones', 1)
            ->whereDate('fecha_retorno_vacaciones', '<=' ,$hoy)
            ->count();

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

    public function DashboardComedor()
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

     public function dashboardPluviometria()
    {
        Gate::authorize('pluviometria.dashboard');
        \Carbon\Carbon::setLocale('es');
        
        // 1. Manejo del Tiempo (Navegación)
        $hoy = now()->format('Y-m-d');
        $mes = now()->month;
        $anio = now()->year;
        
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
