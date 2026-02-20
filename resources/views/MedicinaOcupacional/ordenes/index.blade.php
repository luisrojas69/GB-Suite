@extends('layouts.app')
@section('title-page', 'Gestión de Órdenes de Exámenes')
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
        --green-corporate: #1a592e;
    }

    body {
        background: #f8f9fc;
    }

    /* ========================================
       HEADER PRINCIPAL
    ======================================== */
    .page-header-master {
        background: linear-gradient(135deg, #1a592e 0%, #2d7a4a 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 6px 20px rgba(26, 89, 46, 0.25);
        position: relative;
        overflow: hidden;
    }

    .page-header-master::before {
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
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 5px 0;
    }

    .header-subtitle {
        font-size: 13px;
        opacity: 0.95;
    }

    .btn-nueva-orden {
        background: white;
        color: #1a592e;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .btn-nueva-orden:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        color: #1a592e;
    }

    /* ========================================
       KPIs MODERNOS CON GRADIENTES
    ======================================== */
    .kpi-card-master {
        border-radius: 12px;
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        height: 100%;
        position: relative;
    }

    .kpi-card-master:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .kpi-card-body {
        padding: 22px;
        position: relative;
        overflow: hidden;
    }

    .kpi-floating-icon {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: rgba(255, 255, 255, 0.8);
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(-50%) translateX(0); }
        50% { transform: translateY(-50%) translateX(-5px); }
    }

    .kpi-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .kpi-label i {
        margin-right: 6px;
    }

    .kpi-value {
        font-size: 36px;
        font-weight: 700;
        color: white;
        margin: 0;
        line-height: 1;
    }

    .kpi-meta {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.8);
        margin-top: 8px;
    }

    .kpi-card-primary { background: linear-gradient(135deg, #4e73df, #224abe); }
    .kpi-card-warning { background: linear-gradient(135deg, #f6c23e, #dda20a); }
    .kpi-card-success { background: linear-gradient(135deg, #1cc88a, #13855c); }
    .kpi-card-info { background: linear-gradient(135deg, #36b9cc, #258391); }

    /* ========================================
       ALERTA DE CONSULTAS PENDIENTES
    ======================================== */
    .alert-critical-section {
        background: white;
        border-radius: 12px;
        border: 3px solid #f6c23e;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(246, 194, 62, 0.2);
        overflow: hidden;
        animation: pulse-alert 3s infinite;
    }

    @keyframes pulse-alert {
        0%, 100% {
            box-shadow: 0 4px 15px rgba(246, 194, 62, 0.2);
            border-color: #f6c23e;
        }
        50% {
            box-shadow: 0 6px 25px rgba(246, 194, 62, 0.4);
            border-color: #dda20a;
        }
    }

    .alert-critical-header {
        background: linear-gradient(135deg, #f6c23e, #dda20a);
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .alert-critical-title {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .alert-critical-title i {
        margin-right: 10px;
        font-size: 18px;
    }

    .critical-count-badge {
        background: white;
        color: #f6c23e;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }

    .pending-table {
        margin: 0;
        width: 100%;
    }

    .pending-table thead th {
        background: #fafbfc;
        border-bottom: 2px solid #e3e6f0;
        padding: 14px 20px;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: #5a5c69;
        letter-spacing: 0.5px;
    }

    .pending-table tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fc;
    }

    .pending-table tbody tr {
        transition: all 0.2s ease;
    }

    .pending-table tbody tr:hover {
        background: #fffbf0;
    }

    .pending-patient-name {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .pending-patient-ci {
        font-size: 11px;
        color: #858796;
    }

    .pending-date-main {
        font-size: 13px;
        font-weight: 700;
        color: #2c3e50;
    }

    .pending-date-time {
        font-size: 11px;
        color: #858796;
    }

    .btn-generate-now {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        border: none;
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);
        transition: all 0.2s ease;
    }

    .btn-generate-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
        color: white;
    }

    /* ========================================
       TABLA PRINCIPAL DE ÓRDENES
    ======================================== */
    .main-orders-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .main-orders-header {
        background: white;
        border-bottom: 2px solid #f8f9fc;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .main-orders-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .main-orders-title i {
        color: #4e73df;
        margin-right: 12px;
        font-size: 18px;
    }

    .main-orders-body {
        padding: 25px;
    }

    .orders-table-master {
        width: 100%;
        margin: 0;
    }

    .orders-table-master thead th {
        background: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        padding: 14px 12px;
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 700;
        color: #4e73df;
        letter-spacing: 0.8px;
    }

    .orders-table-master tbody td {
        padding: 16px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fc;
    }

    .orders-table-master tbody tr {
        transition: all 0.2s ease;
    }

    .orders-table-master tbody tr:hover {
        background: #fafbfc;
        box-shadow: inset 4px 0 0 #4e73df;
    }

    /* ========================================
       COLUMNA: ORDEN ID
    ======================================== */
    .order-id-wrapper {
        display: flex;
        flex-direction: column;
    }

    .order-number {
        font-size: 14px;
        font-weight: 700;
        color: #4e73df;
        margin-bottom: 4px;
    }

    .order-timestamp {
        font-size: 10px;
        color: #858796;
        display: flex;
        align-items: center;
    }

    .order-timestamp i {
        margin-right: 4px;
        font-size: 9px;
    }

    /* ========================================
       COLUMNA: PACIENTE
    ======================================== */
    .patient-cell {
        display: flex;
        align-items: center;
    }

    .patient-avatar-wrapper {
        position: relative;
        margin-right: 12px;
    }

    .patient-avatar-img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 2px solid #e3e6f0;
        object-fit: cover;
    }

    .gender-indicator {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
        border: 1px solid #e3e6f0;
    }

    .patient-info-wrapper {
        flex: 1;
    }

    .patient-name-link {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        display: block;
        margin-bottom: 3px;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .patient-name-link:hover {
        color: #4e73df;
        text-decoration: none;
    }

    .patient-ci-label {
        font-size: 10px;
        color: #858796;
        font-weight: 600;
        text-transform: uppercase;
    }

    /* ========================================
       COLUMNA: CONSULTA
    ======================================== */
    .consultation-wrapper {
        display: flex;
        flex-direction: column;
    }

    .consultation-link {
        font-size: 13px;
        font-weight: 700;
        color: #2c3e50;
        display: flex;
        align-items: center;
        margin-bottom: 6px;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .consultation-link:hover {
        color: #36b9cc;
        text-decoration: none;
    }

    .consultation-link i {
        color: #36b9cc;
        margin-right: 6px;
    }

    .consultation-type-badge {
        background: rgba(78, 115, 223, 0.1);
        color: #4e73df;
        border: 1px solid rgba(78, 115, 223, 0.2);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
    }

    /* ========================================
       COLUMNA: EXÁMENES
    ======================================== */
    .exams-display {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        max-width: 300px;
    }

    .exam-pill-modern {
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
        color: #4e73df;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        transition: all 0.2s ease;
    }

    .exam-pill-modern:hover {
        background: #e8f0fe;
        border-color: #4e73df;
        transform: translateY(-1px);
    }

    .more-exams-indicator {
        background: #4e73df;
        color: white;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        cursor: help;
    }

    /* ========================================
       COLUMNA: ESTADO
    ======================================== */
    .status-display {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .status-badge-modern {
        padding: 7px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
        display: inline-flex;
        align-items: center;
    }

    .status-badge-modern i {
        margin-right: 6px;
    }

    .status-pending {
        background: rgba(246, 194, 62, 0.15);
        color: #d97706;
        border: 1px solid rgba(246, 194, 62, 0.3);
    }

    .status-completed {
        background: rgba(28, 200, 138, 0.15);
        color: #065f46;
        border: 1px solid rgba(28, 200, 138, 0.3);
    }

    .status-description {
        font-size: 10px;
        color: #858796;
        font-style: italic;
    }

    /* ========================================
       COLUMNA: ACCIONES
    ======================================== */
    .actions-cell {
        text-align: center;
    }

    .btn-actions-trigger {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: white;
        border: 1px solid #e3e6f0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        margin: 0 auto;
    }

    .btn-actions-trigger:hover {
        background: #f8f9fc;
        border-color: #4e73df;
    }

    .dropdown-menu-actions {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        padding: 10px 0;
        min-width: 220px;
    }

    .dropdown-header-actions {
        padding: 10px 20px;
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 700;
        color: #858796;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f8f9fc;
    }

    .dropdown-item-actions {
        padding: 10px 20px;
        font-size: 13px;
        color: #5a5c69;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
    }

    .dropdown-item-actions:hover {
        background: #f8f9fc;
        color: #4e73df;
    }

    .dropdown-item-actions i {
        width: 20px;
        margin-right: 12px;
        font-size: 14px;
    }

    .dropdown-divider-actions {
        margin: 8px 0;
        border-top: 1px solid #e3e6f0;
    }

    .dropdown-item-complete {
        color: #1cc88a;
        font-weight: 700;
    }

    .dropdown-item-complete:hover {
        background: rgba(28, 200, 138, 0.1);
        color: #13855c;
    }

    /* ========================================
       DATATABLES PERSONALIZACIONES
    ======================================== */
    .dataTables_wrapper .dataTables_length {
        margin-bottom: 20px;
    }

    .dataTables_wrapper .dataTables_length select {
        border: 2px solid #e3e6f0;
        border-radius: 6px;
        padding: 6px 10px;
        margin: 0 8px;
    }

    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 20px;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        padding: 8px 15px;
        margin-left: 8px;
        transition: all 0.2s ease;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #4e73df;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px;
        margin: 0 2px;
        border-radius: 6px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, #4e73df, #224abe) !important;
        border: none !important;
        color: white !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f8f9fc !important;
        border-color: #4e73df !important;
        color: #4e73df !important;
    }

    .dataTables_wrapper .dataTables_info {
        padding-top: 15px;
        font-size: 13px;
        color: #858796;
    }

    /* ========================================
       RESPONSIVE
    ======================================== */
    @media (max-width: 768px) {
        .page-header-master {
            padding: 20px;
        }

        .header-title h1 {
            font-size: 22px;
        }

        .kpi-value {
            font-size: 28px;
        }

        .patient-cell {
            flex-direction: column;
            align-items: flex-start;
        }

        .patient-avatar-wrapper {
            margin-bottom: 10px;
        }

        .exams-display {
            max-width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- HEADER PRINCIPAL -->
    <div class="page-header-master">
        <div class="header-content">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div class="header-icon mr-3">
                        <i class="fas fa-microscope"></i>
                    </div>
                    <div class="header-title">
                        <h1>Gestión de Órdenes de Exámenes</h1>
                        <p class="header-subtitle mb-0">
                            <i class="fas fa-heartbeat mr-2"></i>Panel de Control de Estudios Clínicos y Laboratorios
                        </p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('medicina.pacientes.index') }}" class="btn btn-nueva-orden">
                        <i class="fas fa-plus-circle mr-2"></i>Nueva Orden
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs PRINCIPALES -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card-master kpi-card-primary">
                <div class="kpi-card-body">
                    <div class="kpi-floating-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="kpi-label">
                        <i class="fas fa-clock"></i>Órdenes de Hoy
                    </div>
                    <div class="kpi-value">{{ $stats['hoy'] }}</div>
                    <div class="kpi-meta">Generadas hoy</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card-master kpi-card-warning">
                <div class="kpi-card-body">
                    <div class="kpi-floating-icon">
                        <i class="fas fa-vials"></i>
                    </div>
                    <div class="kpi-label">
                        <i class="fas fa-hourglass-half"></i>En Proceso
                    </div>
                    <div class="kpi-value">{{ $stats['pendientes'] }}</div>
                    <div class="kpi-meta">Esperando resultados</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card-master kpi-card-success">
                <div class="kpi-card-body">
                    <div class="kpi-floating-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="kpi-label">
                        <i class="fas fa-check-circle"></i>Completadas
                    </div>
                    <div class="kpi-value">{{ $stats['completadas'] }}</div>
                    <div class="kpi-meta">Resultados listos</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card-master kpi-card-info">
                <div class="kpi-card-body">
                    <div class="kpi-floating-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="kpi-label">
                        <i class="fas fa-list"></i>Total Registradas
                    </div>
                    <div class="kpi-value">{{ $stats['total'] }}</div>
                    <div class="kpi-meta">Todas las órdenes</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ALERTA: CONSULTAS SIN ORDEN -->
    @if(isset($consultasSinOrden) && $consultasSinOrden->count() > 0)
    <div class="alert-critical-section">
        <div class="alert-critical-header">
            <h6 class="alert-critical-title">
                <i class="fas fa-exclamation-triangle"></i>
                Consultas Pendientes por Generar Orden Médica
            </h6>
            <span class="critical-count-badge">{{ $consultasSinOrden->count() }}</span>
        </div>
        <div class="table-responsive">
            <table class="table pending-table">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Fecha de Consulta</th>
                        <th>Motivo de Consulta</th>
                        <th class="text-center">Acción Requerida</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consultasSinOrden as $c)
                    <tr>
                        <td>
                            <div class="pending-patient-name">{{ $c->paciente->nombre_completo }}</div>
                            <div class="pending-patient-ci">CI: {{ number_format($c->paciente->ci, 0, ',', '.') }}</div>
                        </td>
                        <td>
                            <div class="pending-date-main">{{ $c->created_at->format('d/m/Y') }}</div>
                            <div class="pending-date-time">
                                <i class="far fa-clock mr-1"></i>{{ $c->created_at->format('h:i A') }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 13px; color: #5a5c69;">
                                {{ Str::limit($c->motivo_consulta, 60) }}
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('medicina.ordenes.create', ['consulta_id' => $c->id]) }}" 
                               class="btn btn-generate-now">
                                <i class="fas fa-plus-circle mr-2"></i>Generar Orden
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- TABLA PRINCIPAL DE ÓRDENES -->
    <div class="main-orders-card">
        <div class="main-orders-header">
            <h6 class="main-orders-title">
                <i class="fas fa-list-alt"></i>
                Registro Completo de Órdenes Médicas
            </h6>
        </div>
        <div class="main-orders-body">
            <div class="table-responsive">
                <table class="table orders-table-master" id="dataTableOrdenes">
                    <thead>
                        <tr>
                            <th>Orden / Fecha</th>
                            <th>Paciente</th>
                            <th>Consulta Origen</th>
                            <th>Exámenes Solicitados</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ordenes as $orden)
                        <tr>
                            <!-- ORDEN ID Y FECHA -->
                            <td>
                                <div class="order-id-wrapper">
                                    <span class="order-number">ORD-{{ str_pad($orden->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <span class="order-timestamp">
                                        <i class="far fa-calendar-alt"></i>{{ $orden->created_at->format('d/m/Y') }}
                                        <i class="far fa-clock ml-2"></i>{{ $orden->created_at->format('h:i A') }}
                                    </span>
                                </div>
                            </td>

                            <!-- PACIENTE CON AVATAR -->
                            <td>
                                <div class="patient-cell">
                                    <div class="patient-avatar-wrapper">
                                        @if($orden->paciente->sexo == 'F')
                                            <img src="/assets/img/avatar_female.png" alt="Avatar" class="patient-avatar-img">
                                            <span class="gender-indicator" style="color: #e74a3b;">
                                                <i class="fas fa-venus"></i>
                                            </span>
                                        @else
                                            <img src="/assets/img/avatar_male.png" alt="Avatar" class="patient-avatar-img">
                                            <span class="gender-indicator" style="color: #4e73df;">
                                                <i class="fas fa-mars"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="patient-info-wrapper">
                                        <a href="{{ route('medicina.pacientes.show', $orden->paciente->id) }}" 
                                           class="patient-name-link">
                                            {{ $orden->paciente->nombre_completo }}
                                        </a>
                                        <div class="patient-ci-label">
                                            CI: {{ number_format($orden->paciente->ci, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- CONSULTA ORIGEN -->
                            <td>
                                <div class="consultation-wrapper">
                                    <a href="{{ route('medicina.consultas.show', $orden->consulta_id) }}" 
                                       class="consultation-link">
                                        <i class="fas fa-notes-medical"></i>
                                        Consulta #{{ $orden->consulta_id }}
                                    </a>
                                    <span class="consultation-type-badge">
                                        {{ Str::limit($orden->consulta->motivo_consulta ?? 'General', 20) }}
                                    </span>
                                </div>
                            </td>

                            <!-- EXÁMENES -->
                            <td>
                                <div class="exams-display">
                                    @foreach(array_slice($orden->examenes, 0, 3) as $examen)
                                        <span class="exam-pill-modern">{{ $examen }}</span>
                                    @endforeach
                                    @if(count($orden->examenes) > 3)
                                        <span class="more-exams-indicator" 
                                              data-toggle="tooltip" 
                                              data-html="true"
                                              title="{{ implode('<br>', array_slice($orden->examenes, 3)) }}">
                                            +{{ count($orden->examenes) - 3 }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- ESTADO -->
                            <td>
                                <div class="status-display">
                                    @if($orden->status_orden == 'Pendiente')
                                        <span class="status-badge-modern status-pending">
                                            <i class="fas fa-hourglass-start"></i>En Proceso
                                        </span>
                                        <span class="status-description">Esperando resultados...</span>
                                    @else
                                        <span class="status-badge-modern status-completed">
                                            <i class="fas fa-check-circle"></i>Completada
                                        </span>
                                        <span class="status-description">{{ $orden->updated_at->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- ACCIONES -->
                            <td class="actions-cell">
                                <div class="dropdown">
                                    <button class="btn-actions-trigger" type="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-gray-400"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-actions">
                                        <div class="dropdown-header-actions">Opciones Médicas</div>
                                        
                                        <a class="dropdown-item-actions" href="{{ route('medicina.ordenes.show', $orden->id) }}">
                                            <i class="fas fa-eye text-success"></i>
                                            Ver Detalles
                                        </a>

                                        <a class="dropdown-item-actions" href="{{ route('medicina.ordenes.edit', $orden->id) }}">
                                            <i class="fas fa-flask text-primary"></i>
                                            Gestionar Resultados
                                        </a>
                                        
                                        <a class="dropdown-item-actions" href="{{ route('medicina.ordenes.pdf', $orden->id) }}" target="_blank">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            Generar Orden (PDF)
                                        </a>
                                        
                                        @if($orden->status_orden == 'Pendiente')
                                            <div class="dropdown-divider-actions"></div>
                                            <form action="{{ route('medicina.ordenes.completar', $orden->id) }}" 
                                                  method="POST" 
                                                  class="form-completar"
                                                  data-orden-id="{{ $orden->id }}">
                                                @csrf 
                                                @method('PUT')
                                                <button type="submit" class="dropdown-item-actions dropdown-item-complete">
                                                    <i class="fas fa-check-double"></i>
                                                    Marcar como Completada
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#dataTableOrdenes').DataTable({
        language: { 
            url: "/js/lang/Spanish.json" 
        },
        order: [[ 0, "desc" ]],
        pageLength: 15,
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip({
        html: true
    });

    // Alerta profesional para completar orden
    $('.form-completar').on('submit', function(e){
        e.preventDefault();
        const form = this;
        
        Swal.fire({
            title: '¿Marcar Orden como Completada?',
            html: '<p>Estás a punto de marcar esta orden como <strong>Completada</strong>.</p>' +
                  '<p class="text-muted small">Si necesitas cargar resultados primero, selecciona "Gestionar Resultados".</p>',
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: '#1cc88a',
            denyButtonColor: '#4e73df',
            cancelButtonColor: '#858796',
            confirmButtonText: '<i class="fas fa-check"></i> Sí, Completar',
            denyButtonText: '<i class="fas fa-flask"></i> Gestionar Resultados',
            cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            } else if (result.isDenied) {
                const ordenId = $(form).data('orden-id');
                window.location.href = "{{ url('medicina/ordenes') }}/" + ordenId + "/edit";
            }
        });
    });
});
</script>

@if(session('print_id'))
<script>
    Swal.fire({
        title: '¡Orden Guardada Exitosamente!',
        html: '<p class="mb-2">La orden médica ha sido registrada correctamente.</p>' +
              '<p class="text-muted small">¿Desea imprimirla ahora?</p>',
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#858796',
        confirmButtonText: '<i class="fas fa-print mr-2"></i>Imprimir Ahora',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Más Tarde',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("{{ route('medicina.ordenes.pdf', session('print_id')) }}", '_blank');
        }
    });
</script>
@endif
@endsection