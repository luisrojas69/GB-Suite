@extends('layouts.app')
@section('title-page', 'Modulo de creación de Consulta - Paciente: '.$paciente->cod_emp)

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .info-card {
        transition: all 0.3s ease;
    }
    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: calc(1.5em + 0.75rem);
        padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.75rem);
    }
    .badge-xl {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
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
                    <div class="icon-circle bg-white text-primary" style="width: 80px; height: 80px;">
                        <i class="fas fa-notes-medical fa-3x"></i>
                    </div>
                </div>
                <div class="col">
                    <h1 class="h2 mb-1 font-weight-bold text-white">
                        <i class="fas fa-stethoscope"></i> Atención Médica Digital
                    </h1>
                    <p class="mb-0 text-white-50">
                        <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }} | 
                        <i class="fas fa-clock"></i> {{ \Carbon\Carbon::now()->format('h:i A') }}
                    </p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('medicina.pacientes.index') }}" class="btn btn-light btn-lg shadow">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($accidente)
        <div class="alert alert-success shadow-lg border-left-success mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fas fa-check-double fa-2x"></i>
                </div>
                <div class="col">
                    <h6 class="font-weight-bold mb-1">
                        <i class="fas fa-stethoscope"></i> Accidente sin Consulta Detectado
                    </h6>
                    <p class="mb-1">
                        Se registró un <strong>ACCIDENTE LABORAL</strong> a este paciente el dia: {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('h:i A') }}, Que no tiene una consulta vinculada.
                    </p>
                    <p class="mb-1">
                        Por lo que se vinculará esta consulta automaticamente a esa accidente
                    </p>
                    <span class="badge badge-success">
                        Parte(s) Lesionada(s): {{ $accidente->parte_lesionada }}
                    </span>
                    <span class="badge badge-warning">
                        Gravedad: {{ $accidente->gravedad }}
                    </span>
                </div>
            </div>
        </div>
    @endif



    <div class="row">
        {{-- Columna Izquierda: Información del Paciente --}}
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
                             src="{{ asset($paciente->foto) }}" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <div class="text-center mb-3">
                        <h4 class="font-weight-bold text-primary mb-1">{{ $paciente->nombre_completo }}</h4>
                        <p class="text-muted mb-1">
                            <i class="fas fa-briefcase"></i> {{ $paciente->des_cargo }}
                        </p>
                        <span class="badge badge-primary badge-lg">
                            <i class="fas fa-hashtag"></i> Ficha: {{ $paciente->cod_emp }}
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
                                    @if($paciente->sexo == 'M')
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
                                        <strong>{{ \Carbon\Carbon::parse($paciente->fecha_nac)->age }} años</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar text-info fa-lg mr-2"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Fecha de Nacimiento</div>
                                        <strong>{{ \Carbon\Carbon::parse($paciente->fecha_nac)->format('d/m/Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-door-open text-success fa-lg mr-2"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Fecha de Ingreso</div>
                                        <strong>
                                            {{ \Carbon\Carbon::parse($paciente->fecha_ing)->format('d/m/Y') }}
                                            <span class="badge badge-success ml-1">
                                                {{ \Carbon\Carbon::parse($paciente->fecha_ing)->diffForHumans(null, true) }}
                                            </span>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-wheelchair text-{{ $paciente->discapacitado ? 'warning' : 'secondary' }} fa-lg mr-2"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Discapacidad</div>
                                        @if($paciente->discapacitado)
                                            <strong class="text-warning">Sí - {{ $paciente->tipo_discapac }}</strong>
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
                                        <strong>{{ $paciente->telefono ?? 'N/A'  }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-ambulance text-danger fa-lg mr-2"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.7rem;">En caso de emergencia llamar a:</div>
                                        <strong>{{ $paciente->avisar_a ?? 'N/A' }} - {{ $paciente->telf_contact ?? 'N/A'}}</strong>
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
                                        {{ $paciente->peso_inicial ?? 'N/A' }} <small>kg</small>
                                    </strong>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="text-center p-2 bg-light rounded">
                                    <i class="fas fa-ruler-vertical text-info fa-2x mb-1"></i>
                                    <div class="text-muted small">Estatura</div>
                                    <strong class="h6 text-info">
                                        {{ $paciente->estatura ?? 'N/A' }} <small>cm</small>
                                    </strong>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="text-center p-2 bg-light rounded">
                                    <i class="fas fa-tint text-danger fa-2x mb-1"></i>
                                    <div class="text-muted small">Tipo de Sangre</div>
                                    <strong class="h6 text-danger">
                                        {{ $paciente->tipo_sangre ?? 'N/A' }}
                                    </strong>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="text-center p-2 bg-light rounded">
                                    <i class="fas fa-hand-paper text-warning fa-2x mb-1"></i>
                                    <div class="text-muted small">Lateralidad</div>
                                    <strong class="h6 text-{{ $paciente->es_zurdo ? 'warning' : 'secondary' }}">
                                        {{ $paciente->es_zurdo ? 'Zurdo' : 'Diestro' }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        @if($paciente->peso_inicial && $paciente->estatura)
                        @php
                            $imc = round($paciente->peso_inicial / (($paciente->estatura / 100) ** 2), 1);
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
                    @if($paciente->alergias || $paciente->enfermedades_base)
                    <div class="border-top pt-3 mt-3">
                        <h6 class="font-weight-bold text-danger mb-3">
                            <i class="fas fa-exclamation-triangle"></i> Alertas Médicas
                        </h6>
                        @if($paciente->alergias)
                        <div class="alert alert-warning border-left-warning mb-2">
                            <strong class="small d-block mb-1">
                                <i class="fas fa-allergies"></i> Alergias:
                            </strong>
                            <span class="small">{{ $paciente->alergias }}</span>
                        </div>
                        @endif
                        @if($paciente->enfermedades_base)
                        <div class="alert alert-danger border-left-danger mb-0">
                            <strong class="small d-block mb-1">
                                <i class="fas fa-heartbeat"></i> Patologías:
                            </strong>
                            <span class="small">{{ $paciente->enfermedades_base }}</span>
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
                                <button class="btn btn-primary btn-block btn-sm shadow-sm btnEdit" data-id="{{ $paciente->id }}">
                                    <i class="fas fa-user-edit"></i> Editar Ficha Médica
                                </button>
                            </div>
                            <div class="col-12 mb-2">
                                <a href="{{ route('medicina.consultas.historial', $paciente->id) }}" class="btn btn-info btn-block btn-sm shadow-sm">
                                    <i class="fas fa-history"></i> Ver Historial Completo
                                </a>
                            </div>
                            <div class="col-12 mb-2">
                                <a href="{{ route('medicina.pacientes.show', $paciente->id) }}" class="btn btn-success btn-block btn-sm shadow-sm">
                                    <i class="fas fa-eye"></i> Ver Perfil Completo
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="{{ route('medicina.accidentes.create', $paciente->id) }}" class="btn btn-danger btn-block btn-sm shadow-sm">
                                    <i class="fas fa-ambulance"></i> Registrar Accidente
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Historial Reciente --}}
            <div class="card shadow-lg border-0 info-card">
                <div class="card-header bg-gradient-secondary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-history"></i> Últimas Consultas
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($historial as $h)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge badge-primary">
                                    <i class="fas fa-calendar"></i> {{ $h->created_at->format('d/m/Y') }}
                                </span>
                                <span class="badge badge-secondary">{{ $h->motivo_consulta }}</span>
                            </div>
                            <div class="small text-muted mb-2">
                                <strong>Diagnóstico:</strong> {{ Str::limit($h->diagnostico_cie10, 50) }}
                            </div>
                            <a href="{{ route('medicina.consultas.show', $h->id) }}" class="btn btn-sm btn-outline-primary btn-block">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </a>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-2 d-block"></i>
                            <p class="mb-0">Sin consultas previas</p>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Formulario de Consulta --}}
        <div class="col-xl-8 col-lg-7">
            <form action="{{ route('medicina.consultas.store') }}" method="POST" id="formConsulta">
                @csrf
                <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                <input type="hidden" name="accidente_id" value="{{ $accidente ? $accidente->id : '' }}">

                {{-- Card Principal del Formulario --}}
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header bg-gradient-success text-white py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="m-0 font-weight-bold">
                                    <i class="fas fa-file-medical"></i> Registro de Atención Médica
                                </h5>
                            </div>
                            <div class="col-auto">
                                <div class="form-group mb-0">
                                    <label class="text-white small mb-1">
                                        <i class="fas fa-calendar-alt"></i> Fecha de Consulta
                                    </label>
                                    <input type="date" 
                                           name="fecha_consulta" 
                                           class="form-control form-control-sm" 
                                           value="{{ date('Y-m-d') }}" 
                                           max="{{ date('Y-m-d') }}"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        {{-- Motivo y Diagnóstico --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="font-weight-bold">
                                    <i class="fas fa-question-circle text-primary"></i> Motivo de Atención
                                </label>
                                <select class="form-control form-control-lg border-left-primary" name="motivo_consulta" id="motivo_consulta" required>
                                    <option value="">Seleccione el motivo...</option>
                                     <option value="Enfermedad Común">Enfermedad Común</option>
                                    <option>Accidente Laboral</option>
                                    <option value="Control-interno">Control Médico Interno</option>
                                    <option value="Evaluación Ocupacional">Evaluación Ocupacional (Pre-empleo/Egreso)</option>
                                    <option value="Pre-vacacional" >Evaluación Pre-vacacional</option>
                                    <option value="Post-vacacional" {{ old('motivo_consulta', $motivo_prellenado) == 'Post-vacacional' ? 'selected' : '' }}>Evaluación Post-vacacional</option>
                                    <option value="reincorporacion" {{ old('motivo_consulta', $motivo_prellenado) == 'reincorporacion' ? 'selected' : '' }}>Reincorporación Post-Reposo</option>
                                </select>
                            </div>

                            <div class="col-md-6" id="div_retorno_vacaciones" style="display:none;">
                                <label class="font-weight-bold text-info">
                                    <i class="fas fa-plane-departure"></i> Fecha Estimada de Retorno
                                </label>
                                <input type="date" class="form-control form-control-lg border-left-info" name="fecha_retorno_vacaciones">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Para alerta post-vacacional
                                </small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-10">
                                <label class="font-weight-bold">
                                    <i class="fas fa-diagnoses text-danger"></i> Diagnóstico (CIE-10) | 
                                    <button id="btn-cie10" class="btn btn-primary btn-circle btn-sm"><i class="fas fa-search"></i></button>
                                </label>
                                <select name="diagnostico_cie10" id="diagnostico_cie10" class="form-control form-control-lg" required>
                                    <option value="">Escriba al menos 3 caracteres para buscar...</option>
                                </select>
                                

                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-lightbulb text-warning"></i> 
                                        <strong>Sugerencias:</strong> Use códigos Z00-Z10 para chequeos de rutina o evaluaciones pre-vacacionales.
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Signos Vitales --}}
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-header bg-gradient-info text-white py-2">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-heartbeat"></i> Signos Vitales
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="small font-weight-bold">
                                            <i class="fas fa-heart text-danger"></i> Tensión Arterial
                                        </label>
                                        <input type="text" class="form-control" name="tension_arterial" placeholder="120/80 mmHg">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small font-weight-bold">
                                            <i class="fas fa-heartbeat text-primary"></i> Frec. Cardíaca
                                        </label>
                                        <input type="number" class="form-control" name="frecuencia_cardiaca" placeholder="70 bpm">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small font-weight-bold">
                                            <i class="fas fa-thermometer-half text-warning"></i> Temperatura
                                        </label>
                                        <input type="number" step="0.1" class="form-control" name="temperatura" placeholder="36.5 °C">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small font-weight-bold">
                                            <i class="fas fa-lungs text-info"></i> Sat. O₂
                                        </label>
                                        <input type="number" class="form-control" name="saturacion_oxigeno" placeholder="98 %">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BARRA DE HERRAMIENTAS / PLANTILLAS RÁPIDAS --}}
                        <div class="d-flex justify-content-end mb-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle shadow-sm" type="button" id="dropdownPlantillas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-magic mr-1"></i> Usar Plantilla Rápida
                                </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownPlantillas">
                                        <h6 class="dropdown-header">Seleccione un escenario:</h6>
                                        <a class="dropdown-item template-trigger" href="#" data-tipo="sano">
                                            <i class="fas fa-user-check text-success mr-2"></i> Paciente Sano / Rutina
                                        </a>
                                         <a class="dropdown-item template-trigger" href="#" data-tipo="hipertension">
                                            <i class="fas fa-heartbeat text-danger mr-2"></i> Control Hipertensión
                                        </a>
                                        <h6 class="dropdown-header">Reincorporaciones / Aptitud:</h6>
                                        <a class="dropdown-item template-trigger" href="#" data-tipo="postvacacional">
                                            <i class="fas fa-plane-arrival text-info mr-2"></i> Post-Vacacional (Apto)
                                        </a>
                                        <a class="dropdown-item template-trigger" href="#" data-tipo="postreposo">
                                            <i class="fas fa-user-clock text-primary mr-2"></i> Post-Reposo (Recuperado)
                                        </a>
                                        <a class="dropdown-item template-trigger" href="#" data-tipo="preempleo">
                                            <i class="fas fa-user-check text-success mr-2"></i> Pre-Empleo / Ingreso
                                        </a>
                                        
                                        <div class="dropdown-divider"></div>
                                        <h6 class="dropdown-header">Eventos / Patologías:</h6>
                                        <a class="dropdown-item template-trigger" href="#" data-tipo="accidente">
                                            <i class="fas fa-user-injured text-danger mr-2"></i> Accidente Laboral (Plantilla)
                                        </a>
                                        <a class="dropdown-item template-trigger" href="#" data-tipo="respiratorio">
                                            <i class="fas fa-head-side-cough text-warning mr-2"></i> Cuadro Respiratorio
                                        </a>
                                        <a class="dropdown-item template-trigger" href="#" data-tipo="lumbar">
                                            <i class="fas fa-procedures text-secondary mr-2"></i> Lumbago Mecánico
                                        </a>

                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item template-trigger" href="#" data-tipo="limpiar">
                                            <i class="fas fa-eraser text-muted mr-2"></i> Limpiar Campos
                                        </a>
                                    </div>
                            </div>
                        </div>
                        {{-- Fin Barra de Herramientas --}}

                        {{-- Anamnesis --}}
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">
                                <i class="fas fa-comments text-info"></i> Anamnesis (Relato del Paciente y Antecedentes)
                            </label>
                            <textarea class="form-control border-left-info" name="anamnesis" rows="4" required 
                                      placeholder="¿Qué refiere el paciente? ¿Desde cuándo? ¿Antecedentes relevantes?">
                                          {{ old('anamnesis', isset($motivo_prellenado) ? 'Valoración para reincorporación laboral (' . strtoupper($motivo_prellenado) . ').
RELATO DEL PACIENTE:
-Paciente refiere encontrarse asintomático al momento de la evaluación. 
-Manifiesta haber completado su periodo de recuperación [o tratamiento] sin complicaciones. 
-Expresa sentirse en óptimas condiciones físicas y mentales para retomar sus funciones habituales.

ANTECEDENTES:
- Personales: Niega nuevas patologías o alergias recientes.
- Familiares: Sin cambios relevantes.
- Hábitos: Sueño reparador y alimentación adecuada.' : '') }}
                                      </textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Describa los síntomas, duración, intensidad y antecedentes.
                            </small>
                        </div>

                        {{-- Examen Físico --}}
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">
                                <i class="fas fa-stethoscope text-primary"></i> Examen Físico / Hallazgos Clínicos
                            </label>
                            <textarea class="form-control border-left-primary" name="examen_fisico" rows="3" required
                                      placeholder="Describa los hallazgos del examen físico...">
                                          {{ old('examen_fisico', isset($motivo_prellenado) ? 'EXAMEN FÍSICO:
- Estado General: Paciente alerta, orientado en tiempo, espacio y persona (LOTEP). Hidratado y afebril.
- Signos Vitales: Dentro de parámetros normales.
- Cabeza y Cuello: Normocéfalo, sin adenopatías.
- Cardiopulmonar: Ruidos cardiacos rítmicos sin soplos. Campos pulmonares bien ventilados.
- Abdomen: Blando, depresible, no doloroso a la palpación, sin masas.
- Extremidades: Simétricas, eutróficas, con arcos de movilidad completos.
- Neurológico: Sin déficit aparente. Marcha normal.

HALLAZGOS: Examen físico dentro de la normalidad. No se evidencian limitaciones funcionales para su labor.' . strtolower($motivo_prellenado) . '' : '') }}
                                      </textarea>
                        </div>

                        {{-- Plan de Tratamiento --}}
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">
                                <i class="fas fa-prescription text-success"></i> Plan de Tratamiento / Indicaciones Médicas
                            </label>
                            <textarea class="form-control border-left-success" name="plan_tratamiento" rows="4" required 
                                      placeholder="Medicamentos (nombre, dosis, vía, frecuencia), recomendaciones, cuidados...">
                                          {{ isset($motivo_prellenado) ? 'RECOMENDACIONES Y CUIDADOS:
- Mantener una hidratación adecuada (mínimo 2 litros de agua al día).
- Higiene postural: Mantener posturas ergonómicas y ajustar la estación de trabajo.
- Pausas Activas: Cumplir estrictamente con los ejercicios de estiramiento cada 2-3 horas.
- Adaptación: Reincorporación progresiva a las funciones habituales evitando sobreesfuerzos.
- Seguridad: Uso continuo de EPP y cumplimiento de normas de seguridad industrial.
- Reporte: Notificar cualquier cambio en su estado de salud al departamento médico.

PLAN DE TRATAMIENTO:
- No requiere medicación actual. 
- Continuar con hábitos de vida saludable y actividad física regular.
- Control médico preventivo en 6 meses (Si Aplica).' : '' }}
                                      </textarea>
                            <small class="text-muted">
                                <i class="fas fa-pills"></i> Especifique claramente medicamentos, dosis y duración del tratamiento.
                            </small>
                        </div>

                        {{-- Aptitud y Reposo --}}
                        <div class="card border-warning mb-4" id="div_aptitud">
                            <div class="card-header bg-warning text-white py-2">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fas fa-user-check"></i> Aptitud Laboral y Reposo
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-clipboard-check"></i> Aptitud Laboral Post-Consulta
                                        </label>
                                        <select class="form-control form-control-lg border-left-warning" name="aptitud">
                                            <option value="Apto">✅ Apto - Reincorporación Inmediata</option>
                                            <option value="Apto con Restricción">⚠️ Apto con Restricciones Temporales</option>
                                            <option value="No Apto">❌ No Apto - Requiere Reposo / Traslado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-bed"></i> ¿Genera Reposo?
                                        </label>
                                        <select class="form-control form-control-lg" name="genera_reposo" id="genera_reposo">
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3" id="div_dias" style="display:none;">
                                        <label class="font-weight-bold text-danger">
                                            <i class="fas fa-calendar-times"></i> Días de Reposo
                                        </label>
                                        <input type="number" class="form-control form-control-lg border-left-danger" name="dias_reposo" value="0" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sección de Switch para Exámenes --}}
                        <div class="card-body border-top bg-light py-3">
                            <div class="row align-items-center">
                                <div class="col-md-12 text-right">
                                    <div class="custom-control custom-switch custom-switch-lg">
                                        <input type="checkbox" class="custom-control-input" name="requiere_examenes" id="switchExamenes" value="1">
                                        <label class="custom-control-label font-weight-bold text-primary" for="switchExamenes" style="cursor: pointer;">
                                            <i class="fas fa-microscope mr-1"></i> ¿Solicitar Órdenes de Exámenes?
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Al activar, el sistema le redirigirá para seleccionar los laboratorios después de guardar.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer con Botones --}}
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('medicina.pacientes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success btn-lg shadow" id="btnFinalizar">
                                <i class="fas fa-save"></i> Finalizar y Guardar Consulta
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de Edición (mantenido como estaba) --}}
<div class="modal fade" id="modalPaciente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-md"></i> Ficha Médica: <span id="nombrePacienteTitle" class="font-weight-bold"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPaciente">
                @csrf
                <input type="hidden" id="paciente_id" name="id">
                <div class="modal-body">
                    <ul class="nav nav-pills nav-fill mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-bio-tab" data-toggle="pill" href="#tab-bio">
                                <i class="fas fa-heartbeat fa-lg"></i><br>
                                <strong>Biometría</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-med-tab" data-toggle="pill" href="#tab-med">
                                <i class="fas fa-pills fa-lg"></i><br>
                                <strong>Médicos</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-talla-tab" data-toggle="pill" href="#tab-talla">
                                <i class="fas fa-tshirt fa-lg"></i><br>
                                <strong>Tallas y EPP</strong>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-bio">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-tint text-danger"></i> Tipo de Sangre
                                        </label>
                                        <select class="form-control form-control-lg" name="tipo_sangre" id="tipo_sangre">
                                            <option value="">Seleccione...</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-weight text-primary"></i> Peso (Kg)
                                        </label>
                                        <input type="number" step="0.1" class="form-control form-control-lg" name="peso_inicial" id="peso_inicial">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-ruler-vertical text-info"></i> Estatura (Cm)
                                        </label>
                                        <input type="number" class="form-control form-control-lg" name="estatura" id="estatura">
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <div class="custom-control custom-switch custom-control-lg">
                                    <input type="checkbox" class="custom-control-input" id="es_zurdo" name="es_zurdo">
                                    <label class="custom-control-label font-weight-bold" for="es_zurdo">
                                        <i class="fas fa-hand-paper text-warning"></i> ¿Es Zurdo/a?
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-med">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="fas fa-allergies text-warning"></i> Alergias Conocidas
                                </label>
                                <textarea class="form-control" name="alergias" id="alergias" rows="3" 
                                          placeholder="Ej: Penicilina, polen, mariscos..."></textarea>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Especifique cualquier alergia conocida
                                </small>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="fas fa-file-medical text-danger"></i> Enfermedades de Base / Patologías
                                </label>
                                <textarea class="form-control" name="enfermedades_base" id="enfermedades_base" rows="3"
                                          placeholder="Ej: Diabetes, hipertensión, asma..."></textarea>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Condiciones médicas crónicas o relevantes
                                </small>
                            </div>
                            
                            <div class="alert alert-info">
                                <div class="custom-control custom-switch custom-control-lg mb-3">
                                    <input type="checkbox" class="custom-control-input" id="discapacitado" name="discapacitado">
                                    <label class="custom-control-label font-weight-bold" for="discapacitado">
                                        <i class="fas fa-wheelchair text-info"></i> ¿Tiene alguna discapacidad?
                                    </label>
                                </div>
                                
                                <div id="campo_tipo_discapacidad" style="display: none;">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-clipboard-list text-warning"></i> Tipo de Discapacidad
                                    </label>
                                    <input type="text" class="form-control" name="tipo_discapac" id="tipo_discapac" 
                                           placeholder="Ej: Visual, Auditiva, Motora...">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-talla">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-shirt text-primary"></i> Talla Camisa
                                        </label>
                                        <input type="text" class="form-control form-control-lg" name="talla_camisa" id="talla_camisa" placeholder="M, L, XL">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-user-tie text-info"></i> Talla Pantalón
                                        </label>
                                        <input type="text" class="form-control form-control-lg" name="talla_pantalon" id="talla_pantalon" placeholder="32, 34">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-socks text-success"></i> Calzado
                                        </label>
                                        <input type="text" class="form-control form-control-lg" name="talla_calzado" id="talla_calzado" placeholder="42, 43">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Select2 mejorado
    $('#diagnostico_cie10').select2({
        theme: 'default',
        placeholder: 'Escriba al menos 3 caracteres para buscar diagnóstico...',
        allowClear: true,
        language: {
            inputTooShort: function () {
                return 'Por favor ingrese 3 o más caracteres';
            },
            searching: function () {
                return 'Buscando diagnósticos...';
            },
            noResults: function () {
                return 'No se encontraron diagnósticos';
            }
        },
        ajax: {
            url: "{{ route('medicina.buscarCie10') }}",
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        },
        minimumInputLength: 3
    });

    // Mostrar/Ocultar días de reposo
    $('#genera_reposo').change(function() {
        if($(this).val() == '1') {
            $('#div_dias').slideDown();
            $('input[name="dias_reposo"]').attr('min', '1').val('1');
        } else {
            $('#div_dias').slideUp();
            $('input[name="dias_reposo"]').attr('min', '0').val('0');
        }
    });

    // Mostrar campo de retorno de vacaciones
    $('#motivo_consulta').change(function() {
        if($(this).val() === 'Pre-vacacional') {
            $('#div_retorno_vacaciones').slideDown();
            $('#div_aptitud').slideUp();
            $('input[name="fecha_retorno_vacaciones"]').attr('required', true);
        } else {
            $('#div_retorno_vacaciones').slideUp();
            $('#div_aptitud').slideDown();
            $('input[name="fecha_retorno_vacaciones"]').attr('required', false).val('');
        }
    });

    // Confirmación antes de guardar
    $('#formConsulta').submit(function(e) {
        e.preventDefault();
        
        // Validar que se haya seleccionado un diagnóstico
        if(!$('#diagnostico_cie10').val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Diagnóstico Requerido',
                text: 'Por favor seleccione un diagnóstico CIE-10 antes de continuar.',
            });
            return false;
        }
        
        Swal.fire({
            title: '¿Finalizar Consulta?',
            html: '<p>Se guardará el registro médico y se cerrará la atención del paciente.</p><p class="text-muted small">Verifique que toda la información esté correcta.</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#858796',
            confirmButtonText: '<i class="fas fa-check"></i> Sí, Finalizar',
            cancelButtonText: '<i class="fas fa-times"></i> Revisar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return true;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // Modal - Abrir y Cargar Datos
    $(document).on('click', '.btnEdit', function() {
        let id = $(this).data('id');
        
        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.get('/medicina/pacientes/'+id+'/edit', function(data) {
            Swal.close();
            
            $('#paciente_id').val(data.id);
            $('#nombrePacienteTitle').text(data.nombre_completo);
            $('#tipo_sangre').val(data.tipo_sangre);
            $('#peso_inicial').val(data.peso_inicial);
            $('#estatura').val(data.estatura);
            $('#alergias').val(data.alergias);
            $('#enfermedades_base').val(data.enfermedades_base);
            $('#talla_camisa').val(data.talla_camisa);
            $('#talla_pantalon').val(data.talla_pantalon);
            $('#talla_calzado').val(data.talla_calzado);
            $('#es_zurdo').prop('checked', data.es_zurdo == 1);
            $('#discapacitado').prop('checked', data.discapacitado == 1);
            $('#tipo_discapac').val(data.tipo_discapac);
            
            if(data.discapacitado == 1) {
                $('#campo_tipo_discapacidad').show();
            }
            
            $('#modalPaciente').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar la información'
            });
        });
    });

    // Guardar por AJAX
    $('#formPaciente').on('submit', function(e) {
        e.preventDefault();
        let id = $('#paciente_id').val();
        let formData = $(this).serialize();

        Swal.fire({
            title: 'Guardando...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `/medicina/pacientes/${id}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                $('#modalPaciente').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Guardado!',
                    text: 'Ficha médica actualizada',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo guardar'
                });
            }
        });
    });

    // Toggle discapacidad
    $('#discapacitado').change(function() {
        if($(this).is(':checked')) {
            $('#campo_tipo_discapacidad').slideDown();
        } else {
            $('#campo_tipo_discapacidad').slideUp();
            $('#tipo_discapac').val('');
        }
    });



    $('#btn-cie10').on('click', function(e) {
        e.preventDefault();

        // 1. Loading inicial
        Swal.fire({
            title: 'Cargando Catálogo...',
            html: '<i class="fas fa-spinner fa-spin fa-2x text-primary"></i>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        // 2. Llamada AJAX con jQuery
        $.ajax({
            url: "{{ route('medicina.buscarCie10') }}",
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Construcción de la tabla
                let tablaHtml = `
                    <div class="table-responsive" style="max-height: 450px;">
                        <table class="table table-sm table-hover table-bordered text-left">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 25%">Código</th>
                                    <th>Diagnóstico / Descripción</th>
                                </tr>
                            </thead>
                            <tbody>`;

                $.each(data, function(index, item) {
                    // Separamos el código del texto para que no sea redundante
                    let descripcion = item.text.includes(' - ') ? item.text.split(' - ')[1] : item.text;
                    
                    tablaHtml += `
                        <tr>
                            <td class="align-middle">
                                <code class="h6 text-primary font-weight-bold">${item.id}</code>
                            </td>
                            <td class="small align-middle text-dark">
                                ${descripcion}
                            </td>
                        </tr>`;
                });

                tablaHtml += `</tbody></table></div>`;

                // 3. Mostrar el resultado
                Swal.fire({
                    title: '<i class="fas fa-search-plus text-info"></i> Referencia CIE-10',
                    html: tablaHtml,
                    width: '800px',
                    confirmButtonText: '<i class="fas fa-times"></i> Cerrar',
                    confirmButtonColor: '#6e707e',
                    customClass: {
                        popup: 'animated fadeInDown faster'
                    }
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo obtener el listado de diagnósticos.'
                });
                console.error("Error CIE-10:", xhr.responseText);
            }
        });
    });

    // --- LÓGICA DE PLANTILLAS RÁPIDAS ---
    
    // 1. Definición de los textos (Aquí puedes editar lo que dicen las plantillas)
    const templates = {
        // --- COMUNES ---
            'sano': {
                anamnesis: "PACIENTE ASINTOMÁTICO.\n\nRefiere sentirse bien, niega sintomatología actual.\nNiega antecedentes patológicos recientes de importancia.\nAcude para evaluación de rutina / control.",
                examen: "PACIENTE EN BUENAS CONDICIONES GENERALES.\n\n- LOTEP (Lúcido, Orientado en Tiempo, Espacio y Persona).\n- Mucosas húmedas y normocoloreadas.\n- Cardiopulmonar: Ruidos rítmicos, murmullo vesicular conservado, sin agregados.\n- Abdomen: Blando, depresible, no doloroso.\n- Extremidades: Sin edemas, movilidad conservada.",
                plan: "- No requiere tratamiento farmacológico.\n- Se dan recomendaciones de estilos de vida saludable.\n- Control según cronograma."
            },
            'hipertension': {
                anamnesis: "PACIENTE CON ANTECEDENTE DE HTA.\n\nAcude a control rutinario.\nRefiere adherencia al tratamiento: SÍ/NO.\nNiega cefalea, tinnitius (zumbidos), fosfenos (luces) o dolor torácico actual.",
                examen: "- Ruidos cardiacos rítmicos, buena intensidad.\n- Pulsos periféricos presentes y simétricos.\n- Sin edemas en miembros inferiores.\n- Cifras tensionales actuales: [Ver Signos Vitales].",
                plan: "- Continuar tratamiento habitual.\n- Dieta baja en sodio (sal).\n- Control de tensión arterial diario por 1 semana y traer registro.\n- Próxima cita en 1 mes."
            },
            // --- REINCORPORACIONES ---
            'postvacacional': {
                anamnesis: "EVALUACIÓN POST-VACACIONAL.\n\nPaciente acude para chequeo médico obligatorio tras reintegro de período vacacional.\nRefiere haber disfrutado su descanso sin eventualidades médicas.\nNiega sintomatología actual, accidentes o enfermedades durante su ausencia.\nSe siente en condiciones óptimas para retomar sus labores.",
                examen: "PACIENTE EN BUENAS CONDICIONES GENERALES.\n\n- Signos vitales estables (Ver registro).\n- Examen físico segmentario sin hallazgos patológicos.\n- Peso y talla acordes.\n- No se evidencian limitaciones funcionales.",
                plan: "- APTO para retomar sus funciones habituales.\n- Se indican medidas preventivas de seguridad y salud en el trabajo.\n- Mantener hidratación y pausas activas."
            },
            'postreposo': {
                anamnesis: "EVALUACIÓN POST-REPOSO MÉDICO.\n\nPaciente se reincorpora tras ___ días de reposo por diagnóstico de [DIAGNOSTICO PREVIO].\nRefiere mejoría clínica total.\nNiega dolor o limitación funcional actual.\nTrae informe médico de alta (si aplica): SÍ/NO.",
                examen: "EVALUACIÓN DE CONTROL:\n\n- Zona afectada: Sin signos de inflamación, edema o rubor.\n- Movilidad: Arcos de movimiento completos y no dolorosos.\n- Cicatrización (si hubo herida): En buen estado, bordes afrontados.\n- Resto del examen físico dentro de límites normales.",
                plan: "- APTO para reincorporación laboral.\n- Se recomienda reintegro progresivo a cargas (si aplica).\n- Notificar al servicio médico ante reaparición de síntomas."
            },
            'preempleo': {
                anamnesis: "EVALUACIÓN MÉDICA PRE-EMPLEO.\n\nPaciente acude para valoración de ingreso al cargo de: [CARGO].\nAntecedentes Personales: Niega patologías crónicas.\nAntecedentes Quirúrgicos: Niega.\nAlergias: Niega.\nHábitos: Niega tabaquismo.",
                examen: "- Paciente mesomorfo, hidratado.\n- Cardiopulmonar: Estable.\n- Abdomen: Sin visceromegalias.\n- Osteomuscular: Tono y trofismo conservado. Spine/Columna alineada.\n- Pruebas funcionales: Phalen (-), Tinel (-).",
                plan: "- APTO PARA EL CARGO.\n- Se instruye sobre riesgos laborales específicos del puesto.\n- Uso obligatorio de EPP."
            },

            // --- ACCIDENTES Y PATOLOGÍAS ---
            'accidente': {
                anamnesis: "REPORTE DE ACCIDENTE LABORAL.\n\nFecha y Hora del evento: [HOY] a las [HORA].\nLugar: [AREA DE TRABAJO].\nMecanismo: [GOLPE / CAÍDA / CORTE / SOBREESFUERZO].\n\nRelato: Paciente refiere que se encontraba realizando [ACTIVIDAD] cuando [DESCRIPCIÓN DEL HECHO].\nSíntoma principal: Dolor en [ZONA] intensidad __/10.",
                examen: "HALLAZGOS CLÍNICOS:\n\n- Inspección: Se observa [HEMATOMA / HERIDA / EDEMA] en región [UBICACIÓN].\n- Palpación: Dolor a la palpación en [ZONA].\n- Movilidad: [LIMITADA / CONSERVADA].\n- Neurovascular distal: Conservado.\n- Resto del examen sin particularidades.",
                plan: "- Limpieza y cura local (si aplica).\n- Analgesia: [INDICAR MEDICAMENTO].\n- Hielo local / Reposo relativo.\n- ¿Amerita Reposo?: SÍ/NO (__ días).\n- Se realiza reporte al Comité de Seguridad (CSSL)."
            },
            'respiratorio': {
                anamnesis: "SÍNTOMAS RESPIRATORIOS.\n\nInicio: Hace ___ días.\nSíntomas: Rinorrea, malestar general, tos.\nNiega disnea (falta de aire) o fiebre alta.\nNexo epidemiológico: [DESCONOCIDO / FAMILIAR / LABORAL].",
                examen: "- Orofaringe: Congestiva.\n- Cuello: Sin adenopatías.\n- Tórax: Normoexpansible.\n- Auscultación: Murmullo vesicular presente, sin agregados (No sibilantes, no crepitantes).\n- SatO2: Normal (>95%).",
                plan: "- Tratamiento sintomático (Antigripal / AINEs).\n- Abundante líquido.\n- Uso de mascarilla respiratoria.\n- Reevaluar en 48h si persiste fiebre."
            },
            'lumbar': {
                anamnesis: "LUMBAGO MECÁNICO.\n\nPaciente refiere dolor en región lumbar de aparición [SÚBITA / INSIDIOSA].\nRelacionado con: [LEVANTAMIENTO DE CARGA / MALA POSTURA].\nIrradiación: No irradia a miembros inferiores (No ciática).\nEVA (Dolor): __/10.",
                examen: "- Columna: Dolor a la palpación de musculatura paravertebral.\n- Lasègue: Negativo (bilateral).\n- Fuerza y sensibilidad: Conservada en MMII.\n- Marcha: Independiente.",
                plan: "- Analgésicos y relajante muscular por 3 días.\n- Calor local.\n- Higiene postural.\n- Evitar cargas pesadas temporalmente."
            },
            
            // --- UTILIDADES ---
            'limpiar': {
                anamnesis: "",
                examen_fisico: "", // Asegúrate que coincida con el name del textarea
                plan: ""
            }
        };

    // 2. Evento Click en las opciones
    $('.template-trigger').click(function(e) {
        e.preventDefault();
        let tipo = $(this).data('tipo');
        let data = templates[tipo];

        // Validamos si ya hay texto escrito para no borrarlo por accidente
        let currentAnamnesis = $('textarea[name="anamnesis"]').val();
        let currentExamen = $('textarea[name="examen_fisico"]').val();
        
        // Si hay texto y no es la opción de limpiar, preguntamos
        if ((currentAnamnesis.length > 10 || currentExamen.length > 10) && tipo !== 'limpiar') {
            Swal.fire({
                title: '¿Reemplazar contenido?',
                text: "Ya has escrito información. ¿Quieres reemplazarla con la plantilla o agregarla al final?",
                icon: 'question',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Reemplazar todo',
                denyButtonText: 'Agregar al final',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    aplicarPlantilla(data, true); // True = Reemplazar
                } else if (result.isDenied) {
                    aplicarPlantilla(data, false); // False = Concatenar
                }
            });
        } else {
            // Si está vacío o es limpiar, aplicamos directo
            aplicarPlantilla(data, true);
        }
    });

    // 3. Función auxiliar para inyectar el texto
    function aplicarPlantilla(data, reemplazar) {
        if (reemplazar) {
            $('textarea[name="anamnesis"]').val(data.anamnesis);
            $('textarea[name="examen_fisico"]').val(data.examen);
            $('textarea[name="plan_tratamiento"]').val(data.plan);
        } else {
            // Agregamos dos saltos de línea antes de pegar lo nuevo
            let sep = "\n\n--- NOTA ADICIONAL ---\n";
            $('textarea[name="anamnesis"]').val($('textarea[name="anamnesis"]').val() + sep + data.anamnesis);
            $('textarea[name="examen_fisico"]').val($('textarea[name="examen_fisico"]').val() + sep + data.examen);
            $('textarea[name="plan_tratamiento"]').val($('textarea[name="plan_tratamiento"]').val() + sep + data.plan);
        }

        // Efecto visual sutil para indicar que cambió
        $('textarea').addClass('bg-light');
        setTimeout(() => {
            $('textarea').removeClass('bg-light');
        }, 300);
        
        // Notificación Toast pequeña
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
        Toast.fire({
            icon: 'success',
            title: 'Plantilla aplicada correctamente'
        });
    }

    // Comportamiento del Switch "Necesita exámenes" (Versión jQuery)
    const $switchExamenes = $('#switchExamenes');
    const $btnFinalizar = $('#btnFinalizar');

    // Configuración del Toast (si ya la definiste arriba para las plantillas, puedes reusarla)
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    $switchExamenes.on('change', function() {
        if ($(this).is(':checked')) {
            // Cambio de estética a modo "Orden"
            $btnFinalizar
                .html('<i class="fas fa-file-medical-alt mr-1"></i> Guardar y Crear Orden')
                .removeClass('btn-success')
                .addClass('btn-primary'); // Azul para diferenciar

            Toast.fire({
                icon: 'info',
                title: 'Modo: Generación de Orden activado'
            });
        } else {
            // Regreso al modo "Guardado Normal"
            $btnFinalizar
                .html('<i class="fas fa-save mr-1"></i> Finalizar y Guardar Consulta')
                .removeClass('btn-primary')
                .addClass('btn-success');
        }
    });


    //AutoSelect del Cie10
    $('#motivo_consulta').on('change', function() {
        let motivo = $(this).val();
        let cie10Select = $('#diagnostico_cie10'); 

        if (motivo === 'Post-vacacional' || motivo === 'Pre-vacacional' || motivo === 'reincorporacion') {
            // Forzamos el Z02.7
            if (cie10Select.find("option[value='Z02.7']").length) {
                cie10Select.val('Z02.7').trigger('change');
            } else {
                // Si no existe en el DOM, lo agregamos dinámicamente
                let newOption = new Option('Z02.7 - Expedición de certificado médico (Vacaciones/Aptitud)', 'Z02.7', true, true);
                cie10Select.append(newOption).trigger('change');
            }
            
            // Opcional: Bloquearlo para que no lo cambien por error
               // cie10Select.prop('disabled', true); 
        }else if (motivo === 'Control-interno') {
            // Forzamos el Z00.0
            if (cie10Select.find("option[value='Z00.0']").length) {
                cie10Select.val('Z00.0').trigger('change');
            } else {
                // Si no existe en el DOM, lo agregamos dinámicamente
                let newOption = new Option('Z00.0 - Examen médico general (Chequeo de rutina)', 'Z00.0', true, true);
                cie10Select.append(newOption).trigger('change');
            }
            
            // Opcional: Bloquearlo para que no lo cambien por error
               // cie10Select.prop('disabled', true); 
        } 

         else {
            // Si cambia a otro motivo, lo desbloqueamos y limpiamos
            cie10Select.prop('disabled', false).val(null).trigger('change');
        }
    });

    // Ejecutar al cargar por si ya viene prellenado desde la URL
    $('#motivo_consulta').trigger('change');

});
</script>

@endsection