<?php

namespace App\Http\Controllers\MedicinaOcupacional;

use App\Http\Controllers\Controller;
use App\Models\MedicinaOcupacional\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;

class PacienteController extends Controller
{
    public function index()
    {
        // Aplicar Gate de Spatie (Consistencia Corporativa)
        // $this->authorize('view-pacientes'); 
        Gate::authorize('gestionar_pacientes');
        return view('MedicinaOcupacional.pacientes.index');
    }

    public function getListado(Request $request)
    {
        Gate::authorize('gestionar_activos');
        // Retornar datos para DataTables vía AJAX
        $pacientes = Paciente::all();
        return response()->json(['data' => $pacientes]);
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
                : 'N/A',
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
        
        // Convertir el checkbox a booleano
        $data = $request->all();
        $data['es_zurdo'] = $request->has('es_zurdo') ? 1 : 0;

        $paciente->update($data);

        return response()->json(['status' => 'success']);
    }

}