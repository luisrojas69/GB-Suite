@extends('layouts.app')

@section('title-page', 'Detalle de Orden Médica#: '.$orden->id)

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

    .order-number-display {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .order-number-display i {
        margin-right: 12px;
    }

    .header-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 12px;
    }

    .status-badge-header {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .header-timestamp {
        font-size: 13px;
        opacity: 0.95;
    }

    .btn-header-action {
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

    .btn-header-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        color: #1a592e;
    }

    .btn-header-primary {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
    }

    .btn-header-primary:hover {
        color: white;
    }

    /* ========================================
       PERFIL DEL PACIENTE Y CONSULTA
    ======================================== */
    .patient-consultation-unified {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        overflow: hidden;
        border-left: 4px solid #4e73df;
    }

    .unified-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .patient-section {
        padding: 25px;
        border-right: 2px solid #f8f9fc;
    }

    .consultation-section {
        padding: 25px;
        background: #fafbfc;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 10px;
        color: #4e73df;
    }

    .patient-profile {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .patient-avatar-large {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 3px solid #e3e6f0;
        margin-right: 15px;
        position: relative;
    }

    .gender-badge-large {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        border: 2px solid #e3e6f0;
    }

    .patient-name-large {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .patient-ci-large {
        font-size: 12px;
        color: #858796;
        font-weight: 600;
    }

    .info-grid-compact {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .info-item-compact {
        display: flex;
        flex-direction: column;
    }

    .info-label-compact {
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 700;
        color: #858796;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .info-value-compact {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
    }

    .consultation-timeline {
        border-left: 3px solid #e3e6f0;
        padding-left: 20px;
        margin-left: 10px;
    }

    .timeline-item {
        margin-bottom: 18px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-label {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: #36b9cc;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .timeline-value {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
    }

    .btn-view-consultation {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #36b9cc;
        color: #36b9cc;
        background: white;
        transition: all 0.2s ease;
    }

    .btn-view-consultation:hover {
        background: #36b9cc;
        color: white;
    }

    .btn-view-patient {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #4e73df;
        color: #4e73df;
        background: white;
        transition: all 0.2s ease;
    }

    .btn-view-patient:hover {
        background: #4e73df;
        color: white;
    }

    /* ========================================
       DETALLE DE EXÁMENES Y RESULTADOS
    ======================================== */
    .exams-results-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .exams-results-header {
        background: white;
        border-bottom: 2px solid #f8f9fc;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .exams-results-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .exams-results-title i {
        color: #4e73df;
        margin-right: 12px;
        font-size: 18px;
    }

    .badge-analyzed {
        background: rgba(28, 200, 138, 0.15);
        color: #065f46;
        border: 1px solid rgba(28, 200, 138, 0.3);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .exams-results-body {
        padding: 25px;
    }

    .exams-requested-section {
        background: #fafbfc;
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 25px;
    }

    .exams-section-label {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #5a5c69;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .exams-pills-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .exam-pill-detail {
        background: white;
        border: 1px solid #e3e6f0;
        color: #4e73df;
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    }

    .exam-pill-detail i {
        margin-right: 6px;
        font-size: 11px;
        color: #858796;
    }

    .results-divider {
        margin: 25px 0;
        border: none;
        border-top: 2px dashed #e3e6f0;
    }

    .results-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 20px;
    }

    .interpretation-display {
        background: #f8f9fc;
        border-radius: 10px;
        padding: 20px;
    }

    .interpretation-label {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #5a5c69;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
    }

    .interpretation-card-normal {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 2px solid #1cc88a;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        align-items: center;
    }

    .interpretation-card-alterado {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border: 2px solid #e74a3b;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        align-items: center;
    }

    .interpretation-icon {
        font-size: 42px;
        margin-right: 15px;
    }

    .interpretation-text h5 {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 5px 0;
    }

    .interpretation-text small {
        font-size: 12px;
    }

    .interpretation-card-normal .interpretation-icon,
    .interpretation-card-normal h5 {
        color: #065f46;
    }

    .interpretation-card-alterado .interpretation-icon,
    .interpretation-card-alterado h5 {
        color: #991b1b;
    }

    .findings-display {
        background: white;
        border-left: 4px solid #4e73df;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .findings-label {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #5a5c69;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .findings-text {
        font-size: 14px;
        color: #2c3e50;
        line-height: 1.6;
        white-space: pre-line;
    }

    .pending-results-placeholder {
        text-align: center;
        padding: 60px 20px;
        background: #fafbfc;
        border: 2px dashed #d1d3e2;
        border-radius: 10px;
    }

    .pending-icon {
        font-size: 64px;
        color: #f6c23e;
        opacity: 0.5;
        margin-bottom: 20px;
    }

    .pending-title {
        font-size: 20px;
        font-weight: 700;
        color: #5a5c69;
        margin-bottom: 8px;
    }

    .pending-description {
        font-size: 14px;
        color: #858796;
    }

    /* ========================================
       CENTRO DE IMPRESIÓN
    ======================================== */
    .print-center-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .print-center-header {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
        padding: 18px 20px;
    }

    .print-center-title {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .print-center-title i {
        margin-right: 10px;
    }

    .print-center-body {
        padding: 20px;
        background: #fafbfc;
    }

    .print-action-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
        border-radius: 10px;
        border: 1px solid #e3e6f0;
        background: white;
        color: #5a5c69;
        font-weight: 600;
        transition: all 0.3s ease;
        text-align: left;
        margin-bottom: 12px;
        cursor: pointer;
    }

    .print-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        border-color: #4e73df;
        text-decoration: none;
        color: #4e73df;
    }

    .print-action-content {
        display: flex;
        align-items: center;
    }

    .print-icon-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-right: 14px;
    }

    .print-action-text h6 {
        font-size: 14px;
        font-weight: 700;
        margin: 0 0 3px 0;
        color: #2c3e50;
    }

    .print-action-text small {
        font-size: 11px;
        color: #858796;
    }

    .print-action-btn:hover .print-action-text h6 {
        color: #4e73df;
    }

    .print-chevron {
        color: #d1d3e2;
        transition: all 0.2s ease;
    }

    .print-action-btn:hover .print-chevron {
        color: #4e73df;
        transform: translateX(3px);
    }

    .print-action-featured {
        border-left: 4px solid;
    }

    /* Colores específicos por tipo */
    .print-bg-primary { background: rgba(78, 115, 223, 0.1); color: #4e73df; }
    .print-bg-success { background: rgba(28, 200, 138, 0.1); color: #1cc88a; }
    .print-bg-info { background: rgba(54, 185, 204, 0.1); color: #36b9cc; }
    .print-bg-warning { background: rgba(246, 194, 62, 0.1); color: #f6c23e; }
    .print-bg-danger { background: rgba(231, 74, 59, 0.1); color: #e74a3b; }

    /* ========================================
       ARCHIVOS ADJUNTOS
    ======================================== */
    .attachments-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .attachments-header {
        background: white;
        border-bottom: 2px solid #f8f9fc;
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .attachments-title {
        font-size: 15px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .attachments-title i {
        color: #4e73df;
        margin-right: 10px;
    }

    .attachments-count {
        background: #4e73df;
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
    }

    .attachment-item {
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f8f9fc;
        transition: all 0.2s ease;
    }

    .attachment-item:last-child {
        border-bottom: none;
    }

    .attachment-item:hover {
        background: #fafbfc;
    }

    .attachment-info {
        display: flex;
        align-items: center;
        flex: 1;
        overflow: hidden;
    }

    .attachment-icon {
        font-size: 32px;
        margin-right: 14px;
    }

    .attachment-icon-pdf {
        color: #e74a3b;
    }

    .attachment-icon-image {
        color: #36b9cc;
    }

    .attachment-details {
        flex: 1;
        overflow: hidden;
    }

    .attachment-name {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .attachment-date {
        font-size: 11px;
        color: #858796;
    }

    .btn-view-attachment {
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
    }

    .btn-view-attachment:hover {
        background: #4e73df;
        border-color: #4e73df;
        color: white;
    }

    /* ========================================
       RESPONSIVE
    ======================================== */
    @media (max-width: 768px) {
        .page-header-master {
            padding: 20px;
        }

        .order-number-display {
            font-size: 22px;
        }

        .header-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .unified-grid {
            grid-template-columns: 1fr;
        }

        .patient-section {
            border-right: none;
            border-bottom: 2px solid #f8f9fc;
        }

        .results-grid {
            grid-template-columns: 1fr;
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
                <div class="mb-2 mb-md-0">
                    <div class="order-number-display">
                        <i class="fas fa-file-medical-alt"></i>
                        Orden #{{ str_pad($orden->id, 5, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="header-meta">
                        @if($orden->status_orden == 'Pendiente')
                            <span class="status-badge-header">
                                <i class="fas fa-clock mr-2"></i>En Proceso
                            </span>
                        @else
                            <span class="status-badge-header">
                                <i class="fas fa-check-double mr-2"></i>Completada
                            </span>
                        @endif
                        <span class="header-timestamp">
                            <i class="far fa-calendar-alt mr-2"></i>
                            Emitida: {{ $orden->created_at->format('d/m/Y h:i A') }}
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('medicina.ordenes.index') }}" class="btn btn-header-action">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                    @if($orden->status_orden == 'Pendiente')
                        <a href="{{ route('medicina.ordenes.edit', $orden->id) }}" class="btn btn-header-action btn-header-primary">
                            <i class="fas fa-upload mr-2"></i>Cargar Resultados
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- COLUMNA IZQUIERDA: INFORMACIÓN CLÍNICA -->
        <div class="col-xl-8">
            <!-- PACIENTE Y CONSULTA UNIFICADOS -->
            <div class="patient-consultation-unified">
                <div class="unified-grid">
                    <!-- SECCIÓN DEL PACIENTE -->
                    <div class="patient-section">
                        <div class="section-header">
                            <h6 class="section-title">
                                <i class="fas fa-user-injured"></i>
                                Información del Trabajador
                            </h6>
                            <a href="{{ route('medicina.pacientes.show', $orden->paciente->id) }}"
                               title="Ver detalles del paciente"  
                               class="btn-view-patient">
                                <i class="fas fa-external-link-alt mr-1"></i>Ver
                            </a>
                        </div>

                        <div class="patient-profile">
                            <div class="position-relative">
                                @if($orden->paciente->sexo == 'F')
                                    <img src="/assets/img/avatar_female.png" alt="Avatar" class="patient-avatar-large">
                                    <span class="gender-badge-large" style="color: #e74a3b;">
                                        <i class="fas fa-venus"></i>
                                    </span>
                                @else
                                    <img src="/assets/img/avatar_male.png" alt="Avatar" class="patient-avatar-large">
                                    <span class="gender-badge-large" style="color: #4e73df;">
                                        <i class="fas fa-mars"></i>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <div class="patient-name-large">{{ $orden->paciente->nombre_completo }}</div>
                                <div class="patient-ci-large">CI: {{ number_format($orden->paciente->ci, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="info-grid-compact">
                            <div class="info-item-compact">
                                <span class="info-label-compact">Cargo Actual</span>
                                <span class="info-value-compact">{{ $orden->paciente->des_cargo ?? 'No definido' }}</span>
                            </div>
                            <div class="info-item-compact">
                                <span class="info-label-compact">Departamento</span>
                                <span class="info-value-compact">{{ $orden->paciente->des_depart ?? 'General' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN DE LA CONSULTA -->
                    <div class="consultation-section">
                        <div class="section-header">
                            <h6 class="section-title">
                                <i class="fas fa-stethoscope" style="color: #36b9cc;"></i>
                                Origen: Consulta #{{ $orden->consulta_id }}
                            </h6>
                            <a href="{{ route('medicina.consultas.show', $orden->consulta_id) }}"
                               title="Ver detalles de la Consulta"  
                               class="btn-view-consultation">
                                <i class="fas fa-external-link-alt mr-1"></i>Ver
                            </a>
                        </div>

                        <div class="consultation-timeline">
                            <div class="timeline-item">
                                <div class="timeline-label">Motivo de Atención</div>
                                <div class="timeline-value">{{ $orden->consulta->motivo_consulta }}</div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-label">Diagnóstico Principal</div>
                                <div class="timeline-value">{{ $orden->consulta->diagnostico_cie10 ?? 'En estudio...' }}</div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-label">Médico Tratante</div>
                                <div class="timeline-value">
                                    <i class="fas fa-user-md mr-2" style="color: #858796;"></i>
                                    Dr(a). {{ $orden->medico->name." ".$orden->medico->last_name ?? 'No registrado' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EXÁMENES Y RESULTADOS -->
            <div class="exams-results-card">
                <div class="exams-results-header">
                    <h6 class="exams-results-title">
                        <i class="fas fa-microscope"></i>
                        Detalle y Resultados de Laboratorio
                    </h6>
                    @if($orden->status_orden == 'Completada')
                        <span class="badge-analyzed">
                            <i class="fas fa-check mr-1"></i>Analizados
                        </span>
                    @endif
                </div>

                <div class="exams-results-body">
                    <!-- EXÁMENES SOLICITADOS -->
                    <div class="exams-requested-section">
                        <div class="exams-section-label">
                            <i class="fas fa-vials mr-2"></i>Exámenes Solicitados
                        </div>
                        <div class="exams-pills-container">
                            @foreach($orden->examenes as $examen)
                                <span class="exam-pill-detail">
                                    <i class="fas fa-check-circle"></i>
                                    {{ $examen }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    @if($orden->status_orden == 'Completada')
                        <hr class="results-divider">

                        <!-- RESULTADOS -->
                        <div class="results-grid">
                            <!-- INTERPRETACIÓN -->
                            <div class="interpretation-display">
                                <div class="interpretation-label">
                                    <i class="fas fa-chart-line mr-2"></i>Interpretación Clínica
                                </div>

                                @if($orden->interpretacion == 'Normal')
                                    <div class="interpretation-card-normal">
                                        <div class="interpretation-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="interpretation-text">
                                            <h5>NORMAL</h5>
                                            <small>Dentro de los parámetros</small>
                                        </div>
                                    </div>
                                @else
                                    <div class="interpretation-card-alterado">
                                        <div class="interpretation-icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="interpretation-text">
                                            <h5>ALTERADO</h5>
                                            <small>Requiere atención médica</small>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- HALLAZGOS -->
                            <div class="findings-display">
                                <div class="findings-label">
                                    <i class="fas fa-notes-medical mr-2"></i>Hallazgos y Observaciones del Médico
                                </div>
                                <div class="findings-text">
                                    {{ $orden->hallazgos ?? 'Sin observaciones adicionales registradas por el médico en esta orden.' }}
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- ESTADO PENDIENTE -->
                        <div class="pending-results-placeholder">
                            <div class="pending-icon">
                                <i class="fas fa-flask"></i>
                            </div>
                            <div class="pending-title">Esperando Resultados</div>
                            <div class="pending-description">
                                Aún no se han cargado los hallazgos para estos exámenes.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: DOCUMENTOS Y ARCHIVOS -->
        <div class="col-xl-4">
            <!-- CENTRO DE IMPRESIÓN -->
            <div class="print-center-card">
                <div class="print-center-header">
                    <h6 class="print-center-title">
                        <i class="fas fa-print"></i>
                        Centro de Impresión
                    </h6>
                </div>
                <div class="print-center-body">
                    <!-- 1. Orden para Laboratorio -->
                    <a href="{{ route('medicina.ordenes.pdf', $orden->id) }}" 
                       target="_blank" 
                       class="print-action-btn">
                        <div class="print-action-content">
                            <div class="print-icon-wrapper print-bg-primary">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="print-action-text">
                                <h6>Imprimir Orden</h6>
                                <small>Formato para el Laboratorio</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right print-chevron"></i>
                    </a>

                    <!-- 2. Resultados (si está completada) -->
                    @if($orden->status_orden == 'Completada')
                        <a href="{{ route('medicina.pdf.resultados', $orden->id) }}" 
                           target="_blank" 
                           class="print-action-btn">
                            <div class="print-action-content">
                                <div class="print-icon-wrapper print-bg-success">
                                    <i class="fas fa-file-medical-alt"></i>
                                </div>
                                <div class="print-action-text">
                                    <h6>Imprimir Resultados</h6>
                                    <small>Informe de hallazgos</small>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right print-chevron"></i>
                        </a>
                    @endif

                    <!-- 3. Récipe de la Consulta -->
                    <a href="{{ route('medicina.consultas.imprimir', $orden->consulta_id) }}" 
                       target="_blank" 
                       class="print-action-btn">
                        <div class="print-action-content">
                            <div class="print-icon-wrapper print-bg-info">
                                <i class="fas fa-prescription"></i>
                            </div>
                            <div class="print-action-text">
                                <h6>Imprimir Récipe</h6>
                                <small>Tratamiento de Consulta #{{ $orden->consulta_id }}</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right print-chevron"></i>
                    </a>

                    <!-- 4. Certificado de Aptitud (condicional) -->
                    @if($orden->status_orden == 'Completada' && in_array($orden->consulta->motivo_consulta, ['Pre-empleo', 'Post-vacacional', 'Evaluación Ocupacional']))
                        <a href="{{ route('medicina.pdf.aptitud', $orden->paciente->id) }}" 
                           target="_blank" 
                           class="print-action-btn print-action-featured" 
                           style="border-left-color: #1cc88a;">
                            <div class="print-action-content">
                                <div class="print-icon-wrapper print-bg-success">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <div class="print-action-text">
                                    <h6 style="color: #1cc88a;">Certificado de Aptitud</h6>
                                    <small>Generar aval médico</small>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right print-chevron"></i>
                        </a>
                    @endif

                    <!-- 5. Orden de Reposo (condicional) -->
                    @if(isset($orden->consulta->genera_reposo) && $orden->consulta->genera_reposo == 1)
                        <a href="{{ route('medicina.pdf.reposo', $orden->consulta->id) }}" 
                           target="_blank" 
                           class="print-action-btn print-action-featured" 
                           style="border-left-color: #e74a3b;">
                            <div class="print-action-content">
                                <div class="print-icon-wrapper print-bg-danger">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <div class="print-action-text">
                                    <h6 style="color: #e74a3b;">Orden de Reposo</h6>
                                    <small>{{ $orden->consulta->dias_reposo ?? 'X' }} días de incapacidad</small>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right print-chevron"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- ARCHIVOS ADJUNTOS -->
            @if(isset($archivos) && $archivos->count() > 0)
                <div class="attachments-card">
                    <div class="attachments-header">
                        <h6 class="attachments-title">
                            <i class="fas fa-paperclip"></i>
                            Soportes Escaneados
                        </h6>
                        <span class="attachments-count">{{ $archivos->count() }}</span>
                    </div>
                    <div>
                        @foreach($archivos as $archivo)
                            <div class="attachment-item">
                                <div class="attachment-info">
                                    @if(in_array(strtolower($archivo->tipo_archivo), ['pdf']))
                                        <i class="fas fa-file-pdf attachment-icon attachment-icon-pdf"></i>
                                    @else
                                        <i class="fas fa-file-image attachment-icon attachment-icon-image"></i>
                                    @endif
                                    <div class="attachment-details">
                                        <div class="attachment-name">{{ $archivo->nombre_archivo }}</div>
                                        <div class="attachment-date">
                                            {{ \Carbon\Carbon::parse($archivo->created_at)->format('d/m/Y h:i A') }}
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" 
                                   target="_blank" 
                                   class="btn-view-attachment">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection