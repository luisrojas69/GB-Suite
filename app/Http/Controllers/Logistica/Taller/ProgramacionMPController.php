<?php

namespace App\Http\Controllers\Logistica\Taller;

use App\Http\Controllers\Controller;
use App\Models\Logistica\Taller\Activo;
use App\Models\Logistica\Taller\Checklist;
use App\Models\Logistica\Taller\ProgramacionMP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProgramacionMPController extends Controller
{
    /**
     * Muestra una lista de las programaciones (No usada en este diseño, se ve en activos.show)
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Activo $activo)
    {
        // Esta vista se integra en activos.show, pero por convención debe existir.
        Gate::authorize('programar_mp');
        return view('taller.activos.show', ['activo' => $activo]);
    }

    public function create(Activo $activo)
    {
        Gate::authorize('programar_mp');

        $checklists = Checklist::where('tipo_activo', $activo->tipo)
                                ->orderBy('nombre')
                                ->get();

        return view('taller.programacionMP.create', compact('activo', 'checklists'));
    }

    /**
     * Almacena una nueva programación de Mantenimiento Preventivo para un Activo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Logistica\Taller\Activo  $activo
     * @return \Illuminate\Http\Response
     */



    public function store(Request $request, Activo $activo)
    {
       // dd($request);
        Gate::authorize('programar_mp');

        // 1. **VALIDACIÓN CORREGIDA**
        $request->validate([
            'activo_id' => 'required|exists:activos,id', // Se necesita validar este campo oculto
            'checklist_id' => 'required|exists:checklists,id',
            // Validamos la meta de lectura, debe ser mayor a la lectura actual del activo
            'proximo_valor_lectura' => 'nullable|integer|min:' . ($activo->lectura_actual + 1), 
            // Validamos la meta de fecha, debe ser una fecha posterior a hoy
            'proxima_fecha_mantenimiento' => 'nullable|date|after_or_equal:today', 
        ]);
        
        // Al menos uno de los dos campos (lectura o fecha) debe estar presente.
        if (empty($request->proximo_valor_lectura) && empty($request->proxima_fecha_mantenimiento)) {
            return redirect()->back()
                             ->withInput()
                             ->withErrors(['metas' => 'Debe ingresar al menos una meta: Próximo Valor Lectura o Próxima Fecha de Mantenimiento.']);
        }

        $checklist = Checklist::findOrFail($request->checklist_id);
        
        // Crear la programación
        ProgramacionMP::create([
            'activo_id' => $activo->id, // Usamos el activo inyectado
            'checklist_id' => $checklist->id,
            
            // 2. **USAMOS LAS METAS FINALES DEL FORMULARIO**
            'proximo_valor_lectura' => $request->proximo_valor_lectura, // Usar el nombre del campo del modelo (si es correcto)
            'proxima_fecha_mantenimiento' => $request->proxima_fecha_mantenimiento, // Usar el nombre del campo del modelo (si es correcto)
            
            // 3. **CAMPOS BASE**
            'ultimo_valor_ejecutado' => $activo->lectura_actual, 
            'ultima_ejecucion_fecha' => now(), // Se asume que la programación se basa en la fecha actual
            'status' => 'Vigente',
        ]);

        return redirect()->route('activos.show', $activo->id . '#mp-tab')
                         ->with('success', 'Mantenimiento Preventivo programado exitosamente para ' . $checklist->nombre);
    }

    /**
     * Elimina una programación específica de un Activo.
     *
     * @param  \App\Models\Logistica\Taller\Activo  $activo
     * @param  \App\Models\Logistica\Taller\ProgramacionMP  $programacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activo $activo, ProgramacionMP $programacion)
    {
        Gate::authorize('programar_mp');

        // Verificar que la programación pertenezca al activo
        if ($programacion->activo_id !== $activo->id) {
            abort(403, 'Acceso no autorizado a esta programación.');
        }

        $programacion->delete();

        return back()->with('success', 'Programación de MP eliminada correctamente.');
    }
    
    // Los métodos show, edit y update no son estrictamente necesarios para este CRUD
    // ya que la modificación se puede manejar con un simple delete/store en la práctica.
    public function show($id) { abort(404); }
    public function edit($id) { abort(404); }
    public function update(Request $request, $id) { abort(404); }
}