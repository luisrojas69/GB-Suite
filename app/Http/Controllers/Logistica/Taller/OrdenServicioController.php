<?php

namespace App\Http\Controllers\Logistica\Taller;

use App\Http\Controllers\Controller;
use App\Models\Logistica\Taller\Activo;
use App\Models\Logistica\Taller\OrdenServicio;
use App\Models\Logistica\Taller\Checklist;
use App\Models\Logistica\Taller\ChecklistDetalle;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB; // Para transacciones si es necesario

class OrdenServicioController extends Controller
{
    /** Muestra todas las órdenes, filtrables por estado (Abierta, En Proceso, Cerrada) */
    public function index()
    {
        Gate::authorize('gestionar_ordenes'); // Permiso general para la gestión
        
        $ordenes = OrdenServicio::with('activo')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
        
        return view('taller.ordenes.index', compact('ordenes'));
    }

    /** Muestra el formulario para crear una nueva Orden de Servicio */
    public function create()
    {
        Gate::authorize('gestionar_ordenes'); // Solo el Gerente de Logística puede crear inicialmente
        
        $activos = Activo::where('estado_operativo', 'Operativo')->get(['id', 'codigo', 'nombre', 'lectura_actual', 'unidad_medida']);
        
        return view('taller.ordenes.create', compact('activos'));
    }

