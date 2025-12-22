<?php

namespace App\Http\Controllers\Produccion\Animales;


use App\Models\Produccion\Animales\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    /**
     * Muestra el listado de ubicaciones (CRUD R).
     */
    public function index()
    {
        $locations = Location::orderBy('name')->get();
        return view('produccion.animales.locations.index', compact('locations'));
    }

    /**
     * Muestra el formulario para crear una nueva ubicación.
     */
    public function create()
    {
        return view('produccion.animales.locations.create');
    }

    /**
     * Almacena una nueva ubicación en la base de datos (CRUD C).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:locations,name',
            'cost_center_id' => 'required|string|max:6',
            'is_active' => 'nullable|boolean', // Recibe 1 o 0 si se usa un checkbox
        ]);
        
        Location::create([
            'name' => $request->name,
            'cost_center_id' => $request->cost_center_id,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('locations.index')->with('success', 'Ubicación registrada exitosamente.');
    }
}