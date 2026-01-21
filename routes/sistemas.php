<?php

use App\Http\Controllers\Sistemas\Inventario\InventoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('inventario')->group(function () {
    
    // --- VISTAS PRINCIPALES (CON FILTRO DE GRUPO) ---
    // Estas rutas cargan el mismo método index pero con diferentes "defaults"
    Route::get('/sistemas', [InventoryController::class, 'index'])
        ->name('inventario.sistemas')
        ->defaults('group', 'IT');

    Route::get('/administracion', [InventoryController::class, 'index'])
        ->name('inventario.admin')
        ->defaults('group', 'ADMIN');

    Route::get('/general', [InventoryController::class, 'index'])
        ->name('inventario.general')
        ->defaults('group', 'ALL');

    // Ruta base genérica (por si la necesitas)
    Route::get('/', [InventoryController::class, 'index'])->name('inventario.index');


    // --- ACCIONES DE C.R.U.D. Y EDICIÓN RÁPIDA ---
    Route::post('/store', [InventoryController::class, 'store'])->name('inventario.store');
    Route::get('/{id}/edit', [InventoryController::class, 'edit'])->name('inventario.edit');
    Route::put('/{id}', [InventoryController::class, 'update'])->name('inventario.update');
    Route::get('/{id}/show', [InventoryController::class, 'show'])->name('inventario.show');


    // --- GESTIÓN DE ESTADOS Y ASIGNACIONES ---
    // Cambiar a Dañado/Desincorporado
    Route::post('/change-status', [InventoryController::class, 'changeStatus'])->name('inventario.changeStatus');
    
    // Retorno formal (Desasignar con notas)
    Route::post('/return', [InventoryController::class, 'returnItem'])->name('inventario.return');
    
    // Asignar nuevo responsable
    Route::post('/assign', [InventoryController::class, 'assign'])->name('inventario.assign');
    //Asignaciones Masivas (por lotes)
    Route::post('/inventario/asignacion-masiva', [InventoryController::class, 'massAssignment'])->name('inventario.massAssignment');
    
    // --- BÚSQUEDAS Y REPORTES ---
    // Select2 Remoto para buscar responsables
    Route::get('/buscar-responsable', [InventoryController::class, 'buscarResponsable'])->name('inventario.buscarResponsable');
    
    // Descarga de PDF (Acta)
    Route::get('/download-acta/{id}', [InventoryController::class, 'downloadActa'])->name('inventario.downloadActa');
    Route::get('/download-acta-retorno/{id}', [InventoryController::class, 'downloadActaRetorno'])->name('inventario.downloadActaRetorno');
    Route::get('/download-acta-lote/{id}', [InventoryController::class, 'downloadActaLote'])->name('inventario.downloadActaLote');
    
    // Exportación Excel
    Route::get('/export/{group}', [InventoryController::class, 'export'])->name('inv.export');


});