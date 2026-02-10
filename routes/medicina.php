<?php

use App\Http\Controllers\MedicinaOcupacional\PacienteController;
use App\Http\Controllers\MedicinaOcupacional\ConsultaController;
use App\Http\Controllers\MedicinaOcupacional\DotacionController;
use App\Http\Controllers\MedicinaOcupacional\AccidenteController;
use App\Http\Controllers\MedicinaOcupacional\AlertaController;
use App\Http\Controllers\MedicinaOcupacional\DashboardMedicinaController;
use App\Http\Controllers\MedicinaOcupacional\ReporteSaludController;
use App\Http\Controllers\MedicinaOcupacional\CertificadoController;


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

     // (Opcional) Ruta para ver el historial general de consultas
     Route::get('/consultas/historial/{paciente_id}', [ConsultaController::class, 'historial'])->name('consultas.historial');
     Route::get('/consultas/imprimir/{id}', [ConsultaController::class, 'imprimir'])->name('consultas.imprimir');


     Route::get('/dotaciones', [DotacionController::class, 'index'])->name('dotaciones.index');
     Route::get('/dotaciones/entregar/{paciente_id}', [DotacionController::class, 'create'])->name('dotaciones.create');
     Route::post('/dotaciones/store', [DotacionController::class, 'store'])->name('dotaciones.store');
     Route::get('/dotaciones/{id}', [DotacionController::class, 'show'])->name('dotaciones.show');
     Route::get('/reportes/profit-diario', [DotacionController::class, 'reporteParaProfit'])->name('reportes.profit');

     Route::get('/reportes/consumo-epp', [DotacionController::class, 'reporteConsumo'])->name('reportes.consumo');

     Route::get('/validar-dotacion/{token}', [DotacionController::class, 'validarEpp'])->name('dotaciones.validar');
     Route::get('/dotaciones/ticket/{id}', [DotacionController::class, 'imprimirTicket'])->name('imprimir.ticket');
     Route::post('/confirmar-despacho/{id}', [DotacionController::class, 'confirmarDespacho'])->name('dotaciones.confirmar');

     // --- RUTAS DE ACCIDENTES LABORALES ---
     Route::get('/accidentes', [AccidenteController::class, 'index'])->name('accidentes.index');
     Route::get('/accidentes/registrar/{paciente_id}', [AccidenteController::class, 'create'])->name('accidentes.create');
     Route::post('/accidentes/store', [AccidenteController::class, 'store'])->name('accidentes.store');

     Route::get('/accidentes/show/{id}', [AccidenteController::class, 'show'])->name('accidentes.show');
     Route::get('/accidentes/reporte-inpsasel/{id}', [AccidenteController::class, 'reporteInpsasel'])->name('accidentes.inpsasel');

     //REPORTES
     Route::get('reportes/morbilidad', [ReporteSaludController::class, 'morbilidadMensual'])->name('reportes.morbilidad');
     Route::get('reportes/accidentalidad', [ReporteSaludController::class, 'reporteAccidentalidad'])->name('reportes.accidentalidad');
     Route::get('reportes/vigilancia-epidemiologica', [ReporteSaludController::class, 'reporteVigilancia'])->name('reportes.vigilancia');
      Route::get('/aptitud/{paciente_id}', [CertificadoController::class, 'aptitud'])->name('pdf.aptitud');
      Route::get('/constancia/{consulta_id}', [CertificadoController::class, 'constancia'])->name('pdf.constancia');
      Route::get('/historial/{paciente_id}', [CertificadoController::class, 'historial'])->name('pdf.historial');
      Route::get('/epp/{paciente_id}', [CertificadoController::class, 'entregaEpp'])->name('pdf.epp');
});