    public function store(Request $request)
    {
        // 1. Autorización y Validación
        Gate::authorize('gestionar_ordenes');
        $request->validate([
            'activo_id' => 'required|exists:activos,id',
            'tipo_servicio' => 'required|in:Preventivo,Correctivo', 
            'descripcion_falla' => 'required|string|max:1000', 
            'lectura_inicial' => 'required|integer|min:0',
        ]);

        $activo = Activo::findOrFail($request->activo_id);
        
        // 2. Transacción
        return DB::transaction(function () use ($request, $activo) {
            
            // --- Parte 1: Creación de la Orden de Servicio ---
            
            $lastId = OrdenServicio::max('id') ?? 0;
            $codigoOrden = 'OS-' . date('Y') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
            
            $orden = OrdenServicio::create([
                'activo_id' => $activo->id,
                'codigo_orden' => $codigoOrden,
                'tipo_servicio' => $request->tipo_servicio,
                'solicitante_id' => auth()->id(), 
                'lectura_inicial' => $request->lectura_inicial,
                'descripcion_falla' => $request->descripcion_falla,
                'status' => 'Abierta',
                'fecha_inicio_taller' => now(),
            ]);
            
            // 3. CAMBIO DE STATUS del Activo
            $activo->estado_operativo = 'En Mantenimiento';
            $activo->save();

            // --- Parte 2: Lógica de Instanciación del Checklist ---
            
            $nombrePlantillaRequerida = $this->getNombreChecklistPorTipoServicio(
                $orden->tipo_servicio, 
                $activo->tipo 
            );
            
            if ($nombrePlantillaRequerida) {
                $plantilla = Checklist::where('nombre', $nombrePlantillaRequerida)
                                        ->whereNull('orden_servicio_id') 
                                        ->first();
                
                if ($plantilla) {
                    
                    // A. Crear la INSTANCIA de ejecución del Checklist (solo si es necesario mantenerlo para el 'hasOne')
                    $checklistInstancia = Checklist::create([
                        'orden_servicio_id' => $orden->id, 
                        'nombre' => $plantilla->nombre . ' - OS #' . $orden->codigo_orden,
                        'tipo_activo' => $plantilla->tipo_activo,
                        'intervalo_referencia' => $plantilla->intervalo_referencia,
                        'descripcion_tareas' => $plantilla->descripcion_tareas,
                    ]);

                    // B. Crear las líneas de detalle (ChecklistDetalle)
                    $tareas = json_decode($plantilla->descripcion_tareas, true);
                    
                    if (is_array($tareas) && !empty($tareas)) {
                        $detalles = [];
                        
                        foreach ($tareas as $descripcion) {
                            $detalles[] = [
                                // CLAVE FORÁNEA: El ID de la Orden de Servicio
                                'orden_servicio_id' => $orden->id, 
                                
                                // CONTENIDO: El nombre de columna real en la DB
                                'tarea' => $descripcion, 
                                'completado' => false, 
                                
                                // Timestamps: Necesarios si la tabla los tiene y usamos insert()
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                        
                        // Guardado masivo y eficiente
                        ChecklistDetalle::insert($detalles); 
                    }
                }
            }
            
            // 4. Devolver la respuesta
            return redirect()->route('ordenes.show', $orden->id)
                             ->with('success', "Orden de Servicio **{$codigoOrden}** creada. Activo marcado como 'En Mantenimiento'.");
        });
    }

    /**
     * Lógica auxiliar para generar el código de la orden (ejemplo).
     */
    protected function generateCodigoOrden($id)
    {
        return 'OS-' . date('Y') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT);
    }
    
    /**
     * Lógica auxiliar para determinar el nombre de la plantilla.
     * DEBES adaptar esta lógica a tus reglas de negocio (ej. tipo de OS, tipo de activo).
     */




    // ... (generateCodigoOrden y getNombreChecklistPorTipoServicio aquí) ...
    
    /**
     * Lógica auxiliar para determinar el nombre de la plantilla.
     * Adaptado a tus valores de tipo_servicio: 'Preventivo' y 'Correctivo'.
     */
    protected function getNombreChecklistPorTipoServicio($tipoServicio, $tipoActivo)
    {
        // Ejemplo de regla: Solo los preventivos en tractores con la plantilla 1000H.
           if ($tipoServicio === 'Preventivo' && $tipoActivo === 'Tractor') {
                return 'MP-Tractor'; // La plantilla para Tractores de 250H
            }
            if ($tipoServicio === 'Preventivo' && $tipoActivo === 'Camión') {
                return 'MP-Camion'; // La plantilla para Camiones de 20,000 km
            }
            if ($tipoServicio === 'Preventivo' && $tipoActivo === 'Camioneta') {
                return 'MP-Camioneta'; // La plantilla para Camiones de 20,000 km
            }if ($tipoServicio === 'Preventivo' && $tipoActivo === 'Moto') {
                return 'Moto'; // La plantilla para Camiones de 20,000 km
            }

            
            return null;

    }


    /** Muestra la Orden (Vista del Taller) */
    public function show($id) // Cambiamos la inyección para debug
    {
        Gate::authorize('gestionar_ordenes');
        
        // Buscar el modelo, si no existe fallará.
        $orden = OrdenServicio::findOrFail($id); 

        // 1. Verificar el ID y el activo_id
        // dd([
        //     'Orden ID' => $orden->id, 
        //     'Activo ID' => $orden->activo_id,
        //     'Activo Existe' => Activo::where('id', $orden->activo_id)->exists()
        // ]);
        
        // 2. Carga Eager Loading
        $orden->load(['activo', 'checklist' => function ($query) use ($orden) {
            // Debug: Asegurar que el ID foráneo se está usando correctamente
            // dd($query->where('orden_servicio_id', $orden->id)->toSql()); 
        }, 'checklist.detallesChecklist']); 
        
       //dd($orden->checklist->descripcion_tareas); // Revisa si checklist ya no es null
        
        return view('taller.ordenes.show', compact('orden'));
    }

        // --- MÉTODOS DE FLUJO DE TRABAJO ---

        /** El Jefe de Taller indica que el trabajo empieza */
        public function iniciarTrabajo(OrdenServicio $orden)
        {
            Gate::authorize('gestionar_ordenes'); // Requerir un permiso específico de 'ejecutar_ordenes' sería mejor aquí
            
            if ($orden->status === 'Abierta') {
                $orden->status = 'En Proceso';
                $orden->fecha_inicio_taller = now();
                $orden->mecanico_asignado = auth()->user()->name; // Asignar al usuario que inicia
                $orden->save();
                return back()->with('success', 'Trabajo iniciado. Tiempo de reparación registrado.');
            }
            return back()->with('error', 'La orden ya está en proceso o cerrada.');
        }

        /** El Jefe de Taller indica que el activo está listo para salir */
        public function cerrarOrden(Request $request, OrdenServicio $orden)
        {
            Gate::authorize('gestionar_ordenes');

            $request->validate([
                'lectura_final' => 'required|integer|min:' . $orden->lectura_inicial,
                'tareas_realizadas' => 'required|string|min:10',
            ]);
            
            // 1. Registrar Fin del Trabajo y Costos (luego se añadirán los costos de repuestos)
            $orden->status = 'Cerrada';
            $orden->fecha_fin_trabajo = now();
            $orden->fecha_salida_taller = now();
            $orden->lectura_final = $request->lectura_final;
            // Asumiendo que las 'tareas_realizadas' se guardan en un campo adicional TEXT
            // $orden->tareas_realizadas = $request->tareas_realizadas; 
            $orden->save();
            
            // 2. Actualizar el Activo
            $activo = $orden->activo;
            $activo->estado_operativo = 'Operativo';
            $activo->lectura_actual = $request->lectura_final; // Actualizar la lectura actual
            $activo->save();

            // 3. (Futuro) Finalizar el cálculo de costos y la vinculación con Profit.
            
            return redirect()->route('ordenes.index')->with('success', 'Orden No. ' . $orden->codigo_orden . ' cerrada. Activo puesto en estado OPERATIVO.');
        }

        // Ejemplo de lógica de validación antes de cerrar una Orden de Servicio:

    // En OrdenServicioController.php@cerrar
    public function cerrar(OrdenServicio $ordenServicio)
    {
        // 1. Verificar si hay checklists incompletos
        $checklist = $ordenServicio->checklist; // Asumiendo que OS tiene una relación hasOne con Checklist
        
        if ($checklist) {
            $itemsPendientesCriticos = $checklist->detalles()
                                                ->criticoPendiente() // Usando el scope que definimos
                                                ->count();

            if ($itemsPendientesCriticos > 0) {
                return redirect()->back()->with('error', 
                    "No se puede cerrar la Orden de Servicio. Aún hay {$itemsPendientesCriticos} ítems críticos pendientes en el Checklist.");
            }
        }
        
        // ... Lógica para cambiar el estado de la OS a 'Cerrada' ...
    }

    public function updateDetalleChecklist(Request $request, $ordenId, ChecklistDetalle $checklistDetalle)
    {
        // Usamos el ID de la orden en el path ($ordenId) si lo necesitamos, 
        // pero la inyección de modelo ya nos da el detalle ($checklistDetalle).
        
        Gate::authorize('gestionar_ordenes');

        // 1. Validación
        $request->validate([
            'notas_resultado' => 'nullable|string|max:1000',
            'completado' => 'nullable|boolean',
        ]);
        
        // 2. Lógica de actualización
        
        $checklistDetalle->update([
            // El campo 'completado' solo estará presente si la casilla está marcada.
            'completado' => $request->has('completado'), 
            'notas_resultado' => $request->notas_resultado,
        ]);

        // 3. Redirección
        return redirect()->route('ordenes.show', $checklistDetalle->orden_servicio_id)
                         ->with('success', 'Tarea del Checklist actualizada exitosamente.')
                         ->withFragment('falla-tab'); // Opcional: Para volver a la pestaña Diagnóstico/Falla
    }


}