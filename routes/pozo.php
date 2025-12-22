<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Produccion\Pozo\ActivoController;
use App\Http\Controllers\Produccion\Pozo\MantenimientoController;
use App\Http\Controllers\Produccion\Pozo\AforoController;

// Agrupación de rutas para el módulo de Producción/Pozos
Route::prefix('produccion/pozos')->name('produccion.pozos.')->group(function () {
    
    // DASHBOARD PRINCIPAL DEL MÓDULO
    // Muestra el resumen ejecutivo y los KPIs
    Route::get('dashboard', [ActivoController::class, 'dashboard'])->name('dashboard');

    // =================================================================
    // 1. GESTIÓN DE ACTIVOS (Pozos y Estaciones)
    // =================================================================
    Route::resource('activos', ActivoController::class);

    // Lógica Específica para el cambio rápido de estatus vía AJAX
    Route::post('activos/{activo}/cambiar-estatus', [ActivoController::class, 'cambiarEstatus'])->name('activos.cambiarEstatus');


    // =================================================================
    // 2. GESTIÓN DE MANTENIMIENTOS CORRECTIVOS
    // =================================================================
    // Rutas Anidadas: CREATE y STORE anidados al Activo (para asegurar contexto)
    Route::resource('activos.mantenimientos', MantenimientoController::class)->only(['create', 'store']);

    // Rutas Desanidadas: Para el listado global (INDEX), detalle (SHOW), edición/cierre (EDIT/UPDATE) y eliminación (DESTROY)
    Route::get('mantenimientos', [MantenimientoController::class, 'index'])->name('mantenimientos.index');
    Route::get('mantenimientos/create', [MantenimientoController::class, 'create'])->name('mantenimientos.create');
    Route::get('mantenimientos/{mantenimiento}', [MantenimientoController::class, 'show'])->name('mantenimientos.show');
    Route::get('mantenimientos/{mantenimiento}/edit', [MantenimientoController::class, 'edit'])->name('mantenimientos.edit');
    Route::put('mantenimientos/{mantenimiento}', [MantenimientoController::class, 'update'])->name('mantenimientos.update');
    Route::delete('mantenimientos/{mantenimiento}', [MantenimientoController::class, 'destroy'])->name('mantenimientos.destroy');


    // =================================================================
    // 3. GESTIÓN DE AFOROS (Solo para Pozos)
    // =================================================================
    Route::resource('aforos', AforoController::class);
});