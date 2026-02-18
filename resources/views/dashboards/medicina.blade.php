@extends('layouts.app')

@section('styles')
<style>
    /* ========================================
       VARIABLES GLOBALES
    ======================================== */
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --danger-gradient: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        --success-gradient: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        --info-gradient: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
        --warning-gradient: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
        --dark-gradient: linear-gradient(135deg, #5a5c69 0%, #3a3b45 100%);
    }

    body {
        background: #f8f9fc;
    }

    /* ========================================
       HEADER PRINCIPAL
    ======================================== */
    .dashboard-master-header {
        background: linear-gradient(135deg, #1a592e 0%, #2d7a4a 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 6px 20px rgba(26, 89, 46, 0.2);
        position: relative;
        overflow: hidden;
    }

    .dashboard-master-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .dashboard-master-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 5px 0;
        position: relative;
        z-index: 1;
    }

    .dashboard-master-header .subtitle {
        font-size: 14px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .dashboard-master-header .date-info {
        position: relative;
        z-index: 1;
        font-size: 13px;
        opacity: 0.95;
    }

    /* ========================================
       ALERTA DE RETORNOS
    ======================================== */
    .alert-retornos {
        background: linear-gradient(135deg, #f6c23e 0%, #f4a91e 100%);
        border: none;
        border-radius: 10px;
        padding: 20px 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(246, 194, 62, 0.3);
        animation: pulse-warning 3s infinite;
    }

    @keyframes pulse-warning {
        0%, 100% { box-shadow: 0 4px 15px rgba(246, 194, 62, 0.3); }
        50% { box-shadow: 0 6px 25px rgba(246, 194, 62, 0.5); }
    }

    .alert-retornos-icon {
        font-size: 42px;
        color: rgba(255, 255, 255, 0.9);
    }

    .alert-retornos-content h5 {
        font-size: 18px;
        font-weight: 700;
        color: white;
        margin: 0 0 5px 0;
    }

    .alert-retornos-content p {
        margin: 0;
        color: rgba(255, 255, 255, 0.95);
        font-size: 14px;
    }

    .alert-retornos-badge {
        background: white;
        color: #d97706;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 14px;
        margin-right: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-atender {
        background: white;
        color: #d97706;
        font-weight: 700;
        padding: 10px 25px;
        border-radius: 8px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .btn-atender:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        color: #b45309;
    }

    /* ========================================
       TARJETAS KPI
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
        padding: 25px;
        position: relative;
    }

    .kpi-icon-wrapper {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(0, 0, 0, 0.05);
    }

    .kpi-icon {
        font-size: 28px;
        opacity: 0.25;
    }

    .kpi-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        opacity: 0.7;
    }

    .kpi-value {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
        line-height: 1;
    }

    .kpi-trend {
        font-size: 12px;
        margin-top: 8px;
        font-weight: 600;
    }

    .trend-up {
        color: #1cc88a;
    }

    .trend-down {
        color: #e74a3b;
    }

    /* Colores por tipo */
    .kpi-card-primary {
        border-left: 5px solid #4e73df;
    }

    .kpi-card-primary .kpi-label,
    .kpi-card-primary .kpi-icon {
        color: #4e73df;
    }

    .kpi-card-danger {
        border-left: 5px solid #e74a3b;
    }

    .kpi-card-danger .kpi-label,
    .kpi-card-danger .kpi-icon {
        color: #e74a3b;
    }

    .kpi-card-success {
        border-left: 5px solid #1cc88a;
    }

    .kpi-card-success .kpi-label,
    .kpi-card-success .kpi-icon {
        color: #1cc88a;
    }

    .kpi-card-info {
        border-left: 5px solid #36b9cc;
    }

    .kpi-card-info .kpi-label,
    .kpi-card-info .kpi-icon {
        color: #36b9cc;
    }

    /* ========================================
       SECCIÓN DE GRÁFICOS
    ======================================== */
    .chart-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    .chart-card-header {
        background: white;
        border-bottom: 2px solid #f8f9fc;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chart-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .chart-card-title i {
        margin-right: 8px;
        color: #4e73df;
    }

    .chart-card-body {
        padding: 25px;
    }

    .chart-area {
        position: relative;
        height: 300px;
    }

    /* ========================================
       TOP DIAGNÓSTICOS
    ======================================== */
    .diagnostico-item {
        margin-bottom: 20px;
    }

    .diagnostico-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .diagnostico-name {
        font-size: 13px;
        font-weight: 600;
        color: #2c3e50;
    }

    .diagnostico-percentage {
        font-size: 14px;
        font-weight: 700;
        color: #5a5c69;
    }

    .diagnostico-progress {
        height: 12px;
        border-radius: 10px;
        background: #f8f9fc;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .diagnostico-progress-bar {
        height: 100%;
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    /* ========================================
       TABLA DE PACIENTES
    ======================================== */
    .patients-list-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .patients-list-header {
        background: white;
        border-bottom: 2px solid #f8f9fc;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .patients-list-body {
        padding: 15px 25px;
    }

    .patient-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f8f9fc;
    }

    .patient-item:last-child {
        border-bottom: none;
    }

    .patient-info {
        flex: 1;
    }

    .patient-name {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .patient-meta {
        font-size: 11px;
        color: #858796;
    }

    .patient-count {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 13px;
        min-width: 45px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);
    }

    .patient-bar-wrapper {
        flex: 0 0 40%;
        margin-left: 15px;
    }

    .patient-progress {
        height: 8px;
        border-radius: 10px;
        background: #e3e6f0;
        overflow: hidden;
    }

    .patient-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #4e73df, #224abe);
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    /* ========================================
       MAPA DE RIESGO
    ======================================== */
    .risk-map-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .risk-location-item {
        padding: 15px 0;
        border-bottom: 1px solid #f8f9fc;
    }

    .risk-location-item:last-child {
        border-bottom: none;
    }

    .risk-location-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .risk-location-name {
        font-size: 13px;
        font-weight: 600;
        color: #2c3e50;
    }

    .risk-location-name i {
        color: #e74a3b;
        margin-right: 6px;
    }

    .risk-location-count {
        background: linear-gradient(135deg, #e74a3b, #be2617);
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 12px;
        box-shadow: 0 2px 6px rgba(231, 74, 59, 0.3);
    }

    .risk-progress {
        height: 10px;
        border-radius: 10px;
        background: #fee;
        overflow: hidden;
    }

    .risk-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #e74a3b, #be2617);
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    /* ========================================
       EMPTY STATES
    ======================================== */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state-icon {
        font-size: 56px;
        opacity: 0.15;
        margin-bottom: 15px;
    }

    .empty-state-text {
        font-size: 13px;
        color: #9ca3af;
        font-weight: 500;
    }

    /* ========================================
       BOTONES PERSONALIZADOS
    ======================================== */
    .btn-export {
        background: white;
        border: 2px solid #4e73df;
        color: #4e73df;
        font-weight: 600;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .btn-export:hover {
        background: #4e73df;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }

    .btn-export i {
        margin-right: 6px;
    }

    /* ========================================
       RESPONSIVE
    ======================================== */
    @media (max-width: 768px) {
        .dashboard-master-header h1 {
            font-size: 22px;
        }

        .kpi-value {
            font-size: 26px;
        }

        .kpi-icon-wrapper {
            width: 50px;
            height: 50px;
        }

        .kpi-icon {
            font-size: 22px;
        }

        .chart-area {
            height: 250px;
        }

        .patient-bar-wrapper {
            flex: 0 0 30%;
        }
    }

    /* ========================================
       ANIMACIONES
    ======================================== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .kpi-card {
        animation: fadeInUp 0.5s ease-out;
    }

    .kpi-card:nth-child(1) { animation-delay: 0.1s; }
    .kpi-card:nth-child(2) { animation-delay: 0.2s; }
    .kpi-card:nth-child(3) { animation-delay: 0.3s; }
    .kpi-card:nth-child(4) { animation-delay: 0.4s; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- HEADER PRINCIPAL -->
    <div class="dashboard-master-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1><i class="fas fa-heartbeat mr-3"></i>Panel de Salud y Seguridad Laboral</h1>
                <p class="subtitle mb-0">Gestión Integral del Servicio Médico Ocupacional</p>
            </div>
            <div class="text-right date-info">
                <div class="mb-1"><i class="far fa-calendar-alt mr-2"></i>{{ date('l, d F Y') }}</div>
                <div><i class="far fa-clock mr-2"></i>{{ date('h:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- ALERTA DE RETORNOS PENDIENTES -->
    @if(($alertas_reposo + $alertas_vacas) > 0)
    <div class="alert-retornos">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="alert-retornos-icon">
                    <i class="fas fa-bell"></i>
                </div>
            </div>
            <div class="col alert-retornos-content">
                <h5><i class="fas fa-exclamation-triangle mr-2"></i>Atención de Retornos Pendiente</h5>
                <p>
                    <span class="alert-retornos-badge">{{ $alertas_reposo }} Reposos Vencidos</span>
                    <span class="alert-retornos-badge">{{ $alertas_vacas }} Retornos de Vacaciones</span>
                </p>
            </div>
            <div class="col-auto">
                <a href="{{ route('medicina.alertas.index') }}" class="btn btn-atender">
                    <i class="fas fa-user-md mr-2"></i>Atender Ahora
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- KPIs PRINCIPALES -->
    <div class="row mb-4">
        <!-- Consultas del Mes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card kpi-card-primary">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-notes-medical kpi-icon"></i>
                    </div>
                    <div class="kpi-label">Consultas del Mes</div>
                    <div class="kpi-value">{{ number_format($consultas_mes) }}</div>
                    <div class="kpi-trend trend-up">
                        <i class="fas fa-arrow-up mr-1"></i>+12% vs mes anterior
                    </div>
                </div>
            </div>
        </div>

        <!-- Accidentes Reportados -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card kpi-card-danger">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-exclamation-triangle kpi-icon"></i>
                    </div>
                    <div class="kpi-label">Accidentes Reportados</div>
                    <div class="kpi-value">{{ number_format($accidentes_mes) }}</div>
                    @if($accidentes_mes > 0)
                        <div class="kpi-trend trend-down">
                            <i class="fas fa-arrow-down mr-1"></i>Requiere atención
                        </div>
                    @else
                        <div class="kpi-trend trend-up">
                            <i class="fas fa-check-circle mr-1"></i>Sin incidentes
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dotaciones Realizadas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card kpi-card-success">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-shield-alt kpi-icon"></i>
                    </div>
                    <div class="kpi-label">Dotaciones de EPP</div>
                    <div class="kpi-value">{{ number_format($dotaciones_mes) }}</div>
                    <div class="kpi-trend trend-up">
                        <i class="fas fa-check-circle mr-1"></i>En cumplimiento
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal en Sistema -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card kpi-card-info">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-users kpi-icon"></i>
                    </div>
                    <div class="kpi-label">Personal Activo</div>
                    <div class="kpi-value">{{ number_format($total_personal) }}</div>
                    <div class="kpi-trend" style="color: #36b9cc;">
                        <i class="fas fa-user-check mr-1"></i>Trabajadores registrados
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE GRÁFICOS Y ANÁLISIS -->
    <div class="row">
        <!-- Tendencia de Morbilidad -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card chart-card">
                <div class="chart-card-header">
                    <h6 class="chart-card-title">
                        <i class="fas fa-chart-area"></i>Tendencia de Morbilidad - Últimos 6 Meses
                    </h6>
                    <a href="{{ route('medicina.reportes.morbilidad') }}" target="_blank" class="btn btn-export">
                        <i class="fas fa-file-pdf"></i>Exportar PDF
                    </a>
                </div>
                <div class="chart-card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 5 Diagnósticos -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card chart-card">
                <div class="chart-card-header">
                    <h6 class="chart-card-title">
                        <i class="fas fa-virus"></i>Prevalencia de Diagnósticos
                    </h6>
                </div>
                <div class="chart-card-body">
                    @php 
                        $colores = [
                            ['bg' => '#e74a3b', 'name' => 'Crítico'],
                            ['bg' => '#f6c23e', 'name' => 'Alto'],
                            ['bg' => '#4e73df', 'name' => 'Medio'],
                            ['bg' => '#36b9cc', 'name' => 'Bajo'],
                            ['bg' => '#1cc88a', 'name' => 'Leve']
                        ];
                        $totalDiagnosticos = $topDiagnosticos->sum('total') ?: 1;
                    @endphp

                    @forelse($topDiagnosticos as $index => $diag)
                        @php 
                            $porcentaje = ($diag->total / $totalDiagnosticos) * 100;
                            $color = $colores[$index] ?? ['bg' => '#858796', 'name' => 'Normal'];
                        @endphp
                        <div class="diagnostico-item">
                            <div class="diagnostico-header">
                                <span class="diagnostico-name">
                                    <i class="fas fa-circle mr-2" style="color: {{ $color['bg'] }}; font-size: 8px;"></i>
                                    {{ Str::limit($diag->diagnostico_cie10, 35) }}
                                </span>
                                <span class="diagnostico-percentage">{{ number_format($porcentaje, 1) }}%</span>
                            </div>
                            <div class="diagnostico-progress">
                                <div class="diagnostico-progress-bar" 
                                     style="width: {{ $porcentaje }}%; background: linear-gradient(90deg, {{ $color['bg'] }}, {{ $color['bg'] }}dd);">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <p class="empty-state-text">No hay datos de diagnósticos disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE PACIENTES Y RIESGOS -->
    <div class="row">
        <!-- Pacientes Frecuentes -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card patients-list-card">
                <div class="patients-list-header">
                    <h6 class="chart-card-title">
                        <i class="fas fa-user-clock"></i>Pacientes con Mayor Frecuencia
                    </h6>
                </div>
                <div class="patients-list-body">
                    @php 
                        $maxConsultas = $topPacientes->first()->total ?? 1;
                    @endphp

                    @forelse($topPacientes as $tp)
                        <div class="patient-item">
                            <div class="patient-info">
                                <div class="patient-name">{{ $tp->paciente->nombre_completo }}</div>
                                <div class="patient-meta">
                                    <i class="fas fa-building mr-1"></i>{{ $tp->paciente->des_depart ?? 'Sin departamento' }}
                                </div>
                            </div>
                            <div class="patient-count">{{ $tp->total }}</div>
                            <div class="patient-bar-wrapper">
                                <div class="patient-progress">
                                    <div class="patient-progress-bar" 
                                         style="width: {{ ($tp->total / $maxConsultas) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <p class="empty-state-text">No hay datos de pacientes disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Mapa de Riesgo -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card risk-map-card">
                <div class="patients-list-header">
                    <h6 class="chart-card-title">
                        <i class="fas fa-map-marked-alt"></i>Mapa de Riesgo por Ubicación
                    </h6>
                </div>
                <div class="patients-list-body">
                    @php 
                        $totalAccidentes = $topLugares->sum('total') ?: 1;
                    @endphp

                    @forelse($topLugares as $lugar)
                        <div class="risk-location-item">
                            <div class="risk-location-header">
                                <span class="risk-location-name">
                                    <i class="fas fa-map-marker-alt"></i>{{ $lugar->lugar_exacto }}
                                </span>
                                <span class="risk-location-count">{{ $lugar->total }} Incidentes</span>
                            </div>
                            <div class="risk-progress">
                                <div class="risk-progress-bar" 
                                     style="width: {{ ($lugar->total / $totalAccidentes) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon empty-state-icon-success" style="color: #1cc88a;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="empty-state-text">Sin accidentes registrados este mes</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Configuración del gráfico de área
    const ctx = document.getElementById('myAreaChart');
    
    if (ctx) {
        const labels = @json($labelsMeses);
        const dataValues = @json($dataValores);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Consultas Médicas",
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
                    pointHoverBorderColor: "#ffffff",
                    pointHoverBorderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#ffffff',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyColor: '#ffffff',
                        bodyFont: {
                            size: 13
                        },
                        borderColor: '#4e73df',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Consultas: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '600'
                            },
                            color: '#858796'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f8f9fc',
                            borderDash: [5, 5]
                        },
                        ticks: {
                            stepSize: 5,
                            font: {
                                size: 11,
                                weight: '600'
                            },
                            color: '#858796',
                            callback: function(value) {
                                return value + ' consultas';
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    // Animación de los números en las tarjetas KPI
    $('.kpi-value').each(function() {
        const $this = $(this);
        const countTo = parseInt($this.text().replace(/,/g, ''));
        
        $({ countNum: 0 }).animate({
            countNum: countTo
        }, {
            duration: 1500,
            easing: 'swing',
            step: function() {
                $this.text(Math.floor(this.countNum).toLocaleString());
            },
            complete: function() {
                $this.text(this.countNum.toLocaleString());
            }
        });
    });
});
</script>
@endsection