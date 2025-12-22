<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission; // Importamos el modelo de Permisos de Spatie
use Illuminate\Support\Facades\Gate; // Importamos el Gate

class PermissionController extends Controller
{
    /**
     * Muestra la lista de todos los permisos del sistema.
     */
    public function index()
    {
        // === PROTECCIÓN: Solo si tiene permiso para gestionar la seguridad ===
        Gate::authorize('gestionar_seguridad'); 
        // ===================================================================
        
        // Obtenemos todos los permisos (ordenados por módulo si es posible)
        $permissions = Permission::orderBy('name')->get(); 
        
        return view('admin.permissions.index', compact('permissions'));
    }

    // Nota: Generalmente, los permisos se gestionan desde el RoleController, 
    // y solo se listan aquí, ya que se crean vía Seeders o Artisan.

    // Los métodos create, store, edit, update, destroy se pueden dejar vacíos 
    // o eliminarse si la gestión de permisos se hace solo desde código/base de datos.
    
    // Si decide dejarlos, DEBE PROTEGERLOS:
    /*
    public function create()
    {
        Gate::authorize('gestionar_seguridad'); 
        // ...
    }
    // ...
    */
}