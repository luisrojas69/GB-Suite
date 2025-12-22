<?php

namespace App\Http\Controllers\Produccion\Areas;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Areas\Sector;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectorController extends Controller
{
    /**
     * Muestra una lista de todos los sectores.
     */
    public function index()
    {
        $sectores = Sector::orderBy('codigo_sector')->get();
        return view('produccion.areas.sectores.index', compact('sectores'));
    }

    /**
     * Muestra el formulario para crear un nuevo sector.
     */
    public function create()
    {
        return view('produccion.areas.sectores.create');
    }

    /**
     * Almacena un nuevo sector en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo_sector' => [
                'required', 
                'string', 
                'max:5', 
                // Asegura que el código sea único
                Rule::unique('sectores', 'codigo_sector'),
                // Regla para asegurar que solo contiene dígitos (o letras si lo desea más flexible)
                'regex:/^[a-zA-Z0-9]+$/', 
            ],
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ], [
            'codigo_sector.unique' => 'El código del sector ya existe. Debe ser único.',
            'codigo_sector.regex' => 'El código del sector solo puede contener letras y números.',
        ]);

        Sector::create($request->all());

        return redirect()->route('produccion.areas.sectores.index')
                         ->with('success', '✅ Sector creado exitosamente.');
    }


    /**
     * Muestra la información detallada de un sector específico.
     */
    public function show(Sector $sector)
    {
        // Aunque la ruta show no se usa en el resource, si la definimos, Laravel la encontrará.
        // Pero para simplificar el flujo, usualmente se muestra esta información en el "edit" o "index".
        // Sin embargo, si necesita una vista de detalle separada:
        return view('produccion.areas.sectores.show', compact('sector'));
    }

    /**
     * Muestra el formulario para editar un sector existente.
     */
    public function edit(Sector $sector)
    {
        dd($sector->id);
        return view('produccion.areas.sectores.edit', compact('sector'));
    }

    /**
     * Actualiza un sector existente en la base de datos.
     */
    public function update(Request $request, Sector $sector)
    {
        $request->validate([
            'codigo_sector' => [
                'required', 
                'string', 
                'max:5', 
                // Asegura que el código sea único, EXCLUYENDO el sector actual.
                Rule::unique('sectores', 'codigo_sector')->ignore($sector->id),
                'regex:/^[a-zA-Z0-9]+$/', 
            ],
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ], [
            'codigo_sector.unique' => 'El código del sector ya existe. Debe ser único.',
        ]);

        $sector->update($request->all());

        return redirect()->route('produccion.areas.sectores.index')
                         ->with('success', '✅ Sector "' . $sector->nombre . '" actualizado exitosamente.');
    }

    /**
     * Elimina un sector de la base de datos.
     * La eliminación en cascada (definida en la migración) se encargará de borrar Lotes y Tablones asociados.
     */
    public function destroy(Sector $sector)
    {
        $nombreSector = $sector->nombre;
        
        // Política de seguridad: Spatie debería haberlo manejado, pero es una buena práctica la verificación final.
        if (auth()->user()->cannot('eliminar_sectores')) {
             return redirect()->route('produccion.areas.sectores.index')
                              ->with('error', '❌ Permiso denegado para eliminar sectores.');
        }

        try {
            $sector->delete();
            return redirect()->route('produccion.areas.sectores.index')
                             ->with('success', '🗑️ Sector "' . $nombreSector . '" y todas sus áreas asociadas han sido eliminados.');
        } catch (\Exception $e) {
            // Manejo de errores si la eliminación falla por alguna razón (ej. restricción de BD no definida correctamente)
            return redirect()->route('produccion.areas.sectores.index')
                             ->with('error', '❌ Error al eliminar el sector: ' . $e->getMessage());
        }
    }
}