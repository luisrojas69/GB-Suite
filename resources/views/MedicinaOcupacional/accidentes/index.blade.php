@extends('layouts.app')

@section('styles')
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

        /* ========================================
       MAPA DE RIESGO
    ======================================== */

    .patients-list-header {
        background: white;
        border-bottom: 2px solid #f8f9fc;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

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
        <div class="card-body bg-gradient-danger text-white py-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="stat-icon bg-white text-danger">
                        <i class="fas fa-user-injured"></i>
                    </div>
                </div>
                <div class="col">
                    <h1 class="h2 mb-1 font-weight-bold text-white">
                        <i class="fas fa-ambulance"></i> Control de Accidentes Laborales
                    </h1>
                    <p class="mb-0 text-white-75">
                        <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }} | 
                        <i class="fas fa-hospital"></i> Servicio Médico Ocupacional
                    </p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('medicina.pacientes.index') }}" class="btn btn-light btn-lg shadow">
                        <i class="fas fa-user-plus"></i> Nuevo Accidente
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- KPIs Cards --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg kpi-card h-100">
                <div class="card-body bg-gradient-danger text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-2">
                                <i class="fas fa-calendar-alt"></i> Accidentes del Mes
                            </div>
                            <div class="h2 mb-0 font-weight-bold">{{ $accidentes->count() }}</div>
                            <small class="text-white-75">
                                <i class="fas fa-chart-line"></i> Período actual
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon bg-white-20">
                                <i class="fas fa-ambulance fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-2">
                    <small class="text-primary">
                        <i class="fas fa-info-circle"></i> Total de accidentes laborales
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
                                <i class="fas fa-user-clock"></i> Incidentes Leves
                            </div>
                            <div class="h2 mb-0 font-weight-bold">{{ $accidentes->where('tipo_evento', 'Incidente (Casi-Accidentes)')->count() }}</div>
                            <small class="text-white-75">
                                <i class="fas fa-business-time"></i> Incidente (Casi-Accidentes)
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
                                <i class="fas fa-virus"></i> Lugar mas Accidentado
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-truncate" title="{{ $topLugares->first()->lugar_exacto ?? 'N/A' }}">
                                {{ Str::limit($topLugares->first()->lugar_exacto ?? 'N/A', 30) }}
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
                        <i class="fas fa-chart-bar"></i> Mapa de Riesgos (Top 5)
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
                    
                    @forelse($topLugares as $index => $lugar)
                        @php 
                            $porcentaje = ($lugar->total / $topLugares->sum('total')) * 100; 
                            $color = $colores[$index] ?? ['bg' => 'bg-secondary', 'icon' => 'fas fa-circle', 'text' => 'text-secondary'];
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="{{ $color['icon'] }} {{ $color['text'] }} mr-2"></i>
                                    <strong class="small">{{ Str::limit($lugar->lugar_exacto, 35) }}</strong>
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
                                     title="{{ $lugar->total }} casos">
                                </div>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-users"></i> {{ $lugar->total }} accidentes registrados
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
                        <span class="badge badge-light text-warning">Año en Curso</span>
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
                                <small class="text-muted">accidentes</small>
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
                        <p class="text-muted">No hay datos de accidentes este mes</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Botón de Reporte --}}
        <div class="col-lg-3">
            <div class="row">
                <div class="col-12 mb-4">
                    <a href="{{ route('medicina.reportes.accidentalidad') }}" class="text-decoration-none" target="_blank">
                        <div class="card border-0 shadow-lg h-100">
                            <div class="card-body bg-gradient-danger text-white text-center py-5">
                                <i class="fas fa-file-pdf fa-4x mb-3"></i>
                                <h5 class="font-weight-bold mb-2">Reporte de Accidentabilidad</h5>
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
                    <a href="javascript:void(0)" class="text-decoration-none" onclick="exportarAccidentesExcel()">
                        <div class="card border-0 shadow-lg h-100">
                            <div class="card-body bg-gradient-success text-white text-center py-5">
                                <i class="fas fa-file-excel fa-4x mb-3"></i>
                                <h5 class="font-weight-bold mb-2">Exportar Accidentes</h5>
                                <p class="mb-3">Segun fecha y gravedad seleccionada</p>
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
                            <i class="fas fa-chart-line"></i> Tendencia de Accidentabilidad (Últimos 6 Meses)
                        </h6>
                        <a href="{{ route('medicina.reportes.accidentalidad') }}" target="_blank" class="btn btn-light btn-sm shadow">
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



    {{-- Tabla de Accidentes --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-gradient-secondary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-list"></i> Registro de Accidentes Laborales
                </h6>
                <button class="btn btn-success btn-sm shadow" onclick="exportarAccidentesExcel()">
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
                            <th><i class="fas fa-id-card"></i> Lugar/Consulta</th>
                            <th><i class="fas fa-comment-medical"></i> Tipo Evento</th>
                            <th><i class="fas fa-user-injured"></i> Gravedad</th>
                            <th><i class="fas fa-diagnoses"></i> Investigador</th>
                            <th class="text-center"><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accidentes as $acc)
                        @php
                            $esEditable = $acc->fecha_hora_accidente->gt(now()->subDays(3));
                        @endphp
                        <tr>
                            <td>
                                <div class="small font-weight-bold text-primary">
                                    {{ $acc->fecha_hora_accidente->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">{{ $acc->fecha_hora_accidente->format('h:i A') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('medicina.pacientes.show', $acc->paciente_id) }}" 
                                   class="font-weight-bold text-dark">
                                    {{ $acc->paciente->nombre_completo }}
                                </a>
                                <div class="small text-muted">
                                    <i class="fas fa-hashtag"></i> {{ $acc->paciente->cod_emp }}
                                    <span class="badge badge-secondary">
                                        {{ $acc->paciente->ci }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($acc->consulta_id != null)
                                <i class="fas fa-notes-medical text-success" title="Consulta asociada"></i>
                                    <a href="{{ route('medicina.consultas.show', $acc->consulta_id) }}" 
                                       class="font-weight-bold text-dark">
                                        Consulta Asoc: #{{ $acc->consulta_id }}
                                    </a>
                                @else
                                     <div class="small text-muted">
                                        <i class="fas fa-exclamation-triangle text-warning" title="Sin consulta asociada"></i>
                                        Sin Consulta Acoc.
                                    </div>
                                @endif                               
                                <div class="small text-muted">
                                    <i class="fas fa-map-marker-alt text-danger fa-sm"></i> {{ $acc->lugar_exacto }}
                                </div>
                            </td>
                            <td>
                               @php
                                    $badge = 'badge-secondary';
                                    if(str_contains($acc->tipo_evento, 'Tiempo Perdido')) $badge = 'badge-danger';
                                    if(str_contains($acc->tipo_evento, 'Incidente')) $badge = 'badge-warning text-dark';
                                @endphp
                                <span class="badge {{ $badge }} px-2">{{ $acc->tipo_evento }}</span>
                            </td>
                            <td>
                                @php
                                    $gravedadColors = [
                                        'Mortal' => 'danger',
                                        'Leve' => 'success',
                                        'Grave' => 'warning',
                                    ];
                                    $color = 'primary';
                                    foreach($gravedadColors as $key => $value) {
                                        if(str_contains($acc->gravedad, $key)) {
                                            $color = $value;
                                            break;
                                        }
                                    }
                                @endphp
                                <span class="badge badge-{{ $color }}">
                                    <i class="fas fa-user-injured text-danger fa-sm"></i> {{ Str::limit($acc->gravedad) }}
                                </span>
                                <div class="small text-muted">
                                    <span class="badge badge-info">
                                        <i class="fas fa-person text-warning fa-sm"></i> {{ Str::limit($acc->parte_lesionada) }}
                                    </span> 
                                </div>
                            </td>
                            <td>
                               <div class="small text-muted">
                                    <i class="fas fa-user-secret text-success fa-sm"></i> {{ $acc->user->name." ". $acc->user->last_name }}
                                </div> 
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-lg animated--fade-in">
                                        <div class="dropdown-header bg-gradient-primary text-white">
                                            <i class="fas fa-stethoscope"></i> Accidente
                                        </div>
                                        <a class="dropdown-item" href="{{ route('medicina.accidentes.show', $acc->id) }}">
                                            <i class="fas fa-eye text-info mr-2"></i> Ver Detalle
                                        </a>
                                        <a class="dropdown-item" href="{{ route('medicina.accidentes.inpsasel', $acc->id) }}" target="_blank">
                                            <i class="fas fa-print text-primary mr-2"></i> Imprimir Reporte Inpsasel
                                        </a>
                                        @if($esEditable)
                                        <a class="dropdown-item" href="{{ route('medicina.accidentes.edit', $acc->id) }}">
                                            <i class="fas fa-edit text-warning mr-2"></i> Editar
                                        </a>
                                        @endif
                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-header bg-gradient-success text-white">
                                            <i class="fas fa-user"></i> Paciente
                                        </div>
                                        <a class="dropdown-item" href="{{ route('medicina.pacientes.show', $acc->paciente_id) }}">
                                            <i class="fas fa-user-circle text-info mr-2"></i> Ver Perfil
                                        </a>
                                            @if($acc->consulta_id == null)
                                                <a class="dropdown-item" href="{{ route('medicina.consultas.create', ['paciente_id' => $acc->paciente_id, 'accidente_id' => $acc->id, 'motivo' => 'Accidente-laboral'] ) }}">
                                                    <i class="fas fa-notes-medical text-success mr-2"></i> Registrar Consulta
                                                </a>
                                            @else()
                                                <a class="dropdown-item" href="{{ route('medicina.consultas.show', $acc->consulta_id) }}">
                                                    <i class="fas fa-notes-medical text-success mr-2"></i> Ver Consulta
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

<script>

    $(document).ready(function() {
    // DataTable
        $('#tblAccidentes').DataTable({
            language: { url: "/js/lang/Spanish.json" },
            order: [[0, "desc"]],
            pageLength: 25,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        // Tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Animación de entrada para las cards
        $('.kpi-card').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });
    });

</script>

<script>

// Gráfico de tendencia
const ctx = document.getElementById('myAreaChart').getContext('2d');
const labels = @json($labelsMeses);
const dataValues = @json($dataValores);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: "Accidentes Laborales",
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
                        return value + ' accidentes';
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
                        return ' ' + context.parsed.y + ' accidentes registrados';
                    }
                }
            }
        }
    }
});


