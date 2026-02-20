<?php

namespace App\Http\Controllers\MedicinaOcupacional;

use App\Http\Controllers\Controller;
use App\Models\MedicinaOcupacional\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use App\Exports\MedicinaOcupacional\Pacientes\PacientesExport;
use App\Exports\MedicinaOcupacional\Pacientes\TallasExport;
use Maatwebsite\Excel\Facades\Excel;

class PacienteController extends Controller
{
    public function index()
    {
        Gate::authorize('gestionar_pacientes');
        return view('MedicinaOcupacional.pacientes.index');
    }

    public function getListadoOld(Request $request)
    {
        //Antiguamente lo haciamos asi.. cuando la vista index no tenia cards.
        Gate::authorize('gestionar_pacientes');
        // Retornar datos para DataTables vía AJAX
        $pacientes = Paciente::all();
        return response()->json(['data' => $pacientes]);
    }

    public function getListado(Request $request)
    {
        Gate::authorize('gestionar_pacientes');

        // 1. Obtener la data para la tabla
        //$pacientes = Paciente::oldest('status')->get();
        $pacientes = Paciente::Where('status', 'A')->get();

        // 2. Cálculos eficientes usando agregaciones de Base de Datos
        
        // Total: Activos (A) + Vacaciones (V)
        $totalPacientes = Paciente::whereIn('status', ['A', 'V'])->count();

        // Críticos: Tienen algo escrito en enfermedades_base
        $totalCriticos = Paciente::whereNotNull('enfermedades_base')
            ->where('enfermedades_base', '<>', '')
            ->count();

        // Discapacidad: Campo discapacitado es "1"
        $totalDiscapacidad = Paciente::where('discapacitado', '1')->count();

        // Promedio de Edad: Calculado directamente en SQL para mayor velocidad
        // Nota: TIMESTAMPDIFF es para MySQL/MariaDB
       $promedioEdad = Paciente::whereNotNull('fecha_nac')
            ->selectRaw('AVG(DATEDIFF(YEAR, fecha_nac, GETDATE())) as promedio')
            ->value('promedio');

        // 3. Retornar todo en una sola respuesta JSON
        return response()->json([
            'data' => $pacientes,
            'total_pacientes' => $totalPacientes,
            'total_criticos' => $totalCriticos,
            'total_discapacidad' => $totalDiscapacidad,
            'promedio_edad' => round($promedioEdad, 1) // Un decimal es suficiente
        ]);
    }

    public function show($id)
    {
        Gate::authorize('gestionar_pacientes');
        $paciente = Paciente::with([
            'consultas' => fn($q) => $q->latest()->limit(5),
            'accidentes' => fn($q) => $q->latest()->limit(5),
            'dotaciones' => fn($q) => $q->latest()->limit(5)
        ])->findOrFail($id);

        // Calculamos algunos indicadores rápidos
        $stats = [
            'total_consultas' => $paciente->consultas()->count(),
            'dias_desde_accidente' => $paciente->accidentes()->latest('fecha_hora_accidente')->first() 
                ? now()->diffInDays($paciente->accidentes()->latest('fecha_hora_accidente')->first()->fecha_hora_accidente) 
                : '🥳 Sin Registros',
            'ultima_dotacion' => $paciente->dotaciones()->where('entregado_en_almacen', true)->latest()->first()
        ];

        return view('MedicinaOcupacional.pacientes.show', compact('paciente', 'stats'));
    }

    public function syncProfit()
    {
        Gate::authorize('gestionar_pacientes');
        try {
            Artisan::call('medicina:sync-pacientes');
            return response()->json([
                'icon' => 'success',
                'title' => 'Sincronización Exitosa',
                'text' => 'Los datos del personal han sido actualizados desde Profit.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Error de Conexión',
                'text' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        Gate::authorize('gestionar_pacientes');
        $paciente = Paciente::findOrFail($id);
        return response()->json($paciente);
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('gestionar_pacientes');
        $paciente = Paciente::findOrFail($id);
        
        $data = $request->all();
        $data['es_zurdo'] = $request->has('es_zurdo') ? 1 : 0;
        $data['discapacitado'] = $request->has('discapacitado') ? 1 : 0;

        // Llenamos el modelo con los nuevos datos pero SIN guardar aún
        $paciente->fill($data);

        // Si el modelo detecta que hubo cambios en cualquier campo...
        if ($paciente->isDirty()) {
            $paciente->validado_medico = true;
        }

        $paciente->save();

        return response()->json(['status' => 'success']);
    }

    public function updateOld(Request $request, $id)
    {
        Gate::authorize('gestionar_pacientes');
        $paciente = Paciente::findOrFail($id);
        
        // Convertir el checkbox a booleano
        $data = $request->all();
        $data['es_zurdo'] = $request->has('es_zurdo') ? 1 : 0;
        $data['discapacitado'] = $request->has('discapacitado') ? 1 : 0;

        $paciente->update($data);

        return response()->json(['status' => 'success']);
    }

    public function exportarExcel()
    {
        return Excel::download(new PacientesExport, 'listado_pacientes_'.date('d-m-Y').'.xlsx');
    }

    public function exportarTallas()
    {
        return Excel::download(new TallasExport, 'reporte_tallas_epp_'.date('d-m-Y').'.xlsx');
    }

}