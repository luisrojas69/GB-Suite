<?php

namespace App\Http\Controllers\Produccion\Areas;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Areas\Lote;
use App\Models\Produccion\Areas\Sector; // Importar el Modelo Sector
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoteController extends Controller
{
    /**
     * Muestra una lista de todos los lotes.
     */
    public function index()
    {
        // Carga la relación 'sector' para evitar consultas N+1 en la vista.
        $lotes = Lote::with('sector')->orderBy('codigo_completo')->get();
        $sectores = Sector::orderBy('nombre')->pluck('nombre', 'id');
        return view('produccion.areas.lotes.index', compact('lotes','sectores'));
    }

    /**
     * Muestra el formulario para crear un nuevo lote.
     */
    public function create()
    {
        // Necesitamos la lista de Sectores para el campo de selección (dropdown)
        $sectores = Sector::orderBy('nombre')->pluck('nombre', 'id'); 
        return view('produccion.areas.lotes.create', compact('sectores'));
    }

    /**
     * Almacena un nuevo lote en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sector_id' => 'required|exists:sectores,id',
            'codigo_lote_interno' => [
                'required', 
                'string', 
                'max:5', 
                'regex:/^[a-zA-Z0-9]+$/', 
                // Único: La combinación de sector_id y codigo_lote_interno no puede repetirse.
                Rule::unique('lotes')->where(function ($query) use ($request) {
                    return $query->where('sector_id', $request->sector_id)
                                 ->where('codigo_lote_interno', $request->codigo_lote_interno);
                }),
            ],
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ], [
            'codigo_lote_interno.unique' => 'Ya existe un lote con ese código interno dentro del sector seleccionado.',
        ]);

        // La lógica de generación de codigo_completo se ejecuta automáticamente
        // en el evento 'creating' del Modelo Lote (Paso 2).
        Lote::create($request->all());

       return response()->json(['success' => true, 'message' => 'Lote registrado con éxito.']);
    }
    
    /**
     * Muestra la información detallada de un lote específico.
     */
    public function show(Lote $lote)
    {
        // Precargamos Sector y Tablones para la vista de detalle
        $lote->load('sector', 'tablones'); 
        $sectores = Sector::orderBy('nombre')->pluck('nombre', 'id');
        return view('produccion.areas.lotes.show', compact('lote', 'sectores'));
    }

    /**
     * Muestra el formulario para editar un lote existente.
     */
    public function edit(Lote $lote)
    {
        $sectores = Sector::orderBy('nombre')->pluck('nombre', 'id');
        return view('produccion.areas.lotes.edit', compact('lote', 'sectores'));
    }

    /**
     * Actualiza un lote existente en la base de datos.
     */
    public function update(Request $request, Lote $lote)
    {
        // Validamos la solicitud de actualización
        $request->validate([
            'sector_id' => 'required|exists:sectores,id',
            'codigo_lote_interno' => [
                'required', 
                'string', 
                'max:5', 
                'regex:/^[a-zA-Z0-9]+$/', 
                // La regla de unicidad debe ignorar el lote actual Y verificar la combinación Sector+Código Interno
                Rule::unique('lotes')->ignore($lote->id)->where(function ($query) use ($request) {
                    return $query->where('sector_id', $request->sector_id)
                                 ->where('codigo_lote_interno', $request->codigo_lote_interno);
                }),
            ],
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ], [
            'codigo_lote_interno.unique' => 'Ya existe otro lote con ese código interno dentro del sector seleccionado.',
        ]);

        // Si el sector_id o el codigo_lote_interno han cambiado, el codigo_completo debe regenerarse.
        if ($lote->sector_id != $request->sector_id || $lote->codigo_lote_interno != $request->codigo_lote_interno) {
            
            $sector = Sector::findOrFail($request->sector_id);
            $nuevo_codigo_completo = $sector->codigo_sector . $request->codigo_lote_interno;
            
            // Si el código completo cambia, actualizamos el lote y sus tablones asociados
            $lote->update($request->all());
            $lote->codigo_completo = $nuevo_codigo_completo;
            $lote->save();
            
            // IMPORTANTE: Si el código del lote cambia, DEBEMOS actualizar los códigos completos de sus Tablones.
            // Para esto, podríamos definir un Model Event 'updated' en el modelo Lote o ejecutar la actualización aquí:
            foreach ($lote->tablones as $tablon) {
                $tablon->codigo_completo = $nuevo_codigo_completo . $tablon->codigo_tablon_interno;
                $tablon->save();
            }
            
            $mensaje = '✅ Lote y los códigos de sus tablones asociados han sido actualizados exitosamente.';
            
        } else {
            // Solo actualizamos campos no relacionados con el código
            $lote->update($request->all());
            $mensaje = '✅ Lote "' . $lote->nombre . '" actualizado exitosamente.';
        }


        return response()->json(['success' => true, 'message' => 'Lote editado con éxito.']);
    }

    /**
     * Elimina un lote de la base de datos.
     */
    public function destroy(Lote $lote)
    {
        $nombreLote = $lote->nombre;
        
        if (auth()->user()->cannot('eliminar_sectores')) { // Usamos el mismo permiso general
             return redirect()->route('produccion.areas.lotes.index')
                              ->with('error', '❌ Permiso denegado para eliminar lotes.');
        }

        try {
            // Gracias a la clave foránea con onDelete('cascade'), los tablones hijos se eliminarán automáticamente.
            $lote->delete();
            return redirect()->route('produccion.areas.lotes.index')
                             ->with('success', '🗑️ Lote "' . $nombreLote . '" y todos sus tablones asociados han sido eliminados.');
        } catch (\Exception $e) {
            return redirect()->route('produccion.areas.lotes.index')
                             ->with('error', '❌ Error al eliminar el lote: ' . $e->getMessage());
        }
    }
}