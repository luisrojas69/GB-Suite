<?php

namespace App\Http\Controllers\Produccion\Agro;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Agro\Variedad;
use Illuminate\Http\Request;

class VariedadController extends Controller
{
    /**
     * Muestra una lista de las variedades de caña.
     */
    public function index()
    {
        // Se utiliza 'get()' para cargar todas las variedades y listarlas en la tabla.
        $variedades = Variedad::orderBy('nombre')->get();
        return view('produccion.agro.variedades.index', compact('variedades'));
    }

    /**
     * Muestra el formulario para crear una nueva variedad.
     */
    public function create()
    {
        // No se necesitan datos de otras tablas para crear una variedad
        return view('produccion.agro.variedades.create');
    }

    /**
     * Almacena una nueva variedad en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:variedades,nombre',
            'codigo' => 'nullable|string|max:10|unique:variedades,codigo',
            'meta_pol_cana' => 'nullable|numeric|min:0|max:99999.99',
            'descripcion' => 'nullable|string',
        ], [
            'nombre.unique' => 'Ya existe una variedad con este nombre.',
            'codigo.unique' => 'Ya existe una variedad con este código.',
        ]);

        Variedad::create($request->all());

        return redirect()->route('produccion.agro.variedades.index')
                         ->with('success', 'Variedad **' . $request->nombre . '** creada exitosamente.');
    }

    /**
     * Muestra la variedad especificada.
     */
    public function show(Variedad $variedad)
    {
        // En un catálogo simple como este, show() es útil para ver los detalles.
        return view('produccion.agro.variedades.show', compact('variedad'));
    }

    /**
     * Muestra el formulario para editar la variedad especificada.
     */
    public function edit(Variedad $variedad)
    {
        return view('produccion.agro.variedades.edit', compact('variedad'));
    }

    /**
     * Actualiza la variedad especificada en la base de datos.
     */
    public function update(Request $request, Variedad $variedad)
    {
        $request->validate([
            // La validación unique debe ignorar la variedad actual que estamos editando
            'nombre' => 'required|string|max:100|unique:variedades,nombre,' . $variedad->id,
            'codigo' => 'nullable|string|max:10|unique:variedades,codigo,' . $variedad->id,
            'meta_pol_cana' => 'nullable|numeric|min:0|max:99999.99',
            'descripcion' => 'nullable|string',
        ], [
            'nombre.unique' => 'Ya existe una variedad con este nombre.',
            'codigo.unique' => 'Ya existe una variedad con este código.',
        ]);

        $variedad->update($request->all());

        return redirect()->route('produccion.agro.variedades.index')
                         ->with('success', 'Variedad **' . $variedad->nombre . '** actualizada exitosamente.');
    }

    /**
     * Elimina la variedad especificada de la base de datos.
     */
    public function destroy(Variedad $variedad)
    {
        try {
            // Eliminar la variedad. Si está en uso en 'tablones', Laravel lanzará una excepción
            // (Integridad Referencial), aunque definimos 'variedad_id' como nullable, es bueno
            // mantener el try/catch por si tiene otras FKs.
            $variedad->delete();
            return response()->json(['success' => true, 'message' => 'Variedad eliminada correctamente.']);
        } catch (\Exception $e) {
            // Mensaje más amigable en caso de error de integridad referencial.
            return response()->json(['success' => false, 'message' => 'No se puede eliminar la variedad porque está asociada a uno o más Tablones. Debe actualizar o eliminar primero las referencias.']);
        }
    }

    public function storeAjax(Request $request)
    {
        try {
            $variedad = Variedad::create($request->all());

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $variedad->id,
                    'nombre' => $variedad->nombre
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar en base de datos.'
            ], 500);
        }
    }
}