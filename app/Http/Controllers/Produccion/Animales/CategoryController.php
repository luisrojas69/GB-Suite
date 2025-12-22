<?php

namespace App\Http\Controllers\Produccion\Animales;

use App\Models\Produccion\Animales\Category;
use App\Models\Produccion\Animales\Specie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Muestra el listado de categorías (CRUD R).
     */
    public function index()
    {
        // Carga la especie relacionada para mostrarla en la tabla
        $categories = Category::with('species')->orderBy('species_id')->orderBy('name')->get();
        return view('produccion.animales.categories.index', compact('categories'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría (CRUD C).
     */
    public function create()
    {
        $species = Specie::all();
        return view('produccion.animales.categories.create', compact('species'));
    }

    /**
     * Almacena una nueva categoría en la base de datos (CRUD C).
     */
    public function store(Request $request)
    {
        $request->validate([
            'species_id' => 'required|exists:species,id',
            'name' => 'required|string|max:50',
            // El CeCo debe ser un string de 6 caracteres (según Profit) y es OBLIGATORIO
            'cost_center_id' => 'required|string|size:4', 
            // Validación de unicidad combinada
            'name' => 'unique:categories,name,NULL,id,species_id,' . $request->species_id,
        ]);
        
        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente.');
    }
}