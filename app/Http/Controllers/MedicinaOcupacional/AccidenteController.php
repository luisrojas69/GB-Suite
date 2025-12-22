<?php

namespace App\Http\Controllers\MedicinaOcupacional;

use App\Http\Controllers\Controller;
use App\Models\MedicinaOcupacional\Paciente;
use App\Models\MedicinaOcupacional\Accidente;
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

    // Guardar la investigación del accidente
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:med_pacientes,id',
            'fecha_hora_accidente' => 'required',
            'lugar_exacto' => 'required|string|max:255',
            'tipo_evento' => 'required',
            'descripcion_relato' => 'required',
            'acciones_correctivas' => 'required',
        ]);

        try {
            $accidente = new Accidente($request->all());
            $accidente->user_id = Auth::id();
            $accidente->save();

            return redirect()->route('medicina.accidentes.index')
                             ->with('success', 'Investigación de accidente registrada y guardada.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el registro: ' . $e->getMessage())->withInput();
        }
    }

    // Listado general de accidentes para el Dashboard de Seguridad
    public function index()
    {
        $accidentes = Accidente::with('paciente')->orderBy('fecha_hora_accidente', 'desc')->get();
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