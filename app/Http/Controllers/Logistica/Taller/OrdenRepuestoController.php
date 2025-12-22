<?php

// app/Http/Controllers/Logistica/Taller/OrdenRepuestoController.php

namespace App\Http\Controllers\Logistica\Taller;

use App\Http\Controllers\Controller;
use App\Models\Logistica\Taller\OrdenServicio;
use App\Models\Logistica\Taller\OrdenRepuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrdenRepuestoController extends Controller
{
    /** Agrega un repuesto/insumo a una Orden de Servicio */
    public function store(Request $request, OrdenServicio $orden)
    {
        // Solo puede cargar repuestos si tiene permisos y la orden no está cerrada
        Gate::authorize('gestionar_ordenes'); 
        if ($orden->status === 'Cerrada') {
            return back()->with('error', 'No se pueden añadir repuestos a una orden cerrada.');
        }

        $request->validate([
            'nombre_repuesto' => 'required|string|max:255',
            'cantidad_utilizada' => 'required|numeric|min:0.01',
            // El costo es crítico para los reportes gerenciales
            'costo_unitario' => 'required|numeric|min:0', 
            'codigo_inventario' => 'nullable|string|max:50',
        ]);

        // 1. Crear el registro del Repuesto Consumido
        $costoTotalRepuesto = $request->cantidad_utilizada * $request->costo_unitario;
        
        $repuesto = $orden->repuestos()->create([
            'nombre_repuesto' => $request->nombre_repuesto,
            'codigo_inventario' => $request->codigo_inventario,
            'cantidad_utilizada' => $request->cantidad_utilizada,
            'costo_unitario' => $request->costo_unitario,
            'costo_total' => $costoTotalRepuesto,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
        // Recargar las relaciones (repuestos y posiblemente costos)
            // 2. Actualizar los totales en la Orden de Servicio (MEJOR PRÁCTICA: Usar Mutator o Observer)
        $this->actualizarCostosOrden($orden);
        $orden->load('repuestos'); 
        
        // Devolver el partial de repuestos actualizado
        return view('taller.ordenes.partials.repuestos_form', compact('orden'))->render();
    }

        // Código original (si no es AJAX, redirige)
        return redirect()->route('ordenes.show', $orden->id . '#repuestos-tab')
                         ->with('success-repuestos', 'Repuesto agregado exitosamente.');
    }

    /** Elimina un repuesto de una Orden de Servicio */
    public function destroy(OrdenServicio $orden, OrdenRepuesto $ordenRepuesto)
    {
        Gate::authorize('gestionar_ordenes');
        if ($orden->status === 'Cerrada') {
            return back()->with('error', 'No se pueden eliminar repuestos de una orden cerrada.');
        }

        // Asegurarse de que el repuesto pertenezca a la orden
        if ($ordenRepuesto->orden_servicio_id != $orden->id) {
            abort(403);
        }

        $ordenRepuesto->delete();
        
        // Actualizar los totales tras la eliminación
        $this->actualizarCostosOrden($orden);


        if (request()->ajax() || request()->wantsJson()) {
        // Recargar las relaciones
        $orden->load('repuestos'); 
        
        // Devolver el partial de repuestos actualizado
        return view('taller.ordenes.partials.repuestos_form', compact('orden'))->render();
        }

        // Código original (si no es AJAX, redirige)
        return redirect()->route('ordenes.show', $orden->id . '#repuestos-tab')
                         ->with('success-repuestos', 'Repuesto eliminado exitosamente.');
    }
    
    /** Función para recalcular los totales de la orden (puede ser un Trait o Service) */
    private function actualizarCostosOrden(OrdenServicio $orden)
    {
        // Recalcular la suma de todos los costos de repuestos
        $totalRepuestos = $orden->repuestos()->sum('costo_total');
        
        // Sumar todos los costos (repuestos + mano de obra externa + outsourcing)
        $costoTotalGeneral = $totalRepuestos + $orden->costo_mano_obra_externa + $orden->costo_outsourcing;
        
        $orden->update([
            'costo_repuestos_total' => $totalRepuestos,
            'costo_total_servicio' => $costoTotalGeneral,
        ]);
    }
}