<?php

namespace App\Http\Controllers\Produccion\Agro;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Agro\Zafra;
use Illuminate\Http\Request;

class ZafraController extends Controller
{
    /**
     * Define las opciones del campo ENUM 'estado'.
     */
    private $estados = [
        'Planeada' => 'Planeada', 
        'Activa' => 'Activa', 
        'Cerrada' => 'Cerrada'
    ];

    /**
     * Muestra una lista de las zafras.
     */
    public function index()
    {
        $zafras = Zafra::orderBy('anio_inicio', 'desc')->get();
        return view('produccion.agro.zafras.index', compact('zafras'));
    }

    /**
     * Muestra el formulario para crear una nueva zafra.
     */
    public function create()
    {
        $estados = $this->estados;
        return view('produccion.agro.zafras.create', compact('estados'));
    }

    /**
     * Almacena una nueva zafra en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:zafras,nombre',
            'anio_inicio' => 'required|integer|min:2000|max:2099',
            'anio_fin' => 'required|integer|min:' . (intval($request->anio_inicio) + 1) . '|max:2099',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:' . implode(',', array_keys($this->estados)),
        ], [
            'nombre.unique' => 'Ya existe una zafra con este nombre.',
            'anio_fin.min' => 'El año de fin debe ser al menos un año después del año de inicio.',
            'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
        ]);

        Zafra::create($request->all());

        return redirect()->route('produccion.agro.zafras.index')
                         ->with('success', 'Zafra **' . $request->nombre . '** creada exitosamente.');
    }

    /**
     * Muestra la zafra especificada.
     */
    public function show(Zafra $zafra)
    {
        return view('produccion.agro.zafras.show', compact('zafra'));
    }

    /**
     * Muestra el formulario para editar la zafra especificada.
     */
    public function edit(Zafra $zafra)
    {
        $estados = $this->estados;
        return view('produccion.agro.zafras.edit', compact('zafra', 'estados'));
    }

    /**
     * Actualiza la zafra especificada en la base de datos.
     */
    public function update(Request $request, Zafra $zafra)
    {
        $request->validate([
            // Ignorar la zafra actual en la validación unique
            'nombre' => 'required|string|max:50|unique:zafras,nombre,' . $zafra->id,
            'anio_inicio' => 'required|integer|min:2000|max:2099',
            'anio_fin' => 'required|integer|min:' . (intval($request->anio_inicio) + 1) . '|max:2099',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:' . implode(',', array_keys($this->estados)),
        ], [
            'nombre.unique' => 'Ya existe una zafra con este nombre.',
            'anio_fin.min' => 'El año de fin debe ser al menos un año después del año de inicio.',
            'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
        ]);

        $zafra->update($request->all());

        return redirect()->route('produccion.agro.zafras.index')
                         ->with('success', 'Zafra **' . $zafra->nombre . '** actualizada exitosamente.');
    }

    /**
     * Elimina la zafra especificada de la base de datos (con SweetAlert2/AJAX).
     */
    public function destroy(Zafra $zafra)
    {
        try {
            // Eliminar la zafra.
            $zafra->delete();
            return response()->json(['success' => true, 'message' => 'Zafra eliminada correctamente.']);
        } catch (\Exception $e) {
            // Mensaje amigable en caso de error de integridad referencial (asociada a Moliendas).
            return response()->json(['success' => false, 'message' => 'No se puede eliminar la Zafra porque está asociada a registros de Molienda. Debe actualizar o eliminar primero las referencias.']);
        }
    }
}