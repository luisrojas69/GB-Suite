<?php

namespace App\Http\Controllers\MedicinaOcupacional;

use App\Http\Controllers\Controller;
use App\Models\MedicinaOcupacional\Paciente;
use App\Models\MedicinaOcupacional\Consulta;
use App\Models\MedicinaOcupacional\Accidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use App\Exports\MedicinaOcupacional\Consultas\ConsultasExport;


class ConsultaController extends Controller
{

    public function index() {
        $hoy = now()->format('Y-m-d');
        $mes_actual = now()->month;
        $anio_actual = now()->year;

        $consultas = Consulta::with('paciente')->orderBy('fecha_consulta', 'desc')->get();
    
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
        $data['total_personal'] = Paciente::count();
        $data['consultas_mes'] = Consulta::whereMonth('fecha_consulta', $mes_actual)->count();

        // 4. Alertas (Sintaxis SQL Server)
        $data['alertas_reposo'] = Consulta::where('genera_reposo', 1)->where('reincorporado', 0)
            ->whereRaw("CAST(DATEADD(day, dias_reposo, fecha_consulta) AS DATE) <= ?", [$hoy])->count();

        $data['alertas_vacas'] = Paciente::where('de_vacaciones', 1)
            ->whereDate('fecha_retorno_vacaciones', '<=' ,$hoy)->count();

        // 6. Top 5 Pacientes con más consultas en el mes
        $topPacientes = Consulta::with('paciente')
            ->select('paciente_id')
            ->selectRaw('COUNT(*) as total')
            ->whereMonth('fecha_consulta', $mes_actual)
            ->whereYear('fecha_consulta', $anio_actual)
            ->groupBy('paciente_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $accidentes_no_reportados = Consulta::where('motivo_consulta', 'Accidente Laboral')
            ->where('tiene_accidente_vinculado', false)
            ->count();

        $accidentes_sin_consulta = Accidente::where('consulta_id', null)->count();

        return view('MedicinaOcupacional.consultas.index', $data , compact('consultas','topDiagnosticos', 'topPacientes', 'labelsMeses', 'dataValores', 'accidentes_no_reportados', 'accidentes_sin_consulta' ));
    }

    public function indexOld()
    {
        // Traemos las consultas con su paciente para evitar el problema N+1
        $consultas = Consulta::with('paciente')->orderBy('fecha_consulta', 'desc')->get();
        return view('MedicinaOcupacional.consultas.index', compact('consultas'));
    }

    public function show($id)
    {
        // Agregamos 'orden' para poder mostrar los resultados si existen
        $consulta = Consulta::with(['paciente', 'medico', 'orden'])->findOrFail($id);
        // Verificamos si existe la relación orden antes de buscar archivos
        if ($consulta->orden) {
            $archivos_orden = DB::table('med_paciente_archivos')
                    ->where('orden_id', $consulta->orden->id)
                    ->get();
        } else {
            // Si no hay orden, devolvemos una colección vacía para evitar errores en el foreach de la vista
            $archivos_orden = collect(); 
        }
        return view('MedicinaOcupacional.consultas.show', compact('consulta', 'archivos_orden'));
    }

    public function edit($id)
    {
        $consulta = Consulta::findOrFail($id);

        // REGLA DE NEGOCIO: Solo consultas no mayores a 3 días
        $fechaLimite = Carbon::now()->subDays(3);
        
        if ($consulta->fecha_consulta->lt($fechaLimite)) {
            return redirect()->route('medicina.consultas.index')
                ->with('error', 'No se puede editar una consulta con más de 3 días de antigüedad por motivos de auditoría médica.');
        }

        return view('MedicinaOcupacional.consultas.edit', compact('consulta'));
    }


    public function create(Request $request, $paciente_id)
    {
        $motivo_prellenado = $request->query('motivo');

        $paciente = Paciente::findOrFail($paciente_id);

        //Si el paciente tiene una consulta abierta hoy, la sugerimos para vincular
        $accidente = DB::table('med_accidentes')
                        ->where('paciente_id', $paciente_id)
                        ->where('consulta_id', null)
                        ->first();

        
        // Obtenemos las últimas 5 consultas para el mini-historial lateral
        $historial = Consulta::where('paciente_id', $paciente_id)
                             ->orderBy('fecha_consulta', 'desc')
                             ->take(5)
                             ->get();

        return view('MedicinaOcupacional.consultas.create', compact('paciente', 'historial', 'motivo_prellenado','accidente'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'paciente_id'      => 'required|exists:med_pacientes,id',
            'motivo_consulta'  => 'required',
            'anamnesis'        => 'required',
            'diagnostico_cie10'=> 'required',
            'plan_tratamiento' => 'required',
            'fecha_consulta'   => 'required',
            'accidente_id'     => 'nullable',
            'requiere_examenes'=> 'nullable', // Validamos el nuevo campo
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear la consulta
            $consulta = new Consulta($request->all());
            $consulta->user_id = Auth::id();
            
            // Seteamos el status inicial según el switch
            $consulta->requiere_examenes = $request->has('requiere_examenes');
            $consulta->status_consulta = $request->has('requiere_examenes') 
                ? 'Pendiente por exámenes' 
                : 'Cerrada';

            $consulta->save();

            // 2. Lógica de reincorporación (Cambié $consulta por $consultasAnteriores para no pisar la nueva)
            if ($request->motivo_consulta === 'Reincorporacion') {
                Consulta::where('paciente_id', $request->paciente_id)
                        ->where('genera_reposo', 1)
                        ->where('reincorporado', 0)
                        ->update(['reincorporado' => 1]);
            }

            // 3. Lógica de vacaciones
            if ($request->motivo_consulta === 'Pre-vacacional' && $request->fecha_retorno_vacaciones) {
                Paciente::where('id', $request->paciente_id)->update([
                    'fecha_retorno_vacaciones' => $request->fecha_retorno_vacaciones,
                    'de_vacaciones' => true
                ]);
            } elseif ($request->motivo_consulta === 'Post-vacacional') {
                Paciente::where('id', $request->paciente_id)->update([
                    'de_vacaciones' => false
                ]);
            }

            // 4. Lógica de accidentes
            if ($request->filled('accidente_id')) {
                $accidente = Accidente::find($request->accidente_id);                   
                if ($accidente) {
                    $accidente->update(['consulta_id' => $consulta->id]);
                    $consulta->update(['tiene_accidente_vinculado' => true]);
                }
            }

            DB::commit();

            // --- LÓGICA DE REDIRECCIÓN INTELIGENTE ---
            
            if ($request->has('requiere_examenes')) {
                // Si requiere exámenes, vamos a la vista de creación de orden
                return redirect()->route('medicina.ordenes.create', ['consulta_id' => $consulta->id])
                                 ->with('success', 'Consulta guardada. Por favor, genere la orden de exámenes.')
                                 ->with('print_id', $consulta->id);
            }

            // Si NO requiere, flujo normal al index
            return redirect()->route('medicina.consultas.index')
                             ->with('success', 'Consulta registrada exitosamente.')
                             ->with('print_id', $consulta->id);
                         
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al guardar: ' . $e->getMessage())->withInput();
        }
    }

    public function storeSinExamenes(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:med_pacientes,id',
            'motivo_consulta' => 'required',
            'anamnesis' => 'required',
            'diagnostico_cie10' => 'required',
            'plan_tratamiento' => 'required',
            'fecha_consulta' => 'required',
            'accidente_id'          => 'nullable',
        ]);


        try {
            DB::beginTransaction();

            $consulta = new Consulta($request->all());
            $consulta->user_id = Auth::id(); // Médico autenticado
            $consulta->save();
            // Si la consulta fue reincorporacion, actualizamos la ficha del consulta
            if ($request->motivo_consulta === 'Reincorporacion') {
                $consulta = Consulta::where('paciente_id', $request->paciente_id)
                                    ->where('genera_reposo', 1)
                                    ->where('reincorporado', 0)
                                    ->get();
                $consulta->update([
                    'reincorporado' => 1
                ]);
            }

            // Si la consulta fue pre-vacacional, actualizamos la ficha del paciente
            if ($request->motivo_consulta === 'Pre-vacacional' && $request->fecha_retorno_vacaciones) {
                $paciente = Paciente::find($request->paciente_id);
                $paciente->update([
                    'fecha_retorno_vacaciones' => $request->fecha_retorno_vacaciones,
                    'de_vacaciones' => true
                ]);
            }elseif ($request->motivo_consulta === 'Post-vacacional') {
                $paciente = Paciente::find($request->paciente_id);
                $paciente->update([
                    'de_vacaciones' => false
                ]);
            }

            // 2. Lógica para incorporar el ID de la consulta reciente
                if ($request->filled('accidente_id')) {
                    $accidente = Accidente::find($request->accidente_id);                   
                    if ($accidente) {
                        $accidente->update([
                            'consulta_id' => $consulta->id
                        ]);
                        $consulta->update([
                            'tiene_accidente_vinculado' => true
                        ]);
                    }
                }
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
        if ($consulta->fecha_consulta->lt(now()->subDays(3))) {
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
                    $q->orderBy('fecha_consulta', 'desc');
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

        public function buscarCie10(Request $request) {
            $term = $request->get('q');
            
            $resultados = DB::table('med_cie10')
                ->where('codigo', 'LIKE', "%$term%")
                ->orWhere('descripcion', 'LIKE', "%$term%")
                ->limit(15)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->codigo, // Guardamos el código (Z00.0) en la tabla consultas
                        'text' => $item->codigo . " - " . $item->descripcion
                    ];
                });

            return response()->json($resultados);
        }

