<?php

namespace App\Http\Controllers\MedicinaOcupacional;

use App\Http\Controllers\Controller;
use App\Models\MedicinaOcupacional\Paciente;
use App\Models\MedicinaOcupacional\Accidente;
use App\Models\MedicinaOcupacional\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use App\Exports\MedicinaOcupacional\Accidentes\AccidentesExport;

class AccidenteController extends Controller
{
    // Mostrar el formulario de registro para un paciente específico
    public function create($paciente_id)
    {
        $paciente = Paciente::findOrFail($paciente_id);
        
        //Si el paciente tiene una consulta abierta hoy, la sugerimos para vincular
        $consultaHoy = DB::table('med_consultas')
                        ->where('paciente_id', $paciente_id)
                        ->where('motivo_consulta', 'Accidente Laboral')
                        ->where('tiene_accidente_vinculado', false)
                        //->whereDate('fecha_consulta', today())
                        ->first();

        return view('MedicinaOcupacional.accidentes.create', compact('paciente', 'consultaHoy'));
    }

    public function store(Request $request)
    {
        // 1. Validar los datos
        $validatedData = $request->validate([
            'paciente_id'          => 'required',
            'fecha_hora_accidente' => 'required',
            'lugar_exacto'         => 'required',
            'tipo_evento'          => 'required',
            'horas_trabajadas'     => 'required',
            'parte_lesionada'      => 'required',
            'gravedad'             => 'required',
            'causas_inmediatas'    => 'required',
            'causas_raiz'          => 'required',
            'descripcion_relato'   => 'required',
            'lesion_detallada'     => 'required',
            'acciones_correctivas' => 'required',
            'consulta_id'          => 'nullable',
            'testigos'             => 'nullable',
        ]);

        try {
            // 2. CORRECCIÓN PARA SQL SERVER
            $validatedData['fecha_hora_accidente'] = \Carbon\Carbon::parse($request->fecha_hora_accidente)->format('Y-m-d H:i:s');
            
            // 3. ASIGNAR USUARIO ACTUAL
            $validatedData['user_id'] = auth()->id();

            // 4. CREAR EL REGISTRO (Una sola vez)
            $accidente = Accidente::create($validatedData);

            // 5. LÓGICA DE VÍNCULO CON CONSULTA
            if ($request->filled('consulta_id')) {
                $consulta = Consulta::find($request->consulta_id);
                if ($consulta) {
                    // Actualizamos la consulta para marcar que ya tiene su informe de accidente
                    $consulta->update(['tiene_accidente_vinculado' => true]);
                }
            }

            return redirect()->route('medicina.accidentes.show', $accidente->id)
                             ->with('success', 'Investigación de accidente registrada con éxito.');

        } catch (\Exception $e) {
            // Log para debug si es necesario: \Log::error($e->getMessage());
            return back()->with('error', 'Error al procesar el registro: ' . $e->getMessage())->withInput();
        }
    }

    // Listado general de accidentes para el Dashboard de Seguridad
    public function index()
    {   
        $hoy = now()->format('Y-m-d');
        $mes_actual = now()->month;
        $anio_actual = now()->year;

        
        // 2. Tendencia de Accidentes (Últimos 6 meses)
        // Usamos una colección para asegurar que los meses tengan nombres en español
        $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $tendenciaRaw = Accidente::selectRaw("MONTH(fecha_hora_accidente) as mes, COUNT(*) as total")
            ->where('fecha_hora_accidente', '>=', now()->subMonths(6))
            ->groupByRaw("MONTH(fecha_hora_accidente)")
            ->orderBy('mes')
            ->get();

        $labelsMeses = [];
        $dataValores = [];

        foreach ($tendenciaRaw as $t) {
            $labelsMeses[] = $mesesNombres[$t->mes - 1];
            $dataValores[] = $t->total;
        }

        // 3. Top 5 Pacientes con más accidentes en el mes
        $topPacientes = Accidente::with('paciente')
            ->select('paciente_id')
            ->selectRaw('COUNT(*) as total')
            //->whereMonth('fecha_hora_accidente', $mes_actual)
            ->whereYear('fecha_hora_accidente', $anio_actual)
            ->groupBy('paciente_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 1. Top 5 Lugares (Contar ocurrencias de diagnostico_cie10)
        $topLugares = Accidente::select('lugar_exacto')
            ->selectRaw('COUNT(*) as total')
            ->whereMonth('fecha_hora_accidente', $mes_actual)
            ->whereYear('fecha_hora_accidente', $anio_actual)
            ->groupBy('lugar_exacto')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $total_personal = Paciente::where('status', 'A')->count();


        $accidentes = Accidente::with('paciente', 'consulta')->orderBy('fecha_hora_accidente', 'desc')->get();
        return view('MedicinaOcupacional.accidentes.index', compact('accidentes','topLugares', 'topPacientes','topLugares', 'total_personal', 'labelsMeses', 'dataValores'));
    }


    public function show($id)
    {
        $accidente = Accidente::with(['paciente', 'user', 'consulta'])->findOrFail($id);
        return view('MedicinaOcupacional.accidentes.show', compact('accidente'));
    }

    public function edit($id)
    {
        $accidente = Accidente::with(['paciente', 'user', 'consulta'])->findOrFail($id);
        return view('MedicinaOcupacional.accidentes.show', compact('accidente'));
    }

    public function reporteInpsasel($id)
    {
        $accidente = Accidente::with(['paciente', 'user'])->findOrFail($id);
        
        return view('MedicinaOcupacional.accidentes.reporte_pdf', compact('accidente'));
    }


    public function exportar(Request $request) 
    {
        $desde = $request->get('desde');
        $hasta = $request->get('hasta');
        $gravedad = $request->get('gravedad');

        $nombreArchivo = "Accidentes_{$desde}_al_{$hasta}.xlsx";

        return (new AccidentesExport($desde, $hasta, $gravedad))->download($nombreArchivo);
    }
}