<?php

namespace App\Http\Controllers\MedicinaOcupacional;
use App\Http\Controllers\Controller;
use App\Models\MedicinaOcupacional\OrdenExamen;
use App\Models\MedicinaOcupacional\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF; 


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
            $orden = OrdenExamen::findOrFail($id);
            return view('MedicinaOcupacional.ordenes.edit', compact('orden'));
        }

        //Editae Ordenes (Cargar Resultados)
        public function update(Request $request, $id)
        {

            $request->validate([
                'interpretacion'  => 'required',
                'hallazgos'       => 'nullable|string',
                'archivo_adjunto' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            ]);

            try {
                DB::beginTransaction();

                $orden = OrdenExamen::findOrFail($id);
                 
                if ($request->hasFile('archivo_adjunto')) {
                    $file = $request->file('archivo_adjunto');
                    $nombreFinal = time() . '_' . $file->getClientOriginalName();
                    // Guardamos en una carpeta privada: storage/app/public/examenes_medicos
                    $ruta = $file->storeAs('examenes_medicos/' . $request->consulta_id, $nombreFinal, 'public');
                }

                // Actualizar la Orden
                $orden->update([
                    'interpretacion'  => $request->interpretacion,
                    'hallazgos'       => $request->hallazgos,
                    'archivo_adjunto' => $file->getClientOriginalExtension(),
                    'status_orden'    => 'Completada',
                ]);
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
                return redirect()->route('medicina.ordenes.index')
                                 ->with('success', 'Orden completada y consulta cerrada exitosamente.');

            } catch (\Exception $e) {
                DB::rollback();
                return back()->with('error', 'Error al procesar: ' . $e->getMessage());
            }
        }
}
