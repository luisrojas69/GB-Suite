@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<style>
    .kpi-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .kpi-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.25) !important;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }
    .progress-thin {
        height: 8px;
        border-radius: 10px;
    }
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .alert-urgent {
        animation: urgentBlink 1.5s infinite;
    }
    @keyframes urgentBlink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Mensajes de sesión mejorados --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg border-left-success" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x mr-3"></i>
                <div>
                    <strong>¡Éxito!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-lg border-left-danger" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                <div>
                    <strong>¡Error!</strong> {{ session('error') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    {{-- Header Principal --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body bg-gradient-primary text-white py-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="stat-icon bg-white text-primary">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                </div>
                <div class="col">
                    <h1 class="h2 mb-1 font-weight-bold text-white">
                        <i class="fas fa-notes-medical"></i> Control de Consultas Médicas
                    </h1>
                    <p class="mb-0 text-white-75">
                        <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }} | 
                        <i class="fas fa-hospital"></i> Servicio Médico Ocupacional
                    </p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('medicina.pacientes.index') }}" class="btn btn-light btn-lg shadow">
                        <i class="fas fa-user-plus"></i> Nueva Consulta
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerta de Retornos Pendientes --}}
    @if(($alertas_reposo + $alertas_vacas) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg alert-urgent">
                    <div class="card-body bg-gradient-warning text-white py-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="stat-icon bg-white text-warning pulse-animation">
                                    <i class="fas fa-bell"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h4 class="font-weight-bold mb-1 text-white">
                                    <i class="fas fa-exclamation-circle"></i> ¡Atención Requerida!
                                </h4>
                                <p class="mb-0 h6 text-white">
                                    Hay <strong>{{ $alertas_reposo }} reposos vencidos</strong> y 
                                    <strong>{{ $alertas_vacas }} retornos de vacaciones</strong> pendientes de evaluación
                                </p>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('medicina.alertas.index') }}" 
                                   class="btn btn-light btn-lg font-weight-bold shadow">
                                    <i class="fas fa-clipboard-check"></i> Atender Ahora
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Alerta de Accidentes sin Consultas --}}
    @if($accidentes_sin_consulta > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg alert-urgent">
                    <div class="card-body bg-gradient-danger text-white py-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="stat-icon bg-white text-danger pulse-animation">
                                    <i class="fas fa-ambulance"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h4 class="font-weight-bold mb-1 text-white">
                                    <i class="fas fa-user-injured"></i> ¡Atención Requerida!
                                </h4>
                                <p class="mb-0 h6 text-white">
                                    Hay <strong>{{ $accidentes_sin_consulta }} ACCIDENTES LABORALES </strong> registrados en el Sistema que no tienen una consulta oficial asociada 
                                </p>
                                <p class="mb-0 h6 text-white">
                                    <strong>Comuniquese con el Departamento de SSL para que le indique los detalles del paciente</strong> 
                                    O vaya directamente a la lista de accidentes a chequear 
                                </p>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('medicina.accidentes.index') }}" 
                                   class="btn btn-light btn-lg font-weight-bold shadow">
                                    <i class="fas fa-user-injured"></i> Lista de Accidentes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

        
    {{-- Alerta de consultas de Accidente NO REPORTADOS --}}
    @if($accidentes_no_reportados > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg alert-urgent">
                    <div class="card-body bg-gradient-danger text-white py-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="stat-icon bg-white text-danger pulse-animation">
                                    <i class="fas fa-ambulance"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h4 class="font-weight-bold mb-1 text-white">
                                    <i class="fas fa-user-injured"></i> ¡Atención Requerida!
                                </h4>
                                <p class="mb-0 h6 text-white">
                                    Hay <strong>{{ $accidentes_no_reportados }}</strong>  consultas registradas como 
                                    <strong>ACCIDENTE LABORAL </strong> que no tienen un reporte oficial de accidente vinculado.
                                </p>
                                <p class="mb-0 h6 text-white">
                                    <strong>Comuniquese con el Departamento de SSL para que haga la Investigacion y El Registro Oficial de Ley</strong> 
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    {{-- KPIs Cards --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg kpi-card h-100">
                <div class="card-body bg-gradient-primary text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-2">
                                <i class="fas fa-calendar-alt"></i> Consultas del Mes
                            </div>
                            <div class="h2 mb-0 font-weight-bold">{{ $consultas_mes }}</div>
                            <small class="text-white-75">
                                <i class="fas fa-chart-line"></i> Período actual
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon bg-white-20">
                                <i class="fas fa-notes-medical fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-2">
                    <small class="text-primary">
                        <i class="fas fa-info-circle"></i> Total de atenciones médicas
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg kpi-card h-100">
                <div class="card-body bg-gradient-warning text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-2">
                                <i class="fas fa-user-clock"></i> Retornos de Reposo
                            </div>
                            <div class="h2 mb-0 font-weight-bold">{{ $alertas_reposo }}</div>
                            <small class="text-white-75">
                                <i class="fas fa-calendar-day"></i> Vencen hoy
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon bg-white-20">
                                <i class="fas fa-bed fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-2">
                    <a href="{{ route('medicina.alertas.index') }}" class="small text-warning">
                        <i class="fas fa-arrow-right"></i> Ver pendientes de evaluación
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg kpi-card h-100">
                <div class="card-body bg-gradient-info text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-2">
                                <i class="fas fa-virus"></i> Diagnóstico Principal
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-truncate" title="{{ $topDiagnosticos->first()->diagnostico_cie10 ?? 'N/A' }}">
                                {{ Str::limit($topDiagnosticos->first()->diagnostico_cie10 ?? 'N/A', 30) }}
                            </div>
                            <small class="text-white-75">
                                <i class="fas fa-chart-pie"></i> Mayor prevalencia
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon bg-white-20">
                                <i class="fas fa-diagnoses fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-2">
                    <small class="text-info">
                        <i class="fas fa-info-circle"></i> Más frecuente del mes
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg kpi-card h-100">
                <div class="card-body bg-gradient-success text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-2">
                                <i class="fas fa-users"></i> Personal en Sistema
                            </div>
                            <div class="h2 mb-0 font-weight-bold">{{ $total_personal }}</div>
                            <small class="text-white-75">
                                <i class="fas fa-database"></i> Trabajadores registrados
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon bg-white-20">
                                <i class="fas fa-user-friends fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-2">
                    <a href="{{ route('medicina.pacientes.index') }}" class="small text-success">
                        <i class="fas fa-list"></i> Ver listado completo
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección de Análisis --}}
    <div class="row">
        {{-- Prevalencia de Diagnósticos --}}
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-gradient-danger text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar"></i> Prevalencia de Diagnósticos (Top 5)
                    </h6>
                </div>
                <div class="card-body">
                    @php 
                        $colores = [
                            ['bg' => 'bg-danger', 'icon' => 'fas fa-trophy', 'text' => 'text-danger'],
                            ['bg' => 'bg-warning', 'icon' => 'fas fa-medal', 'text' => 'text-warning'],
                            ['bg' => 'bg-primary', 'icon' => 'fas fa-award', 'text' => 'text-primary'],
                            ['bg' => 'bg-info', 'icon' => 'fas fa-star', 'text' => 'text-info'],
                            ['bg' => 'bg-success', 'icon' => 'fas fa-check', 'text' => 'text-success']
                        ]; 
                    @endphp
                    
                    @forelse($topDiagnosticos as $index => $diag)
                        @php 
                            $porcentaje = ($diag->total / $topDiagnosticos->sum('total')) * 100; 
                            $color = $colores[$index] ?? ['bg' => 'bg-secondary', 'icon' => 'fas fa-circle', 'text' => 'text-secondary'];
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="{{ $color['icon'] }} {{ $color['text'] }} mr-2"></i>
                                    <strong class="small">{{ Str::limit($diag->diagnostico_cie10, 35) }}</strong>
                                </div>
                                <span class="badge badge-pill {{ str_replace('bg-', 'badge-', $color['bg']) }}">
                                    {{ number_format($porcentaje, 1) }}%
                                </span>
                            </div>
                            <div class="progress progress-thin">
                                <div class="progress-bar {{ $color['bg'] }}" 
                                     role="progressbar" 
                                     style="width: {{ $porcentaje }}%"
                                     data-toggle="tooltip"
                                     title="{{ $diag->total }} casos">
                                </div>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-users"></i> {{ $diag->total }} casos registrados
                            </small>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay datos disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Pacientes Frecuentes --}}
        <div class="col-xl-5 col-lg-7 mb-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-gradient-warning text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-user-clock"></i> Pacientes con Mayor Frecuencia
                        </h6>
                        <span class="badge badge-light text-warning">Mes Actual</span>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($topPacientes as $index => $tp)
                    @php 
                        $max_consultas = $topPacientes->first()->total ?? 1;
                        $porcentaje = ($tp->total / $max_consultas) * 100;
                        $posicion = $index + 1;
                    @endphp
                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-center mb-2">
                            <div class="mr-3">
                                <span class="badge badge-warning badge-lg" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                    #{{ $posicion }}
                                </span>
                            </div>
                            <div class="flex-fill">
                                <a href="{{ route('medicina.pacientes.show', $tp->paciente_id) }}" class="font-weight-bold text-dark">
                                    {{ $tp->paciente->nombre_completo }}
                                </a>
                                <div class="small text-muted">
                                    <i class="fas fa-id-card"></i> CI: {{ $tp->paciente->ci }}
                                </div>
                            </div>
                            <div class="text-right">
                                <h5 class="mb-0 text-warning">{{ $tp->total }}</h5>
                                <small class="text-muted">consultas</small>
                            </div>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-warning" 
                                 role="progressbar" 
                                 style="width: {{ $porcentaje }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-user-clock fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay datos de consultas este mes</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Botón de Reporte --}}
        <div class="col-lg-3">
            <div class="row">
                <div class="col-12 mb-4">
                    <a href="{{ route('medicina.reportes.morbilidad') }}" class="text-decoration-none" target="_blank">
                        <div class="card border-0 shadow-lg h-100">
                            <div class="card-body bg-gradient-danger text-white text-center py-5">
                                <i class="fas fa-file-pdf fa-4x mb-3"></i>
                                <h5 class="font-weight-bold mb-2">Reporte de Morbilidad</h5>
                                <p class="mb-3">Exportar datos del mes actual</p>
                                <div class="btn btn-light btn-lg shadow">
                                    <i class="fas fa-file-pdf"></i> Generar PDF
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-4">
                    <a href="javascript:void(0)" class="text-decoration-none" onclick="exportarConsultasExcel()">
                        <div class="card border-0 shadow-lg h-100">
                            <div class="card-body bg-gradient-success text-white text-center py-5">
                                <i class="fas fa-file-excel fa-4x mb-3"></i>
                                <h5 class="font-weight-bold mb-2">Exportar Consultas</h5>
                                <p class="mb-3">Segun fecha y motivo seleccionado</p>
                                <div class="btn btn-light btn-lg shadow">
                                    <i class="fas fa-download"></i> Descargar Excel
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráfico de Tendencia --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-chart-line"></i> Tendencia de Morbilidad (Últimos 6 Meses)
                        </h6>
                        <a href="{{ route('medicina.reportes.morbilidad') }}" target="_blank" class="btn btn-light btn-sm shadow">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Consultas --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-gradient-secondary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-list"></i> Registro de Consultas Médicas
                </h6>
                <button class="btn btn-success btn-sm shadow" onclick="exportarConsultasExcel()">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tblConsultas" width="100%">
                    <thead class="bg-light">
                        <tr>
                            <th><i class="fas fa-calendar"></i> Fecha</th>
                            <th><i class="fas fa-user"></i> Paciente</th>
                            <th><i class="fas fa-id-card"></i> Cédula</th>
                            <th><i class="fas fa-comment-medical"></i> Motivo</th>
                            <th><i class="fas fa-diagnoses"></i> Diagnóstico</th>
                            <th><i class="fas fa-file-medical"></i> Det-Consulta</th>
                            <th class="text-center"><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consultas as $con)
                        @php
                            $esEditable = $con->fecha_consulta->gt(now()->subDays(3));
                            $tieneReposo = $con->genera_reposo && $con->dias_reposo > 0;
                        @endphp
                        <tr>
                            <td>
                                <div class="small font-weight-bold text-primary">
                                    {{ $con->fecha_consulta->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">{{ $con->fecha_consulta->format('h:i A') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('medicina.pacientes.show', $con->paciente_id) }}" 
                                   class="font-weight-bold text-dark">
                                    {{ $con->paciente->nombre_completo }}
                                </a>
                                <div class="small text-muted">
                                    <i class="fas fa-hashtag"></i> {{ $con->paciente->cod_emp }}
                                </div>

                                    @if($con->motivo_consulta === 'Accidente Laboral' && $con->tiene_accidente_vinculado == false)
                                        <div class="small text-muted">
                                            <i class="fas fa-exclamation-triangle text-warning" title="Sin consulta asociada"></i>
                                            Accidente NO reportado -
                                            <a href="{{ route('medicina.accidentes.create', ['paciente_id' => $con->paciente_id, 'consulta_id' => $con->id]) }}" 
                                               class="font-weight-bold text-danger">
                                                Registrar
                                            </a>
                                        </div>
                                    @elseif($con->motivo_consulta === 'Accidente Laboral' && $con->tiene_accidente_vinculado == true)
                                        <i class="fas fa-user-injured text-success" title="Accidente registrado"></i>
                                        <a href="{{ route('medicina.accidentes.show', $con->accidente->id) }}" 
                                           class="font-weight-bold text-dark">
                                            Accidente #: #{{ $con->accidente->id }}
                                        </a>
                                    @endif                               
              
                            </td>
                            <td>
                                <span class="badge badge-secondary">
                                    {{ $con->paciente->ci }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $motivoColors = [
                                        'Enfermedad Común' => 'info',
                                        'Accidente Laboral' => 'danger',
                                        'Pre-vacacional' => 'success',
                                        'Reincorporacion - Post-vacacional' => 'warning',
                                        'Reincorporacion - Post-reposo' => 'primary',
                                    ];
                                    $color = 'primary';
                                    foreach($motivoColors as $key => $value) {
                                        if(str_contains($con->motivo_consulta, $key)) {
                                            $color = $value;
                                            break;
                                        }
                                    }
                                @endphp
                                <span class="badge badge-{{ $color }}">
                                    {{ Str::limit($con->motivo_consulta, 25) }}
                                </span>
                            </td>
                            <td>
                                <div class="small">{{ Str::limit($con->diagnostico_cie10, 40) }}</div>
                                @if($tieneReposo)
                                <span class="badge badge-danger mt-1">
                                    <i class="fas fa-bed"></i> {{ $con->dias_reposo }}d
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-muted">
                                    @if($con->consulta_rapida == 1)
                                        <i class="fas fa-bolt text-warning"></i> Rápida
                                    @else
                                        <i class="fas fa-stethoscope text-success"></i> Normal
                                    @endif
                                </div>
                                <div class="small text-muted">
                                    @if($con->status_consulta == 'Cerrada')
                                        <i class="fas fa-check text-success"></i> Consulta Cerrada
                                    @elseif($con->status_consulta == 'Pendiente por exámenes')
                                        <i class="fas fa-file-medical text-warning"></i> Pendiente por exámenes
                                    @endif
                                </div>                                
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-lg animated--fade-in">
                                        <div class="dropdown-header bg-gradient-primary text-white">
                                            <i class="fas fa-stethoscope"></i> Consulta
                                        </div>
                                        <a class="dropdown-item" href="{{ route('medicina.consultas.show', $con->id) }}">
                                            <i class="fas fa-eye text-info mr-2"></i> Ver Detalle
                                        </a>
                                        <a class="dropdown-item" href="{{ route('medicina.consultas.imprimir', $con->id) }}" target="_blank">
                                            <i class="fas fa-print text-primary mr-2"></i> Imprimir Recipe
                                        </a>
                                        @if($esEditable)
                                        <a class="dropdown-item" href="{{ route('medicina.consultas.edit', $con->id) }}">
                                            <i class="fas fa-edit text-warning mr-2"></i> Editar
                                        </a>
                                        @endif
                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-header bg-gradient-success text-white">
                                            <i class="fas fa-user"></i> Paciente
                                        </div>
                                        <a class="dropdown-item" href="{{ route('medicina.pacientes.show', $con->paciente_id) }}">
                                            <i class="fas fa-user-circle text-info mr-2"></i> Ver Perfil
                                        </a>
                                        @if($con->motivo_consulta === 'Accidente Laboral' && $con->tiene_accidente_vinculado == false)
                                            <a class="dropdown-item" href="{{ route('medicina.accidentes.create', ['paciente_id' => $con->paciente_id, 'consulta_id' => $con->id] ) }}">
                                                <i class="fas fa-ambulance text-danger mr-2"></i> Reportar Accidente
                                            </a>
                                        @elseif($con->motivo_consulta === 'Accidente Laboral' && $con->tiene_accidente_vinculado == true)
                                            <a class="dropdown-item" href="{{ route('medicina.accidentes.inpsasel', $con->accidente->id ) }}">
                                                <i class="fas fa-file-pdf text-danger mr-2"></i> Ver Reporte
                                            </a>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> 
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // DataTable
    $('#tblConsultas').DataTable({
        language: { url: "/js/lang/Spanish.json" },
        order: [[0, "desc"]],
        pageLength: 25,
        dom: 'Bfrtip',
        responsive: true,
    });

    // Tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Animación de entrada para las cards
    $('.kpi-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
});

// Gráfico de tendencia
const ctx = document.getElementById('myAreaChart').getContext('2d');
const labels = @json($labelsMeses);
const dataValues = @json($dataValores);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: "Consultas Médicas",
            lineTension: 0.3,
            backgroundColor: "rgba(78, 115, 223, 0.1)",
            borderColor: "rgba(78, 115, 223, 1)",
            pointRadius: 5,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "#fff",
            pointHoverRadius: 7,
            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
            pointHoverBorderColor: "#fff",
            pointHitRadius: 15,
            pointBorderWidth: 2,
            data: dataValues,
            fill: true
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    callback: function(value) {
                        return value + ' consultas';
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 15
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        return ' ' + context.parsed.y + ' consultas registradas';
                    }
                }
            }
        }
    }
});

