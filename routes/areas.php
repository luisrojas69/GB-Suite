<?php

use App\Http\Controllers\Produccion\Areas\SectorController;
use App\Http\Controllers\Produccion\Areas\LoteController;
use App\Http\Controllers\Produccion\Areas\TablonController;
use Illuminate\Support\Facades\Route;

// Agrupar rutas de Áreas bajo el prefijo 'produccion/areas'
Route::prefix('produccion/areas')->name('produccion.areas.')->group(function () {

    // 1. Sectores
    Route::resource('sectores', SectorController::class)
        // Solución: Definir el singular esperado. 'sectores' debe usar el parámetro 'sector'.
        ->parameters(['sectores' => 'sector']); 
        
    // 2. Lotes
    Route::resource('lotes', LoteController::class)
        // Solución: Definir el singular esperado. 'lotes' debe usar el parámetro 'lote'.
        ->parameters(['lotes' => 'lote']); 

    // 3. Tablones
    Route::resource('tablones', TablonController::class)
        // Solución: Definir el singular esperado. 'tablones' debe usar el parámetro 'tablon'.
        ->parameters(['tablones' => 'tablon']); 
});