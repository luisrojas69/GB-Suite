<?php

use App\Http\Controllers\Produccion\Labores\RegistroLaborController;

Route::group(['prefix' => 'produccion/operaciones', 'as' => 'produccion.'], function () {
    
    // Rutas para el Registro de Labores
    Route::resource('labores', RegistroLaborController::class)->names([
        'index'   => 'labores.index',
        'create'  => 'labores.create',
        'store'   => 'labores.store',
        'show'    => 'labores.show',
    ]);

    // Ruta adicional para obtener datos de activo vía AJAX (Opcional pero recomendada)
    Route::get('api/activo/{id}', [RegistroLaborController::class, 'getActivoData']);
});