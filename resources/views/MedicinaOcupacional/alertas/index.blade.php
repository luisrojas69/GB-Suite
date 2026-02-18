@extends('layouts.app')

@section('styles')
<style>
    /* ========================================
       VARIABLES Y UTILIDADES
    ======================================== */
    :root {
        --danger-gradient: linear-gradient(135deg, #e74a3b 0%, #dc2626 100%);
        --info-gradient: linear-gradient(135deg, #36b9cc 0%, #2c7a7b 100%);
        --success-gradient: linear-gradient(135deg, #1cc88a 0%, #059669 100%);
        --warning-gradient: linear-gradient(135deg, #f6c23e 0%, #f59e0b 100%);
    }

    .bg-light-danger { background-color: #fee2e2 !important; }
    .border-left-danger { border-left: 4px solid #dc2626 !important; }
    .bg-light-info { background-color: #dbeafe !important; }
    .border-left-info { border-left: 4px solid #3b82f6 !important; }
    .bg-warning-light { background-color: #fef3c7 !important; }

    /* ========================================
       HEADER Y BARRA DE PROGRESO
    ======================================== */
    .dashboard-header {
        background: linear-gradient(135deg, #1a592e 0%, #2d7a4a 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .dashboard-header h1 {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }

    .dashboard-stats {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
    }

    .stat-label {
        font-size: 12px;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .progress-enhanced {
        height: 30px;
        border-radius: 15px;
        background: #e5e7eb;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: visible;
        position: relative;
    }

    .progress-bar-enhanced {
        position: relative;
        transition: width 0.6s ease;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .progress-label {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 600;
        font-size: 13px;
    }

    /* ========================================
       CALENDARIO DE PROYECCIÓN
    ======================================== */
    .timeline-container {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    .timeline-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
    }

    .timeline-days {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .timeline-day {
        flex: 1;
        text-align: center;
        padding: 15px 10px;
        border-radius: 8px;
        background: #f9fafb;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .timeline-day:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .timeline-day.today {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #f59e0b;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .timeline-day-name {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .timeline-day.today .timeline-day-name {
        color: #92400e;
    }

    .timeline-date {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .timeline-count {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
    }

    .count-high {
        background: #fee2e2;
        color: #991b1b;
    }

    .count-normal {
        background: #dbeafe;
        color: #1e40af;
    }

    .count-low {
        background: #d1fae5;
        color: #065f46;
    }

    /* ========================================
       TARJETAS DE SECCIÓN
    ======================================== */
    .section-card {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
        border: none;
    }

    .section-card-header-danger {
        background: var(--danger-gradient);
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #b91c1c;
    }

    .section-card-header-info {
        background: var(--info-gradient);
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #0e7490;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .section-badge {
        background: white;
        color: #dc2626;
        padding: 6px 15px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .section-badge-info {
        color: #0891b2;
    }

    /* ========================================
       TABLA Y FILAS
    ======================================== */
    .patients-table {
        margin: 0;
    }

    .patients-table thead {
        background: #f9fafb;
    }

    .patients-table thead th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #6b7280;
        letter-spacing: 0.5px;
        padding: 12px 15px;
        border-bottom: 2px solid #e5e7eb;
    }

    .patients-table tbody tr {
        transition: all 0.2s ease;
    }

    .patients-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .patients-table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }

    /* Filas con mora */
    .row-overdue-danger {
        background-color: #fef2f2 !important;
        border-left: 5px solid #dc2626 !important;
    }

    .row-overdue-danger:hover {
        background-color: #fee2e2 !important;
    }

    .row-overdue-info {
        background-color: #eff6ff !important;
        border-left: 5px solid #3b82f6 !important;
    }

    .row-overdue-info:hover {
        background-color: #dbeafe !important;
    }

    /* ========================================
       INFORMACIÓN DEL PACIENTE
    ======================================== */
    .patient-name {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .patient-detail {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 3px;
    }

    .patient-detail i {
        width: 14px;
        text-align: center;
        margin-right: 4px;
    }

    /* Badges de estado */
    .badge-overdue {
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-left: 8px;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);
        animation: pulse-danger 2s infinite;
    }

    .badge-pending {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        margin-left: 8px;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }

    @keyframes pulse-danger {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* ========================================
       INFORMACIÓN DE DÍAS/FECHA
    ======================================== */
    .days-info {
        text-align: center;
    }

    .days-count {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        display: block;
    }

    .days-label {
        font-size: 10px;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .return-date {
        font-size: 11px;
        font-weight: 600;
        margin-top: 4px;
        display: block;
    }

    .date-danger {
        color: #dc2626;
    }

    .date-info {
        color: #3b82f6;
    }

    .date-normal {
        color: #6b7280;
    }

    /* ========================================
       BOTONES DE ACCIÓN
    ======================================== */
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-size: 14px;
        border: 1px solid transparent;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-action-primary {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        border-color: #dc2626;
    }

    .btn-action-primary:hover {
        background: linear-gradient(135deg, #b91c1c, #991b1b);
        color: white;
    }

    .btn-action-info {
        background: linear-gradient(135deg, #0891b2, #0e7490);
        color: white;
        border-color: #0891b2;
    }

    .btn-action-info:hover {
        background: linear-gradient(135deg, #0e7490, #155e75);
        color: white;
    }

    .btn-action-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-color: #10b981;
    }

    .btn-action-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
    }

    .btn-action-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border-color: #f59e0b;
    }

    .btn-action-warning:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        color: white;
    }

    .btn-action-light {
        background: white;
        border: 1px solid #d1d5db;
        color: #6b7280;
    }

    .btn-action-light:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .btn-action-whatsapp {
        color: #25d366 !important;
    }

    .btn-action-whatsapp:hover {
        background: #ecfdf5 !important;
    }

    .btn-action-email {
        color: #3b82f6 !important;
    }

    .btn-action-email:hover {
        background: #eff6ff !important;
    }

    /* ========================================
       EMPTY STATES
    ======================================== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        font-size: 64px;
        opacity: 0.2;
        margin-bottom: 20px;
    }

    .empty-state-icon-success {
        color: #10b981;
    }

    .empty-state-icon-info {
        color: #3b82f6;
    }

    .empty-state-text {
        font-size: 14px;
        color: #9ca3af;
        font-weight: 500;
    }

    /* ========================================
       SWAL PERSONALIZADO
    ======================================== */
    .swal2-html-container {
        font-size: 14px !important;
    }

    .alert-critical {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: 2px solid #dc2626;
        border-left: 5px solid #dc2626;
        border-radius: 6px;
        padding: 15px;
        margin: 15px 0;
        animation: shake 0.5s;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }

    .alert-critical i {
        color: #dc2626;
        font-size: 18px;
    }

    .custom-radio-option {
        display: block;
        padding: 12px 15px;
        margin: 8px 0;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .custom-radio-option:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .custom-radio-option input[type="radio"] {
        margin-right: 10px;
    }

    .option-success {
        border-color: #d1fae5;
        background: #f0fdf4;
    }

    .option-success:hover {
        border-color: #10b981;
        background: #d1fae5;
    }

    .option-danger {
        border-color: #fee2e2;
        background: #fef2f2;
    }

    .option-danger:hover {
        border-color: #dc2626;
        background: #fee2e2;
    }

    /* ========================================
       RESPONSIVE
    ======================================== */
    @media (max-width: 768px) {
        .timeline-days {
            overflow-x: auto;
            gap: 5px;
        }

        .timeline-day {
            min-width: 80px;
        }

        .action-buttons {
            justify-content: center;
        }

        .dashboard-stats {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- DASHBOARD HEADER -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1><i class="fas fa-heartbeat mr-2"></i>Centro de Retornos Médicos</h1>
                <p class="mb-0" style="opacity: 0.9; font-size: 13px;">Panel de Guardia - {{ date('l, d F Y', strtotime('today')) }}</p>
            </div>
            <div class="dashboard-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ $procesadosHoy }}</div>
                    <div class="stat-label">Procesados</div>
                </div>
                <div class="stat-item mx-4">
                    <div class="stat-value">{{ $totalHoy }}</div>
                    <div class="stat-label">Pendientes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ round($porcentajeAvance) }}%</div>
                    <div class="stat-label">Avance</div>
                </div>
            </div>
        </div>
    </div>

    <!-- BARRA DE PROGRESO MEJORADA -->
    <div class="progress progress-enhanced mb-4">
        <div class="progress-bar progress-bar-striped progress-bar-animated progress-bar-enhanced {{ $porcentajeAvance == 100 ? 'bg-success' : 'bg-primary' }}" 
             role="progressbar" 
             style="width: {{ $porcentajeAvance }}%"
             aria-valuenow="{{ $porcentajeAvance }}" 
             aria-valuemin="0" 
             aria-valuemax="100">
            <span class="progress-label">
                @if($porcentajeAvance == 100)
                    <i class="fas fa-check-circle mr-1"></i>Completado
                @else
                    {{ $procesadosHoy }} de {{ $totalHoy }} procesados
                @endif
            </span>
        </div>
    </div>

    <!-- PROYECCIÓN 7 DÍAS -->
    <div class="timeline-container">
        <div class="timeline-title">
            <i class="fas fa-calendar-week mr-2"></i>Proyección de Retornos (Próximos 7 Días)
        </div>
        <div class="timeline-days">
            @foreach($proximos7Dias as $item)
            <div class="timeline-day {{ $item['is_today'] ? 'today' : '' }}">
                <div class="timeline-day-name">{{ $item['dia'] }}</div>
                <div class="timeline-date">{{ $item['fecha'] }}</div>
                <span class="timeline-count {{ $item['cant'] > 10 ? 'count-high' : ($item['cant'] > 5 ? 'count-normal' : 'count-low') }}">
                    {{ $item['cant'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- SECCIONES DE RETORNOS -->
    <div class="row">
        <!-- SECCIÓN: FIN DE REPOSO -->
        <div class="col-lg-6">
            <div class="card section-card">
                <div class="section-card-header-danger">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-bed mr-2"></i>Fin de Reposo Médico
                    </h6>
                    <span class="section-badge">{{ count($retornoReposo) }}</span>
                </div>

                <div class="card-body p-0">
                    <table class="table patients-table mb-0">
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th class="text-center">Reposo</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($retornoReposo as $r)
                                @php
                                    $fechaCreacion = \Carbon\Carbon::parse($r->fecha_consulta);
                                    $dias = (int) ($r->dias_reposo ?? 0);
                                    $fechaFinReposo = $fechaCreacion->copy()->addDays($dias);
                                    $esMora = $fechaFinReposo->isPast() && !$fechaFinReposo->isToday();
                                    $diasVencidos = $esMora ? now()->diffInDays($fechaFinReposo) : 0;
                                @endphp

                                <tr class="{{ $esMora ? 'row-overdue-danger' : '' }}">
                                    <td>
                                        <div class="patient-name">
                                            <a href="{{ route('medicina.pacientes.show', $r->paciente->id) }} ">{{ $r->paciente->nombre_completo }}</a> 
                                            @if($esMora)
                                                <span class="badge badge-primary">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ round($diasVencidos) }} dias atrasado
                                                </span>
                                            @endif
                                        </div>
                                        <div class="patient-detail">
                                            <i class="fas fa-stethoscope"></i>{{ Str::limit($r->diagnostico_cie10, 45) }}
                                            <i class="fas fa-calendar"></i>{{ \Carbon\Carbon::parse($fechaCreacion)->format('d/m/Y') }}
                                        </div>
                                        <div class="patient-detail">
                                            <i class="fas fa-building"></i>{{ $r->paciente->des_depart ?? 'Sin departamento' }}
                                        </div>
                                    </td>
                                    <td class="days-info">
                                        <span class="days-label">Días de Reposo</span>
                                        <span class="days-count">{{ $dias }}</span>
                                        <span class="return-date {{ $esMora ? 'date-danger' : 'date-normal' }}">
                                            <i class="fas fa-calendar-day mr-1"></i>{{ $fechaFinReposo->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons justify-content-center">
                                            <a href="{{ route('medicina.consultas.create', 
                                                    [
                                                        'paciente_id' => $r->paciente_id, 
                                                        'motivo' => 'reincorporacion',
                                                    ])}}" 
                                               class="btn btn-action btn-action-primary" 
                                               data-toggle="tooltip" 
                                               title="Consulta Completa">
                                                <i class="fas fa-user-md"></i>
                                            </a>

                                            <button class="btn btn-action btn-action-light" 
                                                    data-toggle="tooltip" 
                                                    title="Adjuntar Alta Médica / Resultado Laboratorio. etc"
                                                    onclick="abrirModalArchivos({{ $r->paciente_id }})">
                                                <i class="fas fa-paperclip"></i>
                                            </button>

                                            <button class="btn btn-action btn-action-warning fast-track-btn" 
                                                    data-toggle="tooltip" 
                                                    title="Reincorporación Rápida"
                                                    data-paciente-id="{{ $r->paciente_id }}"
                                                    data-origen="Post-reposo"
                                                    data-nombre="{{ $r->paciente->nombre_completo }}"
                                                    data-reposo-id="{{ $r->id }}">
                                                <i class="fas fa-bolt"></i>
                                            </button>

                                            @if($esMora)
                                                @php
                                                    $nombrePaciente = $r->paciente->nombre_completo ?? 'Paciente';
                                                    $asunto = "Recordatorio: Evaluación Médica Post-Reposo Pendiente";

                                                    
                                                    // Usamos PHP_EOL para saltos de línea que mailto reconozca como %0D%0A
                                                    $cuerpoMensaje = "Estimado(a) $nombrePaciente," . "\r\n\r\n" .
                                                        "Reciba un cordial saludo. Notamos que su fecha de retorno de reposo fue el ".$fechaFinReposo->format('d/m/Y')." . Por medio de la presente, le recordamos que, conforme a los protocolos de salud ocupacional, es indispensable realizar su evaluación médica de reincorporación (Post-Reposo) tras su periodo de ausencia." . "\r\n\r\n" .
                                                        "Este paso es fundamental para validar su estado de salud actual y asegurar un retorno seguro a sus actividades laborales." . "\r\n\r\n" .
                                                        "Por favor, acuda al consultorio médico a la brevedad posible o póngase en contacto para agendar su cita." . "\r\n\r\n" .
                                                        "Atentamente," . "\r\n" .
                                                        "Servicio de Salud Ocupacional";

                                                    // Codificamos usando rawurlencode para evitar los signos "+"
                                                    $mailtoLink = "mailto:" . ($r->paciente->correo_e ?? '') . 
                                                                  "?subject=" . rawurlencode($asunto) . 
                                                                  "&body=" . rawurlencode($cuerpoMensaje);
                                                    
                                                    $waLink = "https://wa.me/".(preg_replace('/[^0-9]/', '', $r->paciente->telefono ?? '')).
                                                              "?text=".rawurlencode($cuerpoMensaje);              
                                                @endphp

                                                <a href="{{ $waLink }}" 
                                                   target="_blank" 
                                                   class="btn btn-action btn-action-light btn-action-whatsapp" 
                                                   data-toggle="tooltip" 
                                                   title="Contactar por WhatsApp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>

                                                

                                                <a href="{{ $mailtoLink }}" 
                                                   class="btn btn-action btn-action-light btn-action-email" 
                                                   data-toggle="tooltip" 
                                                   title="Enviar Correo">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state">
                                            <div class="empty-state-icon empty-state-icon-success">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <p class="empty-state-text">No hay reincorporaciones por reposo pendientes para hoy</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: RETORNO DE VACACIONES -->
        <div class="col-lg-6">
            <div class="card section-card">
                <div class="section-card-header-info">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-umbrella-beach mr-2"></i>Retorno de Vacaciones
                    </h6>
                    <span class="section-badge section-badge-info">{{ count($retornoVacaciones) }}</span>
                </div>

                <div class="card-body p-0">
                    <table class="table patients-table mb-0">
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th class="text-center">Retorno</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($retornoVacaciones as $v)
                                @php
                                    $fechaRetorno = \Carbon\Carbon::parse($v->fecha_retorno_vacaciones);
                                    $esMoraVacas = $fechaRetorno->isPast() && !$fechaRetorno->isToday();
                                    $diasVencidos = $esMoraVacas ? round(now()->diffInDays($fechaRetorno)) : 0;
                                @endphp

                                <tr class="{{ $esMoraVacas ? 'row-overdue-info' : '' }}">
                                    <td>
                                        <div class="patient-name">
                                            <a href="{{ route('medicina.pacientes.show', $v->id) }} ">{{ $v->nombre_completo }}</a> 
                                            @if($esMoraVacas)
                                                <span class="badge badge-primary">
                                                    <i class="fas fa-clock mr-1"></i>{{ round($diasVencidos) }} dias pendiente
                                                </span>
                                            @endif
                                        </div>
                                        <div class="patient-detail">
                                            <i class="fas fa-building"></i>{{ $v->des_depart }}
                                        </div>
                                        <div class="patient-detail">
                                            <i class="fas fa-id-badge"></i>{{ $v->des_cargo ?? 'Sin cargo' }}
                                        </div>
                                    </td>
                                    <td class="days-info">
                                        <span class="days-label">Fin de Vacaciones</span>
                                        <span class="return-date {{ $esMoraVacas ? 'date-info' : 'date-normal' }}">
                                            <i class="fas fa-calendar-check mr-1"></i>{{ $fechaRetorno->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons justify-content-center">
                                            <a href="{{ route('medicina.consultas.create', 
                                                        [
                                                        'paciente_id' => $v->id, 
                                                        'motivo' => 'Post-vacacional',
                                                    ])}}" 
                                               class="btn btn-action btn-action-info" 
                                               data-toggle="tooltip" 
                                               title="Consulta Completa">
                                                <i class="fas fa-user-md"></i>
                                            </a>

                                            <button class="btn btn-action btn-action-light"
                                                    id="clip-{{ $v->id }}" 
                                                    data-toggle="tooltip" 
                                                    title="Adjuntar Documentos"
                                                    onclick="abrirModalArchivos({{ $v->id }})">
                                                <i class="fas fa-paperclip"></i>
                                            </button>

                                            <button class="btn btn-action btn-action-success fast-track-btn" 
                                                    data-toggle="tooltip" 
                                                    title="Reincorporación Rápida"
                                                    data-paciente-id="{{ $v->id }}"
                                                    data-origen="Post-vacacional"
                                                    data-nombre="{{ $v->nombre_completo }}"
                                                    data-reposo-id="">
                                                <i class="fas fa-bolt"></i>
                                            </button>

                                            @if($esMoraVacas)
                                                @php
                                                    $nombrePaciente = $v->nombre_completo ?? 'Paciente';
                                                    $asunto = "Recordatorio: Evaluación Médica Post-Vacacional Pendiente";

                                                    
                                                    // Usamos PHP_EOL para saltos de línea que mailto reconozca como %0D%0A
                                                    $cuerpoMensaje = "Estimado(a) $nombrePaciente," . "\r\n\r\n" .
                                                        "Reciba un cordial saludo. Notamos que su fecha de retorno de vacaciones fue el ".$fechaFinReposo->format('d/m/Y')." . Por medio de la presente, le recordamos que, conforme a los protocolos de salud ocupacional, es indispensable realizar su evaluación médica de reincorporación (Post-Vacacional ) tras su periodo de ausencia." . "\r\n\r\n" .
                                                        "Este paso es fundamental para validar su estado de salud actual y asegurar un retorno seguro a sus actividades laborales." . "\r\n\r\n" .
                                                        "Por favor, acuda al consultorio médico a la brevedad posible o póngase en contacto para agendar su cita." . "\r\n\r\n" .
                                                        "Atentamente," . "\r\n" .
                                                        "Servicio de Salud Ocupacional";

                                                    // Codificamos usando rawurlencode para evitar los signos "+"
                                                    $mailtoLink = "mailto:" . ($v->correo_e ?? '') . 
                                                                  "?subject=" . rawurlencode($asunto) . 
                                                                  "&body=" . rawurlencode($cuerpoMensaje);
                                                    
                                                    $waLink = "https://wa.me/".(preg_replace('/[^0-9]/', '', $v->telefono ?? '')).
                                                              "?text=".rawurlencode($cuerpoMensaje);              
                                                @endphp

                                                <a href="{{ $waLink }}" 
                                                   target="_blank" 
                                                   class="btn btn-action btn-action-light btn-action-whatsapp" 
                                                   data-toggle="tooltip" 
                                                   title="Contactar por WhatsApp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>

                                            
                                                <a href="{{ $mailtoLink }}" 
                                                   class="btn btn-action btn-action-light btn-action-email" 
                                                   data-toggle="tooltip" 
                                                   title="Enviar Correo">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state">
                                            <div class="empty-state-icon empty-state-icon-info">
                                                <i class="fas fa-umbrella-beach"></i>
                                            </div>
                                            <p class="empty-state-text">No hay retornos de vacaciones pendientes para hoy</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para subir archivos --}}
<div class="modal fade" id="modalSubirArchivo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title"><i class="fas fa-paperclip mr-2 text-primary"></i>Adjuntar Documento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formSubirArchivo" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="paciente_id" id="modal_paciente_id">
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Descripción del Documento</label>
                        <input type="text" name="nombre_archivo" class="form-control" 
                               placeholder="Ej: Alta Médica, Resultado Laboratorio..." required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Archivo (PDF, JPG, PNG)</label>
                        <div class="custom-file">
                            <input type="file" name="archivo" class="custom-file-input" id="inputArchivo" 
                                   accept=".pdf,.jpg,.jpeg,.png" required>
                            <label class="custom-file-label" for="inputArchivo">Seleccionar...</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarArchivo">
                        <i class="fas fa-upload mr-1"></i> Subir y Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Manejador de Fast Track
    $('.fast-track-btn').on('click', function() {
        const $btn = $(this);
        const pacienteId = $btn.data('paciente-id');
        const origen = $btn.data('origen');
        const nombrePaciente = $btn.data('nombre');
        const reposoId = $btn.data('reposo-id') || null;

        iniciarFastTrack(pacienteId, origen, nombrePaciente, reposoId);
    });


    // 1. Mostrar nombre del archivo seleccionado en el label de Bootstrap
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // 2. Función para abrir el modal (se llama desde el botón clip de la tabla)
    window.abrirModalArchivos = function(pacienteId) {
        $('#formSubirArchivo')[0].reset(); // Limpiar formulario
        $('.custom-file-label').html('Seleccionar archivo...'); // Limpiar label
        $('#modal_paciente_id').val(pacienteId); // Setear ID
        $('#modalSubirArchivo').modal('show');
    };

    // 3. Envío del formulario vía AJAX
    $('#formSubirArchivo').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let btn = $('#btnGuardarArchivo');
        
        btn.html('<i class="fas fa-spinner fa-spin"></i> Subiendo...').prop('disabled', true);

        $.ajax({
            url: "{{ route('medicina.pacientes.subirArchivo') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#modalSubirArchivo').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Logrado!',
                    text: 'El documento se adjuntó al historial.',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Opcional: Cambiar color del clip en la fila actual para dar feedback
                $(`#clip-${formData.get('paciente_id')}`).removeClass('text-secondary').addClass('text-primary');
            },
            error: function(xhr) {
                let errorMsg = xhr.responseJSON?.message || 'Error al subir el archivo';
                Swal.fire('Error', errorMsg, 'error');
            },
            complete: function() {
                btn.html('<i class="fas fa-upload mr-1"></i> Subir y Guardar').prop('disabled', false);
            }
        });
    });
});

function iniciarFastTrack(pacienteId, origen, nombrePaciente, reposoId) {
    // Configurar contenido según origen
    let advertenciaHTML = '';
    let colorBoton = '#1cc88a';
    let tituloModal = '';
    let iconoModal = '';

    if (origen === 'reposo') {
        advertenciaHTML = `
            <div class="alert-critical">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                    <div class="text-left">
                        <div class="font-weight-bold mb-1">ATENCIÓN: Paciente en Recuperación Médica</div>
                        <div style="font-size: 12px;">
                            Este trabajador viene de un <strong>reposo médico</strong>. 
                            Use esta opción solo si la patología fue <strong>leve</strong> y está completamente <strong>resuelta</strong>.
                            En caso de duda, realice una consulta completa.
                        </div>
                    </div>
                </div>
            </div>
        `;
        colorBoton = '#f59e0b';
        tituloModal = 'Chequeo Post-Reposo Rápido';
        iconoModal = 'fa-bed';
    } else {
        tituloModal = 'Chequeo Post-Vacacional Rápido';
        iconoModal = 'fa-umbrella-beach';
        advertenciaHTML = `
            <div class="alert alert-info border-left-info">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Evaluación Post-Vacacional:</strong> Verificación rápida del estado general del trabajador.
            </div>
        `;
    }

    Swal.fire({
        title: `<i class="fas ${iconoModal} mr-2"></i>${tituloModal}`,
        html: `
            <div class="text-left">
                <div class="mb-3 p-3 bg-light rounded">
                    <div class="font-weight-bold text-dark mb-1">Trabajador:</div>
                    <div class="h5 mb-0 text-primary">${nombrePaciente}</div>
                </div>

                ${advertenciaHTML}

                <hr class="my-3">

                <div class="font-weight-bold mb-3" style="font-size: 15px;">
                    <i class="fas fa-clipboard-check mr-2 text-primary"></i>
                    ¿Cómo se siente actualmente el trabajador?
                </div>

                <label class="custom-radio-option option-success">
                    <input type="radio" name="estado_paciente" value="bien">
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    <strong>Se siente bien</strong>
                    <div class="small text-muted ml-4">Sin dolor, molestias ni limitaciones para trabajar</div>
                </label>

                <label class="custom-radio-option option-danger">
                    <input type="radio" name="estado_paciente" value="mal">
                    <i class="fas fa-times-circle text-danger mr-2"></i>
                    <strong>Refiere molestias</strong>
                    <div class="small text-muted ml-4">Dolor, limitación funcional o requiere atención médica</div>
                </label>
            </div>
        `,
        width: '600px',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-save mr-2"></i>Procesar Reincorporación',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
        confirmButtonColor: colorBoton,
        cancelButtonColor: '#6c757d',
        customClass: {
            confirmButton: 'btn btn-lg px-4',
            cancelButton: 'btn btn-lg px-4'
        },
        didOpen: () => {
            const confirmBtn = Swal.getConfirmButton();
            confirmBtn.disabled = true;

            // Manejador de radio buttons con jQuery
            $('input[name="estado_paciente"]').on('change', function() {
                const valorSeleccionado = $(this).val();
                
                if (valorSeleccionado === 'bien') {
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Confirmar Reincorporación';
                    Swal.resetValidationMessage();
                } else {
                    confirmBtn.disabled = true;
                    confirmBtn.innerHTML = '<i class="fas fa-ban mr-2"></i>Requiere Consulta Médica';
                    Swal.showValidationMessage(
                        '<i class="fas fa-exclamation-triangle mr-2"></i>Si el paciente tiene molestias, debe realizar una consulta médica completa.'
                    );
                }
            });
        },
        preConfirm: () => {
            const estado = $('input[name="estado_paciente"]:checked').val();
            if (!estado || estado === 'mal') {
                Swal.showValidationMessage('Debe seleccionar que el paciente se siente bien para procesar la reincorporación rápida');
                return false;
            }
            return true;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            procesarFastTrackBackend(pacienteId, origen, reposoId);
        }
    });
}

function procesarFastTrackBackend(pacienteId, origen, reposoId) {
    Swal.fire({
        title: 'Procesando Reincorporación',
        html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i><br>Guardando información...</div>',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    axios.post("{{ route('medicina.consultas.fast-track') }}", {
        paciente_id: pacienteId,
        origen: origen,
        reposo_id: reposoId,
        _token: '{{ csrf_token() }}'
    })
    .then(response => {
        if (response.data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Reincorporación Exitosa!',
                html: `
                    <div class="text-center">
                        <i class="fas fa-check-circle text-success" style="font-size: 64px; opacity: 0.2;"></i>
                        <p class="mt-3">El trabajador ha sido reincorporado correctamente al sistema.</p>
                    </div>
                `,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error al Procesar',
            text: error.response?.data?.message || 'No se pudo completar la operación. Por favor, intente nuevamente.',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#dc2626'
        });
    });
}
</script>
@endsection