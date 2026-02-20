<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate; // Importamos el Gate

class PermissionController extends Controller
{
    public function index()
    {
         Gate::authorize('gestionar_seguridad');

        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy(fn($p) => $p->module ?: 'GLOBAL');
        $stats = [
            'total_permisos' => Permission::count(),
            'total_modulos' => $groupedPermissions->count(),
            'recientes' => Permission::where('created_at', '>=', now()->subDays(7))->count(),
        ];
        
        return view('admin.permissions.index', compact('groupedPermissions', 'stats'));
    }

    public function store(Request $request)
    {
         Gate::authorize('gestionar_seguridad'); 
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'module' => 'required|string'
        ]);

        Permission::create([
            'name' => strtolower(str_replace(' ', '_', $request->name)),
            'module' => strtoupper($request->module),
            'guard_name' => 'web'
        ]);

        return redirect()->back()->with('success', 'Permiso creado correctamente.');
    }

    public function edit(Permission $permission)
    {
         Gate::authorize('gestionar_seguridad'); 
        return response()->json($permission);
    }

    public function update(Request $request, Permission $permission)
    {
         Gate::authorize('gestionar_seguridad'); 
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
            'module' => 'required|string'
        ]);

        $permission->update([
            'name' => strtolower(str_replace(' ', '_', $request->name)),
            $permission->module = strtoupper($request->module)
        ]);

        return redirect()->back()->with('success', 'Permiso actualizado.');
    }

    public function destroy(Permission $permission)
    {
         Gate::authorize('gestionar_seguridad'); 
        $permission->delete();
        return redirect()->back()->with('success', 'Permiso eliminado.');
    }
}