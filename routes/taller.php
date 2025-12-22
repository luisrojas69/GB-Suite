<?php

use App\Http\Controllers\Logistica\Taller\ActivoController;
use App\Http\Controllers\Logistica\Taller\ChecklistController;
use App\Http\Controllers\Logistica\Taller\OrdenRepuestoController;
use App\Http\Controllers\Logistica\Taller\OrdenServicioController; // Usamos el namespace completo
use App\Http\Controllers\Logistica\Taller\ProgramacionMPController;
use App\Http\Controllers\Logistica\Taller\ReporteGerencialController;
use App\Http\Controllers\Logistica\Taller\LecturaActivoController;


Route::middleware('auth')->prefix('logistica/taller')->group(function () {
    // Activos (Maquinaria y Vehículos)
    Route::resource('activos', ActivoController::class)->except(['destroy']);
    Route::delete('activos/{activo}', [ActivoController::class, 'destroy'])->name('activos.destroy');

    Route::get('/activos/{activo}/lecturas/historial', [LecturaActivoController::class, 'show'])
    ->name('activos.lecturas.historial');

    // Gestión de Plantillas de Mantenimiento Preventivo
    Route::resource('checklists', ChecklistController::class);

    // Órdenes de Servicio
    Route::resource('ordenes', OrdenServicioController::class)->parameters(['ordenes' => 'orden']);
    
    // Rutas específicas para el Flujo del Taller
    Route::post('ordenes/{orden}/iniciar', [OrdenServicioController::class, 'iniciarTrabajo'])->name('ordenes.iniciar');
    Route::post('ordenes/{orden}/cerrar', [OrdenServicioController::class, 'cerrarOrden'])->name('ordenes.cerrar');
});


    // Rutas de gestión de Repuestos dentro de una Orden
    Route::middleware('auth')->prefix('logistica/taller/ordenes/{orden}')->group(function () {
        // Almacena un nuevo repuesto consumido
        Route::post('repuestos', [OrdenRepuestoController::class, 'store'])->name('ordenes.repuestos.store');
        // Elimina un repuesto de la lista (si hubo error al cargar)
        Route::delete('repuestos/{ordenRepuesto}', [OrdenRepuestoController::class, 'destroy'])->name('ordenes.repuestos.destroy');

    });


    // Rutas anidadas bajo un Activo específico para su programación de MP
    Route::middleware('auth')->prefix('logistica/taller/activos/{activo}')->group(function () {
        Route::resource('programacion', ProgramacionMPController::class)->only(['store', 'update', 'destroy'])->names([
            'store' => 'activos.programacion.store',
            'update' => 'activos.programacion.update',
            'destroy' => 'activos.programacion.destroy',
        ]);
    });


    Route::middleware('auth')->prefix('logistica/taller/reportes')->group(function () {
        // Ruta para el reporte principal
        Route::get('gerencial', [ReporteGerencialController::class, 'index'])->name('reportes.gerencial');
    });

        // Rutas para Programación de Mantenimiento Preventivo
    Route::middleware(['auth'])->prefix('logistica/taller')->group(function () {
        
        // Ruta para mostrar el formulario de creación: 
        // Necesita el ID del activo ($activo) para saber a qué máquina se le asignará el MP.
        Route::get('/programaciones-mp/crear/{activo}', [ProgramacionMPController::class, 'create'])->name('programacionesMP.create');

        Route::get('/programaciones-mp/editar/{activo}', [ProgramacionMPController::class, 'edit'])->name('programacionesMP.edit');
        
        // Ruta para guardar la nueva programación:
        Route::post('/programaciones-mp/almacenar/{activo}', [ProgramacionMPController::class, 'store'])->name('programacionesMP.store');

        Route::patch('ordenes/{orden}/checklist/detalle/{ordenRepuesto}', [OrdenServicioController::class, 'updateDetalleChecklist'])->name('ordenes.checklist.update_detalle');

        // Aquí irían las rutas resource o show/edit/update/destroy para la ProgramaciónMP si fueran necesarias
    });


    Route::middleware(['auth'])->prefix('logistica/taller')->group(function () {
        // ... Otras rutas de recursos (Activos, Checklists, Ordenes, etc.)

        // --- Módulo LECTURAS DE ACTIVO ---
        
        // 1. Rutas CREATE/STORE (Usando nombres personalizados para el formulario)
        Route::get('/lecturas/crear', [LecturaActivoController::class, 'create'])->name('lecturas.create');
        Route::post('/lecturas/almacenar', [LecturaActivoController::class, 'store'])->name('lecturas.store');
        
        // 2. Rutas RESOURCE para el resto del CRUD (index, show, edit, update, destroy)
        // Usamos 'except' para excluir 'create' y 'store' y evitar duplicidades/conflictos.
        Route::resource('lecturas', LecturaActivoController::class)->except([
            'create', 
            'store'
        ])->names([
            'index' => 'lecturas.index',
            'show' => 'lecturas.show',
            'edit' => 'lecturas.edit',
            'update' => 'lecturas.update',
            'destroy' => 'lecturas.destroy',
        ]);
    });
