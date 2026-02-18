@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Header Principal --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary py-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-primary">
                                <i class="fas fa-notes-medical fa-3x"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h1 class="h3 mb-1 text-white font-weight-bold">
                                <i class="fas fa-stethoscope"></i> Consulta Médica #{{ str_pad($consulta->id, 5, '0', STR_PAD_LEFT) }}
                            </h1>
                            <p class="text-white-50 mb-0">
                                <i class="fas fa-calendar"></i> {{ $consulta->created_at->format('d/m/Y h:i A') }} | 
                                <i class="fas fa-user-md"></i> Dr. {{ $consulta->medico->name." ".$consulta->medico->last_name }}
                            </p>
                        </div>
                        <div class="col-auto">
                            <div class="dropdown no-arrow">
                                <button class="btn btn-light dropdown-toggle shadow-sm" type="button" data-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i> Opciones
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                    <div class="dropdown-header">Acciones Rápidas</div>
                                    <a class="dropdown-item" href="{{ route('medicina.consultas.imprimir', $consulta->id) }}" target="_blank">
                                        <i class="fas fa-print text-primary mr-2"></i> Imprimir Recipe
                                    </a>
                                    <a class="dropdown-item" href="{{ route('medicina.consultas.edit', $consulta->id) }}">
                                        <i class="fas fa-edit text-warning mr-2"></i> Editar Consulta
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <div class="dropdown-header">Certificados Médicos</div>
                                    <a class="dropdown-item" href="{{ route('medicina.pdf.aptitud', $consulta->paciente->id) }}" target="_blank">
                                        <i class="fas fa-person-circle-check text-warning mr-2"></i> Certificado de Aptitud
                                    </a>
                                    <a class="dropdown-item" href="{{ route('medicina.pdf.constancia', $consulta->id) }}" target="_blank">
                                        <i class="fas fa-person-walking-arrow-right text-info mr-2"></i> Constancia de Asistencia
                                    </a>
                                    <a class="dropdown-item" href="{{ route('medicina.pdf.historial', $consulta->id) }}" target="_blank">
                                        <i class="fas fa-virus text-danger mr-2"></i> Historial Epidemiológico
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('medicina.pacientes.show', $consulta->paciente->id) }}">
                                        <i class="fas fa-user text-secondary mr-2"></i> Ver Perfil del Paciente
                                    </a>
                                    <a class="dropdown-item" href="{{ route('medicina.pacientes.index') }}">
                                        <i class="fas fa-users text-secondary mr-2"></i> Lista de Pacientes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerta de Accidente Laboral --}}
    @if($consulta->motivo_consulta == 'Accidente Laboral')
        @if($consulta->accidente)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-left-danger shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="icon-circle bg-danger text-white">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="font-weight-bold text-danger mb-2">
                                    <i class="fas fa-link"></i> Accidente Laboral Vinculado
                                </h5>
                                <p class="mb-2">
                                    Esta evaluación médica está asociada a un accidente ocurrido el 
                                    <strong>{{ \Carbon\Carbon::parse($consulta->accidente->fecha_hora_accidente)->format('d/m/Y h:i A') }}</strong>
                                </p>
                                <div class="mt-2">
                                    <span class="badge badge-danger mr-2">
                                        <i class="fas fa-map-marker-alt"></i> {{ $consulta->accidente->lugar_exacto }}
                                    </span>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-circle"></i> {{ $consulta->accidente->tipo_evento }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('medicina.accidentes.show', $consulta->accidente->id) }}" 
                                   class="btn btn-danger shadow-sm">
                                    <i class="fas fa-file-medical-alt"></i> Ver Investigación de Accidente
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-left-warning shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                            </div>
                            <div class="col">
                                <h6 class="font-weight-bold text-warning mb-1">Reporte de Accidente Pendiente</h6>
                                <p class="mb-0">
                                    Esta evaluación médica está clasificada como accidente laboral, pero aún no se ha generado 
                                    el reporte de investigación correspondiente.
                                </p>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('medicina.accidentes.create', ['consulta_id' => $consulta->id, 'paciente_id' => $consulta->paciente_id]) }}" 
                                   class="btn btn-warning shadow-sm">
                                    <i class="fas fa-plus-circle"></i> Crear Reporte de Accidente
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif

    {{-- Información del Paciente y Consulta --}}
    <div class="row">
        {{-- Datos del Paciente --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-info">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-user-circle"></i> Información del Paciente
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="img-profile rounded-circle border border-info border-3" 
                             src="{{ asset($consulta->paciente->foto) }}" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <div class="text-center mb-3">
                        <h5 class="font-weight-bold text-gray-800 mb-1">
                            <a href="{{ route('medicina.pacientes.show', $consulta->paciente->id) }}" 
                               class="text-primary">
                                {{ $consulta->paciente->nombre_completo }}
                            </a>
                        </h5>
                        <div class="mb-2">
                            <span class="badge badge-secondary">
                                <i class="fas fa-id-card"></i> CI: {{ $consulta->paciente->ci }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="small">
                        <div class="mb-2">
                            <i class="fas fa-briefcase text-info"></i> 
                            <strong>Cargo:</strong> {{ $consulta->paciente->des_cargo }}
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-building text-success"></i> 
                            <strong>Departamento:</strong> {{ $consulta->paciente->des_depart }}
                        </div>
                        @if($consulta->paciente->tipo_sangre)
                        <div class="mb-2">
                            <i class="fas fa-tint text-danger"></i> 
                            <strong>Tipo de Sangre:</strong> 
                            <span class="badge badge-danger">{{ $consulta->paciente->tipo_sangre }}</span>
                        </div>
                        @endif
                        @if($consulta->paciente->alergias)
                        <div class="mb-2">
                            <i class="fas fa-allergies text-warning"></i> 
                            <strong>Alergias:</strong> {{ $consulta->paciente->alergias }}
                        </div>
                        @endif
                    </div>
                    <hr>
                    <div class="text-center">
                        <a href="{{ route('medicina.pacientes.show', $consulta->paciente->id) }}" 
                           class="btn btn-info btn-sm btn-block shadow-sm">
                            <i class="fas fa-eye"></i> Ver Perfil Completo
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detalles de la Consulta --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-primary">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-file-medical"></i> Detalles de la Consulta
                    </h6>
                </div>
                <div class="card-body">
                    {{-- Motivo de Consulta --}}
                    <div class="mb-4">
                        <div class="border-left border-primary pl-3">
                            <small class="text-muted text-uppercase d-block mb-2">
                                <i class="fas fa-question-circle"></i> Motivo de Consulta
                            </small>
                            <div class="alert alert-primary mb-0">
                                <strong>{{ $consulta->motivo_consulta }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Anamnesis --}}
                    <div class="mb-4">
                        <div class="border-left border-info pl-3">
                            <small class="text-muted text-uppercase d-block mb-2">
                                <i class="fas fa-comments"></i> Anamnesis (Relato y Antecedentes)
                            </small>
                            @if($consulta->anamnesis)
                                <p class="text-gray-800 text-justify mb-0">{{ $consulta->anamnesis }}</p>
                            @else
                                <p class="text-muted mb-0"><i>No registrado</i></p>
                            @endif
                        </div>
                    </div>

                    {{-- Examen Físico --}}
                    <div class="mb-4">
                        <div class="border-left border-success pl-3">
                            <small class="text-muted text-uppercase d-block mb-2">
                                <i class="fas fa-heartbeat"></i> Examen Físico / Hallazgos
                            </small>
                            @if($consulta->examen_fisico)
                                <p class="text-gray-800 text-justify mb-0">{{ $consulta->examen_fisico }}</p>
                            @else
                                <p class="text-muted mb-0"><i>No registrado</i></p>
                            @endif
                        </div>
                    </div>

                    {{-- Signos Vitales (si existen en el modelo) --}}
                    @if(isset($consulta->presion_arterial) || isset($consulta->frecuencia_cardiaca) || isset($consulta->temperatura))
                    <div class="mb-4">
                        <div class="border-left border-warning pl-3">
                            <small class="text-muted text-uppercase d-block mb-2">
                                <i class="fas fa-heartbeat"></i> Signos Vitales
                            </small>
                            <div class="row">
                                @if(isset($consulta->tension_arterial))
                                <div class="col-md-4">
                                    <div class="text-center p-2 bg-light rounded">
                                        <i class="fas fa-heart text-danger"></i>
                                        <div class="small text-muted">Presión Arterial</div>
                                        <strong>{{ $consulta->tension_arterial }}</strong>
                                    </div>
                                </div>
                                @endif
                                @if(isset($consulta->frecuencia_cardiaca))
                                <div class="col-md-4">
                                    <div class="text-center p-2 bg-light rounded">
                                        <i class="fas fa-heartbeat text-primary"></i>
                                        <div class="small text-muted">Frecuencia Cardíaca</div>
                                        <strong>{{ $consulta->frecuencia_cardiaca }} lpm</strong>
                                    </div>
                                </div>
                                @endif
                                @if(isset($consulta->temperatura))
                                <div class="col-md-4">
                                    <div class="text-center p-2 bg-light rounded">
                                        <i class="fas fa-thermometer-half text-warning"></i>
                                        <div class="small text-muted">Temperatura</div>
                                        <strong>{{ $consulta->temperatura }}°C</strong>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Reposo Médico --}}
                    <div class="mb-4">
                        <div class="border-left border-{{ $consulta->genera_reposo ? 'danger' : 'info' }} pl-3">
                            <small class="text-muted text-uppercase d-block mb-2">
                                <i class="fas fa-bed"></i> Reposo Médico
                            </small>
                            @if($consulta->genera_reposo == 1)
                                <div class="alert alert-danger mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-bed-pulse fa-3x mr-3"></i>
                                        <div>
                                            <strong class="d-block">AMERITA REPOSO MÉDICO</strong>
                                            <span class="h4 mb-0">{{ $consulta->dias_reposo }} días</span>
                                            @if($consulta->fecha_inicio_reposo && $consulta->fecha_fin_reposo)
                                            <div class="small mt-1">
                                                Desde: {{ \Carbon\Carbon::parse($consulta->fecha_inicio_reposo)->format('d/m/Y') }} 
                                                hasta: {{ \Carbon\Carbon::parse($consulta->fecha_fin_reposo)->format('d/m/Y') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="badge badge-info badge-lg">
                                    <i class="fas fa-check-circle"></i> No amerita reposo
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Diagnóstico y Tratamiento --}}
    <div class="row">
        {{-- Diagnóstico --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-danger">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-diagnoses"></i> Diagnóstico CIE-10
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger border-left-danger mb-0">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-notes-medical fa-3x mr-3 text-danger"></i>
                            <div>
                                <strong class="text-uppercase small d-block mb-2">Diagnóstico Principal</strong>
                                <p class="mb-0 h5 text-justify">{{ $consulta->diagnostico_cie10 }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($consulta->diagnostico_secundario)
                    <div class="alert alert-warning border-left-warning mt-3 mb-0">
                        <strong class="text-uppercase small d-block mb-1">Diagnóstico Secundario</strong>
                        <p class="mb-0">{{ $consulta->diagnostico_secundario }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tratamiento --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-success">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-prescription"></i> Plan de Tratamiento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success border-left-success mb-0">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-pills fa-3x mr-3 text-success"></i>
                            <div>
                                <strong class="text-uppercase small d-block mb-2">Indicaciones Médicas</strong>
                                <p class="mb-0 text-justify">{{ $consulta->plan_tratamiento }}</p>
                            </div>
                        </div>
                    </div>

                    @if($consulta->medicamentos)
                    <div class="mt-3 bg-light p-3 rounded">
                        <strong class="small text-uppercase d-block mb-2">
                            <i class="fas fa-capsules text-success"></i> Medicamentos Prescritos
                        </strong>
                        <p class="mb-0 small">{{ $consulta->medicamentos }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Observaciones y Datos Adicionales --}}
    @if($consulta->observaciones || $consulta->examenes_solicitados)
    <div class="row">
        @if($consulta->observaciones)
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-warning">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-clipboard"></i> Observaciones Adicionales
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-gray-800 text-justify mb-0">{{ $consulta->observaciones }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($consulta->examenes_solicitados)
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-info">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-flask"></i> Exámenes Solicitados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $consulta->examenes_solicitados }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- Información del Médico y Registro --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-gradient-secondary">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-info-circle"></i> Información del Registro
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-user-md fa-2x text-primary mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Médico Tratante</div>
                                <div class="h6 mb-0 font-weight-bold text-primary">
                                    Dr. {{ $consulta->medico->name." ".$consulta->medico->last_name }}
                                </div>
                                @if($consulta->medico->especialidad)
                                <small class="text-muted">{{ $consulta->medico->especialidad }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Fecha de Consulta</div>
                                <div class="h6 mb-0 font-weight-bold text-success">
                                    {{ $consulta->created_at->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">{{ $consulta->created_at->format('h:i A') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-edit fa-2x text-info mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Última Modificación</div>
                                <div class="h6 mb-0 font-weight-bold text-info">
                                    {{ $consulta->updated_at->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">{{ $consulta->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-hashtag fa-2x text-warning mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">N° de Consulta</div>
                                <div class="h6 mb-0 font-weight-bold text-warning">
                                    #{{ str_pad($consulta->id, 5, '0', STR_PAD_LEFT) }}
                                </div>
                                <small class="text-muted">Registro Médico</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Botones de Acción Final --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body bg-light">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mb-2 mb-md-0">
                            <a href="{{ route('medicina.pacientes.show', $consulta->paciente->id) }}" 
                               class="btn btn-secondary shadow-sm">
                                <i class="fas fa-arrow-left"></i> Volver al Paciente
                            </a>
                            <a href="{{ route('medicina.consultas.index') }}" 
                               class="btn btn-outline-secondary shadow-sm">
                                <i class="fas fa-list"></i> Lista de Consultas
                            </a>
                        </div>
                        <div>
                            @if($consulta->motivo_consulta == 'Accidente Laboral' && !$consulta->accidente)
                            <a href="{{ route('medicina.accidentes.create', ['consulta_id' => $consulta->id, 'paciente_id' => $consulta->paciente_id]) }}" 
                               class="btn btn-danger shadow-sm">
                                <i class="fas fa-plus-circle"></i> Crear Reporte de Accidente
                            </a>
                            @endif
                            <a href="{{ route('medicina.consultas.edit', $consulta->id) }}" 
                               class="btn btn-warning shadow-sm">
                                <i class="fas fa-edit"></i> Editar Consulta
                            </a>
                            <a href="{{ route('medicina.consultas.imprimir', $consulta->id) }}" 
                               class="btn btn-primary shadow-sm" target="_blank">
                                <i class="fas fa-print"></i> Imprimir Recipe
                            </a>
                            <button class="btn btn-info shadow-sm" onclick="window.print()">
                                <i class="fas fa-print"></i> Imprimir Vista
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Estilos adicionales --}}
<style>
.icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.badge-lg {
    font-size: 0.95rem;
    padding: 0.5rem 1rem;
}

@media print {
    .btn, .dropdown, .card-header, .alert-warning, .no-print,
    .navbar, .sidebar, footer {
        display: none !important;
    }
    .card {
        page-break-inside: avoid;
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    .alert-danger, .alert-success {
        border: 2px solid #000 !important;
    }
    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}
</style>
@endsection

@section('scripts')
<script>
// Animación de entrada suave
$(document).ready(function() {
    $('.card').hide().fadeIn(800);
});
</script>
@endsection