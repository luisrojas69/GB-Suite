<?php

use App\Http\Controllers\MedicinaOcupacional\PacienteController;
use App\Http\Controllers\MedicinaOcupacional\ConsultaController;
use App\Http\Controllers\MedicinaOcupacional\DotacionController;
use App\Http\Controllers\MedicinaOcupacional\AccidenteController;
use App\Http\Controllers\MedicinaOcupacional\AlertaController;
use App\Http\Controllers\MedicinaOcupacional\DashboardMedicinaController;
use App\Http\Controllers\MedicinaOcupacional\ReporteSaludController;
use App\Http\Controllers\MedicinaOcupacional\CertificadoController;
use App\Http\Controllers\MedicinaOcupacional\OrdenExamenController;


Route::prefix('medicina')->name('medicina.')->group(function () {
      Route::get('/dashboard', [DashboardMedicinaController::class, 'index'])->name('dashboard');

      // --- RUTAS DE PACIENTES ---
      Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
      Route::get('/pacientes/listado', [PacienteController::class, 'getListado'])->name('pacientes.listado'); //JSON Empleados
      Route::get('paciente/{id}', [PacienteController::class, 'show'])->name('pacientes.show');
      Route::post('/pacientes/sync', [PacienteController::class, 'syncProfit'])->name('pacientes.sync');
      Route::get('/pacientes/{id}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit'); //JSON Paciente for AJAX
      Route::put('/pacientes/{id}', [PacienteController::class, 'update'])->name('pacientes.update');
      Route::post('/pacientes/subir-archivo', [ConsultaController::class, 'subirArchivo'])->name('pacientes.subirArchivo');

      // --- RUTAS DE ORDENES DE EXAMENES ---
      // Rutas de Órdenes
      // Route::resource('ordenes', OrdenExamenController::class); 
      Route::get('/ordenes', [OrdenExamenController::class, 'index'])->name('ordenes.index');
      Route::get('/ordenes/registrar/{consulta_id}', [OrdenExamenController::class, 'create'])->name('ordenes.create');
      Route::put('/ordenes/completar/{orden_id}', [OrdenExamenController::class, 'markAsCompleted'])->name('ordenes.completar');
      Route::post('/ordenes/store', [OrdenExamenController::class, 'store'])->name('ordenes.store');
      Route::get('/ordenes/{id}/edit', [OrdenExamenController::class, 'edit'])->name('ordenes.edit');
      Route::put('/ordenes/{id}', [OrdenExamenController::class, 'update'])->name('ordenes.update');
      Route::get('/ordenes/{id}', [OrdenExamenController::class, 'show'])->name('ordenes.show');

      // Ruta extra para imprimir (PDF)
      Route::get('ordenes/{orden}/pdf', [OrdenExamenController::class, 'pdf'])->name('ordenes.pdf');


      // --- RUTAS DE CONSULTAS ---
      // Esta es la ruta que dispara la vista que acabamos de crear
      Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');
      Route::get('/consultas/crear/{paciente_id}', [ConsultaController::class, 'create'])->name('consultas.create');
      Route::get('/consultas', [ConsultaController::class, 'index'])->name('consultas.index');
      Route::get('/consultas/{id}', [ConsultaController::class, 'show'])->name('consultas.show');
      Route::get('/consultas/{id}/edit', [ConsultaController::class, 'edit'])->name('consultas.edit');
      Route::put('/consultas/{id}', [ConsultaController::class, 'update'])->name('consultas.update');
      Route::get('/buscarCie10', [ConsultaController::class, 'buscarCie10'])->name('buscarCie10'); //JSON de Cie10 for Query Ajax

      // Esta es la ruta que procesa el guardado del formulario
      Route::post('/consultas/store', [ConsultaController::class, 'store'])->name('consultas.store');
      Route::post('/consultas/fast-track', [ConsultaController::class, 'storeFastTrack'])->name('consultas.fast-track');

      // (Opcional) Ruta para ver el historial general de consultas
      Route::get('/consultas/historial/{paciente_id}', [ConsultaController::class, 'historial'])->name('consultas.historial');
      Route::get('/consultas/imprimir/{id}', [ConsultaController::class, 'imprimir'])->name('consultas.imprimir');


      Route::get('/dotaciones', [DotacionController::class, 'index'])->name('dotaciones.index');
      Route::get('/dotaciones/entregar/{paciente_id}', [DotacionController::class, 'create'])->name('dotaciones.create');
      Route::get('/dotaciones/alertas', [DotacionController::class, 'alertasRedotacion'])->name('dotaciones.alertas');
      Route::post('/dotaciones/store', [DotacionController::class, 'store'])->name('dotaciones.store');
      Route::get('/dotaciones/{id}', [DotacionController::class, 'show'])->name('dotaciones.show');
      Route::get('/reportes/profit-diario', [DotacionController::class, 'reporteParaProfit'])->name('reportes.profit');
      Route::get('dotaciones/exportar/excel', [DotacionController::class, 'exportar'])->name('dotaciones.export.excel');

      Route::get('/reportes/consumo-epp', [DotacionController::class, 'reporteConsumo'])->name('reportes.consumo');

      Route::get('/validar-dotacion/{token}', [DotacionController::class, 'validarEpp'])->name('dotaciones.validar');
      Route::get('/dotaciones/ticket/{id}', [DotacionController::class, 'imprimirTicket'])->name('imprimir.ticket');
      Route::post('/confirmar-despacho/{id}', [DotacionController::class, 'confirmarDespacho'])->name('dotaciones.confirmar');

      // --- RUTAS DE ACCIDENTES LABORALES ---
      Route::get('/accidentes', [AccidenteController::class, 'index'])->name('accidentes.index');
      Route::get('/accidentes/registrar/{paciente_id}', [AccidenteController::class, 'create'])->name('accidentes.create');
      Route::post('/accidentes/store', [AccidenteController::class, 'store'])->name('accidentes.store');

      Route::get('/accidentes/show/{id}', [AccidenteController::class, 'show'])->name('accidentes.show');
      Route::get('/accidentes/{id}/edit', [AccidenteController::class, 'edit'])->name('accidentes.edit');
      Route::get('/accidentes/reporte-inpsasel/{id}', [AccidenteController::class, 'reporteInpsasel'])->name('accidentes.inpsasel');

      //REPORTES
      Route::get('reportes/morbilidad', [ReporteSaludController::class, 'morbilidadMensual'])->name('reportes.morbilidad');
      Route::get('reportes/accidentalidad', [ReporteSaludController::class, 'reporteAccidentalidad'])->name('reportes.accidentalidad');
      Route::get('reportes/vigilancia-epidemiologica', [ReporteSaludController::class, 'reporteVigilancia'])->name('reportes.vigilancia');
      Route::get('/aptitud/{paciente_id}', [CertificadoController::class, 'aptitud'])->name('pdf.aptitud');
      Route::get('/reposo/{consulta_id}', [CertificadoController::class, 'reposo'])->name('pdf.reposo');
      Route::get('/constancia/{consulta_id}', [CertificadoController::class, 'constancia'])->name('pdf.constancia');
      Route::get('/historial/{paciente_id}', [CertificadoController::class, 'historial'])->name('pdf.historial');
      Route::get('/epp/{paciente_id}', [CertificadoController::class, 'entregaEpp'])->name('pdf.epp');
      Route::get('pacientes/exportar/excel', [PacienteController::class, 'exportarExcel'])->name('pacientes.export.excel');
      Route::get('pacientes/exportar/tallas', [PacienteController::class, 'exportarTallas'])->name('pacientes.export.tallas');
      Route::get('consultas/exportar/excel', [ConsultaController::class, 'exportar'])->name('consultas.export.excel');
      Route::get('accidentes/exportar/excel', [AccidenteController::class, 'exportar'])->name('accidentes.export.excel');
});