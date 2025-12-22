<?php

namespace App\Http\Controllers\Produccion\Animales;


use App\Models\Produccion\Animales\Specie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class SpecieController extends Controller
{
    /**
     * Muestra el listado de especies (CRUD R).
     */
    public function index()
    {
        Gate::authorize('gestionar_especies');
        // En una primera ejecución, usted debe precargar: Bovino, Ovino, Equino.
        $species = Specie::orderBy('id')->get();
        return view('produccion.animales.species.index', compact('species'));
    }

    /**
     * Muestra el formulario para crear una nueva especie.
     */
    public function create()
    {
        Gate::authorize('gestionar_especies');
        return view('produccion.animales.species.create');
    }

    /**
     * Almacena una nueva especie en la base de datos (CRUD C).
     */
    public function store(Request $request)
    {
        Gate::authorize('gestionar_especies');
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('species', 'name')],
            // Otros campos de validación...
        ]);
        
        // Normalizamos el nombre (ej: "BOVINO")
        $request->merge(['name' => ucfirst(strtolower($request->name))]);
        
        Specie::create($request->all());

        return redirect()->route('species.index')->with('success', 'Especie creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una especie.
     */
    public function edit(Specie $species)
    {
        Gate::authorize('gestionar_especies');
        return view('produccion.animales.species.edit', compact('species'));
    }

    /**
     * Actualiza la especie.
     */
    public function update(Request $request, Specie $species)
    {
        Gate::authorize('gestionar_especies');
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('species', 'name')->ignore($species->id)],
        ]);

        $species->update($request->all());

        return redirect()->route('species.index')->with('success', 'Especie actualizada correctamente.');
    }

    /**
     * Elimina la especie.
     */
    public function destroy(Specie $species)
    {
        Gate::authorize('gestionar_especies');
        $species->delete();
        return redirect()->route('species.index')->with('success', 'Especie eliminada.');
    }
}