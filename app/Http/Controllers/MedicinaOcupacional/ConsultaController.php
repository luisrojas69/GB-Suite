<?php

namespace App\Http\Controllers\MedicinaOcupacional;

use App\Http\Controllers\Controller;
use App\Models\MedicinaOcupacional\Paciente;
use App\Models\MedicinaOcupacional\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class ConsultaController extends Controller
{

    public function index()
    {
        // Traemos las consultas con su paciente para evitar el problema N+1
        $consultas = Consulta::with('paciente')->orderBy('created_at', 'desc')->get();
        return view('MedicinaOcupacional.consultas.index', compact('consultas'));
    }

    public function show($id)
    {
        $consulta = Consulta::with('paciente', 'medico')->findOrFail($id);
        //dd($consulta);
        return view('MedicinaOcupacional.consultas.show', compact('consulta'));
    }

    public function edit($id)
    {
        $consulta = Consulta::findOrFail($id);

        // REGLA DE NEGOCIO: Solo consultas no mayores a 3 días
        $fechaLimite = Carbon::now()->subDays(3);
        
        if ($consulta->created_at->lt($fechaLimite)) {
            return redirect()->route('medicina.consultas.index')
                ->with('error', 'No se puede editar una consulta con más de 3 días de antigüedad por motivos de auditoría médica.');
        }

        return view('MedicinaOcupacional.consultas.edit', compact('consulta'));
    }


    public function create($paciente_id)
    {
        $paciente = Paciente::findOrFail($paciente_id);
        
        // Obtenemos las últimas 5 consultas para el mini-historial lateral
        $historial = Consulta::where('paciente_id', $paciente_id)
                             ->orderBy('created_at', 'desc')
                             ->take(5)
                             ->get();

        return view('MedicinaOcupacional.consultas.create', compact('paciente', 'historial'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:med_pacientes,id',
            'motivo_consulta' => 'required',
            'anamnesis' => 'required',
            'diagnostico_cie10' => 'required',
            'plan_tratamiento' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $consulta = new Consulta($request->all());
            $consulta->user_id = Auth::id(); // Médico autenticado
            $consulta->save();

            DB::commit();

          // Enviamos el ID de la consulta para que la vista sepa cuál imprimir
            return redirect()->route('medicina.consultas.index')
                             ->with('success', 'Consulta registrada exitosamente.')
                             ->with('print_id', $consulta->id);
                     
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al guardar: ' . $e->getMessage())->withInput();
        }
    }


    public function update(Request $request, $id)
    {
        // 1. Encontrar la consulta
        $consulta = Consulta::findOrFail($id);
       // dd($request);
        // 2. Reforzar Regla de Seguridad: No editar después de 3 días
        if ($consulta->created_at->lt(now()->subDays(3))) {
            return redirect()->route('medicina.consultas.index')
                ->with('error', 'Acceso denegado: Esta consulta tiene más de 72 horas y ha sido bloqueada para modificaciones legales.');
        }

        // 3. Validación de datos
        $request->validate([
            'motivo_consulta' => 'required',
            'diagnostico_cie10' => 'required|string|max:255',
            'anamnesis' => 'required',
            'plan_tratamiento' => 'required',
            'aptitud' => 'required',
        ]);

        try {
            // 4. Actualizar el registro
            $consulta->update([
                'motivo_consulta'    => $request->motivo_consulta,
                'diagnostico_cie10'  => $request->diagnostico_cie10,
                'tension_arterial'   => $request->tension_arterial,
                'frecuencia_cardiaca'=> $request->frecuencia_cardiaca,
                'temperatura'        => $request->temperatura,
                'saturacion_oxigeno' => $request->saturacion_oxigeno,
                'anamnesis'          => $request->anamnesis,
                'examen_fisico'      => $request->examen_fisico,
                'plan_tratamiento'   => $request->plan_tratamiento,
                'aptitud'            => $request->aptitud,
                'genera_reposo'      => $request->genera_reposo,
                'dias_reposo'        => $request->genera_reposo == '1' ? $request->dias_reposo : 0,
                // Guardamos quién hizo la última edición para trazabilidad
                //'updated_by'         => Auth::id(), 
            ]);

            return redirect()->route('medicina.consultas.index')
                ->with('success', 'La consulta médica ha sido actualizada exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput();
        }
    }


    //Imprimir Recipe / COnstancias
    public function imprimir($id)
    {
        $consulta = Consulta::with(['paciente', 'medico'])->findOrFail($id);
        
        $pdf = Pdf::loadView('MedicinaOcupacional.consultas.pdf', compact('consulta'));
        
        // Si quieres que se descargue: download(). Si quieres ver en navegador: stream()
        return $pdf->stream("Consulta_{$consulta->paciente->cedula}.pdf");
    }


    public function historial($paciente_id)
    {
        $paciente = Paciente::with([
            'consultas' => function($q) {
                $q->orderBy('created_at', 'desc');
            },
            'dotaciones' => function($q) {
                $q->orderBy('created_at', 'desc');
            },
            'accidentes' => function($q) {
                $q->orderBy('fecha_hora_accidente', 'desc');
            }
        ])->findOrFail($paciente_id);

        return view('MedicinaOcupacional.consultas.historial', compact('paciente'));
    }

    public function subirArchivo(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required',
            'nombre_archivo' => 'required|string|max:100',
            'archivo' => 'required|mimes:pdf,jpg,jpeg,png|max:5120', // Máx 5MB
        ]);

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $nombreFinal = time() . '_' . $file->getClientOriginalName();
            // Guardamos en una carpeta privada: storage/app/public/examenes_medicos
            $ruta = $file->storeAs('examenes_medicos/' . $request->paciente_id, $nombreFinal, 'public');

            DB::table('med_paciente_archivos')->insert([
                'paciente_id' => $request->paciente_id,
                'nombre_archivo' => $request->nombre_archivo,
                'ruta_archivo' => $ruta,
                'tipo_archivo' => $file->getClientOriginalExtension(),
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);

            return back()->with('success', 'Archivo adjuntado correctamente.');
        }
    }


}