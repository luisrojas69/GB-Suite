<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate; // Asegúrese de importar esto

class RoleController extends Controller
{


    /**
     * Listar todos los roles.
     */
    public function index()
    {
        Gate::authorize('gestionar_seguridad');
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Muestra el formulario de creación y todos los permisos disponibles.
     */
    public function create()
    {
        Gate::authorize('gestionar_seguridad');
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Almacena un nuevo rol y asigna permisos.
     */
    public function store(Request $request)
    {
        Gate::authorize('gestionar_seguridad');
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')],
            'permissions' => ['nullable', 'array'],
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions); // Spatie lo hace fácil
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rol creado y permisos asignados.');
    }

    /**
     * Muestra el formulario para editar un rol y sus permisos.
     */
    public function edit(Role $role)
    {
        Gate::authorize('gestionar_seguridad');
        $permissions = Permission::all();
        // Obtener los nombres de los permisos que tiene este rol
        $rolePermissions = $role->permissions->pluck('name')->toArray(); 

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Actualiza el rol y sus permisos.
     */
    public function update(Request $request, Role $role)
    {
        Gate::authorize('gestionar_seguridad');
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => ['nullable', 'array'],
        ]);
        
        $role->update(['name' => $request->name]);
        
        // Sincroniza (añade, quita o mantiene) los permisos seleccionados
        $role->syncPermissions($request->permissions ?? []); 

        return redirect()->route('admin.roles.index')->with('success', 'Rol actualizado y permisos sincronizados.');
    }

    /**
     * Elimina el rol.
     */
    public function destroy(Role $role)
    {
        Gate::authorize('gestionar_seguridad');
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Rol eliminado.');
    }
}