<?php

namespace App\Http\Controllers\Produccion\Agro;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Agro\Tarifa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TarifaController extends Controller
{
    /**
     * Muestra una lista de todas las tarifas.
     */
    public function index()
    {
        $this->authorize('gestionar_tarifas');

        // Obtener tarifas ordenadas por concepto y fecha de vigencia (más reciente primero)
        $tarifas = Tarifa::orderBy('concepto')->orderBy('fecha_vigencia', 'desc')->get();
        
        return view('produccion.agro.tarifas.index', compact('tarifas'));
    }

    /**
     * Muestra el formulario para crear una nueva tarifa.
     */
    public function create()
    {
        $this->authorize('gestionar_tarifas');
        
        $estados = ['Activo' => 'Activo', 'Inactivo' => 'Inactivo'];
        return view('produccion.agro.tarifas.create', compact('estados'));
    }

    /**
     * Almacena una nueva tarifa.
     */
    public function store(Request $request)
    {
        $this->authorize('gestionar_tarifas');

        $validated = $request->validate([
            'concepto'         => 'required|string|max:100',
            'valor'            => 'required|numeric|min:0.0001|max:999999.9999',
            'unidad'           => 'required|string|max:20',
            'fecha_vigencia'   => ['required', 'date', 
                                    // Validación única: el concepto no debe repetirse en la misma fecha
                                    Rule::unique('tarifas')->where(function ($query) use ($request) {
                                        return $query->where('concepto', $request->concepto)
                                                     ->where('fecha_vigencia', $request->fecha_vigencia);
                                    })
                                  ],
            'estado'           => 'required|in:Activo,Inactivo',
            'descripcion'      => 'nullable|string',
        ], [
            'fecha_vigencia.unique' => 'Ya existe una tarifa con este concepto registrada para la misma fecha de vigencia.'
        ]);
        
        Tarifa::create($validated);

        return redirect()->route('liquidacion.tarifas.index')
                         ->with('success', 'Tarifa registrada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una tarifa.
     */
    public function edit(Tarifa $tarifa)
    {
        $this->authorize('gestionar_tarifas');
        
        $estados = ['Activo' => 'Activo', 'Inactivo' => 'Inactivo'];
        return view('produccion.agro.tarifas.edit', compact('tarifa', 'estados'));
    }

    /**
     * Actualiza la tarifa en la base de datos.
     */
    public function update(Request $request, Tarifa $tarifa)
    {
        $this->authorize('gestionar_tarifas');

        // Reglas de validación, ignorando la combinación 'concepto' y 'fecha_vigencia' del registro actual
        $validated = $request->validate([
            'concepto'         => 'required|string|max:100',
            'valor'            => 'required|numeric|min:0.0001|max:999999.9999',
            'unidad'           => 'required|string|max:20',
            'fecha_vigencia'   => ['required', 'date', 
                                    Rule::unique('tarifas')->ignore($tarifa->id)->where(function ($query) use ($request) {
                                        return $query->where('concepto', $request->concepto)
                                                     ->where('fecha_vigencia', $request->fecha_vigencia);
                                    })
                                  ],
            'estado'           => 'required|in:Activo,Inactivo',
            'descripcion'      => 'nullable|string',
        ], [
            'fecha_vigencia.unique' => 'Ya existe otra tarifa con este concepto registrada para la misma fecha de vigencia.'
        ]);
        
        $tarifa->update($validated);

        return redirect()->route('liquidacion.tarifas.index')
                         ->with('success', 'Tarifa actualizada exitosamente.');
    }

    /**
     * Elimina la tarifa.
     */
    public function destroy(Tarifa $tarifa)
    {
        $this->authorize('gestionar_tarifas');

        try {
            $tarifa->delete();
            return redirect()->route('liquidacion.tarifas.index')
                             ->with('success', 'Tarifa eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('liquidacion.tarifas.index')
                             ->with('error', 'No se pudo eliminar la tarifa. Podría estar relacionada con liquidaciones ya generadas.');
        }
    }
}