// Exportar a Excel
function exportarAccidentesExcel() {
    Swal.fire({
        title: '<i class="fas fa-file-excel text-success"></i> Exportar Accidentes',
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
                    <label class="font-weight-bold"><i class="fas fa-filter"></i> Filtrar por Gravedad:</label>
                    <select id="gravedad_accidente" class="form-control">
                        <option value="todos">📋 Todos los accidentes</option>
                        <option value="accidente">🚑 Solo Accidentes Leves</option>
                        <option value="enfermedad">🤒 Solo Accidentes Graves</option>
                        <option value="preventiva">✅ Solo Accidentes Mortales</option>
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
                gravedad: document.getElementById('gravedad_accidente').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { desde, hasta, gravedad } = result.value;
            Swal.fire({
                title: 'Generando...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            let url = `{{ route('medicina.accidentes.export.excel') }}?desde=${desde}&hasta=${hasta}&gravedad=${gravedad}`;
            window.location.href = url;
            
            setTimeout(() => Swal.close(), 2000);
        }
    });
}
</script>

@if(session('print_id'))
<script>
    Swal.fire({
        title: '¡Accidente Guardado Exitosamente!',
        html: '<p>El suceso ha sido registrado correctamente.</p><p class="text-muted small">¿Desea imprimir el reporte Inpsasel?</p>',
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#858796',
        confirmButtonText: '<i class="fas fa-print"></i> Imprimir Ahora',
        cancelButtonText: '<i class="fas fa-times"></i> Más Tarde'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("{{ route('medicina.accidentes.inpsasel', session('print_id')) }}", '_blank');
        }
    });
</script>
@endif
@endsection