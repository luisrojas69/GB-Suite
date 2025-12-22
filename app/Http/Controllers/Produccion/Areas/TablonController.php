<?php

namespace App\Http\Controllers\Produccion\Areas;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Areas\Tablon;
use App\Models\Produccion\Areas\Lote;   
use App\Models\Produccion\Agro\Variedad; // <--- NUEVO: Importar el Modelo Variedad
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TablonController extends Controller
{
    
    // ... index() se mantiene igual, salvo que se carga la relación 'variedad'
    public function index()
    {
        // Carga las relaciones 'lote', 'lote.sector' y AHORA 'variedad'
        $tablones = Tablon::with('lote.sector', 'variedad')->orderBy('codigo_completo')->get();
        return view('produccion.areas.tablones.index', compact('tablones'));
    }

    /**
     * Muestra el formulario para crear un nuevo tablón.
     */
    public function create()
    {
        $lotes = Lote::with('sector')
                    ->get()
                    ->mapWithKeys(function ($lote) {
                        return [$lote->id => $lote->codigo_completo . ' - ' . $lote->nombre . ' (' . $lote->sector->nombre . ')'];
                    });

        $estados = ['Activo' => 'Activo', 'Inactivo' => 'Inactivo', 'Preparacion' => 'Preparación'];
        
        // <--- NUEVO: Cargar las Variedades
        $variedades = Variedad::pluck('nombre', 'id'); 
        
        return view('produccion.areas.tablones.create', compact('lotes', 'estados', 'variedades'));
    }
    
    // ... store() se mantiene igual en la lógica, solo se actualiza la validación
    public function store(Request $request)
    {
        $request->validate([
            'lote_id' => 'required|exists:lotes,id',
            'codigo_tablon_interno' => [
                'required',
                'string',
                'min:1', 
                'max:5', 
                'regex:/^[a-zA-Z0-9]+$/', 
                // Asegurar unicidad de codigo_interno dentro del lote seleccionado
                Rule::unique('tablones')->where(function ($query) use ($request) {
                    return $query->where('lote_id', $request->lote_id);
                }),
            ],
            'nombre' => 'required|string|max:100',
            'area_ha' => 'required|numeric|min:0.01|max:99999.99', // CAMBIO DE NOMBRE: hectareas -> area_ha
            'variedad_id' => 'nullable|exists:variedades,id', // NUEVO
            'fecha_siembra' => 'nullable|date', // NUEVO
            'meta_ton_ha' => 'nullable|numeric|min:0|max:99999.99', // NUEVO
            'tipo_suelo' => 'nullable|string|max:50',
            'estado' => 'required|in:Activo,Inactivo,Preparacion',
            'descripcion' => 'nullable|string',
        ], [
            'codigo_tablon_interno.unique' => 'Ya existe un tablón con ese código interno dentro del lote seleccionado.',
            'area_ha.required' => 'El campo de Área en Hectáreas es obligatorio.',
        ]);

        // 1. Creación
        // NOTA: La lógica de generación de codigo_completo se maneja en el Model Event 'creating'
        Tablon::create($request->all());

        return redirect()->route('produccion.areas.tablones.index')
                         ->with('success', 'Tablón creado exitosamente, listo para sembrar!');
    }


    // ... show() se mantiene igual, salvo que se carga la relación 'variedad'
    public function show(Tablon $tablon)
    {
        // Carga la relación variedad para la vista de detalle
        $tablon->load('lote.sector', 'variedad'); 
        return view('produccion.areas.tablones.show', compact('tablon'));
    }

    /**
     * Muestra el formulario para editar un tablón existente.
     */
    public function edit(Tablon $tablon)
    {
        $lotes = Lote::with('sector')
                    ->get()
                    ->mapWithKeys(function ($lote) {
                        return [$lote->id => $lote->codigo_completo . ' - ' . $lote->nombre . ' (' . $lote->sector->nombre . ')'];
                    });
                    
        $estados = ['Activo' => 'Activo', 'Inactivo' => 'Inactivo', 'Preparacion' => 'Preparación'];
        
        // <--- NUEVO: Cargar las Variedades
        $variedades = Variedad::pluck('nombre', 'id');

        return view('produccion.areas.tablones.edit', compact('tablon', 'lotes', 'estados', 'variedades'));
    }

    // ... update() se mantiene igual, solo se actualiza la validación
    public function update(Request $request, Tablon $tablon)
    {
        $request->validate([
            'lote_id' => 'required|exists:lotes,id',
            'codigo_tablon_interno' => [
                'required',
                'string',
                'min:1', 
                'max:5', 
                'regex:/^[a-zA-Z0-9]+$/', 
                // Asegurar unicidad, ignorando el tablón actual.
                Rule::unique('tablones')->ignore($tablon->id)->where(function ($query) use ($request) {
                    return $query->where('lote_id', $request->lote_id);
                }),
            ],
            'nombre' => 'required|string|max:100',
            'area_ha' => 'required|numeric|min:0.01|max:99999.99', // CAMBIO DE NOMBRE: hectareas -> area_ha
            'variedad_id' => 'nullable|exists:variedades,id', // NUEVO
            'fecha_siembra' => 'nullable|date', // NUEVO
            'meta_ton_ha' => 'nullable|numeric|min:0|max:99999.99', // NUEVO
            'tipo_suelo' => 'nullable|string|max:50',
            'estado' => 'required|in:Activo,Inactivo,Preparacion',
            'descripcion' => 'nullable|string',
        ], [
            'codigo_tablon_interno.unique' => 'Ya existe un tablón con ese código interno dentro del lote seleccionado.',
        ]);

        // 2. Actualización
        // La lógica de regeneración de código completo se maneja en el Model Event 'updating'
        $tablon->update($request->all());

        return redirect()->route('produccion.areas.tablones.index')
                         ->with('success', 'Tablón **'.$tablon->codigo_completo.'** actualizado exitosamente.');
    }
    
    // ... destroy() se mantiene igual
    
}