<?php

namespace App\Http\Controllers\RRHH\Comedor;

use App\Http\Controllers\Controller;
use App\Models\RRHH\Comedor\MealType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class MealTypeController extends Controller
{
    public function index()
    {
        Gate::authorize('ver_meal_types');
        $mealTypes = MealType::orderBy('status_code')->get();
        return view('RRHH.Comedor.meal_types.index', compact('mealTypes'));
    }

    public function store(Request $request)
    {
        Gate::authorize('crear_meal_types');

        $request->validate([
            'name' => 'required|string|max:255',
            'status_code' => 'required|integer|unique:meal_types,status_code',
            'start_time' => 'required',
            'end_time' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        MealType::create($request->all());

        return response()->json(['success' => 'Tipo de comida creado correctamente.']);
    }

    public function edit(MealType $mealType)
    {
        Gate::authorize('editar_meal_types');
        return response()->json($mealType);
    }

    public function update(Request $request, MealType $mealType)
    {
        Gate::authorize('editar_meal_types');

        $request->validate([
            'name' => 'required|string|max:255',
            'status_code' => 'required|integer|unique:meal_types,status_code,' . $mealType->id,
            'start_time' => 'required',
            'end_time' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        $mealType->update($request->all());

        return response()->json(['success' => 'Tipo de comida actualizado correctamente.']);
    }

    public function destroy(MealType $mealType)
    {
        Gate::authorize('eliminar_meal_types');
        
        // Verificar si tiene registros asociados antes de eliminar
        if ($mealType->diningRecords()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar porque tiene registros asociados.'], 422);
        }

        $mealType->delete();
        return response()->json(['success' => 'Tipo de comida eliminado correctamente.']);
    }
}