// Exportar a Excel
function exportarConsultasExcel() {
    Swal.fire({
        title: '<i class="fas fa-file-excel text-success"></i> Exportar Consultas',
        html: `
            <div class="text-left p-3">
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fas fa-calendar-alt"></i> Rango de Fechas:</label>
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
                    <label class="font-weight-bold"><i class="fas fa-filter"></i> Filtrar por Tipo:</label>
                    <select id="tipo_consulta" class="form-control">
                        <option value="todos">📋 Todos los motivos</option>
                        <option value="accidente">🚑 Solo Accidentes Laborales</option>
                        <option value="enfermedad">🤒 Enfermedad Común</option>
                        <option value="preventiva">✅ Preventiva / Control</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-download"></i> Descargar Excel',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
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
            
            return {
                desde: desde,
                hasta: hasta,
                tipo: document.getElementById('tipo_consulta').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { desde, hasta, tipo } = result.value;
            Swal.fire({
                title: 'Generando...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            let url = `{{ route('medicina.consultas.export.excel') }}?desde=${desde}&hasta=${hasta}&tipo=${tipo}`;
            window.location.href = url;
            
            setTimeout(() => Swal.close(), 2000);
        }
    });
}
</script>

@if(session('print_id'))
<script>
    Swal.fire({
        title: '¡Consulta Guardada Exitosamente!',
        html: '<p>La atención médica ha sido registrada correctamente.</p><p class="text-muted small">¿Desea imprimir el récipe ahora?</p>',
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#858796',
        confirmButtonText: '<i class="fas fa-print"></i> Imprimir Ahora',
        cancelButtonText: '<i class="fas fa-times"></i> Más Tarde'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("{{ route('medicina.consultas.imprimir', session('print_id')) }}", '_blank');
        }
    });
</script>
@endif

@endsection