<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Produccion\Animales\Costos\ExpenseController;
use App\Http\Controllers\Produccion\Animales\Costos\ProfitExportController;

Route::middleware(['auth'])
    ->prefix('produccion/animales/costos')
    ->name('produccion.animales.costos.')
    ->group(function () {

        // --- GESTIÓN DE GASTOS (Tabla: expenses) ---
        // CORRECCIÓN APLICADA: Se usa parameters() para mapear 'gastos' a 'gasto'
        Route::resource('gastos', ExpenseController::class)
            ->names('expenses')
            ->parameters([
                'gastos' => 'gasto' // Esto resuelve el problema de pluralización
            ]);

        // --- EXPORTACIÓN CONTABLE A PROFIT ---
        Route::prefix('profit-exportacion')->name('profit.')->group(function () {
            
            Route::get('/', [ProfitExportController::class, 'index'])->name('index'); 
            Route::post('/generar', [ProfitExportController::class, 'generateExport'])->name('generate_export');
        });
    });