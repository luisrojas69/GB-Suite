<?php

namespace App\Http\Controllers\Produccion\Animales\Costos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Produccion\Animales\Costos\Expense; // Usamos el namespace completo
use App\Models\Produccion\Animales\Costos\CostType;
use App\Models\Produccion\Animales\Location;
use App\Models\Produccion\Animales\Animal;

class ExpenseController extends Controller
{
    /**
     * Muestra la lista de todos los gastos registrados.
     */
    public function index()
    {
        // Se cargan las relaciones explícitas para el Accessor 'reference'
        $gastos = Expense::with('costType', 'accountingExport', 'referenceAnimal', 'referenceLocation')
            ->orderBy('expense_date', 'desc')
            ->paginate(15);

        return view('produccion.animales.costos.expense_index', compact('gastos'));
    }

    /**
     * Muestra el formulario para registrar un nuevo gasto. (STORE ya implementado)
     */
    public function create()
    {
        $costTypes = CostType::where('is_active', true)->pluck('name', 'id');
        $locations = Location::all()->pluck('name', 'id');
        $animals = Animal::limit(100)->pluck('iron_id', 'id'); // Limite para no cargar millones

        return view('produccion.animales.costos.expense_create', compact('costTypes', 'locations', 'animals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'cost_type_id' => 'required|exists:cost_types,id',
            'amount' => 'required|numeric|min:0.01',
            'reference_type' => 'required|in:animal,location',
            'reference_id' => 'required|integer',
            'description' => 'nullable|string|max:500',
            'supplier_name' => 'nullable|string|max:255',
            'document_number' => 'nullable|string|max:50',
        ]);
        
        // 1. Validar si la referencia existe realmente
        if ($request->reference_type === 'animal') {
            $exists = Animal::where('id', $request->reference_id)->exists();
        } else { // 'location'
            $exists = Location::where('id', $request->reference_id)->exists();
        }

        if (!$exists) {
            return redirect()->back()->withInput()->with('error', 'La referencia (Animal o Lote) seleccionada no es válida.');
        }

        try {
            // 2. Crear el registro de gasto
            Expense::create([
                'uid' => (string) Str::uuid(), // Genera el UID para trazabilidad
                'expense_date' => $request->expense_date,
                'cost_type_id' => $request->cost_type_id,
                'amount' => $request->amount,
                'reference_type' => $request->reference_type,
                'reference_id' => $request->reference_id,
                'description' => $request->description,
                'supplier_name' => $request->supplier_name,
                'document_number' => $request->document_number,
                // 'export_id' se mantiene NULL hasta ser exportado
            ]);

            return redirect()->route('produccion.animales.costos.expenses.index')->with('success', 'Gasto registrado exitosamente. Pendiente de exportación contable.');

        } catch (\Exception $e) {
            // Manejo de errores
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al guardar el gasto: ' . $e->getMessage());
        }
    }

    /**
     * Muestra un gasto específico.
     */
    public function show(Expense $gasto)
    {
        // Asegurar que el Accessor 'reference' funcione cargando las relaciones
        $gasto->load('costType', 'accountingExport', 'referenceAnimal', 'referenceLocation');
        
        return view('produccion.animales.costos.expense_show', compact('gasto'));
    }

    /**
     * Muestra el formulario para editar un gasto.
     */
    public function edit(Expense $gasto)
    {
        //dd($gasto->id);
        if ($gasto->export_id !== null) {
            return redirect()->route('produccion.animales.costos.expenses.index')->with('error', 'El gasto ya fue exportado (Lote #' . $gasto->export_id . ') y no puede ser modificado.');
        }
        
        $costTypes = CostType::where('is_active', true)->pluck('name', 'id');
        $locations = Location::all()->pluck('name', 'id');
        $animals = Animal::limit(100)->pluck('iron_id', 'id'); 
        
        return view('produccion.animales.costos.expense_edit', compact('gasto', 'costTypes', 'locations', 'animals'));
    }

    /**
     * Actualiza un gasto en la base de datos.
     */
    public function update(Request $request, Expense $gasto)
    {
        if ($gasto->export_id !== null) {
            return redirect()->route('produccion.animales.costos.expenses.index')->with('error', 'El gasto ya fue exportado y no puede ser modificado.');
        }

        $request->validate([
            'expense_date' => 'required|date',
            'cost_type_id' => 'required|exists:cost_types,id',
            'amount' => 'required|numeric|min:0.01',
            'reference_type' => 'required|in:animal,location',
            'reference_id' => 'required|integer',
            'description' => 'nullable|string|max:500',
            'supplier_name' => 'nullable|string|max:255',
            'document_number' => 'nullable|string|max:50',
        ]);
        
        // 1. Validar si la referencia existe realmente
        $ModelClass = $request->reference_type === 'animal' ? Animal::class : Location::class;
        if (!$ModelClass::where('id', $request->reference_id)->exists()) {
            return redirect()->back()->withInput()->with('error', 'La referencia (Animal o Lote) seleccionada no es válida.');
        }

        try {
            // 2. Actualizar el registro de gasto. El UID y export_id NO deben cambiarse.
            $gasto->update($request->only([
                'expense_date',
                'cost_type_id',
                'amount',
                'reference_type',
                'reference_id',
                'description',
                'supplier_name',
                'document_number',
            ]));

            return redirect()->route('produccion.animales.costos.expenses.index')->with('success', 'Gasto actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar el gasto: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un gasto de la base de datos.
     */
    public function destroy(Expense $gasto)
    {
        if ($gasto->export_id !== null) {
            return redirect()->route('produccion.animales.costos.expenses.index')->with('error', 'El gasto ya fue exportado y NO puede ser eliminado.');
        }

        try {
            $gasto->delete();
            return redirect()->route('produccion.animales.costos.expenses.index')->with('success', 'Gasto eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el gasto: ' . $e->getMessage());
        }
    }
}