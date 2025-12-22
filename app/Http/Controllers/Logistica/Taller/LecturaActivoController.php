<?php

namespace App\Http\Controllers\Logistica\Taller;

use App\Http\Controllers\Controller;
use App\Models\Logistica\Taller\Activo;
use App\Models\Logistica\Taller\LecturaActivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class LecturaActivoController extends Controller
{

    /**
     * Muestra la lista de todas las lecturas registradas.
     */
    public function index()
    {
        Gate::authorize('ver_lecturas'); 
        
        // Carga la información del Activo y del Registrador (Usuario) para mostrar en la tabla
        $lecturas = LecturaActivo::with(['activo', 'registrador'])->latest()->paginate(15);
        
        return view('taller.lecturas.index', compact('lecturas'));
    }


    /**
     * Muestra el formulario para registrar una nueva lectura.
     */
    public function create(Request $request)
    {
        Gate::authorize('crear_lecturas'); 
        
        // Obtenemos Activos Operativos para la selección en el formulario
        $activos = Activo::where('estado_operativo', 'Operativo')->get(['id', 'codigo', 'nombre', 'lectura_actual', 'unidad_medida']);

        $activo_id_url = $request->query('activo'); // Obtiene el valor '2' de la URL
        
        return view('taller.lecturas.create', compact('activos', 'activo_id_url'));
    }

    /**
     * Almacena la nueva Lectura y actualiza el Activo.
     */
    public function store(Request $request)
    {
        Gate::authorize('crear_lecturas');
        
        $request->validate([
            'activo_id' => 'required|exists:activos,id',
            'valor_lectura' => 'required|integer|min:0',
            'fecha_lectura' => 'required|date|before_or_equal:today',
            'observaciones' => 'nullable|string|max:500',
            // El resto de campos (unidad_medida, registrador_id) se obtienen internamente.
        ]);

        $activo = Activo::find($request->activo_id);
        
        // 🛑 VALIDACIÓN CRÍTICA
        if ($request->valor_lectura < $activo->lectura_actual) {
            return back()->withErrors(['valor_lectura' => 'La nueva lectura debe ser igual o mayor a la lectura actual del activo (' . number_format($activo->lectura_actual) . ' ' . $activo->unidad_medida . ').'])->withInput();
        }

        // Transacción para garantizar la integridad de los datos
        return DB::transaction(function () use ($request, $activo) {
            
            // 1. Crear el nuevo registro de Lectura
            LecturaActivo::create([
                'activo_id' => $activo->id,
                'registrador_id' => auth()->id(), // Usuario autenticado
                'valor_lectura' => $request->valor_lectura,
                'unidad_medida' => $activo->unidad_medida, // Tomamos la unidad del Activo
                'fecha_lectura' => $request->fecha_lectura,
                'observaciones' => $request->observaciones,
            ]);
            
            // 2. Actualizar la Lectura Actual del Activo
            $activo->lectura_actual = $request->valor_lectura;
            $activo->save();

            // 3. (Futura Lógica de MP) Aquí se revisaría si la nueva lectura disparó alguna alerta de Mantenimiento Preventivo

            return redirect()->route('activos.lecturas.historial', $activo->id) 
                             ->with('success', "Lectura registrada. El activo **{$activo->codigo}** tiene ahora **" . number_format($activo->lectura_actual, 0, ',', '.') . " {$activo->unidad_medida}**.");
        });
    }

    /**
     * Muestra el formulario para editar una Lectura.
     */
    public function edit(LecturaActivo $lectura)
    {
        Gate::authorize('crear_lecturas');
        
        // Solo permitimos editar la lectura si fue registrada recientemente (Ej: hoy) o tiene permiso especial
        // Y solo si la lectura a editar es la ÚLTIMA lectura registrada para ese activo.
        
        $ultimaLectura = LecturaActivo::where('activo_id', $lectura->activo_id)
                                        ->latest('fecha_lectura')
                                        ->latest('id')
                                        ->first();

        if ($ultimaLectura->id != $lectura->id) {
             return redirect()->route('lecturas.show', $lectura->id)
                              ->with('error', 'Solo se puede modificar la **última** lectura registrada para el activo ' . $lectura->activo->codigo . '.');
        }

        return view('taller.lecturas.edit', compact('lectura'));
    }

    /**
     * Actualiza una Lectura específica (Solo la última del activo).
     */
    public function update(Request $request, LecturaActivo $lectura)
    {
        Gate::authorize('crear_lecturas');
        
        // Re-validación del chequeo de "última lectura" (seguridad adicional)
        $ultimaLectura = LecturaActivo::where('activo_id', $lectura->activo_id)
                                        ->latest('fecha_lectura')
                                        ->latest('id')
                                        ->first();

        if ($ultimaLectura->id != $lectura->id) {
             return redirect()->route('lecturas.show', $lectura->id)
                              ->with('error', 'Error de actualización: Solo se permite modificar la última lectura registrada.');
        }

        $activo = $lectura->activo;

        // Validamos la nueva lectura. Si el valor de la lectura cambia, debe seguir siendo >= la lectura anterior
        // Aquí la validación es compleja: si el nuevo valor es menor que la anterior, podría romper la secuencia.
        // Asumiremos que el nuevo valor debe ser >= la penúltima lectura si la hay.
        $penultimaLectura = LecturaActivo::where('activo_id', $lectura->activo_id)
                                          ->where('id', '!=', $lectura->id)
                                          ->latest('valor_lectura')
                                          ->first();
        
        $minValor = $penultimaLectura ? $penultimaLectura->valor_lectura : 0;

        $request->validate([
            'valor_lectura' => "required|integer|min:{$minValor}",
            'fecha_lectura' => 'required|date|before_or_equal:today',
            'observaciones' => 'nullable|string|max:500',
        ]);
        
        return DB::transaction(function () use ($request, $lectura, $activo) {
            
            // 1. Actualizar el registro de Lectura
            $lectura->update([
                'valor_lectura' => $request->valor_lectura,
                'fecha_lectura' => $request->fecha_lectura,
                'observaciones' => $request->observaciones,
            ]);

            // 2. Actualizar la Lectura Actual del Activo (Si se modificó la última lectura)
            $activo->lectura_actual = $request->valor_lectura;
            $activo->save();
            
            return redirect()->route('activos.lecturas.historial', $lectura->id)
                             ->with('success', "Lectura No. {$lectura->id} actualizada y activo **{$activo->codigo}** corregido.");
        });
    }


    /**
     * Muestra el historial de lecturas registradas para un activo específico.
     *
     * @param  \App\Models\Logistica\Taller\Activo  $activo
     * @return \Illuminate\View\View
     */
    public function show(Activo $activo)
    {
        // 1. Autorización: Se mantiene el permiso general para gestionar (CRUD) o se define uno específico
        Gate::authorize('ver_lecturas');
        
        // 2. Cargar el historial de lecturas (asumiendo que Activo tiene una relación 'lecturas')
        // Usamos la relación para obtener el historial, ordenado por fecha de lectura o ID descendente
        $lecturas = $activo->lecturas()
                            ->with('registrador') // Si quieres mostrar quién registró la lectura
                            ->orderBy('fecha_lectura', 'desc')
                            ->orderBy('id', 'desc')
                            ->paginate(20); // Paginación para tablas grandes

        // 3. Retornar la vista
        return view('taller.lecturas.show', compact('activo', 'lecturas'));
    }

    /**
     * Elimina una Lectura específica (Solo la última del activo).
     */
    public function destroy(LecturaActivo $lectura)
    {
        Gate::authorize('eliminar_lecturas');
        
        // Solo se puede eliminar la ÚLTIMA lectura registrada
        $ultimaLectura = LecturaActivo::where('activo_id', $lectura->activo_id)
                                        ->latest('fecha_lectura')
                                        ->latest('id')
                                        ->first();

        if ($ultimaLectura->id != $lectura->id) {
             return redirect()->route('lecturas.index')
                              ->with('error', 'Solo se puede eliminar la **última** lectura registrada para un activo.');
        }

        return DB::transaction(function () use ($lectura) {
            $activo = $lectura->activo;
            $unidad = $activo->unidad_medida;
            $codigo = $activo->codigo;

            // 1. Obtener la penúltima lectura para revertir el valor del activo
            $penultimaLectura = LecturaActivo::where('activo_id', $lectura->activo_id)
                                              ->where('id', '!=', $lectura->id)
                                              ->latest('valor_lectura')
                                              ->first();
            
            // 2. Eliminar la lectura
            $lectura->delete();

            // 3. Revertir la lectura actual del Activo
            $activo->lectura_actual = $penultimaLectura ? $penultimaLectura->valor_lectura : 0;
            $activo->save();

            return redirect()->route('lecturas.index', $activo->id) 
                             ->with('success', "Lectura eliminada. El valor del activo **{$codigo}** se revirtió a " . number_format($activo->lectura_actual) . " {$unidad}.");
        });
    }
}