<?php

namespace App\Http\Controllers\Produccion\Agro;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Agro\Destino;
use Illuminate\Http\Request;

class DestinoController extends Controller
{
    /**
     * Muestra una lista de los destinos (centrales) de caña.
     */
    public function index()
    {
        // Se utiliza 'get()' para cargar todos los destinos y listarlos en la tabla.
        $destinos = Destino::orderBy('nombre')->get();
        return view('produccion.agro.destinos.index', compact('destinos'));
    }

    /**
     * Muestra el formulario para crear un nuevo destino.
     */
    public function create()
    {
        return view('produccion.agro.destinos.create');
    }

    /**
     * Almacena un nuevo destino en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:10|unique:destinos,codigo',
        ], [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Ya existe un destino con este código.',
        ]);

        Destino::create($request->all());

        return redirect()->route('produccion.agro.destinos.index')
                         ->with('success', 'Destino **' . $request->nombre . '** creado exitosamente.');
    }

    /**
     * Muestra el destino especificado.
     */
    public function show(Destino $destino)
    {
        return view('produccion.agro.destinos.show', compact('destino'));
    }

    /**
     * Muestra el formulario para editar el destino especificado.
     */
    public function edit(Destino $destino)
    {
        return view('produccion.agro.destinos.edit', compact('destino'));
    }

    /**
     * Actualiza el destino especificado en la base de datos.
     */
    public function update(Request $request, Destino $destino)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            // La validación unique debe ignorar el destino actual
            'codigo' => 'required|string|max:10|unique:destinos,codigo,' . $destino->id,
        ], [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Ya existe un destino con este código.',
        ]);

        $destino->update($request->all());

        return redirect()->route('produccion.agro.destinos.index')
                         ->with('success', 'Destino **' . $destino->nombre . '** actualizado exitosamente.');
    }

    /**
     * Elimina el destino especificado de la base de datos (con SweetAlert2/AJAX).
     */
    public function destroy(Destino $destino)
    {
        try {
            // Eliminar el destino.
            $destino->delete();
            return response()->json(['success' => true, 'message' => 'Destino eliminado correctamente.']);
        } catch (\Exception $e) {
            // Mensaje amigable en caso de error de integridad referencial (asociado a Moliendas).
            return response()->json(['success' => false, 'message' => 'No se puede eliminar el destino porque está asociado a registros de Molienda. Debe actualizar o eliminar primero las referencias.']);
        }
    }
}