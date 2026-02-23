<?php
use App\Http\Controllers\Produccion\Pluviometria\PluviometriaController;

Route::middleware(['auth'])->prefix('produccion')->name('produccion.')->group(function () {

    // Módulo de Pluviometría
    Route::prefix('pluviometria')->name('pluviometria.')->group(function () {
        
        // Vista principal de la Matriz
        Route::get('/', [PluviometriaController::class, 'matrizIndex'])->name('index');
        
        // Guardado masivo vía AJAX
        Route::post('/guardar-masivo', [PluviometriaController::class, 'guardarMasivo'])->name('guardar_masivo');

        // Nueva ruta para exportación
        Route::get('/exportar', [PluviometriaController::class, 'exportar'])->name('exportar');

        Route::get('/dashboard', [PluviometriaController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboardOLD', [PluviometriaController::class, 'dashboardOLD'])->name('dashboardOLD');
        
    });

});
