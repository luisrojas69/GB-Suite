@extends('layouts.app')
@section('title-page', 'Centro de Reportes Agro Premium')

@section('styles')
<style>
    /* ========================================\
       VARIABLES GLOBALES - TEMA AGRO PREMIUM
    ======================================== */
    :root {
        --agro-dark: #1b4332;      
        --agro-primary: #2d6a4f;   
        --agro-accent: #52b788;    
        --pdf-color: #e74a3b;
        --excel-color: #1cc88a;
    }

    /* HEADER */
    .page-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; 
        padding: 25px 30px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(27, 67, 50, 0.2);
    }

    /* FILTROS GLOBALES */
    .filtros-container {
        background: white;
        border-radius: 10px;
        border-left: 5px solid var(--agro-accent);
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        padding: 20px;
        margin-bottom: 30px;
        position: sticky;
        top: 20px;
        z-index: 10;
    }

    /* TARJETAS DE REPORTES */
    .card-report {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .card-report:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .card-report-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 15px;
        background: rgba(45, 106, 79, 0.1);
        color: var(--agro-primary);
    }
    .card-report-body {
        padding: 25px;
        flex-grow: 1;
    }
    .card-report-footer {
        background: transparent;
        border-top: 1px solid #eaecf4;
        padding: 15px 25px;
        display: flex;
        gap: 10px;
    }
    
    /* BOTONES */
    .btn-report { font-weight: 600; font-size: 0.85rem; flex: 1; border-radius: 8px; }
    .btn-pdf { color: var(--pdf-color); border: 1px solid rgba(231, 74, 59, 0.3); background: rgba(231, 74, 59, 0.05); }
    .btn-pdf:hover { background: var(--pdf-color); color: white; }
    .btn-excel { color: var(--excel-color); border: 1px solid rgba(28, 200, 138, 0.3); background: rgba(28, 200, 138, 0.05); }
    .btn-excel:hover { background: var(--excel-color); color: white; }
</style>
@endsection

