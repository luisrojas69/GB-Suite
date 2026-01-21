<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Produccion\Animales\AnimalController;
use App\Http\Controllers\Produccion\Animales\WeighingController;
use App\Http\Controllers\Produccion\Animales\SpecieController;
use App\Http\Controllers\Produccion\Animales\CategoryController;
use App\Http\Controllers\Produccion\Animales\LocationController;
use App\Http\Controllers\Produccion\Animales\OwnerController;
use App\Http\Controllers\Produccion\Animales\BajaController;


// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

    Route::prefix('produccion')->group(function () {
            /*
        |--------------------------------------------------------------------------
        | Módulo Pecuario - Registros Transaccionales
        |--------------------------------------------------------------------------
        */
        
         //Ruta para exportar todos los animales
        Route::get('animals/export', [AnimalController::class, 'export'])->name('animals.export');
        Route::resource('animals', AnimalController::class); // No implementamos show por ahora
       
        

        Route::get('weighings/create', [WeighingController::class, 'create'])->name('weighings.create');
        Route::post('weighings', [WeighingController::class, 'store'])->name('weighings.store');
        Route::get('weighings', [WeighingController::class, 'index'])->name('weighings.index');
        Route::get('bajas', [BajaController::class, 'index'])->name('bajas.index');
        Route::get('bajas/create', [BajaController::class, 'create'])->name('bajas.create');
        Route::get('bajas/search', [BajaController::class, 'search'])->name('bajas.search');
        Route::post('bajas', [BajaController::class, 'store'])->name('bajas.store');

        /*
        |--------------------------------------------------------------------------
        | Módulo Pecuario - Tablas Maestras (Mantenimiento)
        |--------------------------------------------------------------------------
        */

        // Especies
        Route::resource('species', SpecieController::class)->except(['show']);

        // Categorías (Clasificación y CeCo)
        Route::resource('categories', CategoryController::class)->except(['show', 'destroy']);

        // Ubicaciones (Potreros)
        Route::resource('locations', LocationController::class)->except(['show', 'destroy']);

        // Propietarios
        Route::resource('owners', OwnerController::class)->except(['show', 'destroy']);

    });
    
});