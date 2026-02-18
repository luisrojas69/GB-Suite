@extends('layouts.app')

@section('styles')
<style>
    /* ========================================
       VARIABLES GLOBALES
    ======================================== */
    :root {
        --primary: #4e73df;
        --success: #1cc88a;
        --danger: #e74a3b;
        --warning: #f6c23e;
        --info: #36b9cc;
        --dark: #5a5c69;
    }

    body {
        background: #f8f9fc;
    }

    /* ========================================
       HEADER MASTER
    ======================================== */
    .dashboard-header-master {
        background: linear-gradient(135deg, #1a592e 0%, #2d7a4a 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 6px 20px rgba(26, 89, 46, 0.25);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header-master::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    .header-title h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .header-subtitle {
        font-size: 14px;
        opacity: 0.95;
        margin: 0;
    }

    .header-subtitle i {
        margin: 0 8px 0 0;
    }

    .btn-nueva-dotacion {
        background: white;
        color: #1a592e;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .btn-nueva-dotacion:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.25);
        color: #1a592e;
    }

    /* ========================================
       ALERTAS MEJORADAS
    ======================================== */
    .alert-enhanced {
        border-radius: 10px;
        border: none;
        padding: 20px 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-enhanced i.fa-2x {
        font-size: 32px;
    }

    .alert-enhanced strong {
        font-size: 16px;
    }

    /* ========================================
       TARJETAS KPI MEJORADAS
    ======================================== */
    .kpi-card-modern {
        border-radius: 12px;
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        height: 100%;
    }

    .kpi-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .kpi-card-header {
        padding: 20px 25px;
        position: relative;
    }

    .kpi-icon-modern {
        position: absolute;
        top: 20px;
        right: 25px;
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: rgba(255, 255, 255, 0.8);
    }

    .kpi-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 8px;
    }

    .kpi-value {
        font-size: 32px;
        font-weight: 700;
        color: white;
        margin-bottom: 5px;
        line-height: 1;
    }

    .kpi-meta {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 600;
    }

    .kpi-footer {
        padding: 12px 25px;
        background: white;
        font-size: 12px;
        font-weight: 600;
    }

    /* Gradientes por tipo */
    .kpi-primary { background: linear-gradient(135deg, #4e73df, #224abe); }
    .kpi-warning { background: linear-gradient(135deg, #f6c23e, #dda20a); }
    .kpi-info { background: linear-gradient(135deg, #36b9cc, #258391); }
    .kpi-dark { background: linear-gradient(135deg, #5a5c69, #373840); }

    /* ========================================
       SECCIÓN DE GRÁFICOS
    ======================================== */
    .chart-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .chart-card-header {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
        padding: 18px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chart-card-header h6 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
    }

    .chart-card-body {
        padding: 25px;
    }

    .chart-area {
        position: relative;
        height: 320px;
    }

    .btn-refresh {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-refresh:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(180deg);
    }

    /* ========================================
       TARJETAS DE EXPORTACIÓN
    ======================================== */
    .export-card {
        border-radius: 12px;
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .export-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        text-decoration: none;
    }

    .export-card-body {
        padding: 35px 25px;
        text-align: center;
    }

    .export-icon {
        font-size: 56px;
        margin-bottom: 20px;
    }

    .export-title {
        font-size: 17px;
        font-weight: 700;
        color: white;
        margin-bottom: 10px;
    }

    .export-description {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 20px;
    }

    .export-btn {
        background: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        display: inline-block;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .export-card-danger { background: linear-gradient(135deg, #e74a3b, #be2617); }
    .export-card-success { background: linear-gradient(135deg, #1cc88a, #13855c); }

    /* ========================================
       RANKING DE DEPARTAMENTOS
    ======================================== */
    .ranking-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .ranking-header {
        background: white;
        padding: 20px 25px;
        border-bottom: 2px solid #f8f9fc;
    }

    .ranking-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .ranking-title i {
        color: #4e73df;
        margin-right: 10px;
    }

    .ranking-body {
        padding: 20px 25px;
    }

    .ranking-item {
        margin-bottom: 20px;
    }

    .ranking-item:last-child {
        margin-bottom: 0;
    }

    .ranking-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .ranking-name {
        display: flex;
        align-items: center;
        font-size: 13px;
        font-weight: 700;
        color: #2c3e50;
    }

    .ranking-medal {
        margin-right: 10px;
        font-size: 16px;
    }

    .ranking-count {
        font-size: 13px;
        font-weight: 700;
        color: #5a5c69;
    }

    .ranking-progress {
        height: 10px;
        border-radius: 10px;
        background: #f8f9fc;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .ranking-progress-bar {
        height: 100%;
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    /* ========================================
       LISTA DE TRABAJADORES
    ======================================== */
    .workers-list-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .workers-list-item {
        padding: 18px 25px;
        border-bottom: 1px solid #f8f9fc;
        transition: all 0.2s ease;
    }

    .workers-list-item:last-child {
        border-bottom: none;
    }

    .workers-list-item:hover {
        background: #f8f9fc;
    }

    .worker-position {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #f6c23e, #dda20a);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 2px 6px rgba(246, 194, 62, 0.3);
    }

    .worker-info {
        flex: 1;
        margin-left: 15px;
    }

    .worker-name {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .worker-dept {
        font-size: 11px;
        color: #858796;
    }

    .worker-count {
        background: #f8f9fc;
        border: 2px solid #e3e6f0;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        color: #2c3e50;
    }

    /* ========================================
       TABLA DE HISTORIAL
    ======================================== */
    .table-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-card-header {
        background: linear-gradient(135deg, #5a5c69, #373840);
        color: white;
        padding: 18px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-card-title {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
    }

    .btn-export-profit {
        background: white;
        color: #1cc88a;
        padding: 8px 18px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 13px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: all 0.2s ease;
    }

    .btn-export-profit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        color: #1cc88a;
    }

    .table-card-body {
        padding: 25px;
    }

    .table thead th {
        background: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #5a5c69;
        padding: 12px 15px;
    }

    .table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
        font-size: 13px;
    }

    .table tbody tr:hover {
        background: #f8f9fc;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 11px;
        display: inline-block;
    }

    .status-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }

    .status-warning {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #f59e0b;
    }

    .item-icon-pill {
        background: #f8f9fc;
        padding: 4px 10px;
        border-radius: 6px;
        margin-right: 5px;
        font-size: 12px;
        display: inline-block;
    }

    .btn-action {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e3e6f0;
        background: white;
        margin: 0 3px;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* ========================================
       MODAL MEJORADO
    ======================================== */
    .modal-enhanced .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-enhanced .modal-header {
        background: linear-gradient(135deg, #5a5c69, #373840);
        color: white;
        padding: 20px 25px;
        border-radius: 12px 12px 0 0;
        border: none;
    }

    .modal-enhanced .modal-title {
        font-size: 18px;
        font-weight: 700;
    }

    .modal-enhanced .modal-body {
        padding: 0;
    }

    .modal-detail-section {
        padding: 25px;
    }

    .detail-section-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: #858796;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .detail-value-primary {
        font-size: 20px;
        font-weight: 700;
        color: #4e73df;
        margin-bottom: 10px;
    }

    .detail-item-modal {
        margin-bottom: 10px;
        font-size: 13px;
    }

    .detail-item-modal strong {
        color: #2c3e50;
        font-weight: 700;
    }

    .items-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .items-list li {
        padding: 12px 15px;
        background: #f8f9fc;
        border-radius: 6px;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .items-list li:last-child {
        margin-bottom: 0;
    }

    .observations-box {
        background: #f8f9fc;
        padding: 18px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .signature-box {
        text-align: center;
        padding: 20px;
        background: #f8f9fc;
        border-radius: 8px;
    }

    .signature-box img {
        max-height: 150px;
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* ========================================
       EMPTY STATES
    ======================================== */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }

    .empty-state-icon {
        font-size: 64px;
        opacity: 0.15;
        margin-bottom: 15px;
        color: #858796;
    }

    .empty-state-text {
        font-size: 14px;
        color: #858796;
        font-weight: 500;
    }

    /* ========================================
       RESPONSIVE
    ======================================== */
    @media (max-width: 768px) {
        .dashboard-header-master {
            padding: 20px;
        }

        .header-title h1 {
            font-size: 22px;
        }

        .kpi-value {
            font-size: 26px;
        }

        .chart-area {
            height: 250px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- MENSAJES DE SESIÓN -->
    @if (session('success'))
        <div class="alert alert-success alert-enhanced alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x mr-3"></i>
                <div>
                    <strong>¡Operación Exitosa!</strong><br>
                    {{ session('success') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-enhanced alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                <div>
                    <strong>¡Error en la Operación!</strong><br>
                    {{ session('error') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- HEADER PRINCIPAL -->
    <div class="dashboard-header-master">
        <div class="header-content">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="header-title ml-4">
                        <h1>Control de Dotaciones EPP</h1>
                        <p class="header-subtitle">
                            <i class="fas fa-calendar"></i>{{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-shield-alt"></i>Servicio de Seguridad y Salud Laboral
                        </p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('medicina.pacientes.index') }}" class="btn btn-nueva-dotacion">
                        <i class="fas fa-plus-circle mr-2"></i>Nueva Dotación
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs PRINCIPALES -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card-modern">
                <div class="kpi-card-header kpi-primary">
                    <div class="kpi-icon-modern">
                        <i class="fas fa-truck-loading"></i>
                    </div>
                    <div class="kpi-label">
                        <i class="fas fa-hourglass-half mr-1"></i>Por Despachar
                    </div>
                    <div class="kpi-value">{{ number_format($stats['pendientes']) }}</div>
                    <div class="kpi-meta">
                        <i class="fas fa-boxes mr-1"></i>Entregas pendientes
                    </div>
                </div>
                <div class="kpi-footer">
                    <span class="text-primary">
                        <i class="fas fa-info-circle mr-1"></i>Pendientes de despacho
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card-modern">
                <div class="kpi-card-header kpi-warning">
                    <div class="kpi-icon-modern">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="kpi-label">
                        <i class="fas fa-chart-line mr-1"></i>Total del Mes
                    </div>
                    <div class="kpi-value">{{ number_format($stats['total_mes']) }}</div>
                    <div class="kpi-meta">
                        <i class="fas fa-calendar-day mr-1"></i>Dotaciones procesadas
                    </div>
                </div>
                <div class="kpi-footer">
                    <a href="{{ route('medicina.alertas.index') }}" class="text-warning">
                        <i class="fas fa-arrow-right mr-1"></i>Ver evaluaciones pendientes
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card-modern">
                <div class="kpi-card-header kpi-info">
                    <div class="kpi-icon-modern">
                        <i class="fas fa-shoe-prints"></i>
                    </div>
                    <div class="kpi-label">
                        <i class="fas fa-chart-pie mr-1"></i>Calzado Entregado
                    </div>
                    <div class="kpi-value">{{ number_format($stats['botas_entregadas']) }}</div>
                    <div class="kpi-meta">
                        <i class="fas fa-box mr-1"></i>Pares en el mes
                    </div>
                </div>
                <div class="kpi-footer">
                    <span class="text-info">
                        <i class="fas fa-check-circle mr-1"></i>Stock controlado
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card-modern">
                <div class="kpi-card-header kpi-dark">
                    <div class="kpi-icon-modern">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="kpi-label">
                        <i class="fas fa-clock mr-1"></i>Actividad Hoy
                    </div>
                    <div class="kpi-value">{{ number_format($stats['dotaciones_hoy']) }}</div>
                    <div class="kpi-meta">
                        <i class="fas fa-calendar-alt mr-1"></i>Dotaciones registradas
                    </div>
                </div>
                <div class="kpi-footer">
                    <a href="{{ route('medicina.pacientes.index') }}" class="text-success">
                        <i class="fas fa-users mr-1"></i>Lista de trabajadores
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS Y EXPORTACIONES -->
    <div class="row mb-4">
        <!-- Tendencia -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card chart-card">
                <div class="chart-card-header">
                    <h6><i class="fas fa-chart-area mr-2"></i>Tendencia de Entregas - Últimos 6 Meses</h6>
                    <button class="btn btn-refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="chart-card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exportaciones -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="row">
                <div class="col-12 mb-4">
                    <a href="{{ route('medicina.reportes.morbilidad') }}" target="_blank" class="export-card export-card-danger">
                        <div class="export-card-body">
                            <div class="export-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <h5 class="export-title">Reporte de Morbilidad</h5>
                            <p class="export-description">Exportar datos del mes actual en PDF</p>
                            <div class="export-btn">
                                <i class="fas fa-download mr-2"></i>Generar PDF
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 mb-4">
                    <a href="javascript:void(0)" onclick="exportarDotacionesExcel()" class="export-card export-card-success">
                        <div class="export-card-body">
                            <h5 class="export-title">Exportar Dotaciones</h5>
                            <p class="export-description">Según fecha y motivo seleccionado</p>
                            <div class="export-btn">
                                <i class="fas fa-file-excel mr-2"></i>Descargar Excel
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- RANKINGS -->
    <div class="row mb-4">
        <!-- Top Departamentos -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card ranking-card">
                <div class="ranking-header">
                    <h6 class="ranking-title">
                        <i class="fas fa-building"></i>Top 5 Departamentos por Consumo
                    </h6>
                </div>
                <div class="ranking-body">
                    @php 
                        $colores = [
                            ['bar' => 'linear-gradient(90deg, #e74a3b, #be2617)', 'medal' => 'fas fa-trophy', 'color' => '#e74a3b'],
                            ['bar' => 'linear-gradient(90deg, #f6c23e, #dda20a)', 'medal' => 'fas fa-medal', 'color' => '#f6c23e'],
                            ['bar' => 'linear-gradient(90deg, #4e73df, #224abe)', 'medal' => 'fas fa-award', 'color' => '#4e73df'],
                            ['bar' => 'linear-gradient(90deg, #36b9cc, #258391)', 'medal' => 'fas fa-star', 'color' => '#36b9cc'],
                            ['bar' => 'linear-gradient(90deg, #1cc88a, #13855c)', 'medal' => 'fas fa-check', 'color' => '#1cc88a']
                        ]; 
                        $totalDep = $topDepartamentos->sum('total') ?: 1;
                    @endphp

                    @forelse($topDepartamentos as $index => $depto)
                        @php 
                            $porcentaje = ($depto->total / $totalDep) * 100; 
                            $color = $colores[$index] ?? ['bar' => '#e3e6f0', 'medal' => 'fas fa-circle', 'color' => '#858796'];
                        @endphp
                        <div class="ranking-item">
                            <div class="ranking-item-header">
                                <div class="ranking-name">
                                    <i class="{{ $color['medal'] }} ranking-medal" style="color: {{ $color['color'] }};"></i>
                                    {{ Str::limit($depto->departamento, 35) }}
                                </div>
                                <div class="ranking-count">{{ $depto->total }} items</div>
                            </div>
                            <div class="ranking-progress">
                                <div class="ranking-progress-bar" style="width: {{ $porcentaje }}%; background: {{ $color['bar'] }};"></div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <p class="empty-state-text">No hay datos disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Trabajadores -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card workers-list-card">
                <div class="ranking-header">
                    <h6 class="ranking-title">
                        <i class="fas fa-users"></i>Trabajadores con Más Dotaciones (Año)
                    </h6>
                </div>
                <div>
                    @forelse($topPacientes as $index => $tp)
                        <div class="workers-list-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="worker-position">{{ $index + 1 }}</div>
                                    <div class="worker-info">
                                        <div class="worker-name">{{ $tp->paciente->nombre_completo }}</div>
                                        <div class="worker-dept">{{ $tp->paciente->des_depart }}</div>
                                    </div>
                                </div>
                                <div class="worker-count">{{ $tp->total }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <p class="empty-state-text">No hay datos disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA DE HISTORIAL -->
    <div class="card table-card">
        <div class="table-card-header">
            <h6 class="table-card-title">
                <i class="fas fa-table mr-2"></i>Historial General de Dotaciones
            </h6>
            <button class="btn btn-export-profit" id="btnExportarProfit">
                <i class="fas fa-file-csv mr-2"></i>Exportar Profit
            </button>
        </div>
        <div class="table-card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tblDotaciones" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Trabajador</th>
                            <th>Departamento</th>
                            <th>Implementos</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE DETALLES -->
<div class="modal fade modal-enhanced" id="modalShowDotacion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle mr-2"></i>Detalles de la Dotación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalleContenido"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
<script>
$(document).ready(function() {
    // ========================================
    // CONFIGURACIÓN DEL GRÁFICO
    // ========================================
    Chart.defaults.font.family = 'Segoe UI, sans-serif';
    Chart.defaults.color = '#858796';

    const ctx = document.getElementById('myAreaChart');
    if(ctx) {
        const labels = @json($labelsMeses);
        const dataValues = @json($dataValores);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Dotaciones Entregadas",
                    data: dataValues,
                    backgroundColor: "rgba(78, 115, 223, 0.08)",
                    borderColor: "#4e73df",
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: "#4e73df",
                    pointBorderColor: "#ffffff",
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: "#4e73df",
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        borderColor: '#4e73df',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Dotaciones: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#858796' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f8f9fc', borderDash: [5, 5] },
                        ticks: { 
                            font: { size: 11 },
                            color: '#858796',
                            callback: function(value) {
                                return value + ' dotaciones';
                            }
                        }
                    }
                }
            }
        });
    }

    // ========================================
    // DATATABLE
    // ========================================
    let tabla = $('#tblDotaciones').DataTable({
        ajax: "{{ route('medicina.dotaciones.index') }}",
        order: [[0, 'desc']],
        columns: [
            { 
                data: 'id', 
                render: (data) => `<span class="font-weight-bold text-primary">#${data}</span>` 
            },
            { 
                data: 'fecha_entrega', 
                render: (data) => `
                    <div class="font-weight-bold">${moment(data).format('DD/MM/YYYY')}</div>
                    <div class="small text-muted">${moment(data).fromNow()}</div>
                ` 
            },
            { 
                data: 'paciente', 
                render: (p) => `
                    <div class="font-weight-bold">${p.nombre_completo}</div>
                    <div class="small text-muted">CI: ${p.ci}</div>
                ` 
            },
            { 
                data: 'paciente.des_depart', 
                render: (d) => `<span class="font-weight-bold text-uppercase">${d}</span>` 
            },
            { 
                data: null,
                render: function(data) {
                    let html = '<div class="d-flex flex-wrap">';
                    if(data.calzado_entregado) html += `<span class="item-icon-pill" title="Calzado"><i class="fas fa-shoe-prints text-success"></i> Botas</span>`;
                    if(data.pantalon_entregado) html += `<span class="item-icon-pill" title="Pantalón"><i class="fas fa-user-tag text-primary"></i> Pantalón</span>`;
                    if(data.camisa_entregado) html += `<span class="item-icon-pill" title="Camisa"><i class="fas fa-tshirt text-warning"></i> Camisa</span>`;
                    if(data.otros_epp_codigos) html += `<span class="item-icon-pill" title="Otros"><i class="fas fa-plus-circle text-info"></i> Otros</span>`;
                    html += '</div>';
                    return html;
                }
            },
            { 
                data: 'entregado_en_almacen',
                render: function(data) {
                    return data == 1 
                        ? `<span class="status-badge status-success"><i class="fas fa-check-circle mr-1"></i>Despachado</span>`
                        : `<span class="status-badge status-warning"><i class="fas fa-clock mr-1"></i>Pendiente</span>`;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                        <div class="text-center">
                            <button class="btn btn-action btnShow" data-id="${data.id}" title="Ver Detalles">
                                <i class="fas fa-eye text-info"></i>
                            </button>
                            <a href="/medicina/dotaciones/ticket/${data.id}" target="_blank" class="btn btn-action" title="Imprimir">
                                <i class="fas fa-print text-dark"></i>
                            </a>
                            <a href="/medicina/validar-dotacion/${data.qr_token}" class="btn btn-action" title="Confirmar Entrega">
                                <i class="fas fa-check text-success"></i>
                            </a>
                            <button class="btn btn-action btnDelete" data-id="${data.id}" title="Anular">
                                <i class="fas fa-trash text-danger"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: { url: "/js/lang/Spanish.json" }
    });

    // ========================================
    // VER DETALLES (MODAL)
    // ========================================
    $(document).on('click', '.btnShow', function() {
        let id = $(this).data('id');
        $.get(`/medicina/dotaciones/${id}`, function(data) {
            let itemsHtml = '';
            if(data.co_art_calzado) itemsHtml += `<li><i class="fas fa-shoe-prints text-success mr-2"></i><strong>Calzado:</strong> ${data.co_art_calzado} (Talla: ${data.calzado_talla})</li>`;
            if(data.co_art_pantalon) itemsHtml += `<li><i class="fas fa-user-tag text-primary mr-2"></i><strong>Pantalón:</strong> ${data.co_art_pantalon} (Talla: ${data.pantalon_talla})</li>`;
            if(data.co_art_camisa) itemsHtml += `<li><i class="fas fa-tshirt text-warning mr-2"></i><strong>Camisa:</strong> ${data.co_art_camisa} (Talla: ${data.camisa_talla})</li>`;

            let html = `
                <div class="modal-detail-section">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-section-title">Información del Trabajador</div>
                            <div class="detail-value-primary">${data.paciente.nombre_completo}</div>
                            <div class="detail-item-modal"><strong>Cédula:</strong> ${data.paciente.ci}</div>
                            <div class="detail-item-modal"><strong>Departamento:</strong> ${data.paciente.des_depart}</div>
                            <div class="detail-item-modal"><strong>Cargo:</strong> ${data.paciente.des_cargo || 'No especificado'}</div>
                            <div class="detail-item-modal"><strong>Motivo:</strong> ${data.motivo}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-section-title">Items Autorizados</div>
                            <ul class="items-list">
                                ${itemsHtml || '<li>No se especificaron items</li>'}
                            </ul>
                        </div>
                    </div>

                    <div class="observations-box">
                        <div class="detail-section-title">Observaciones del Servicio SSL</div>
                        <p class="mb-0">${data.observaciones || 'Sin observaciones registradas.'}</p>
                    </div>

                    <div class="signature-box mt-3">
                        <div class="detail-section-title">Firma de Conformidad del Trabajador</div>
                        <img src="${data.firma_digital}" class="img-fluid" alt="Firma">
                    </div>
                </div>
            `;
            $('#detalleContenido').html(html);
            $('#modalShowDotacion').modal('show');
        });
    });

    // ========================================
    // ELIMINAR DOTACIÓN
    // ========================================
    $(document).on('click', '.btnDelete', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: '¿Anular esta Dotación?',
            text: "Esta acción registrará la dotación como anulada y no podrá ser procesada por almacén.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#858796',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Sí, Anular',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/medicina/dotaciones/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        tabla.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Dotación Anulada',
                            text: 'El registro ha sido eliminado correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    });

    // ========================================
    // EXPORTAR PROFIT
    // ========================================
    $('#btnExportarProfit').click(function() {
        Swal.fire({
            title: 'Generar Archivo Profit',
            text: "¿Desea exportar las dotaciones procesadas en formato CSV para Profit Plus?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#858796',
            confirmButtonText: '<i class="fas fa-file-csv mr-2"></i>Generar CSV',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Generando CSV...',
                    html: 'Procesando registros compatibles.',
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => { Swal.showLoading() }
                }).then(() => {
                    Swal.fire('¡Éxito!', 'El archivo se ha descargado.', 'success');
                });
            }
        });
    });
});

// ========================================
// EXPORTAR DOTACIONES A EXCEL
// ========================================
function exportarDotacionesExcel() {
    Swal.fire({
        title: '<i class="fas fa-file-excel text-success"></i> Exportar Dotaciones',
        html: `
            <div class="text-left p-3">
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fas fa-calendar-alt mr-2"></i>Rango de Fechas:</label>
                    <div class="row">
                        <div class="col">
                            <small class="text-muted">Desde:</small>
                            <input type="date" id="fecha_desde" class="form-control" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col">
                            <small class="text-muted">Hasta:</small>
                            <input type="date" id="fecha_hasta" class="form-control" value="{{ now()->endOfMonth()->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fas fa-filter mr-2"></i>Filtrar por Motivos:</label>
                    <select id="motivo_dotacion" class="form-control">
                        <option value="todos">Todos los motivos</option>
                        <option value="Dotación Semestral">Dotación Semestral</option>
                        <option value="Reposición por Deterioro">Reposición por Deterioro</option>
                        <option value="Ingreso de Personal">Ingreso de Personal</option>
                        <option value="Reposición por Accidente">Reposición por Accidente</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-download mr-2"></i>Descargar Excel',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
        confirmButtonColor: '#1cc88a',
        cancelButtonColor: '#858796',
        width: '500px',
        preConfirm: () => {
            const desde = document.getElementById('fecha_desde').value;
            const hasta = document.getElementById('fecha_hasta').value;
            
            if (!desde || !hasta) {
                Swal.showValidationMessage('Por favor seleccione ambas fechas');
                return false;
            }
            
            if (desde > hasta) {
                Swal.showValidationMessage('La fecha inicial no puede ser mayor a la final');
                return false;
            }
            
            return { desde: desde, hasta: hasta, motivo: document.getElementById('motivo_dotacion').value };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { desde, hasta, motivo } = result.value;
            Swal.fire({
                title: 'Generando Excel...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            let url = `{{ route('medicina.dotaciones.export.excel') }}?desde=${desde}&hasta=${hasta}&motivo=${motivo}`;
            window.location.href = url;
            
            setTimeout(() => Swal.close(), 2000);
        }
    });
}
</script>

@if(session('print_id'))
<script>
    Swal.fire({
        title: '¡Dotación Guardada Exitosamente!',
        html: '<p>La dotación ha sido registrada correctamente.</p><p class="text-muted small">¿Desea imprimir el Ticket de Entrega?</p>',
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#858796',
        confirmButtonText: '<i class="fas fa-print mr-2"></i>Imprimir Ahora',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Más Tarde'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("{{ route('medicina.imprimir.ticket', session('print_id')) }}", '_blank');
        }
    });
</script>
@endif
@endsection