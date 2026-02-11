@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Header con info del paciente --}}
    <div class="row mb-4">
        <div class="col-xl-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary py-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img class="img-profile rounded-circle border border-white border-3" 
                                 src="{{ asset($paciente->foto) }}" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="col">
                            <h2 class="h3 mb-1 text-white font-weight-bold">{{ $paciente->nombre_completo }}</h2>
                            <div class="text-white-50">
                                <i class="fas fa-id-card"></i> CI: {{ $paciente->ci }} | 
                                <i class="fas fa-briefcase"></i> {{ $paciente->des_cargo }} | 
                                <i class="fas fa-map-marker-alt"></i> {{ $paciente->des_depart }}
                            </div>
                        </div>
                        <div class="col-auto">
                            @if($paciente->status = 'A')
                                <span class="badge badge-success badge-lg px-3 py-2">
                                    <i class="fas fa-check-circle"></i> ACTIVO
                                </span>
                            @else
                                <span class="badge badge-secondary badge-lg px-3 py-2">
                                    <i class="fas fa-times-circle"></i> LIQUIDADO
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="dropdown d-inline-block mr-2">
                                <button class="btn btn-primary dropdown-toggle shadow-sm" type="button" data-toggle="dropdown">
                                    <i class="fas fa-plus-circle"></i> Acción Rápida
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('medicina.consultas.create', ['paciente_id' => $paciente->id]) }}">
                                        <i class="fas fa-notes-medical text-primary mr-2"></i> Nueva Consulta
                                    </a>
                                    <a class="dropdown-item" href="{{ route('medicina.accidentes.create', $paciente->id) }}">
                                        <i class="fas fa-ambulance text-danger mr-2"></i> Registrar Accidente
                                    </a>
                                    <a class="dropdown-item" href="{{ route('medicina.dotaciones.create', $paciente->id) }}">
                                        <i class="fas fa-tshirt text-success mr-2"></i> Nueva Dotación
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('medicina.consultas.historial', $paciente->id) }}">
                                        <i class="fas fa-history text-info mr-2"></i> Ver Historial Médico
                                    </a>
                                    <button class="dropdown-item btnEdit" data-id="{{ $paciente->id }}">
                                        <i class="fas fa-user-edit text-warning mr-2"></i> Editar Datos Médicos
                                    </button>
                                    <a class="dropdown-item" href="{{ route('medicina.pacientes.index') }}">
                                        <i class="fas fa-list text-secondary mr-2"></i> Lista de Pacientes
                                    </a>
                                </div>
                            </div>

                            <div class="dropdown d-inline-block">
                                <button class="btn btn-danger dropdown-toggle shadow-sm" type="button" data-toggle="dropdown">
                                    <i class="fas fa-file-pdf"></i> Certificados
                                </button>
                                <div class="dropdown-menu">
                                    <div class="dropdown-header">Certificados Médicos:</div>
                                    <a class="dropdown-item" href="{{ route('medicina.pdf.aptitud', $paciente->id) }}" target="_blank">
                                        <i class="fas fa-person-circle-check text-warning mr-2"></i> Certificado de Aptitud
                                    </a>
                                    <a class="dropdown-item" href="{{ route('medicina.pdf.constancia', $paciente->id) }}" target="_blank">
                                        <i class="fas fa-person-walking-arrow-right text-info mr-2"></i> Constancia de Asistencia
                                    </a>
                                    <a class="dropdown-item" href="{{ route('medicina.pdf.historial', $paciente->id) }}" target="_blank">
                                        <i class="fas fa-virus text-danger mr-2"></i> Historial Epidemiológico
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <div class="dropdown-header">Certificados SSL:</div>
                                    <a class="dropdown-item" href="{{ route('medicina.pdf.epp', $paciente->id) }}" target="_blank">
                                        <i class="fas fa-user-tag text-info mr-2"></i> Entrega de EPP
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjetas de Estadísticas --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Consultas Realizadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_consultas'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-stethoscope fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Días sin Accidente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['dias_desde_accidente'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Última Dotación</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['ultima_dotacion'] ? $stats['ultima_dotacion']->created_at->format('d/m/Y') : 'Sin registros' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box-open fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Antigüedad</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @php
                                    $antiguedad = \Carbon\Carbon::parse($paciente->fecha_ing)->diffForHumans(null, true);
                                @endphp
                                {{ $antiguedad }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Información Personal y Biométrica --}}
    <div class="row">
        {{-- Datos Personales --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-info">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-user-circle"></i> Datos Personales
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="border-left border-info pl-3">
                                <small class="text-muted text-uppercase d-block">Sexo</small>
                                <strong class="text-gray-800">
                                    @if($paciente->sexo == 'M')
                                        <i class="fas fa-mars text-primary"></i> Masculino
                                    @else
                                        <i class="fas fa-venus text-danger"></i> Femenino
                                    @endif
                                </strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border-left border-info pl-3">
                                <small class="text-muted text-uppercase d-block">Edad</small>
                                <strong class="text-gray-800">
                                    <i class="fas fa-birthday-cake text-purple"></i> 
                                    @php
                                        $edad = \Carbon\Carbon::parse($paciente->fecha_nac)->age;
                                    @endphp
                                    {{ $edad }} años
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="border-left border-info pl-3">
                                <small class="text-muted text-uppercase d-block">Fecha de Nacimiento</small>
                                <strong class="text-gray-800">
                                    <i class="fas fa-calendar text-info"></i> 
                                    {{ \Carbon\Carbon::parse($paciente->fecha_nac)->format('d/m/Y') }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="border-left border-success pl-3">
                                <small class="text-muted text-uppercase d-block">Fecha de Ingreso</small>
                                <strong class="text-gray-800">
                                    <i class="fas fa-door-open text-success"></i> 
                                    {{ \Carbon\Carbon::parse($paciente->fecha_ing)->format('d/m/Y') }}
                                    <span class="badge badge-success ml-2">{{ $antiguedad }}</span>
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="border-left border-{{ $paciente->discapacitado ? 'warning' : 'secondary' }} pl-3">
                                <small class="text-muted text-uppercase d-block">Discapacidad</small>
                                @if($paciente->discapacitado)
                                    <strong class="text-warning">
                                        <i class="fas fa-wheelchair"></i> Sí
                                        @if($paciente->tipo_discapac)
                                            <span class="badge badge-warning">{{ $paciente->tipo_discapac }}</span>
                                        @endif
                                    </strong>
                                @else
                                    <strong class="text-muted">
                                        <i class="fas fa-times-circle"></i> No
                                    </strong>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Datos Biométricos --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-success">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-heartbeat"></i> Datos Biométricos
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-weight fa-2x text-primary mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Peso</div>
                                <div class="h4 mb-0 font-weight-bold text-primary">
                                    {{ $paciente->peso_inicial ?? 'N/A' }} <small>kg</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-ruler-vertical fa-2x text-info mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Estatura</div>
                                <div class="h4 mb-0 font-weight-bold text-info">
                                    {{ $paciente->estatura ?? 'N/A' }} <small>cm</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-tint fa-2x text-danger mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Tipo de Sangre</div>
                                <div class="h4 mb-0 font-weight-bold text-danger">
                                    {{ $paciente->tipo_sangre ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-hand-paper fa-2x text-warning mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Lateralidad</div>
                                <div class="h4 mb-0 font-weight-bold text-{{ $paciente->es_zurdo ? 'warning' : 'secondary' }}">
                                    {{ $paciente->es_zurdo ? 'Zurdo' : 'Diestro' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($paciente->peso_inicial && $paciente->estatura)
                    <div class="row">
                        <div class="col-12">
                            <div class="border-left border-success pl-3">
                                <small class="text-muted text-uppercase d-block">IMC (Índice de Masa Corporal)</small>
                                @php
                                    $imc = round($paciente->peso_inicial / (($paciente->estatura / 100) ** 2), 1);
                                    $imcClass = 'success';
                                    if ($imc < 18.5) $imcClass = 'warning';
                                    elseif ($imc >= 25 && $imc < 30) $imcClass = 'info';
                                    elseif ($imc >= 30) $imcClass = 'danger';
                                @endphp
                                <strong class="text-{{ $imcClass }}">
                                    <i class="fas fa-chart-line"></i> {{ $imc }}
                                    <span class="badge badge-{{ $imcClass }} ml-2">
                                        @if($imc < 18.5) Bajo Peso
                                        @elseif($imc < 25) Normal
                                        @elseif($imc < 30) Sobrepeso
                                        @else Obesidad
                                        @endif
                                    </span>
                                </strong>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Información Médica y Tallas --}}
    <div class="row">
        {{-- Historial Médico --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-danger">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-hospital"></i> Historial Médico
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="border-left border-danger pl-3">
                            <small class="text-muted text-uppercase d-block mb-2">
                                <i class="fas fa-allergies"></i> Alergias Conocidas
                            </small>
                            @if($paciente->alergias)
                                <div class="alert alert-warning mb-0" role="alert">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $paciente->alergias }}
                                </div>
                            @else
                                <p class="text-muted mb-0"><i>No registra alergias</i></p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="border-left border-danger pl-3">
                            <small class="text-muted text-uppercase d-block mb-2">
                                <i class="fas fa-file-medical"></i> Enfermedades de Base / Patologías
                            </small>
                            @if($paciente->enfermedades_base)
                                <div class="alert alert-danger mb-0" role="alert">
                                    <i class="fas fa-heartbeat"></i> {{ $paciente->enfermedades_base }}
                                </div>
                            @else
                                <p class="text-muted mb-0"><i>Sin patologías registradas</i></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tallas y EPP --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-warning">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-tshirt"></i> Tallas de EPP
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>
                                <i class="fas fa-shirt text-primary"></i> Camisa
                            </span>
                            <strong class="badge badge-primary badge-pill">{{ $paciente->talla_camisa ?? 'N/A' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>
                                <i class="fas fa-user-tie text-info"></i> Pantalón
                            </span>
                            <strong class="badge badge-info badge-pill">{{ $paciente->talla_pantalon ?? 'N/A' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>
                                <i class="fas fa-socks text-success"></i> Calzado
                            </span>
                            <strong class="badge badge-success badge-pill">{{ $paciente->talla_calzado ?? 'N/A' }}</strong>
                        </li>
                    </ul>

                    <div class="mt-3 text-center">
                        <button class="btn btn-warning btn-sm btnEdit" data-id="{{ $paciente->id }}">
                            <i class="fas fa-edit"></i> Actualizar Tallas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Historial de Consultas y Accidentes --}}
    <div class="row">
        {{-- Consultas Recientes --}}
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-primary">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-stethoscope"></i> Consultas Recientes
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($paciente->consultas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0"><i class="fas fa-calendar"></i> Fecha</th>
                                        <th class="border-0"><i class="fas fa-diagnoses"></i> Diagnóstico</th>
                                        <th class="border-0 text-center"><i class="fas fa-cog"></i> Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paciente->consultas->take(5) as $con)
                                    <tr>
                                        <td class="align-middle">
                                            <small class="text-muted">{{ $con->created_at->format('d/m/Y') }}</small>
                                        </td>
                                        <td class="align-middle">
                                            <small>{{ Str::limit($con->diagnostico_cie10, 40) }}</small>
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('medicina.consultas.show', $con->id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($paciente->consultas->count() > 5)
                        <div class="card-footer bg-light text-center">
                            <a href="{{ route('medicina.consultas.historial', $paciente->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-history"></i> Ver Historial Completo ({{ $paciente->consultas->count() }})
                            </a>
                        </div>
                        @endif
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No hay consultas registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Accidentes Registrados --}}
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-danger">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-exclamation-triangle"></i> Accidentes Registrados
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($paciente->accidentes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0"><i class="fas fa-calendar"></i> Fecha</th>
                                        <th class="border-0"><i class="fas fa-ambulance"></i> Tipo de Evento</th>
                                        <th class="border-0 text-center"><i class="fas fa-cog"></i> Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paciente->accidentes->take(5) as $acc)
                                    <tr>
                                        <td class="align-middle">
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($acc->fecha_hora_accidente)->format('d/m/Y') }}</small>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge badge-danger">{{ $acc->tipo_evento }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('medicina.accidentes.show', $acc->id) }}" 
                                               class="btn btn-sm btn-outline-danger" 
                                               title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($paciente->accidentes->count() > 5)
                        <div class="card-footer bg-light text-center">
                            <a href="{{ route('medicina.consultas.historial', $paciente->id) }}" class="btn btn-sm btn-danger">
                                <i class="fas fa-list"></i> Ver Todos los Accidentes ({{ $paciente->accidentes->count() }})
                            </a>
                        </div>
                        @endif
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-shield-alt fa-3x mb-3 text-success"></i>
                            <p class="text-success font-weight-bold">Sin accidentes registrados</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Edición --}}
    <div class="modal fade" id="modalPaciente" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-md"></i> Ficha Médica: <span id="nombrePacienteTitle"></span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formPaciente">
                    @csrf
                    <input type="hidden" id="paciente_id" name="id">
                    <div class="modal-body">
                        <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-bio-tab" data-toggle="pill" href="#tab-bio">
                                    <i class="fas fa-heartbeat"></i> Biometría
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-med-tab" data-toggle="pill" href="#tab-med">
                                    <i class="fas fa-pills"></i> Médicos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-talla-tab" data-toggle="pill" href="#tab-talla">
                                    <i class="fas fa-tshirt"></i> Tallas y EPP
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-bio">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><i class="fas fa-tint text-danger"></i> Tipo de Sangre</label>
                                            <select class="form-control" name="tipo_sangre" id="tipo_sangre">
                                                <option value="">Seleccione...</option>
                                                <option value="O+">O+</option><option value="O-">O-</option>
                                                <option value="A+">A+</option><option value="A-">A-</option>
                                                <option value="B+">B+</option><option value="B-">B-</option>
                                                <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><i class="fas fa-weight text-primary"></i> Peso (Kg)</label>
                                            <input type="number" step="0.1" class="form-control" name="peso_inicial" id="peso_inicial">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><i class="fas fa-ruler-vertical text-info"></i> Estatura (Cm)</label>
                                            <input type="number" class="form-control" name="estatura" id="estatura">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="es_zurdo" name="es_zurdo">
                                        <label class="custom-control-label" for="es_zurdo">
                                            <i class="fas fa-hand-paper text-warning"></i> ¿Es Zurdo?
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-med">
                                <div class="form-group">
                                    <label><i class="fas fa-allergies text-warning"></i> Alergias Conocidas</label>
                                    <textarea class="form-control" name="alergias" id="alergias" rows="3" 
                                              placeholder="Ej: Penicilina, polen, mariscos..."></textarea>
                                    <small class="form-text text-muted">Especifique cualquier alergia conocida</small>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-file-medical text-danger"></i> Enfermedades de Base / Patologías</label>
                                    <textarea class="form-control" name="enfermedades_base" id="enfermedades_base" rows="3"
                                              placeholder="Ej: Diabetes, hipertensión, asma..."></textarea>
                                    <small class="form-text text-muted">Indique condiciones médicas crónicas o relevantes</small>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-talla">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><i class="fas fa-shirt text-primary"></i> Talla Camisa</label>
                                            <input type="text" class="form-control" name="talla_camisa" id="talla_camisa" placeholder="Ej: M, L, XL">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><i class="fas fa-user-tie text-info"></i> Talla Pantalón</label>
                                            <input type="text" class="form-control" name="talla_pantalon" id="talla_pantalon" placeholder="Ej: 32, 34">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><i class="fas fa-socks text-success"></i> Calzado</label>
                                            <input type="text" class="form-control" name="talla_calzado" id="talla_calzado" placeholder="Ej: 42, 43">
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle"></i> <strong>Nota:</strong> Estas tallas son necesarias para la entrega de EPP (Equipo de Protección Personal)
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Abrir Modal y Cargar Datos
    $(document).on('click', '.btnEdit', function() {
        let id = $(this).data('id');
        $.get('/medicina/pacientes/'+id+'/edit', function(data) {
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
            
            $('#modalPaciente').modal('show');
        });
    });

    // Guardar por AJAX
    $('#formPaciente').on('submit', function(e) {
        e.preventDefault();
        let id = $('#paciente_id').val();
        let formData = $(this).serialize();

        $.ajax({
            url: `/medicina/pacientes/${id}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                $('#modalPaciente').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Guardado!',
                    text: 'La ficha médica ha sido actualizada correctamente.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    location.reload(); // Recargar para mostrar datos actualizados
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al guardar los datos. Intente nuevamente.'
                });
            }
        });
    });
});
</script>
@endsection