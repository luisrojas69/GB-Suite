<?php

namespace App\Http\Controllers\Logistica\Taller;

use App\Http\Controllers\Controller;
use App\Models\Logistica\Taller\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ChecklistController extends Controller
{
    /** Muestra el listado de plantillas de MP */

    public function index()
    {
        // Autorización para ver la lista de Checklists
        //Gate::authorize('gestionar_checklists'); 
        
        $checklists = Checklist::orderBy('tipo_activo')->orderBy('nombre')->paginate(10);
        
        return view('taller.checklists.index', compact('checklists'));
    }

    public function show(Checklist $checklist)
    {
        Gate::authorize('gestionar_checklists');
        
        return view('taller.checklists.show', compact('checklist'));
    }


    /**
     * Muestra el formulario para editar un Checklist.
     */
    public function edit(Checklist $checklist)
    {
        Gate::authorize('gestionar_checklists');
        
        // Asumimos los tipos de activo válidos
        $tipos_activo = ['Tractor', 'Camión', 'Camioneta', 'Moto', 'Cosechadora', 'Zorra', 'Otro'];

        return view('taller.checklists.edit', compact('checklist', 'tipos_activo'));
    }



    /** Muestra el formulario de creación */
    public function create()
    {
      //  Gate::authorize('gestionar_checklists');
        
        // Asumimos los tipos de activo válidos que ya usaste en la migración de Activos
        $tipos_activo = ['Tractor', 'Camión', 'Camioneta', 'Moto', 'Cosechadora', 'Zorra', 'Otro'];

        return view('taller.checklists.create', compact('tipos_activo'));
    }


    /**
     * Actualiza un Checklist específico.
     */
    public function update(Request $request, Checklist $checklist)
    {
        Gate::authorize('gestionar_checklists');
        
        $tipos_activo_validos = ['Tractor', 'Camión', 'Camioneta', 'Moto', 'Cosechadora', 'Zorra', 'Otro'];

        $request->validate([
            // La validación de unicidad debe ignorar el checklist actual
            'nombre' => ['required', 'string', 'max:255', Rule::unique('checklists', 'nombre')->ignore($checklist->id)],
            'tipo_activo' => ['required', Rule::in($tipos_activo_validos)],
            'intervalo_referencia' => ['required', 'string', 'max:50'],
            'descripcion_tareas' => ['required', 'string'],
        ]);

        $checklist->update($request->all());

        return redirect()->route('checklists.show', $checklist->id)
                         ->with('success', 'Plan de Mantenimiento actualizado exitosamente.');
    }

    /**
     * Elimina un Checklist específico.
     */
    public function destroy(Checklist $checklist)
    {
        Gate::authorize('gestionar_checklists');

        // PREVENCIÓN DE ELIMINACIÓN: Verifica si está siendo usado en alguna ProgramacionMP
        if ($checklist->programacionesMP()->exists()) {
             return redirect()->route('checklists.index')
                              ->with('error', 'No se puede eliminar el Checklist porque está asociado a Programaciones de Mantenimiento.');
        }

        $checklist->delete();

        return redirect()->route('checklists.index')
                         ->with('success', 'Plan de Mantenimiento (Checklist) eliminado exitosamente.');
    }

    /** Almacena la nueva plantilla */
    public function store(Request $request)
    {
        Gate::authorize('programar_mp');

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_activo' => 'required|string|max:50',
            'intervalo_referencia' => 'required|string|max:50',
            'tareas' => 'required|array', // Se espera un array de tareas
            'tareas.*' => 'required|string|max:500', 
        ]);

        // Las tareas se guardarán serializadas como JSON o como texto separado por líneas
        Checklist::create([
            'nombre' => $request->nombre,
            'tipo_activo' => $request->tipo_activo,
            'intervalo_referencia' => $request->intervalo_referencia,
            'descripcion_tareas' => json_encode($request->tareas), // Guardamos como JSON
        ]);

        return redirect()->route('checklists.index')->with('success', 'Plantilla de Checklist creada exitosamente.');
    }
    
    // show, edit, update, y destroy se implementarían de forma estándar
}