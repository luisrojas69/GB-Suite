@extends('layouts.app')
@php
    $esEditable = $consulta->fecha_consulta->gt(now()->subDays(3));
@endphp
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
                                
                                {{-- BADGE DE ESTATUS --}}
                                @php
                                    $statusColor = 'secondary';
                                    $statusIcon = 'fa-circle';
                                    switch($consulta->status_consulta) {
                                        case 'Registrada': $statusColor = 'primary'; $statusIcon = 'fa-file-signature'; break;
                                        case 'Pendiente por exámenes': $statusColor = 'warning'; $statusIcon = 'fa-flask'; break;
                                        case 'En evaluación': $statusColor = 'info'; $statusIcon = 'fa-user-md'; break;
                                        case 'Cerrada': $statusColor = 'success'; $statusIcon = 'fa-check-circle'; break;
                                    }
                                @endphp
                                <span class="badge badge-{{ $statusColor }} text-uppercase ml-3 shadow-sm" style="font-size: 0.5em; vertical-align: middle; border: 1px solid white;">
                                    <i class="fas {{ $statusIcon }} mr-1"></i> {{ $consulta->status_consulta }}
                                </span>
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
                                    @if($esEditable)
                                    <a class="dropdown-item" href="{{ route('medicina.consultas.edit', $consulta->id) }}">
                                        <i class="fas fa-edit text-warning mr-2"></i> Editar Consulta
                                    </a>
                                    @endif
                                    @if($consulta->requiere_examenes == false && $esEditable)
                                    <a class="dropdown-item" href="{{ route('medicina.ordenes.create', $consulta->id) }}">
                                        <i class="fas fa-file-medical text-success mr-2"></i> Requerir Examenes
                                    </a>
                                    @endif
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
        <div class="col-xl-4 col-lg-5 mb-4">
            {{-- Card Principal del Paciente --}}
            <div class="card shadow-lg border-0 mb-4 info-card">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h5 class="m-0 font-weight-bold">
                        <i class="fas fa-user-circle"></i> Información del Paciente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="img-profile rounded-circle border border-info border-3 shadow" 
                             src="{{ asset($consulta->paciente->foto) }}" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <div class="text-center mb-3">
                        <h4 class="font-weight-bold text-primary mb-1">{{ $consulta->paciente->nombre_completo }}</h4>
                        <p class="text-muted mb-1">
                            <i class="fas fa-briefcase"></i> {{ $consulta->paciente->des_cargo }}
                        </p>
                        <span class="badge badge-primary badge-lg">
                            <i class="fas fa-hashtag"></i> Ficha: {{ $consulta->paciente->cod_emp }}
                        </span>
                    </div>

                    {{-- Datos Personales --}}
                    <div class="border-top pt-3">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-id-card"></i> Datos Personales
                        </h6>
                        <div class="row small">
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    @if($consulta->paciente->sexo == 'M')
                                        <i class="fas fa-mars text-primary fa-lg mr-2"></i>
                                        <div>
                                            <div class="text-muted" style="font-size: 0.7rem;">Sexo</div>
                                            <strong>Masculino</strong>
                                        </div>
                                    @else
                                        <i class="fas fa-venus text-danger fa-lg mr-2"></i>
                                        <div>
                                            <div class="text-muted" style="font-size: 0.7rem;">Sexo</div>
                                            <strong>Femenino</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-birthday-cake text-purple fa-lg mr-2"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Edad</div>
                                        <strong>{{ \Carbon\Carbon::parse($consulta->paciente->fecha_nac)->age }} años</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar text-info fa-lg mr-2"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Fecha de Nacimiento</div>
                                        <strong>{{ \Carbon\Carbon::parse($consulta->paciente->fecha_nac)->format('d/m/Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-door-open text-success fa-lg mr-2"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Fecha de Ingreso</div>
                                        <strong>
                                            {{ \Carbon\Carbon::parse($consulta->paciente->fecha_ing)->format('d/m/Y') }}
                                            <span class="badge badge-success ml-1">
                                                {{ \Carbon\Carbon::parse($consulta->paciente->fecha_ing)->diffForHumans(null, true) }}
                                            </span>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-wheelchair text-{{ $consulta->paciente->discapacitado ? 'warning' : 'secondary' }} fa-lg mr-2"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Discapacidad</div>
                                        @if($consulta->paciente->discapacitado)
                                            <strong class="text-warning">Sí - {{ $consulta->paciente->tipo_discapac }}</strong>
                                        @else
                                            <strong class="text-muted">No</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone text-warnin fa-lg mr-2"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Tel&eacute;fono:</div>
                                        <strong>{{ $consulta->paciente->telefono ?? 'N/A'  }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-ambulance text-danger fa-lg mr-2"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.7rem;">En caso de emergencia llamar a:</div>
                                        <strong>{{ $consulta->paciente->avisar_a ?? 'N/A' }} - {{ $consulta->paciente->telf_contact ?? 'N/A'}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Datos Biométricos --}}
                    <div class="border-top pt-3 mt-3">
                        <h6 class="font-weight-bold text-success mb-3">
                            <i class="fas fa-heartbeat"></i> Datos Biométricos
                        </h6>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="text-center p-2 bg-light rounded">
                                    <i class="fas fa-weight text-primary fa-2x mb-1"></i>
                                    <div class="text-muted small">Peso</div>
                                    <strong class="h6 text-primary">
                                        {{ $consulta->paciente->peso_inicial ?? 'N/A' }} <small>kg</small>
                                    </strong>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="text-center p-2 bg-light rounded">
                                    <i class="fas fa-ruler-vertical text-info fa-2x mb-1"></i>
                                    <div class="text-muted small">Estatura</div>
                                    <strong class="h6 text-info">
                                        {{ $consulta->paciente->estatura ?? 'N/A' }} <small>cm</small>
                                    </strong>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="text-center p-2 bg-light rounded">
                                    <i class="fas fa-tint text-danger fa-2x mb-1"></i>
                                    <div class="text-muted small">Tipo de Sangre</div>
                                    <strong class="h6 text-danger">
                                        {{ $consulta->paciente->tipo_sangre ?? 'N/A' }}
                                    </strong>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="text-center p-2 bg-light rounded">
                                    <i class="fas fa-hand-paper text-warning fa-2x mb-1"></i>
                                    <div class="text-muted small">Lateralidad</div>
                                    <strong class="h6 text-{{ $consulta->paciente->es_zurdo ? 'warning' : 'secondary' }}">
                                        {{ $consulta->paciente->es_zurdo ? 'Zurdo' : 'Diestro' }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        @if($consulta->paciente->peso_inicial && $consulta->paciente->estatura)
                        @php
                            $imc = round($consulta->paciente->peso_inicial / (($consulta->paciente->estatura / 100) ** 2), 1);
                            $imcClass = 'success';
                            $imcText = 'Normal';
                            if ($imc < 18.5) { 
                                $imcClass = 'warning'; 
                                $imcText = 'Bajo Peso';
                            } elseif ($imc >= 25 && $imc < 30) { 
                                $imcClass = 'info'; 
                                $imcText = 'Sobrepeso';
                            } elseif ($imc >= 30) { 
                                $imcClass = 'danger'; 
                                $imcText = 'Obesidad';
                            }
                        @endphp
                        <div class="alert alert-{{ $imcClass }} mb-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="small font-weight-bold">IMC (Índice de Masa Corporal)</div>
                                    <div class="h4 mb-0">{{ $imc }}</div>
                                </div>
                                <div>
                                    <span class="badge badge-{{ $imcClass }} badge-xl">
                                        {{ $imcText }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Alertas Médicas --}}
                    @if($consulta->paciente->alergias || $consulta->paciente->enfermedades_base)
                    <div class="border-top pt-3 mt-3">
                        <h6 class="font-weight-bold text-danger mb-3">
                            <i class="fas fa-exclamation-triangle"></i> Alertas Médicas
                        </h6>
                        @if($consulta->paciente->alergias)
                        <div class="alert alert-warning border-left-warning mb-2">
                            <strong class="small d-block mb-1">
                                <i class="fas fa-allergies"></i> Alergias:
                            </strong>
                            <span class="small">{{ $consulta->paciente->alergias }}</span>
                        </div>
                        @endif
                        @if($consulta->paciente->enfermedades_base)
                        <div class="alert alert-danger border-left-danger mb-0">
                            <strong class="small d-block mb-1">
                                <i class="fas fa-heartbeat"></i> Patologías:
                            </strong>
                            <span class="small">{{ $consulta->paciente->enfermedades_base }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Acciones Rápidas --}}
                    <div class="border-top pt-3 mt-3">
                        <h6 class="font-weight-bold text-secondary mb-3">
                            <i class="fas fa-bolt"></i> Acciones Rápidas
                        </h6>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <a href="{{ route('medicina.consultas.historial', $consulta->paciente->id) }}" class="btn btn-info btn-block btn-sm shadow-sm">
                                    <i class="fas fa-history"></i> Ver Historial Completo
                                </a>
                            </div>
                            <div class="col-12 mb-2">
                                <a href="{{ route('medicina.pacientes.show', $consulta->paciente->id) }}" class="btn btn-success btn-block btn-sm shadow-sm">
                                    <i class="fas fa-eye"></i> Ver Perfil Completo
                                </a>
                            </div>
                        </div>
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


    {{-- SECCIÓN DE RESULTADOS DE LABORATORIO (CONDICIONAL) --}}
    @if($consulta->requiere_examenes)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-left-{{ ($consulta->orden && $consulta->orden->status_orden == 'Completada') ? 'success' : 'warning' }}">
                <div class="card-header py-3 bg-gradient-{{ ($consulta->orden && $consulta->orden->status_orden == 'Completada') ? 'success' : 'warning' }}">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-microscope"></i> Resultados de Exámenes Complementarios
                    </h6>
                </div>
                <div class="card-body">
                    
                    @if($consulta->orden && $consulta->orden->status_orden == 'Completada')
                        {{-- CASO 1: RESULTADOS YA CARGADOS --}}
                        <div class="row">
                            <div class="col-md-8">
                                <div class="alert alert-{{ $consulta->orden->interpretacion == 'Normal' ? 'success' : 'danger' }} mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-white text-{{ $consulta->orden->interpretacion == 'Normal' ? 'success' : 'danger' }} mr-3">
                                            <i class="fas {{ $consulta->orden->interpretacion == 'Normal' ? 'fa-check' : 'fa-exclamation-triangle' }}"></i>
                                        </div>
                                        <div>
                                            <div class="small text-uppercase font-weight-bold">Interpretación Global</div>
                                            <span class="h5 font-weight-bold">{{ $consulta->orden->interpretacion }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <strong class="text-primary text-uppercase small"><i class="fas fa-search"></i> Hallazgos Clínicos:</strong>
                                    <p class="text-justify bg-light p-3 rounded mt-1 border-left-primary" style="border-left: 4px solid;">
                                        {{ $consulta->orden->hallazgos ?? 'Sin hallazgos registrados.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4 text-center">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                                        <h6 class="font-weight-bold">Soporte Digital</h6>
                                        @forelse($archivos_orden as $archivo)
                                            <div class="card mb-2 border-left-info">
                                                <div class="card-body py-2">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            @if($archivo->tipo_archivo == 'pdf')
                                                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                            @else
                                                                <i class="fas fa-file-image fa-2x text-primary"></i>
                                                            @endif
                                                        </div>
                                                        <div class="col">
                                                           
                                                                <strong>{{ $archivo->nombre_archivo }}</strong>

                                                            <div class="small text-muted">
                                                                <i class="fas fa-calendar"></i> 
                                                                {{ \Carbon\Carbon::parse($archivo->created_at)->format('d/m/Y h:i A') }}
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            
                                                            <span class="badge badge-secondary">
                                                                {{ strtoupper($archivo->tipo_archivo) }}
                                                            </span>
                                                            <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" target="_blank" class="btn btn-sm btn-circle btn-info shadow-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @empty
                                               <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                                                {{-- <h5 class="text-muted">No hay documentos almacenados</h5> --}}
                                                <button class="btn btn-secondary btn-block" disabled>No hay archivos</button>

                                            @endforelse
                                            <small class="text-muted d-block mt-2">Cargado el {{ $consulta->orden->updated_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        {{-- CASO 2: PENDIENTE POR CARGAR --}}
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-hourglass-half fa-4x text-warning animated--pulse"></i>
                            </div>
                            <h4 class="font-weight-bold text-gray-800">Pendiente por Resultados</h4>
                            <p class="text-muted mb-4">
                                Esta consulta requiere exámenes, pero aún no se han cargado los resultados o la orden no ha sido procesada.
                            </p>
                            
                            <div class="d-flex justify-content-center">
                                @if($consulta->orden)
                                    {{-- Si ya existe la orden, botón para ir a cargar --}}
                                    <a href="{{ route('medicina.ordenes.edit', $consulta->orden->id) }}" class="btn btn-warning btn-lg shadow px-4">
                                        <i class="fas fa-upload mr-2"></i> Cargar Resultados Ahora
                                    </a>
                                @else
                                    {{-- Si se marcó el check pero no se generó orden (caso raro, pero posible) --}}
                                    <a href="{{ route('medicina.ordenes.create', ['consulta_id' => $consulta->id]) }}" class="btn btn-primary btn-lg shadow px-4">
                                        <i class="fas fa-plus-circle mr-2"></i> Generar Orden Faltante
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
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
                            @if($esEditable)
                            <a href="{{ route('medicina.consultas.edit', $consulta->id) }}" 
                               class="btn btn-warning shadow-sm">
                                <i class="fas fa-edit"></i> Editar Consulta
                            </a>
                            @endif
                            @if($consulta->requiere_examenes == false && $esEditable)
                            <a href="{{ route('medicina.ordenes.create', $consulta->id) }}" 
                               class="btn btn-success shadow-sm">
                                <i class="fas fa-edit"></i> Requerir Examenes
                            </a>
                            @endif
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