        public function exportar(Request $request) 
        {
            $desde = $request->get('desde');
            $hasta = $request->get('hasta');
            $tipo = $request->get('tipo');

            $nombreArchivo = "Morbilidad_{$desde}_al_{$hasta}.xlsx";

            return (new ConsultasExport($desde, $hasta, $tipo))->download($nombreArchivo);
        }



        public function storeFastTrack(Request $request)
        {
            // Validamos que venga lo mínimo necesario
            $request->validate([
                'paciente_id' => 'required|exists:med_pacientes,id', // <--- Aquí estaba el detalle
                'origen' => 'required|in:Post-vacacional,Post-reposo',
                'reposo_id' => 'nullable|exists:med_consultas,id' // Verifica si tu tabla de consultas también tiene prefijo med_
            ]);
            try {
                DB::beginTransaction();

                $paciente = Paciente::findOrFail($request->paciente_id);

                // 1. Creamos la "Consulta Fantasma" (Administrativa)
                $consulta = Consulta::create([
                    'paciente_id' => $paciente->id,
                    'user_id' => auth()->id(),
                    'fecha_consulta' => now(),
                    
                    // CAMPOS OBLIGATORIOS (NOT NULL) LLENADOS AUTOMÁTICAMENTE
                    'motivo_consulta' => 'Reincorporacion - ' . $request->origen,
                    'anamnesis' => 'Paciente acude para valoración de reincorporación laboral, refiriendo encontrarse asintomático al momento de la evaluación. Manifiesta haber completado su periodo de descanso sin complicaciones, expresando sentirse en óptimas condiciones físicas y mentales para retomar sus funciones habituales. En cuanto a sus antecedentes, niega nuevas patologías o alergias recientes, sin cambios relevantes en el ámbito familiar y manteniendo hábitos saludables con sueño reparador y alimentación adecuada. Paciente Refiere condiciones óptimas para reincorporación inmediata.',

                    'examen_fisico' => 'Inspección general conservada, omitiéndose evaluación instrumental por protocolo de Fast-Track bajo declaración del paciente. Se observa al paciente en buen estado general, alerta y orientado en tiempo, espacio y persona (LOTEP), hidratado y afebril. Los signos vitales se encuentran dentro de los parámetros normales; a la exploración, se presenta normocéfalo y sin adenopatías en cuello. El sistema cardiopulmonar evidencia ruidos cardiacos rítmicos sin soplos y campos pulmonares bien ventilados. El abdomen se percibe blando, depresible, no doloroso a la palpación y sin presencia de masas. Las extremidades se muestran simétricas y eutróficas, con arcos de movilidad completos y sin déficit neurológico aparente, manteniendo una marcha normal. En conclusión, los hallazgos del examen físico se encuentran dentro de la normalidad, no evidenciándose limitaciones funcionales para el desempeño de su labor tras su periodo de descanso.',
                    
                    // Signos Vitales "N/E" (No Evaluado) para ser honestos
                    'tension_arterial' => 'N/E', 
                    'frecuencia_cardiaca' => 0,
                    'saturacion_oxigeno' => 0,
                    'temperatura' => 0,
                    
                    // Diagnóstico Administrativo
                    'diagnostico_cie10' => 'Z02.7', // Examen para certificado médico
                    //'diagnostico_descripcion' => 'EXAMEN PARA LA OBTENCION DE CERTIFICADO MEDICO',
                    'plan_tratamiento' => 'Paciente niega sintomatología actual y refiere condiciones óptimas para su reincorporación inmediata, por lo que no requiere plan de tratamiento farmacológico. Se indica mantener una hidratación adecuada de al menos 2 litros de agua al día y asegurar una correcta higiene postural mediante el ajuste de la estación de trabajo. Es indispensable cumplir estrictamente con las pausas activas y ejercicios de estiramiento cada 2 a 3 horas, permitiendo una adaptación progresiva a las funciones habituales para evitar sobreesfuerzos. Asimismo, se reitera la obligatoriedad del uso continuo de EPP y el cumplimiento de las normas de seguridad industrial, debiendo notificar cualquier cambio en su estado de salud al departamento médico. Como plan preventivo, se sugiere continuar con hábitos de vida saludable y actividad física regular, programando su próximo control médico en un periodo de 6 meses. (SI APLICA)', // Examen para certificado médico
                    
                    // Marcas internas
                    'consulta_rapida' => true,
                    'genera_reposo' => false,
                    'dias_reposo' => 0
                ]);

                // 2. Apagamos la Alerta (Lógica de Negocio)
                if ($request->origen === 'Post-vacacional') {
                    // Si viene de vacaciones, lo pasamos a Activo
                    $paciente->update(['de_vacaciones' => false]); 
                } 
                elseif ($request->origen === 'Post-reposo' && $request->reposo_id) {
                    // Si viene de reposo, marcamos el reposo anterior como "Cerrado/Reincorporado"
                    Consulta::where('id', $request->reposo_id)->update(['reincorporado' => true]);
                }

                DB::commit();

                return response()->json([
                    'success' => true, 
                    'message' => 'Reincorporación procesada exitosamente.',
                    'consulta_id' => $consulta->id // Devolvemos el ID por si queremos subir el archivo a esta consulta
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
            }
        }

}