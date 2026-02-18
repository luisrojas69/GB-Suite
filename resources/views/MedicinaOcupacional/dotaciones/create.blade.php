@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* ========================================
       VARIABLES Y FONDOS
    ======================================== */
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --success-gradient: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        --danger-gradient: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        --warning-gradient: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    }

    body {
        background: #f8f9fc;
    }

    /* ========================================
       HEADER PRINCIPAL
    ======================================== */
    .page-header {
        background: linear-gradient(135deg, #1a592e 0%, #2d7a4a 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 6px 20px rgba(26, 89, 46, 0.2);
    }

    .page-header h1 {
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 5px 0;
    }

    .page-header .subtitle {
        font-size: 13px;
        opacity: 0.9;
    }

    .header-badge {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ========================================
       PERFIL DEL EMPLEADO
    ======================================== */
    .employee-profile-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 25px;
    }

    .employee-header {
        background: var(--primary-gradient);
        color: white;
        padding: 25px 30px;
    }

    .employee-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        color: #4e73df;
        border: 5px solid rgba(255, 255, 255, 0.3);
        margin-bottom: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .employee-name {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 5px 0;
    }

    .employee-position {
        font-size: 14px;
        opacity: 0.95;
        margin-bottom: 15px;
    }

    .employee-meta-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .employee-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .employee-body {
        padding: 25px 30px;
        background: white;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        align-items: start;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 18px;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        color: #858796;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
    }

    /* ========================================
       KPIs
    ======================================== */
    .kpi-card {
        border-radius: 10px;
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        height: 100%;
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .kpi-card-body {
        padding: 20px;
        position: relative;
    }

    .kpi-icon-wrapper {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(0, 0, 0, 0.05);
    }

    .kpi-icon {
        font-size: 24px;
        opacity: 0.3;
    }

    .kpi-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        opacity: 0.7;
    }

    .kpi-value {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 5px 0;
        line-height: 1;
    }

    .kpi-meta {
        font-size: 11px;
        margin-top: 5px;
        font-weight: 600;
    }

    .kpi-card-info {
        border-left: 4px solid #36b9cc;
    }

    .kpi-card-info .kpi-label,
    .kpi-card-info .kpi-icon {
        color: #36b9cc;
    }

    .kpi-card-danger {
        border-left: 4px solid #e74a3b;
    }

    .kpi-card-danger .kpi-label,
    .kpi-card-danger .kpi-icon {
        color: #e74a3b;
    }

    .kpi-card-primary {
        border-left: 4px solid #4e73df;
    }

    .kpi-card-primary .kpi-label,
    .kpi-card-primary .kpi-icon {
        color: #4e73df;
    }

    .kpi-card-success {
        border-left: 4px solid #1cc88a;
    }

    .kpi-card-success .kpi-label,
    .kpi-card-success .kpi-icon {
        color: #1cc88a;
    }

    /* ========================================
       FORMULARIO DE DOTACIÓN
    ======================================== */
    .dotation-form-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .dotation-form-header {
        background: white;
        border-bottom: 3px solid #4e73df;
        padding: 20px 25px;
    }

    .dotation-form-header h6 {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .dotation-form-header h6 i {
        color: #4e73df;
        margin-right: 8px;
    }

    .dotation-form-body {
        padding: 30px;
        background: #fafbfc;
    }

    /* ========================================
       STEP INDICATOR
    ======================================== */
    .step-section {
        margin-bottom: 35px;
    }

    .step-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .step-indicator {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        margin-right: 15px;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }

    .step-title {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .step-subtitle {
        font-size: 13px;
        color: #858796;
        margin: 0;
    }

    /* ========================================
       TARJETAS DE ARTÍCULOS
    ======================================== */
    .article-card {
        border-radius: 10px;
        border: 2px solid #e3e6f0;
        background: white;
        transition: all 0.3s ease;
        height: 100%;
    }

    .article-card:hover {
        border-color: #4e73df;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.15);
        transform: translateY(-2px);
    }

    .article-card-body {
        padding: 20px;
    }

    .article-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .article-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-right: 12px;
    }

    .article-info h6 {
        font-size: 15px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 5px 0;
    }

    .size-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        display: inline-block;
    }

    /* Colores por tipo de artículo */
    .article-calzado .article-icon-wrapper {
        background: linear-gradient(135deg, #1cc88a22, #1cc88a11);
        color: #1cc88a;
    }

    .article-calzado .size-badge {
        background: linear-gradient(135deg, #1cc88a, #13855c);
        color: white;
    }

    .article-pantalon .article-icon-wrapper {
        background: linear-gradient(135deg, #4e73df22, #4e73df11);
        color: #4e73df;
    }

    .article-pantalon .size-badge {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
    }

    .article-camisa .article-icon-wrapper {
        background: linear-gradient(135deg, #f6c23e22, #f6c23e11);
        color: #f6c23e;
    }

    .article-camisa .size-badge {
        background: linear-gradient(135deg, #f6c23e, #dda20a);
        color: white;
    }

    /* ========================================
       SELECT2 PERSONALIZADO
    ======================================== */
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        border: 2px solid #e3e6f0 !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        height: auto !important;
        transition: all 0.3s ease !important;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--multiple:focus,
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #4e73df !important;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15) !important;
    }

    /* ========================================
       EQUIPOS ESPECIALES
    ======================================== */
    .special-equipment-card {
        border-radius: 10px;
        border: 2px solid #f6c23e;
        background: linear-gradient(135deg, #fffbf0, #ffffff);
    }

    .special-equipment-card .card-body {
        padding: 20px;
    }

    .special-equipment-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .special-equipment-header i {
        font-size: 24px;
        color: #f6c23e;
        margin-right: 10px;
    }

    .special-equipment-header h6 {
        font-size: 15px;
        font-weight: 700;
        color: #d97706;
        margin: 0;
    }

    /* ========================================
       FIRMA DIGITAL
    ======================================== */
    .signature-section {
        background: white;
        border-radius: 10px;
        padding: 25px;
        border: 2px solid #e3e6f0;
    }

    .signature-pad-container {
        border: 3px dashed #d1d3e2;
        border-radius: 10px;
        background: #fafbfc;
        padding: 15px;
        text-align: center;
    }

    .signature-label {
        font-size: 12px;
        color: #858796;
        font-weight: 600;
        margin-bottom: 10px;
    }

    #signature-pad {
        width: 100%;
        height: 200px;
        background: white;
        border-radius: 8px;
        cursor: crosshair;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
    }

    .btn-clear-signature {
        margin-top: 15px;
    }

    /* ========================================
       DETALLES MÉDICOS
    ======================================== */
    .medical-details-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        border: 2px solid #e3e6f0;
        height: 100%;
    }

    .form-label-custom {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #5a5c69;
        margin-bottom: 8px;
    }

    .form-control-custom {
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }

    /* ========================================
       BOTONES DE ACCIÓN
    ======================================== */
    .action-footer {
        margin-top: 40px;
        padding-top: 25px;
        border-top: 2px solid #e3e6f0;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    .btn-custom {
        padding: 12px 35px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-cancel {
        background: #e3e6f0;
        color: #5a5c69;
    }

    .btn-cancel:hover {
        background: #d1d3e2;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-submit {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
    }

    /* ========================================
       RESPONSIVE
    ======================================== */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 20px;
        }

        .employee-name {
            font-size: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-footer {
            flex-direction: column;
        }

        .btn-custom {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- HEADER PRINCIPAL -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <div class="mb-2 mb-md-0">
                <h1><i class="fas fa-box-open mr-3"></i>Emisión de Dotación Técnica</h1>
                <p class="subtitle mb-0">Gestión de Entrega de Equipos de Protección Personal</p>
            </div>
            <div class="header-badges">
                <span class="header-badge mr-2">
                    <i class="fas fa-hashtag mr-1"></i>ID: {{ $paciente->id }}
                </span>
                <span class="header-badge">
                    <i class="fas fa-check-circle mr-1"></i>Activo
                </span>
            </div>
        </div>
    </div>

    <!-- PERFIL DEL EMPLEADO -->
    <div class="card employee-profile-card">
        <div class="employee-header">
            <div class="row align-items-center">
                <div class="col-md-auto text-center text-md-left mb-3 mb-md-0">
                    <div class="employee-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="col-md">
                    <div class="employee-name">{{ $paciente->nombre_completo }}</div>
                    <div class="employee-position">
                        <i class="fas fa-briefcase mr-2"></i>{{ $paciente->des_cargo }}
                    </div>
                    <div class="employee-meta-badges">
                        <span class="employee-badge">
                            <i class="fas fa-id-card mr-1"></i>{{ $paciente->ci }}
                        </span>
                        <span class="employee-badge">
                            <i class="fas fa-barcode mr-1"></i>{{ $paciente->cod_emp }}
                        </span>
                        <span class="employee-badge">
                            <i class="fas fa-building mr-1"></i>{{ $paciente->des_depart }}
                        </span>
                        <span class="employee-badge">
                            <i class="fas fa-calendar-alt mr-1"></i>Ingreso: {{ \Carbon\Carbon::parse($paciente->fecha_ing)->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="employee-body">
            <div class="info-grid">
                <!-- Información Personal -->
                <div class="info-item">
                    <div class="info-icon" style="background: #e3f2fd; color: #2196f3;">
                        <i class="fas fa-birthday-cake"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Fecha de Nacimiento</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($paciente->fecha_nac)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($paciente->fecha_nac)->age }} años)</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon" style="background: #f3e5f5; color: #9c27b0;">
                        <i class="fas fa-venus-mars"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Género</div>
                        <div class="info-value">{{ $paciente->sexo == 'M' ? 'Masculino' : 'Femenino' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon" style="background: #fff3e0; color: #ff9800;">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Tipo de Sangre</div>
                        <div class="info-value">{{ $paciente->tipo_sangre ?? 'No registrado' }}</div>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="info-item">
                    <div class="info-icon" style="background: #e8f5e9; color: #4caf50;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Teléfono</div>
                        <div class="info-value">{{ $paciente->telefono ?? 'No registrado' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon" style="background: #fce4ec; color: #e91e63;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Correo Electrónico</div>
                        <div class="info-value" style="font-size: 12px;">{{ $paciente->correo_e ?? 'No registrado' }}</div>
                    </div>
                </div>

                <!-- Datos Físicos -->
                <div class="info-item">
                    <div class="info-icon" style="background: #e1f5fe; color: #03a9f4;">
                        <i class="fas fa-weight"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Peso / Estatura</div>
                        <div class="info-value">{{ $paciente->peso_inicial ?? 'N/D' }} kg / {{ $paciente->estatura ?? 'N/D' }} cm</div>
                    </div>
                </div>

                <!-- Tallas -->
                <div class="info-item">
                    <div class="info-icon" style="background: #fff8e1; color: #fbc02d;">
                        <i class="fas fa-ruler"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Tallas (Camisa/Pantalón/Calzado)</div>
                        <div class="info-value">
                            <span class="badge badge-pill badge-warning mr-1">{{ $paciente->talla_camisa }}</span>
                            <span class="badge badge-pill badge-primary mr-1">{{ $paciente->talla_pantalon }}</span>
                            <span class="badge badge-pill badge-success">{{ $paciente->talla_calzado }}</span>
                        </div>
                    </div>
                </div>

                <!-- Antigüedad -->
                <div class="info-item">
                    <div class="info-icon" style="background: #ede7f6; color: #673ab7;">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Antigüedad</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($paciente->fecha_ing)->diffForHumans(null, true) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card kpi-card kpi-card-info">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-history kpi-icon"></i>
                    </div>
                    <div class="kpi-label">Última Dotación</div>
                    <div class="kpi-value">
                        {{ $paciente->dotaciones->last() ? \Carbon\Carbon::parse($paciente->dotaciones->last()->fecha_entrega)->format('d/m/Y') : 'Sin registro' }}
                    </div>
                    <div class="kpi-meta text-muted">
                        {{ $paciente->dotaciones->last() ? \Carbon\Carbon::parse($paciente->dotaciones->last()->fecha_entrega)->diffForHumans(null, true) : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card kpi-card kpi-card-danger">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-ambulance kpi-icon"></i>
                    </div>
                    <div class="kpi-label">Historial Accidentes</div>
                    <div class="kpi-value">{{ $paciente->accidentes->count() }} Registrados</div>
                    <div class="kpi-meta {{ $paciente->accidentes->where('fecha_hora_accidente', '>', now()->subMonths(6))->count() > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $paciente->accidentes->where('fecha_hora_accidente', '>', now()->subMonths(6))->count() }} en últimos 6 meses
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card kpi-card kpi-card-primary">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-calendar-alt kpi-icon"></i>
                    </div>
                    <div class="kpi-label">Antigüedad</div>
                    <div class="kpi-value">{{ $paciente->fecha_ing ? \Carbon\Carbon::parse($paciente->fecha_ing)->diffForHumans(null, true) : 'N/D' }}</div>
                    <div class="kpi-meta text-muted">
                        Área: {{ $paciente->des_depart }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card kpi-card kpi-card-success" style="background: linear-gradient(135deg, #1a592e, #2d7a4a);">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-shield-virus kpi-icon" style="color: rgba(255,255,255,0.5);"></i>
                    </div>
                    <div class="kpi-label" style="color: rgba(255,255,255,0.9);">Días sin Accidentes (Sede)</div>
                    <div class="kpi-value" style="color: white;">124 Días</div>
                    <div class="progress mt-2" style="height: 8px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-success" style="width: 80%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORMULARIO DE DOTACIÓN -->
    <div class="card dotation-form-card">
        <div class="dotation-form-header">
            <h6>
                <i class="fas fa-clipboard-check"></i>
                Configuración de Kit EPP para: {{ $paciente->nombre_completo }}
            </h6>
        </div>

        <div class="dotation-form-body">
            <form id="formDotacion" action="{{ route('medicina.dotaciones.store') }}" method="POST">
                @csrf
                <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

                <!-- PASO 1: SELECCIÓN DE ARTÍCULOS -->
                <div class="step-section">
                    <div class="step-header">
                        <div class="step-indicator">1</div>
                        <div>
                            <div class="step-title">Selección de Artículos</div>
                            <div class="step-subtitle">Stock disponible en Profit</div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Calzado -->
                        <div class="col-md-4 mb-3">
                            <div class="card article-card article-calzado">
                                <div class="article-card-body">
                                    <div class="article-header">
                                        <div class="article-icon-wrapper">
                                            <i class="fas fa-shoe-prints"></i>
                                        </div>
                                        <div class="article-info">
                                            <h6>Calzado de Seguridad</h6>
                                            <span class="size-badge">Talla: {{ $paciente->talla_calzado }}</span>
                                        </div>
                                    </div>
                                    <select name="co_art_calzado" class="form-control select2-single">
                                        <option value="">-- No entregar --</option>
                                        @foreach($stock['botas'] as $item)
                                            <option value="{{ $item->co_art }}" {{ (Str::contains($item->art_des, $paciente->talla_calzado)) ? 'selected' : '' }}>
                                                {{ $item->art_des }} (Stock: {{ number_format($item->stock_act, 0) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="calzado_talla" value="{{ $paciente->talla_calzado }}">
                                </div>
                            </div>
                        </div>

                        <!-- Pantalón -->
                        <div class="col-md-4 mb-3">
                            <div class="card article-card article-pantalon">
                                <div class="article-card-body">
                                    <div class="article-header">
                                        <div class="article-icon-wrapper">
                                            <i class="fas fa-user-tag"></i>
                                        </div>
                                        <div class="article-info">
                                            <h6>Pantalón de Trabajo</h6>
                                            <span class="size-badge">Talla: {{ $paciente->talla_pantalon }}</span>
                                        </div>
                                    </div>
                                    <select name="co_art_pantalon" class="form-control select2-single">
                                        <option value="">-- No entregar --</option>
                                        @foreach($stock['pantalones'] as $item)
                                            <option value="{{ $item->co_art }}" {{ (Str::contains($item->art_des, $paciente->talla_pantalon)) ? 'selected' : '' }}>
                                                {{ $item->art_des }} (Stock: {{ number_format($item->stock_act, 0) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="pantalon_talla" value="{{ $paciente->talla_pantalon }}">
                                </div>
                            </div>
                        </div>

                        <!-- Camisa -->
                        <div class="col-md-4 mb-3">
                            <div class="card article-card article-camisa">
                                <div class="article-card-body">
                                    <div class="article-header">
                                        <div class="article-icon-wrapper">
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                        <div class="article-info">
                                            <h6>Camisa de Trabajo</h6>
                                            <span class="size-badge">Talla: {{ $paciente->talla_camisa }}</span>
                                        </div>
                                    </div>
                                    <select name="co_art_camisa" class="form-control select2-single">
                                        <option value="">-- No entregar --</option>
                                        @foreach($stock['camisas'] as $item)
                                            <option value="{{ $item->co_art }}" {{ (Str::contains($item->art_des, $paciente->talla_camisa)) ? 'selected' : '' }}>
                                                {{ $item->art_des }} (Stock: {{ number_format($item->stock_act, 0) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="camisa_talla" value="{{ $paciente->talla_camisa }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Equipos Especiales -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card special-equipment-card">
                                <div class="card-body">
                                    <div class="special-equipment-header">
                                        <i class="fas fa-mask"></i>
                                        <h6>Equipos Especiales y Consumibles</h6>
                                    </div>
                                    <select name="otros_epp_codigos[]" class="form-control select2-multiple" multiple="multiple">
                                        @foreach($stock['otros'] as $item)
                                            <option value="{{ $item->co_art }}">
                                                {{ $item->art_des }} | Stock: {{ number_format($item->stock_act, 0) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-2 mb-0 small text-muted">Añada guantes, lentes, protectores auditivos, etc.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PASOS 2 Y 3 -->
                <div class="row">
                    <!-- PASO 2: DETALLES MÉDICOS -->
                    <div class="col-lg-6 mb-3">
                        <div class="step-section">
                            <div class="step-header">
                                <div class="step-indicator">2</div>
                                <div>
                                    <div class="step-title">Detalles Médicos</div>
                                    <div class="step-subtitle">Motivo y observaciones</div>
                                </div>
                            </div>

                            <div class="medical-details-card">
                                <div class="form-group">
                                    <label class="form-label-custom">Motivo Legal/Administrativo</label>
                                    <select class="form-control form-control-custom" name="motivo">
                                        <option>Dotación Semestral</option>
                                        <option>Reposición por Deterioro</option>
                                        <option>Ingreso de Personal</option>
                                        <option>Reposición por Accidente</option>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label-custom">Observaciones Médicas / SSL</label>
                                    <textarea name="observaciones" class="form-control form-control-custom" rows="6" placeholder="Detalle cualquier condición especial aquí..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 3: FIRMA DIGITAL -->
                    <div class="col-lg-6 mb-3">
                        <div class="step-section">
                            <div class="step-header">
                                <div class="step-indicator">3</div>
                                <div>
                                    <div class="step-title">Validación del Trabajador</div>
                                    <div class="step-subtitle">Firma digital de recepción</div>
                                </div>
                            </div>

                            <div class="signature-section">
                                <div class="signature-pad-container">
                                    <p class="signature-label">Firma digital en pantalla</p>
                                    <canvas id="signature-pad"></canvas>
                                    <button type="button" id="clear" class="btn btn-sm btn-outline-danger btn-clear-signature">
                                        <i class="fas fa-eraser mr-1"></i>Borrar Firma
                                    </button>
                                </div>
                                <input type="hidden" name="firma_digital" id="firma_digital">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN -->
                <div class="action-footer">
                    <button type="button" onclick="history.back()" class="btn btn-custom btn-cancel">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-custom btn-submit">
                        <i class="fas fa-paper-plane mr-2"></i>Generar Orden de Entrega
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.select2-single').select2({ 
        placeholder: "Seleccione artículo o deje vacío",
        allowClear: true
    });
    
    $('.select2-multiple').select2({ 
        placeholder: "Busque y seleccione artículos...",
        allowClear: true
    });

    // Configurar Signature Pad
    const canvas = document.getElementById("signature-pad");
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)'
    });

    // Ajustar tamaño del canvas
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }
    
    window.onresize = resizeCanvas;
    resizeCanvas();

    // Botón limpiar firma
    $('#clear').on('click', function() {
        signaturePad.clear();
        $('#firma_digital').val('');
    });

    // Validación del formulario
    $('#formDotacion').on('submit', function(e) {
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            Swal.fire({
                title: 'Firma Requerida',
                text: 'El trabajador debe firmar la recepción del equipo antes de continuar.',
                icon: 'warning',
                confirmButtonColor: '#4e73df'
            });
            return false;
        } else {
            const dataURL = signaturePad.toDataURL("image/png");
            $('#firma_digital').val(dataURL);
            return true;
        }
    });
});
</script>
@endsection