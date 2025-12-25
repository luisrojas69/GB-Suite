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

class AccidenteController extends Controller
{
    // Mostrar el formulario de registro para un paciente específico
    public function create($paciente_id)
    {
        $paciente = Paciente::findOrFail($paciente_id);
        
        // Opcional: Si el paciente tiene una consulta abierta hoy, la sugerimos para vincular
        $consultaHoy = DB::table('med_consultas')
                        ->where('paciente_id', $paciente_id)
                        ->whereDate('created_at', today())
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
        $accidentes = Accidente::with('paciente', 'consulta')->orderBy('fecha_hora_accidente', 'desc')->get();
        return view('MedicinaOcupacional.accidentes.index', compact('accidentes'));
    }


    public function show($id)
    {
        $accidente = Accidente::with(['paciente', 'user', 'consulta'])->findOrFail($id);
        return view('MedicinaOcupacional.accidentes.show', compact('accidente'));
    }

    public function reporteInpsasel($id)
    {
        $accidente = Accidente::with(['paciente', 'user'])->findOrFail($id);
        
        // Aquí podrías usar una librería como Snappy o DomPDF para generar el PDF
        // Por ahora, enviaremos a una vista optimizada para impresión
        return view('MedicinaOcupacional.accidentes.reporte_pdf', compact('accidente'));
    }
}