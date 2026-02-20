@extends('layouts.app')
@section('title-page', 'Detalles de la Orden # ' .str_pad($orden->id, 6, '0', STR_PAD_LEFT))
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
        width: 400px;
        height: 400px;
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

    .header-badge {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ========================================
       ALERTAS MEJORADAS
    ======================================== */
    .alert-enhanced {
        border-radius: 10px;
        border: none;
        padding: 18px 25px;
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

    /* ========================================
       PERFIL DEL PACIENTE Y CONSULTA
    ======================================== */
    .patient-consultation-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .patient-consultation-header {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .patient-consultation-header h6 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .consultation-badge {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .patient-consultation-body {
        padding: 25px;
    }

    .patient-section {
        padding-right: 25px;
        border-right: 2px solid #f8f9fc;
    }

    .consultation-section {
        padding-left: 25px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 15px;
    }

    .info-item {
        display: flex;
        align-items: start;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 16px;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 700;
        color: #858796;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
    }

    .consultation-info-box {
        background: #f8f9fc;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .consultation-info-label {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: #858796;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .consultation-info-value {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
    }

    /* ========================================
       EXÁMENES SOLICITADOS
    ======================================== */
    .exams-requested-card {
        background: linear-gradient(135deg, #e8f0fe 0%, #d3e3fd 100%);
        border: 2px solid #4e73df;
        border-radius: 10px;
        padding: 18px 20px;
        margin-bottom: 20px;
    }

    .exams-requested-title {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #4e73df;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .exam-badge {
        background: white;
        border: 1px solid #4e73df;
        color: #4e73df;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        margin-right: 8px;
        margin-bottom: 8px;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(78, 115, 223, 0.15);
    }

    /* ========================================
       FORMULARIO
    ======================================== */
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .form-card-header {
        background: white;
        border-bottom: 2px solid #f8f9fc;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-card-header h6 {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .form-card-header h6 i {
        color: #4e73df;
        margin-right: 10px;
    }

    .form-card-body {
        padding: 25px;
    }

    /* ========================================
       INTERPRETACIÓN RADIO BUTTONS
    ======================================== */
    .interpretation-selector {
        display: flex;
        gap: 15px;
        margin-top: 15px;
    }

    .interpretation-option {
        flex: 1;
        position: relative;
    }

    .interpretation-option input[type="radio"] {
        display: none;
    }

    .interpretation-label {
        display: block;
        padding: 15px 20px;
        border: 2px solid #e3e6f0;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .interpretation-label:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .interpretation-option input[type="radio"]:checked + .interpretation-label {
        border-width: 3px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .interpretation-option input[type="radio"]:checked + .interpretation-label.label-normal {
        border-color: #1cc88a;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    }

    .interpretation-option input[type="radio"]:checked + .interpretation-label.label-alterado {
        border-color: #e74a3b;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    }

    .interpretation-icon {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .interpretation-text {
        font-size: 15px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .label-normal .interpretation-icon,
    .label-normal .interpretation-text {
        color: #1cc88a;
    }

    .label-alterado .interpretation-icon,
    .label-alterado .interpretation-text {
        color: #e74a3b;
    }

    /* ========================================
       TEXTAREA PERSONALIZADO
    ======================================== */
    .form-control-custom {
        border: 2px solid #e3e6f0;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }

    .form-label-custom {
        font-size: 13px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .form-label-custom i {
        color: #4e73df;
        margin-right: 8px;
    }

    /* ========================================
       ARCHIVOS
    ======================================== */
    .files-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .files-card-header {
        background: white;
        border-bottom: 2px solid #f8f9fc;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .files-count-badge {
        background: #4e73df;
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
    }

    .upload-area {
        border: 2px dashed #d1d3e2;
        border-radius: 10px;
        background: #fafbfc;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .upload-area:hover {
        border-color: #4e73df;
        background: #f8f9fc;
    }

    .upload-icon {
        font-size: 36px;
        color: #4e73df;
        margin-bottom: 10px;
    }

    .upload-text {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .upload-hint {
        font-size: 12px;
        color: #858796;
    }

    .files-list {
        max-height: 350px;
        overflow-y: auto;
    }

    .file-item {
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
    }

    .file-item:hover {
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .file-info {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .file-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #e74a3b, #be2617);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-right: 15px;
    }

    .file-details {
        flex: 1;
    }

    .file-name {
        font-size: 13px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .file-meta {
        font-size: 11px;
        color: #858796;
    }

    .btn-view-file {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #36b9cc, #258391);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-view-file:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(54, 185, 204, 0.3);
    }

    .empty-files {
        text-align: center;
        padding: 50px 20px;
    }

    .empty-files-icon {
        font-size: 64px;
        color: #e3e6f0;
        margin-bottom: 15px;
    }

    .empty-files-text {
        font-size: 14px;
        color: #858796;
    }

    /* ========================================
       BOTONES DE ACCIÓN
    ======================================== */
    .actions-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        padding: 20px;
    }

    .btn-action {
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        width: 100%;
        margin-bottom: 10px;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-primary-action {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
    }

    .btn-success-action {
        background: linear-gradient(135deg, #1cc88a, #13855c);
        color: white;
    }

    .btn-secondary-action {
        background: #e3e6f0;
        color: #5a5c69;
    }

    /* ========================================
       SCROLLBAR PERSONALIZADO
    ======================================== */
    .files-list::-webkit-scrollbar {
        width: 6px;
    }

    .files-list::-webkit-scrollbar-track {
        background: #f8f9fc;
        border-radius: 10px;
    }

    .files-list::-webkit-scrollbar-thumb {
        background: #d1d3e2;
        border-radius: 10px;
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

        .patient-section {
            border-right: none;
            border-bottom: 2px solid #f8f9fc;
            padding-right: 0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .consultation-section {
            padding-left: 0;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .interpretation-selector {
            flex-direction: column;
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

    <!-- HEADER PRINCIPAL -->
    <div class="page-header-master">
        <div class="header-content">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div class="header-icon mr-3">
                        <i class="fas fa-file-medical-alt"></i>
                    </div>
                    <div class="header-title">
                        <h1>Interpretación de Resultados</h1>
                        <p class="header-subtitle mb-0">Registro de Hallazgos y Adjuntos de Laboratorio</p>
                    </div>
                </div>
                <div>
                    <span class="header-badge mr-2">
                        <i class="fas fa-hashtag mr-1"></i>Orden #{{ str_pad($orden->id, 6, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="header-badge">
                        <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($orden->created_at)->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- PERFIL DEL PACIENTE Y CONSULTA -->
    <div class="patient-consultation-card">
        <div class="patient-consultation-header">
            <h6><i class="fas fa-clipboard-list mr-2"></i>Información de la Orden</h6>
            <span class="consultation-badge">
                <i class="fas fa-stethoscope mr-1"></i>Consulta #{{ str_pad($orden->consulta->id, 6, '0', STR_PAD_LEFT)   }}  -  <a href="{{ route('medicina.consultas.show', $orden->consulta->id) }}" title="Ver Detalles de la Consulta"><i class="fas fa-eye mr-1 text-info"></i></a> 
            </span>
        </div>
        <div class="patient-consultation-body">
            <div class="row">
                <!-- INFORMACIÓN DEL PACIENTE -->
                <div class="col-md-6 patient-section">
                    <h6 class="font-weight-bold text-primary mb-3">
                        <i class="fas fa-user-circle mr-2"></i>Datos del Trabajador
                    </h6>
                    
                    <div class="info-item mb-3">
                        <div class="patient-name" style="font-size: 20px; font-weight: 700; color: #2c3e50;">
                            {{ $orden->paciente->nombre_completo }}
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon" style="background: rgba(78, 115, 223, 0.1); color: #4e73df;">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Cédula</div>
                                <div class="info-value">{{ number_format($orden->paciente->ci, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon" style="background: rgba(246, 194, 62, 0.1); color: #f6c23e;">
                                <i class="fas fa-barcode"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Código Empleado</div>
                                <div class="info-value">{{ $orden->paciente->cod_emp ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon" style="background: rgba(28, 200, 138, 0.1); color: #1cc88a;">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Cargo</div>
                                <div class="info-value">{{ $orden->paciente->des_cargo }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon" style="background: rgba(54, 185, 204, 0.1); color: #36b9cc;">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Departamento</div>
                                <div class="info-value">{{ $orden->paciente->des_depart }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon" style="background: rgba(246, 194, 62, 0.1); color: #f6c23e;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Telefono</div>
                                <div class="info-value">{{ $orden->paciente->telefono ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon" style="background: rgba(78, 115, 223, 0.1); color: #4e73df;">
                                <i class="fas fa-fax"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Correo</div>
                                <div class="info-value">{{ $orden->paciente->correo_e ?? 'N/A' }}</div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- INFORMACIÓN DE LA CONSULTA -->
                <div class="col-md-6 consultation-section">
                    <h6 class="font-weight-bold text-info mb-3">
                        <i class="fas fa-notes-medical mr-2"></i>Contexto Clínico
                    </h6>

                    <div class="consultation-info-box">
                        <div class="consultation-info-label">Motivo de Consulta</div>
                        <div class="consultation-info-value">{{ $orden->consulta->motivo_consulta }}</div>
                    </div>

                    <div class="consultation-info-box">
                        <div class="consultation-info-label">Diagnóstico CIE-10</div>
                        <div class="consultation-info-value">{{ $orden->consulta->diagnostico_cie10 ?? 'Sin especificar' }}</div>
                    </div>

                    <div class="consultation-info-box mb-0">
                        <div class="consultation-info-label">Fecha de Consulta</div>
                        <div class="consultation-info-value">
                            <i class="fas fa-calendar-alt mr-2" style="color: #4e73df;"></i>
                            {{ \Carbon\Carbon::parse($orden->consulta->fecha_consulta)->format('d/m/Y') }}
                            <span class="text-muted" style="font-size: 12px;">
                                ({{ \Carbon\Carbon::parse($orden->consulta->fecha_consulta)->diffForHumans() }})
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('medicina.ordenes.update', $orden->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- COLUMNA IZQUIERDA: INTERPRETACIÓN -->
            <div class="col-lg-7">
                <!-- EXÁMENES SOLICITADOS -->
                <div class="exams-requested-card">
                    <div class="exams-requested-title">
                        <i class="fas fa-microscope mr-2"></i>Exámenes Solicitados en esta Orden
                    </div>
                    <div>
                        @foreach($orden->examenes as $examen)
                            <span class="exam-badge">
                                <i class="fas fa-check mr-1"></i>{{ $examen }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- INTERPRETACIÓN GLOBAL -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h6>
                            <i class="fas fa-clipboard-check"></i>Interpretación Global de Resultados
                        </h6>
                    </div>
                    <div class="form-card-body">
                        <label class="form-label-custom">
                            <i class="fas fa-chart-line"></i>Estado General de los Exámenes
                        </label>

                        <div class="interpretation-selector">
                            <div class="interpretation-option">
                                <input type="radio" id="normal" name="interpretacion" value="Normal" 
                                       {{ $orden->interpretacion == 'Normal' ? 'checked' : '' }}>
                                <label class="interpretation-label label-normal" for="normal">
                                    <div class="interpretation-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="interpretation-text">Normal</div>
                                    <div style="font-size: 11px; margin-top: 5px; opacity: 0.8;">
                                        Sin hallazgos patológicos
                                    </div>
                                </label>
                            </div>

                            <div class="interpretation-option">
                                <input type="radio" id="alterado" name="interpretacion" value="Alterado" 
                                       {{ $orden->interpretacion == 'Alterado' ? 'checked' : '' }}>
                                <label class="interpretation-label label-alterado" for="alterado">
                                    <div class="interpretation-icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="interpretation-text">Alterado</div>
                                    <div style="font-size: 11px; margin-top: 5px; opacity: 0.8;">
                                        Requiere atención médica
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HALLAZGOS -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h6>
                            <i class="fas fa-notes-medical"></i>Hallazgos y Notas Médicas
                        </h6>
                    </div>
                    <div class="form-card-body">
                        <label class="form-label-custom">
                            <i class="fas fa-pen"></i>Detalles de los Resultados
                        </label>
                        <textarea name="hallazgos" 
                                  class="form-control form-control-custom" 
                                  rows="8" 
                                  placeholder="Registre aquí los hallazgos relevantes, valores fuera de rango, observaciones del médico tratante, y recomendaciones de seguimiento...">{{ old('hallazgos', $orden->hallazgos) }}</textarea>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle mr-1"></i>
                            Incluya valores específicos, comparaciones con rangos normales y cualquier recomendación clínica.
                        </small>
                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: ARCHIVOS -->
            <div class="col-lg-5">
                <!-- GESTIÓN DE ARCHIVOS -->
                <div class="files-card">
                    <div class="files-card-header">
                        <h6>
                            <i class="fas fa-folder-open"></i>Documentos de la Orden
                        </h6>
                        <span class="files-count-badge">
                            {{ count($archivos_orden) }} archivo(s)
                        </span>
                    </div>
                    <div class="form-card-body">
                        <!-- ÁREA DE CARGA -->
                        <div class="upload-area">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="upload-text">Subir Nuevos Resultados</div>
                            <div class="upload-hint">PDF, PNG, JPG, JPEG (máx. 10MB por archivo)</div>
                            <input type="file" 
                                   name="archivos[]" 
                                   class="form-control mt-3" 
                                   multiple 
                                   accept=".pdf,.png,.jpg,.jpeg">
                        </div>

                        <!-- LISTA DE ARCHIVOS -->
                        <div>
                            <label class="form-label-custom mb-3">
                                <i class="fas fa-paperclip"></i>Archivos Adjuntos
                            </label>
                            
                            <div class="files-list">
                                @forelse($archivos_orden as $file)
                                    <div class="file-item">
                                        <div class="file-info">
                                            <div class="file-icon">
                                                <i class="fas fa-file-pdf"></i>
                                            </div>
                                            <div class="file-details">
                                                <div class="file-name">{{ $file->nombre_archivo }}</div>
                                                <div class="file-meta">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($file->created_at)->format('d/m/Y h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $file->ruta_archivo) }}" 
                                           target="_blank" 
                                           class="btn-view-file">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                @empty
                                    <div class="empty-files">
                                        <div class="empty-files-icon">
                                            <i class="fas fa-folder-open"></i>
                                        </div>
                                        <div class="empty-files-text">
                                            No hay archivos adjuntos en esta orden
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN -->
                <div class="actions-card">
                    <button type="submit" class="btn btn-action btn-primary-action">
                        <i class="fas fa-save mr-2"></i>Guardar Cambios
                    </button>

                    <a href="{{ route('medicina.consultas.show', $orden->consulta_id) }}" 
                       class="btn btn-action btn-success-action">
                        <i class="fas fa-stethoscope mr-2"></i>Ver Detalles de Consulta
                    </a>
                    <a href="{{ route('medicina.ordenes.show', $orden->id) }}" 
                       class="btn btn-action btn-info">
                        <i class="fas fa-microscope mr-2"></i>Ver Detalles de la Orden Médica
                    </a>

                    <a href="{{ route('medicina.ordenes.index') }}" 
                       class="btn btn-action btn-secondary-action">
                        <i class="fas fa-arrow-left mr-2"></i>Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection