<?php

namespace App\Http\Controllers\Produccion\Animales;

use App\Models\Produccion\Animales\Owner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OwnerController extends Controller
{
    /**
     * Muestra el listado de propietarios (CRUD R).
     */
    public function index()
    {
        // Los valores iniciales serán "Granja Boraure" y "Hacienda Boraure"
        $owners = Owner::orderBy('name')->get();
        return view('produccion.animales.owners.index', compact('owners'));
    }

    /**
     * Muestra el formulario para crear un nuevo propietario.
     */
    public function create()
    {
        return view('produccion.animales.owners.create');
    }

    /**
     * Almacena un nuevo propietario en la base de datos (CRUD C).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:owners,name',
        ]);
        
        $request->merge(['name' => ucfirst(strtolower($request->name))]);
        
        Owner::create($request->all());

        return redirect()->route('owners.index')->with('success', 'Propietario registrado exitosamente.');
    }
}