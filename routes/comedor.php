<?php

use App\Http\Controllers\RRHH\Comedor\ReporteComedorController;
use App\Http\Controllers\RRHH\Comedor\MealTypeController;
use App\Http\Controllers\RRHH\Comedor\DiningRecordController;
use App\Http\Controllers\RRHH\Comedor\DiningDashboardController;
use App\Http\Controllers\RRHH\Comedor\DeviceControlController;
use App\Http\Controllers\RRHH\Comedor\DiningEmployeeController;


Route::group(['prefix' => 'RRHH/Comedor', 'as' => 'comedor.'], function () {
    Route::resource('meal_types', MealTypeController::class)->except(['create', 'show']);

    // Rutas para los registros
    Route::get('records', [DiningRecordController::class, 'index'])->name('records.index');
    Route::post('records', [DiningRecordController::class, 'store'])->name('records.store');

    //Ruta para los reportes
    Route::get('reports', [ReporteComedorController::class, 'index'])->name('reports.index');
    Route::post('reports/generar', [ReporteComedorController::class, 'generar'])->name('reports.generar');

    //Ruta para ver el dashboard de comedor
    Route::get('dashboard', [DiningDashboardController::class, 'index'])->name('dashboard.index');

    //Rutas Control de dispositivos
    Route::get('device/control', [DeviceControlController::class, 'index'])->name('device.index');
    Route::post('device/execute', [DeviceControlController::class, 'execute'])->name('device.execute');

    //Rutas para sincronizacion activa (forzar)
    Route::post('device/force-sync', [DeviceControlController::class, 'forceSync'])->name('device.forceSync');
    Route::post('device/push', [DeviceControlController::class, 'pushToDevice'])->name('device.push');

    // CRUD Empleados
    Route::get('employees', [DiningEmployeeController::class, 'index'])->name('employees.index');
    Route::get('employees/{employee}/edit', [DiningEmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('employees/{employee}', [DiningEmployeeController::class, 'update'])->name('employees.update');
    Route::post('employees/{employee}/toggle', [DiningEmployeeController::class, 'toggleStatus'])->name('employees.toggle');
});