@section('content')
<div class="container-fluid pb-5">

    <div class="page-header-agro d-flex align-items-center justify-content-between">
        <div>
            <h2 class="font-weight-bold mb-1"><i class="fas fa-chart-pie mr-2"></i> Centro de Reportes y Auditoría</h2>
            <p class="mb-0 opacity-75">Generación de documentos PDF (Snappy) y matrices de datos Excel (Maatwebsite)</p>
        </div>
    </div>

    <div class="filtros-container">
        <form id="form-filtros-globales">
            <div class="row align-items-end">
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="font-weight-bold text-dark small text-uppercase">Zafra Activa</label>
                    <select class="form-control select2-agro" id="filtro_zafra" name="zafra_id">
                        <option value="1">Zafra 2025-2026</option>
                        <option value="2">Zafra 2026-2027</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="font-weight-bold text-dark small text-uppercase">Sector / Hacienda</label>
                    <select class="form-control select2-agro" id="filtro_sector" name="sector_id">
                        <option value="todos">- Todos los Sectores -</option>
                        <option value="1">Palo a Pique</option>
                        <option value="2">Piedras Negras</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="font-weight-bold text-dark small text-uppercase">Desde</label>
                    <input type="date" class="form-control" id="filtro_desde" name="fecha_desde" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="font-weight-bold text-dark small text-uppercase">Hasta</label>
                    <input type="date" class="form-control" id="filtro_hasta" name="fecha_hasta" value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="col-md-2 text-right">
                    <button type="button" class="btn btn-outline-secondary btn-block shadow-sm" onclick="limpiarFiltros()">
                        <i class="fas fa-sync-alt mr-1"></i> Resetear
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-report">
                <div class="card-report-body">
                    <div class="card-report-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark">Plan de Zafra vs Real</h5>
                    <p class="text-muted small mb-0">Compara las toneladas y hectáreas planificadas en el Rol de Molienda contra los arrimes reales ejecutados al ingenio.</p>
                </div>
                <div class="card-report-footer">
                    <button type="button" class="btn btn-report btn-pdf" onclick="descargarReporte('molienda_comparativo', 'pdf')">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </button>
                    <button type="button" class="btn btn-report btn-excel" onclick="descargarReporte('molienda_comparativo', 'excel')">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-report border-left-danger">
                <div class="card-report-body">
                    <div class="card-report-icon" style="background: rgba(231, 74, 59, 0.1); color: #e74a3b;">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark">Labores Post-Cosecha</h5>
                    <p class="text-muted small mb-0">Control de la ventana crítica de tiempo entre quema/corte y ejecución de labores. Seguimiento de maquinaria propia y subcontratada.</p>
                </div>
                <div class="card-report-footer">
                    <button type="button" class="btn btn-report btn-pdf" onclick="descargarReporte('labores_criticas', 'pdf')">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </button>
                    <button type="button" class="btn btn-report btn-excel" onclick="descargarReporte('labores_criticas', 'excel')">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-report">
                <div class="card-report-body">
                    <div class="card-report-icon" style="background: rgba(246, 194, 62, 0.1); color: #f6c23e;">
                        <i class="fas fa-tractor"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark">Consolidado Horas Máquina</h5>
                    <p class="text-muted small mb-0">Análisis de horómetros (inicial y final) de maquinaria propia, agrupado por equipo, implemento y operador en el periodo filtrado.</p>
                </div>
                <div class="card-report-footer">
                    <button type="button" class="btn btn-report btn-pdf" onclick="descargarReporte('horas_maquina', 'pdf')">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </button>
                    <button type="button" class="btn btn-report btn-excel" onclick="descargarReporte('horas_maquina', 'excel')">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-report">
                <div class="card-report-body">
                    <div class="card-report-icon" style="background: rgba(54, 185, 204, 0.1); color: #36b9cc;">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark">Liquidación Outsourcing</h5>
                    <p class="text-muted small mb-0">Resumen de trabajos manuales y labores mecanizadas ejecutadas por contratistas para su respectiva facturación y pago.</p>
                </div>
                <div class="card-report-footer">
                    <button type="button" class="btn btn-report btn-pdf" onclick="descargarReporte('liquidacion_contratistas', 'pdf')">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </button>
                    <button type="button" class="btn btn-report btn-excel" onclick="descargarReporte('liquidacion_contratistas', 'excel')">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<form id="form-descarga-reporte" method="GET" action="" target="_blank">
    <input type="hidden" name="tipo_exportacion" id="input_tipo">
    <input type="hidden" name="zafra_id" id="input_zafra">
    <input type="hidden" name="sector_id" id="input_sector">
    <input type="hidden" name="fecha_desde" id="input_desde">
    <input type="hidden" name="fecha_hasta" id="input_hasta">
</form>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar Select2 si lo estás usando
        $('.select2-agro').select2({ theme: 'bootstrap4' });
    });

    function limpiarFiltros() {
        $('#filtro_sector').val('todos').trigger('change');
        // Mantener las fechas lógicas o limpiar del todo
        toastr.info('Filtros restaurados a sus valores por defecto');
    }

    function descargarReporte(nombreReporte, tipo) {
        // 1. Obtener los valores actuales de los filtros globales
        let zafra = $('#filtro_zafra').val();
        let sector = $('#filtro_sector').val();
        let desde = $('#filtro_desde').val();
        let hasta = $('#filtro_hasta').val();

        // Validación simple de fechas
        if(desde && hasta && desde > hasta) {
            toastr.error('La fecha "Desde" no puede ser mayor a la fecha "Hasta"');
            return;
        }

        // 2. Llenar el formulario oculto
        $('#input_tipo').val(tipo);
        $('#input_zafra').val(zafra);
        $('#input_sector').val(sector);
        $('#input_desde').val(desde);
        $('#input_hasta').val(hasta);

        // 3. Construir la URL base según el reporte seleccionado
        // Ejemplo: /reportes/exportar/horas_maquina
        let baseUrl = "{{ url('produccion/agro/rol_molienda/reportes/exportar') }}/" + nombreReporte;
        
        // 4. Configurar el action del formulario oculto y enviarlo
        let form = $('#form-descarga-reporte');
        form.attr('action', baseUrl);
        form.submit();
        
        // Alerta visual
        let formatoTxt = tipo === 'pdf' ? 'PDF (Snappy)' : 'Excel (Maatwebsite)';
        toastr.success(`Generando reporte de ${nombreReporte.replace('_', ' ')} en formato ${formatoTxt}...`);
    }
</script>
@endpush