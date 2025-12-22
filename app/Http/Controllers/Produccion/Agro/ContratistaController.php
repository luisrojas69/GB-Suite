<?php

namespace App\Http\Controllers\Produccion\Agro;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Agro\Contratista;
use Illuminate\Http\Request;

class ContratistaController extends Controller
{
    /**
     * Muestra una lista de los contratistas registrados.
     */
    public function index()
    {
        $contratistas = Contratista::orderBy('nombre')->get();
        return view('produccion.agro.contratistas.index', compact('contratistas'));
    }

    /**
     * Muestra el formulario para crear un nuevo contratista.
     */
    public function create()
    {
        return view('produccion.agro.contratistas.create');
    }

    /**
     * Almacena un nuevo contratista en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'rif' => 'nullable|string|max:20|unique:contratistas,rif',
            'persona_contacto' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
        ], [
            'rif.unique' => 'Ya existe un contratista con este RIF.',
        ]);

        Contratista::create($request->all());

        return redirect()->route('produccion.agro.contratistas.index')
                         ->with('success', 'Contratista **' . $request->nombre . '** creado exitosamente.');
    }

    /**
     * Muestra el contratista especificado.
     */
    public function show(Contratista $contratista)
    {
        return view('produccion.agro.contratistas.show', compact('contratista'));
    }

    /**
     * Muestra el formulario para editar el contratista especificado.
     */
    public function edit(Contratista $contratista)
    {
        return view('produccion.agro.contratistas.edit', compact('contratista'));
    }

    /**
     * Actualiza el contratista especificado en la base de datos.
     */
    public function update(Request $request, Contratista $contratista)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            // La validación unique debe ignorar el contratista actual
            'rif' => 'nullable|string|max:20|unique:contratistas,rif,' . $contratista->id,
            'persona_contacto' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
        ], [
            'rif.unique' => 'Ya existe un contratista con este RIF.',
        ]);

        $contratista->update($request->all());

        return redirect()->route('produccion.agro.contratistas.index')
                         ->with('success', 'Contratista **' . $contratista->nombre . '** actualizado exitosamente.');
    }

    /**
     * Elimina el contratista especificado de la base de datos (con SweetAlert2/AJAX).
     */
    public function destroy(Contratista $contratista)
    {
        try {
            // Eliminar el contratista.
            $contratista->delete();
            return response()->json(['success' => true, 'message' => 'Contratista eliminado correctamente.']);
        } catch (\Exception $e) {
            // Mensaje amigable en caso de error de integridad referencial (asociado a Moliendas).
            return response()->json(['success' => false, 'message' => 'No se puede eliminar el contratista porque está asociado a registros de Molienda o Cosecha. Debe actualizar o eliminar primero las referencias.']);
        }
    }
}