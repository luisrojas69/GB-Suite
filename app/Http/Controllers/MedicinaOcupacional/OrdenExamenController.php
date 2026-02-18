<?php

namespace App\Http\Controllers\MedicinaOcupacional;
use App\Http\Controllers\Controller;
use App\Models\MedicinaOcupacional\OrdenExamen;
use App\Models\MedicinaOcupacional\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;


class OrdenExamenController extends Controller
{
     public function index()
        {
            // Traemos las órdenes con sus relaciones para evitar N+1 queries
            $ordenes = OrdenExamen::with(['paciente', 'medico'])->latest()->get();

            // Calculamos indicadores para las Cards
            $stats = [
                'total'      => $ordenes->count(),
                'pendientes' => $ordenes->where('status_orden', 'Pendiente')->count(),
                'completadas'=> $ordenes->where('status_orden', 'Completada')->count(),
                'hoy'        => $ordenes->where('created_at', '>=', now()->startOfDay())->count(),
            ];

            return view('MedicinaOcupacional.ordenes.index', compact('ordenes', 'stats'));
        }

        public function create(Request $request)
        {
            // Lógica para mostrar el formulario (ya la tienes visualizada)
            $consulta = Consulta::findOrFail($request->consulta_id);
            $paciente = $consulta->paciente;
            return view('MedicinaOcupacional.ordenes.create', compact('consulta', 'paciente'));
        }

        public function store(Request $request)
        {
            if (is_string($request->examenes)) {
                $request->merge([
                    'examenes' => json_decode($request->examenes, true)
                ]);
            }

            $request->validate([
                'examenes' => 'required|array|min:1',
                'observaciones' => 'nullable|string'
            ]);

            $orden = OrdenExamen::create([
                'consulta_id' => $request->consulta_id,
                'paciente_id' => $request->paciente_id,
                'user_id'     => Auth::id(),
                'examenes'    => $request->examenes,
                'observaciones'=> $request->observaciones,
                'status_orden' => 'Pendiente'
            ]);

            // --- EL VÍNCULO CON LA CONSULTA ---
            // Buscamos la consulta que generó esta orden y la cerramos también
            $consulta = $orden->consulta;
            if ($consulta) {
                $consulta->update([
                    'status_consulta' => 'Pendiente por exámenes' 
                    // Podrías incluso concatenar los hallazgos al plan de tratamiento si quisieras
                ]);
            }

            
            // Redirigir al PDF o al Index
            return redirect()->route('medicina.ordenes.index')
                             ->with('success', 'Orden generada correctamente.')
                             ->with('print_id', $orden->id); // Para abrir el PDF automáticamente si quieres
        }

        // Método para cambiar estatus (opcional, para usar con AJAX o botón simple)
        public function markAsCompleted($id)
        {
            $orden = OrdenExamen::findOrFail($id);
            $orden->update(['status_orden' => 'Completada']);
            return back()->with('success', 'Orden marcada como completada.');
        }


      public function edit($id)
        {
            // Traemos la orden con TODA la artillería de datos
            $orden = OrdenExamen::with(['paciente', 'consulta', 'medico'])->findOrFail($id);
            
            // Traemos los archivos que ya se subieron específicamente para ESTA orden
            $archivos_orden = DB::table('med_paciente_archivos')
                ->where('orden_id', $id)
                ->get();

            return view('MedicinaOcupacional.ordenes.edit', compact('orden', 'archivos_orden'));
        }

        public function update(Request $request, $id)
        {
            

            $request->validate([
                'interpretacion' => 'nullable|in:Normal,Alterado',
                'hallazgos' => 'nullable|string',
                'archivos.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240', // Múltiples archivos
            ]);

            try {
                DB::beginTransaction();
                $orden = OrdenExamen::findOrFail($id);
                // 1. Actualizamos datos básicos (Persistencia)
                $orden->update([
                    'interpretacion' => $request->interpretacion,
                    'hallazgos' => $request->hallazgos, // El nombre que tengas en la DB
                    'status_orden' => 'Completada', // Un estado intermedio si quieres
                ]);

                // 2. Manejo de múltiples archivos
                if ($request->hasFile('archivos')) {
                    foreach ($request->file('archivos') as $file) {
                        $nombreFinal = time() . '_' . $file->getClientOriginalName();
                        
                        // Guardamos en storage/app/public/ordenes/{paciente_id}/{orden_id}
                        $ruta = $file->storeAs(
                            "examenes_medicos/{$orden->paciente_id}/orden_{$id}", 
                            $nombreFinal, 
                            'public'
                        );

                        // Registramos en la tabla de archivos general
                        DB::table('med_paciente_archivos')->insert([
                            'paciente_id' => $orden->paciente_id,
                            'orden_id' => $orden->id, // Vinculamos a la orden
                            'nombre_archivo' => $file->getClientOriginalName(),
                            'ruta_archivo' => $ruta,
                            'tipo_archivo' => $file->getClientOriginalExtension(),
                            'user_id' => Auth::id(),
                            'created_at' => now(),
                        ]);
                    }
                }

                // --- EL VÍNCULO CON LA CONSULTA ---
                // Buscamos la consulta que generó esta orden y la cerramos también
                $consulta = $orden->consulta;
                if ($consulta) {
                    $consulta->update([
                        'status_consulta' => 'Cerrada' 
                        // Podrías incluso concatenar los hallazgos al plan de tratamiento si quisieras
                    ]);
                }


                DB::commit();
                return redirect()->route('medicina.ordenes.edit', $id)
                             ->with('success', 'Información y archivos actualizados correctamente.');

            } catch (\Exception $e) {
                DB::rollback();
                return back()->with('error', 'Error al procesar: ' . $e->getMessage());
            }

        }

        //Imprimir Orden
        public function pdf($id)
        {
            $orden = OrdenExamen::with(['paciente', 'medico'])->findOrFail($id);

            // Configuramos Snappy para un PDF limpio
            $pdf = PDF::loadView('MedicinaOcupacional.ordenes.pdf', compact('orden'))
                      ->setPaper('letter')
                      ->setOption('margin-bottom', 0)
                      ->setOption('margin-top', 0)
                      ->setOption('enable-local-file-access', true); // Importante para las imágenes

            return $pdf->inline('Orden_Medica_'.$orden->id.'.pdf');
        }
}