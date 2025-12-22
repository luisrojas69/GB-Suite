<?php

use App\Http\Controllers\Produccion\Agro\ContratistaController;
use App\Http\Controllers\Produccion\Agro\DestinoController;
use App\Http\Controllers\Produccion\Agro\VariedadController;
use App\Http\Controllers\Produccion\Agro\ZafraController;
use App\Http\Controllers\Produccion\Agro\MoliendaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas del Módulo de Producción / Agro (Molienda, Catálogos)
| Prefijo de Ruta: /produccion/agro
| Nombre de Ruta: produccion.agro.
|--------------------------------------------------------------------------
*/

Route::prefix('produccion/agro')->name('produccion.agro.')->group(function () {

    // 1. Catálogos Base
    Route::resource('variedades', VariedadController::class)->parameters(['variedades' => 'variedad']);
    Route::resource('destinos', DestinoController::class)->parameters(['destinos' => 'destino']);
    Route::resource('zafras', ZafraController::class)->parameters(['zafras' => 'zafra']);
    Route::resource('contratistas', ContratistaController::class)->parameters(['contratistas' => 'contratista']);

    // 2. Módulo de Transacción Principal (Molienda)
    Route::resource('moliendas', MoliendaController::class)->parameters(['moliendas' => 'molienda']);
    
    // 3. Rutas específicas para Liquidación (CRUD completo podría ser excesivo, mejor como acciones anidadas)
    // Usaremos un controlador para el CRUD de Liquidación, pero es un recurso anidado o separado si lo prefiere.
    // Por ahora, lo dejamos fuera del resource principal, asumiendo que el CRUD se maneja desde Molienda.
    // Si necesita un CRUD de Liquidacion separado:
    // Route::resource('liquidaciones', LiquidacionController::class)->parameters(['liquidaciones' => 'liquidacion']);
});