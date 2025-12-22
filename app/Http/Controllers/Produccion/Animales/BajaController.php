<?php

namespace App\Http\Controllers\Produccion\Animales;

use App\Models\Produccion\Animales\Animal;
use App\Models\Produccion\Animales\AnimalEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class BajaController extends Controller
{
    // Muestra el formulario de búsqueda de animal para darle de baja
    public function create()
    {
        return view('produccion.animales.bajas.create');
    }


    public function index()
    {
        Gate::authorize('ver_bajas');
        // Carga todos los eventos, ordenados por fecha de evento reciente, e incluye el animal relacionado
        $events = AnimalEvent::with('animal')
                             ->orderByDesc('event_date')
                             ->paginate(50); // Paginación recomendada
                             
        return view('produccion.animales.bajas.index', compact('events'));
    }
        
    // Busca el animal y muestra el formulario de baja
    public function search(Request $request)
    {
        $request->validate(['iron_id' => 'required|string|max:50']);
        
        $animal = Animal::where('iron_id', $request->iron_id)
                        ->where('is_active', true) // Solo animales activos
                        ->first();
                        
        if (!$animal) {
            return back()->with('error', "Animal con ID/Tatuaje '{$request->iron_id}' no encontrado o ya está inactivo.");
        }
        
        return view('produccion.animales.bajas.create', compact('animal'));
    }

    // Procesa el formulario de baja y actualiza el estado
    public function store(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'event_type' => 'required|in:Mortalidad,Venta,Traslado,Descarte',
            'event_date' => 'required|date|before_or_equal:today',
            'cause' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $animal = Animal::findOrFail($request->animal_id);
        
        // 1. Crear el registro del evento
        AnimalEvent::create($request->all());
        
        // 2. Dar de baja al animal
        $animal->is_active = false;
        $animal->save();

        return redirect()->route('animals.index')->with('success', "Animal #{$animal->iron_id} dado de baja (Motivo: {$request->event_type}) exitosamente.");
    }
}