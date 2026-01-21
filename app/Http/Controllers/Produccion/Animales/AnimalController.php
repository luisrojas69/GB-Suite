<?php

namespace App\Http\Controllers\Produccion\Animales;

use App\Models\Produccion\Animales\Animal;
use App\Models\Produccion\Animales\Specie;
use App\Models\Produccion\Animales\Category;
use App\Models\Produccion\Animales\Owner;
use App\Models\Produccion\Animales\Location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use App\Exports\Produccion\Animals\AnimalsExport;
use Maatwebsite\Excel\Facades\Excel;

class AnimalController extends Controller
{
    // Carga las tablas maestras para los formularios
    protected function getMasterData()
    {
        return [
            'species' => Specie::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'owners' => Owner::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
        ];
    }

    /**
     * Muestra el listado del Inventario General (CRUD R - index).
     */
    public function index()
    {
        Gate::authorize('ver_animales');
        $animals = Animal::with(['specie', 'category', 'owner', 'location'])
                         ->where('is_active', true) // Solo mostrar activos por defecto
                         ->orderBy('iron_id')
                         ->paginate(50);
        
        return view('produccion.animales.animals.index', compact('animals'));
    }

    /**
     * Muestra el formulario para registrar un nuevo animal (CRUD C - create).
     */
    public function create()
    {
        Gate::authorize('crear_animal');
        $masterData = $this->getMasterData();
        return view('produccion.animales.animals.create', $masterData);
    }

    /**
     * Almacena un nuevo animal en la base de datos (CRUD C - store).
     */
    public function store(Request $request)
    {
      
        
        Animal::create($request->all());

        return redirect()->route('animals.index')->with('success', 'Animal registrado exitosamente.');
    }

    public function show(Animal $animal)
    {
        // 1. PROTECCIÓN DE ACCESO: Solo si tiene permiso para ver detalles
        Gate::authorize('ver_animales'); 
        
        // 2. CARGA DE RELACIONES: Cargar relaciones necesarias para la vista
        $animal->load([
            'specie',   // Especie/Raza
            'location',    // Padre/Madre
            'owner',
            'location',  // Ubicación actual
            // 'latestPesaje' // Si usa una relación para el último peso
        ]);

        // Aseguramos que la fecha de nacimiento sea un objeto Carbon si existe
        if ($animal->birth_date) {
            // Esto es útil si birth_date no fue casteado automáticamente en el modelo
            $animal->birth_date = \Carbon\Carbon::parse($animal->birth_date);
        }

        // Si su modelo Animal incluye una propiedad (accessor) para el peso actual, 
        // no necesitaría cargar una relación aquí.

        // 3. RETORNO DE LA VISTA
        // La vista debe estar en: resources/views/produccion/animales/show.blade.php
        return view('produccion.animales.animals.show', compact('animal'));
    }

    /**
     * Muestra el formulario para editar un animal existente (CRUD U - edit).
     */
    public function edit(Animal $animal)
    {
        Gate::authorize('editar_animal');
        $masterData = $this->getMasterData();
        return view('produccion.animales.animals.edit', compact('animal') + $masterData);
    }

    /**
     * Actualiza el animal en la base de datos (CRUD U - update).
     */
    public function update(Request $request, Animal $animal)
    {
        $request->validate([
            // La regla unique debe ignorar el ID del animal que se está actualizando
            'iron_id' => [
                'nullable', 
                'string', 
                'max:50', 
                Rule::unique('animals', 'iron_id')->ignore($animal->id)->where(fn ($query) => $query->whereNotNull('iron_id')),
            ],
            'lot' => 'nullable|string|max:50',
            'sex' => 'required|in:Macho,Hembra',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'specie_id' => 'required|exists:species,id',
            'category_id' => 'required|exists:categories,id',
            'owner_id' => 'required|exists:owners,id',
            'location_id' => 'required|exists:locations,id',
            // is_active se maneja en el módulo de Baja/Mortalidad, pero se puede incluir si se quiere modificar.
        ]);
        
        $animal->update($request->all());

        return redirect()->route('animals.index')->with('success', 'Animal #'.$animal->iron_id.' actualizado exitosamente.');
    }

    /**
     * Elimina el animal de la base de datos (CRUD D - destroy).
     * NOTA IMPORTANTE: Esto eliminará CUALQUIER registro relacionado (Pesajes, Eventos).
     * Se recomienda usar 'is_active = false' (Baja) en lugar de eliminar,
     * pero se implementa para completar el CRUD.
     */
    public function destroy(Animal $animal)
    {
        Gate::authorize('eliminar_animal');
        try {
            // El 'onDelete('cascade')' en las migraciones manejará la eliminación de pesajes y eventos.
            $animal->delete(); 
            return redirect()->route('animals.index')->with('success', 'Animal eliminado permanentemente (ID: '.$animal->iron_id.').');
        } catch (\Exception $e) {
            // Manejar errores de claves foráneas restantes que onDelete('cascade') no cubrió
            return redirect()->route('animals.index')->with('error', 'Error al eliminar. Verifique que no tenga dependencias no cascada.');
        }
    }
    
    public function export() 
    {
        return Excel::download(new AnimalsExport, 'inventario_animales_' . now()->format('d-m-Y') . '.xlsx');
    }
}