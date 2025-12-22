<?php

namespace App\Http\Controllers\RRHH\Comedor;

use App\Http\Controllers\Controller;
use App\Models\RRHH\Comedor\DiningEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Jmrashed\Zkteco\Lib\ZKTeco;

class DiningEmployeeController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('ver_empleados_comedor');

        $query = DiningEmployee::query();

        // Filtros
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('biometric_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $employees = $query->orderBy('name')->paginate(20);
        $departments = DiningEmployee::select('department')->distinct()->pluck('department');

        return view('RRHH.Comedor.employees.index', compact('employees', 'departments'));
    }

    public function edit(DiningEmployee $employee)
    {
        Gate::authorize('editar_empleados_comedor');
        return response()->json($employee);
    }

    public function update(Request $request, DiningEmployee $employee)
    {
        Gate::authorize('editar_empleados_comedor');

        $request->validate([
            'name' => 'required|string|max:50',
            'department' => 'nullable|string|max:50',
            'card_number' => 'nullable|numeric'
        ]);

        $employee->update($request->all());

        return response()->json(['success' => 'Datos actualizados localmente.']);
    }

    public function toggleStatus(DiningEmployee $employee)
    {
        Gate::authorize('editar_empleados_comedor');

        $employee->is_active = !$employee->is_active;
        $employee->save();

        $status = $employee->is_active ? 'activado' : 'inactivado';
        return response()->json(['success' => "Empleado $status correctamente."]);
    }
}