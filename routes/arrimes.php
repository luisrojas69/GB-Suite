<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Produccion\Arrimes\ArrimeImportController;

/*
|--------------------------------------------------------------------------
| Rutas del Módulo de Arrime y Cosecha (GB-SUITE)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    Route::prefix('produccion/arrimes')->name('produccion.')->group(function () {
        
        // Vista Principal (Historial de Boletos)
        Route::get('/historial', [ArrimeImportController::class, 'index'])->name('arrimes.index');

        // Flujo de Importación
        Route::get('/importar', [ArrimeImportController::class, 'importar'])->name('arrimes.importar'); // Formulario
        Route::post('/preview', [ArrimeImportController::class, 'preview'])->name('arrimes.preview'); // Purgatorio
        Route::post('/process', [ArrimeImportController::class, 'process'])->name('arrimes.process'); // Guardado Final

        // Extras (Si necesitas ver un boleto específico)
        Route::get('/{id}', [ArrimeImportController::class, 'show'])->name('arrimes.show');
        
    });

});