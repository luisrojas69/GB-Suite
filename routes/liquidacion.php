<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Produccion\Agro\LiquidacionController;
use App\Http\Controllers\Produccion\Agro\TarifaController; 

/**
 * Rutas para el Módulo de Liquidación
 * Prefijo URI: /liquidacion
 * Prefijo de Nombre: liquidacion.
 */
Route::prefix('liquidacion')
    ->name('liquidacion.')
    //->middleware(['auth', 'can:acceder_menu_liquidacion']) 
    ->group(function () {

    // 1. Recurso Principal: Liquidaciones
    // Nombres de ruta: liquidacion.index, liquidacion.create, liquidacion.show, etc.
    Route::resource('liquidaciones', LiquidacionController::class)
        ->parameters(['liquidaciones' => 'liquidacion'])
        ->names([
            'index' => 'index', 
            'create' => 'create', 
            'store' => 'store', 
            'show' => 'show', 
            'edit' => 'edit', 
            'update' => 'update', 
            'destroy' => 'destroy'
        ]); 

    // 2. Tablas Maestras: Gestión de Tarifas/Precios
    // Nombres de ruta: liquidacion.tarifas.index, liquidacion.tarifas.create, etc.
    Route::resource('tarifas', TarifaController::class)
        ->parameters(['tarifas' => 'tarifa'])
        ->names('tarifas');
        
    // 3. Ruta Adicional para Descargar Reporte/PDF de una liquidación
    Route::get('liquidaciones/{liquidacion}/pdf', [LiquidacionController::class, 'downloadPdf'])
        ->name('liquidaciones.download.pdf') // Nota: Esta se llama 'liquidacion.liquidaciones.download.pdf'
        ->middleware('can:ver_liquidaciones'); 
});