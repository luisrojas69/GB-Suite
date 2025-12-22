<?php

namespace App\Http\Controllers\Produccion\Agro;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Agro\Liquidacion;
use App\Models\Produccion\Agro\Molienda;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class LiquidacionController extends Controller
{
    /**
     * Muestra la lista de liquidaciones.
     */
    public function index()
    {
        // Se aplica el permiso de visualización
        $this->authorize('ver_liquidaciones');

        // Carga la relación 'molienda'
        $liquidaciones = Liquidacion::with('molienda')->orderBy('fecha_cierre', 'desc')->get();
        return view('produccion.agro.liquidaciones.index', compact('liquidaciones'));
    }

    /**
     * Muestra el formulario para generar una nueva liquidación.
     */
    public function create()
    {
        $this->authorize('generar_liquidaciones');

        // Obtener las Moliendas que aún NO tienen una liquidación asociada.
        // Se asume que el Molienda::class está correctamente definido.
        $moliendas_sin_liquidar = Molienda::whereDoesntHave('liquidacion')
            ->orderBy('fecha', 'desc')
            ->get()
            ->mapWithKeys(function ($molienda) {
                return [
                    $molienda->id => 
                        'Arrime #' . $molienda->id . 
                        ' - Peso: ' . number_format($molienda->toneladas_arrimadas, 2, ',', '.') . ' Tn' .
                        ' (' . Carbon::parse($molienda->fecha_arrime)->format('d/M/Y') . ')'
                ];
            });
        
        return view('produccion.agro.liquidaciones.create', compact('moliendas_sin_liquidar'));
    }

    /**
     * Almacena una nueva liquidación.
     */
    public function store(Request $request)
    {
        $this->authorize('generar_liquidaciones');

        $validated = $request->validate([
            'molienda_id'        => ['required', 'exists:moliendas,id', Rule::unique('liquidaciones', 'molienda_id')],
            'pol_cana'           => 'required|numeric|min:0|max:100',
            'fibra_cana'         => 'required|numeric|min:0|max:100',
            'precio_base'        => 'required|numeric|min:0|max:999999.9999',
            'deducibles'         => 'nullable|numeric|min:0|max:999999.99',
            'liquidacion_neta'   => 'required|numeric|min:0|max:999999.99',
            'fecha_cierre'       => 'nullable|date',
        ], [
            'molienda_id.unique' => 'Esta molienda ya tiene una liquidación registrada.'
        ]);
        
        Liquidacion::create($validated);

        return redirect()->route('liquidacion.index')
                         ->with('success', '¡Liquidación generada exitosamente!');
    }

    /**
     * Muestra el detalle de una liquidación.
     */
    public function show(Liquidacion $liquidacion)
    {
        $this->authorize('ver_liquidaciones');
        // Carga la relación Molienda
        $liquidacion->load('molienda');
        
        return view('produccion.agro.liquidaciones.show', compact('liquidacion'));
    }

    /**
     * Muestra el formulario para editar una liquidación.
     */
    public function edit(Liquidacion $liquidacion)
    {
        $this->authorize('generar_liquidaciones'); // Usamos el mismo permiso para editar y crear

        // Solo se necesita la liquidación para la vista de edición.
        // La relación molienda_id no se puede cambiar ya que es un campo unique 1:1.
        return view('produccion.agro.liquidaciones.edit', compact('liquidacion'));
    }

    /**
     * Actualiza la liquidación en la base de datos.
     */
    public function update(Request $request, Liquidacion $liquidacion)
    {
        $this->authorize('generar_liquidaciones');

        // Reglas de validación, ignorando el campo molienda_id
        $validated = $request->validate([
            'pol_cana'           => 'required|numeric|min:0|max:100',
            'fibra_cana'         => 'required|numeric|min:0|max:100',
            'precio_base'        => 'required|numeric|min:0|max:999999.9999',
            'deducibles'         => 'nullable|numeric|min:0|max:999999.99',
            'liquidacion_neta'   => 'required|numeric|min:0|max:999999.99',
            'fecha_cierre'       => 'nullable|date',
        ]);
        
        $liquidacion->update($validated);

        return redirect()->route('liquidacion.index')
                         ->with('success', '¡Liquidación actualizada exitosamente!');
    }

    /**
     * Elimina la liquidación.
     */
    public function destroy(Liquidacion $liquidacion)
    {
        $this->authorize('generar_liquidaciones'); // Permiso para destruir

        try {
            $liquidacion->delete();
            return redirect()->route('liquidacion.index')
                             ->with('success', 'Liquidación eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('liquidacion.index')
                             ->with('error', 'No se pudo eliminar la liquidación.');
        }
    }
}