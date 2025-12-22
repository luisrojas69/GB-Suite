<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController; // Para asignación de roles a usuarios

/* |--------------------------------------------------------------------------
| Rutas de Administración (Solo Super Admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Usamos el prefijo 'admin' para todas las URLs de este módulo (ej: /admin/roles)
    Route::prefix('admin')->name('admin.')->group(function () {

        // Rutas para la gestión de Roles
        // El middleware se aplicará en el constructor del controlador.
        Route::resource('roles', RoleController::class);

        // Rutas para la gestión de Permisos (Generalmente solo lectura y asignación)
        Route::resource('permissions', PermissionController::class)->only(['index', 'show']);

        // Rutas para asignar Roles a Usuarios (CRUD)
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}/edit-roles', [UserController::class, 'editRoles'])->name('users.edit-roles');
        Route::put('users/{user}/update-roles', [UserController::class, 'updateRoles'])->name('users.update-roles');
    });
});


