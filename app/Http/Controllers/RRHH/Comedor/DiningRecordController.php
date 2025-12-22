<?php

namespace App\Http\Controllers\RRHH\Comedor;

use App\Http\Controllers\Controller;
use App\Models\RRHH\Comedor\DiningRecord;
use App\Models\RRHH\Comedor\MealType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DiningRecordController extends Controller
{
    public function index_old(Request $request)
    {
        Gate::authorize('ver_registros_comedor');

        $query = DiningRecord::with(['mealType'])->orderBy('punch_time', 'desc');

        // Filtros opcionales (por fecha, empleado, etc.)
        if ($request->filled('date')) {
            $query->whereDate('punch_time', $request->date);
        }

        $records = $query->paginate(50);

        return view('RRHH.Comedor.records.index', compact('records'));
    }


    public function index(Request $request)
    {
        Gate::authorize('ver_registros_comedor');

        $mealTypes = MealType::where('is_active', true)->get(); // Para el filtro y el modal

        $query = DiningRecord::with(['mealType'])->orderBy('punch_time', 'desc');

        if ($request->filled('date')) {
            $query->whereDate('punch_time', $request->date);
        }
        
        if ($request->filled('meal_type')) {
            $query->where('meal_type_id', $request->meal_type);
        }

        $records = $query->paginate(50);

        return view('RRHH.Comedor.records.index', compact('records', 'mealTypes'));
    }

    public function store(Request $request)
    {
        Gate::authorize('crear_registros_manuales');

        $request->validate([
            'employee_id' => 'required',
            'meal_type_id' => 'required|exists:meal_types,id',
            'punch_time' => 'required|date',
        ]);

        $mealType = MealType::find($request->meal_type_id);

        // Lógica de Anti-Passback Manual
        $exists = DiningRecord::where('employee_id', $request->employee_id)
            ->where('meal_type_id', $request->meal_type_id)
            ->whereDate('punch_time', date('Y-m-d', strtotime($request->punch_time)))
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'El usuario ya tiene este servicio registrado hoy.'], 422);
        }

        DiningRecord::create([
            'employee_id' => $request->employee_id,
            'meal_type_id' => $request->meal_type_id,
            'punch_time' => $request->punch_time,
            'status_code' => $mealType->status_code,
            'cost' => $mealType->price,
            'source' => 'manual',
            'observation' => $request->observation
        ]);

        return response()->json(['success' => 'Registro manual creado con éxito.']);
    }
}