@extends('layouts.app')

@section('styles')
<style>
    .timeline-item {
        border-left: 3px solid #4e73df;
        padding-left: 20px;
        padding-bottom: 20px;
        position: relative;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 0;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        background: #4e73df;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #4e73df;
    }
    .timeline-item:last-child {
        border-left-color: transparent;
    }
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
    }
    .nav-pills .nav-link {
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }
    .nav-pills .nav-link:hover {
        transform: translateY(-2px);
    }
    .nav-pills .nav-link.active {
        box-shadow: 0 0.5rem 1rem rgba(78, 115, 223, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Header Principal --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body bg-gradient-primary text-white py-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <img class="rounded-circle border border-white border-3 shadow" 
                         src="{{ asset($paciente->foto) }}" 
                         style="width: 100px; height: 100px; object-fit: cover;">
                </div>
                <div class="col">
                    <h1 class="h2 mb-1 font-weight-bold text-white">
                        <i class="fas fa-history"></i> Historial Médico Completo
                    </h1>
                    <h3 class="h4 mb-2 text-white-50">{{ $paciente->nombre_completo }}</h3>
                    <div class="text-white-75">
                        <span class="badge badge-light text-primary mr-2">
                            <i class="fas fa-id-card"></i> CI: {{ $paciente->ci }}
                        </span>
                        <span class="badge badge-light text-primary mr-2">
                            <i class="fas fa-briefcase"></i> {{ $paciente->des_cargo }}
                        </span>
                        <span class="badge badge-light text-primary">
                            <i class="fas fa-building"></i> {{ $paciente->des_depart }}
                        </span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-light btn-lg dropdown-toggle shadow" type="button" data-toggle="dropdown">
                            <i class="fas fa-bolt"></i> Acciones Rápidas
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow-lg animated--fade-in">
                            <div class="dropdown-header bg-gradient-primary text-white">
                                <i class="fas fa-stethoscope"></i> Atención Médica
                            </div>
                            <a class="dropdown-item" href="{{ route('medicina.consultas.create', ['paciente_id' => $paciente->id]) }}">
                                <i class="fas fa-notes-medical text-primary mr-2"></i> Nueva Consulta
                            </a>
                            <a class="dropdown-item" href="{{ route('medicina.accidentes.create', $paciente->id) }}">
                                <i class="fas fa-ambulance text-danger mr-2"></i> Registrar Accidente
                            </a>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-header bg-gradient-success text-white">
                                <i class="fas fa-cog"></i> Gestión
                            </div>
                            <a class="dropdown-item" href="{{ route('medicina.dotaciones.create', $paciente->id) }}">
                                <i class="fas fa-tshirt text-success mr-2"></i> Nueva Dotación EPP
                            </a>
                            <button class="dropdown-item btnEdit" data-id="{{ $paciente->id }}">
                                <i class="fas fa-user-edit text-warning mr-2"></i> Editar Ficha Médica
                            </button>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('medicina.pacientes.show', $paciente->id) }}">
                                <i class="fas fa-user text-info mr-2"></i> Ver Perfil Completo
                            </a>
                            <a class="dropdown-item" href="{{ route('medicina.pacientes.index') }}">
                                <i class="fas fa-list text-secondary mr-2"></i> Lista de Pacientes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Columna Izquierda: Info del Paciente --}}
        <div class="col-xl-3 col-lg-4 mb-4">
            {{-- Datos Biométricos --}}
            <div class="card shadow-lg border-0 mb-4 stat-card">
                <div class="card-header bg-gradient-success text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-heartbeat"></i> Datos Biométricos
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-weight text-primary fa-lg mb-1"></i>
                                <div class="text-muted small">Peso</div>
                                <strong class="h6 text-primary">
                                    {{ $paciente->peso_inicial ?? 'N/A' }} <small>kg</small>
                                </strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-ruler-vertical text-info fa-lg mb-1"></i>
                                <div class="text-muted small">Estatura</div>
                                <strong class="h6 text-info">
                                    {{ $paciente->estatura ?? 'N/A' }} <small>cm</small>
                                </strong>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-tint text-danger fa-lg mb-1"></i>
                                <div class="text-muted small">Sangre</div>
                                <strong class="h6 text-danger">
                                    {{ $paciente->tipo_sangre ?? 'N/A' }}
                                </strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-birthday-cake text-purple fa-lg mb-1"></i>
                                <div class="text-muted small">Edad</div>
                                <strong class="h6 text-purple">
                                    {{ \Carbon\Carbon::parse($paciente->fecha_nac)->age }} años
                                </strong>
                            </div>
                        </div>
                    </div>
                    @if($paciente->peso_inicial && $paciente->estatura)
                    @php
                        $imc = round($paciente->peso_inicial / (($paciente->estatura / 100) ** 2), 1);
                        $imcClass = $imc < 18.5 ? 'warning' : ($imc < 25 ? 'success' : ($imc < 30 ? 'info' : 'danger'));
                        $imcText = $imc < 18.5 ? 'Bajo Peso' : ($imc < 25 ? 'Normal' : ($imc < 30 ? 'Sobrepeso' : 'Obesidad'));
                    @endphp
                    <div class="alert alert-{{ $imcClass }} mb-0 mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="d-block font-weight-bold">IMC</small>
                                <strong class="h5 mb-0">{{ $imc }}</strong>
                            </div>
                            <span class="badge badge-{{ $imcClass }}">{{ $imcText }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Estadísticas del Historial --}}
            <div class="card shadow-lg border-0 mb-4 stat-card">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-line"></i> Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-notes-medical text-primary fa-lg"></i>
                                <strong class="ml-2">Consultas</strong>
                            </div>
                            <h4 class="mb-0 text-primary">{{ $paciente->consultas->count() }}</h4>
                        </div>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-ambulance text-danger fa-lg"></i>
                                <strong class="ml-2">Accidentes</strong>
                            </div>
                            <h4 class="mb-0 text-danger">{{ $paciente->accidentes->count() }}</h4>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-tshirt text-success fa-lg"></i>
                                <strong class="ml-2">Dotaciones</strong>
                            </div>
                            <h4 class="mb-0 text-success">{{ $paciente->dotaciones->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alertas Médicas --}}
            @if($paciente->alergias || $paciente->enfermedades_base)
            <div class="card shadow-lg border-0 mb-4 stat-card">
                <div class="card-header bg-gradient-danger text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle"></i> Alertas Médicas
                    </h6>
                </div>
                <div class="card-body">
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
            </div>
            @endif
        </div>

        {{-- Columna Derecha: Historial --}}
        <div class="col-xl-9 col-lg-8">
            {{-- Tabs de Navegación --}}
            <ul class="nav nav-pills nav-fill mb-4" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active shadow-sm" id="consultas-tab" data-toggle="pill" href="#consultas" role="tab">
                        <i class="fas fa-notes-medical fa-lg d-block mb-1"></i>
                        <strong>Consultas Médicas</strong>
                        <span class="badge badge-info ml-2">{{ $paciente->consultas->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link shadow-sm" id="accidentes-tab" data-toggle="pill" href="#accidentes" role="tab">
                        <i class="fas fa-user-injured fa-lg d-block mb-1"></i>
                        <strong>Accidentes/Incidentes</strong>
                        <span class="badge badge-danger ml-2">{{ $paciente->accidentes->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link shadow-sm" id="dotaciones-tab" data-toggle="pill" href="#dotaciones" role="tab">
                        <i class="fas fa-tshirt fa-lg d-block mb-1"></i>
                        <strong>Historial EPP</strong>
                        <span class="badge badge-success ml-2">{{ $paciente->dotaciones->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link shadow-sm" id="archivos-tab" data-toggle="pill" href="#archivos" role="tab">
                        <i class="fas fa-file-medical fa-lg d-block mb-1"></i>
                        <strong>Expediente Digital</strong>
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                
                {{-- TAB: Consultas Médicas --}}
                <div class="tab-pane fade show active" id="consultas" role="tabpanel">
                    @forelse($paciente->consultas as $c)
                    <div class="card shadow-sm mb-3 border-left-primary">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="icon-circle bg-primary text-white" style="width: 50px; height: 50px;">
                                        <i class="fas fa-stethoscope fa-lg"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Fecha</small>
                                            <strong class="text-primary">{{ $c->created_at->format('d/m/Y') }}</strong>
                                            <div class="small text-muted">{{ $c->created_at->format('h:i A') }}</div>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Motivo</small>
                                            <span class="badge badge-info">{{ $c->motivo_consulta }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted d-block">Diagnóstico</small>
                                            <strong>{{ Str::limit($c->diagnostico_cie10, 40) }}</strong>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            @if($c->genera_reposo)
                                            <span class="badge badge-danger">
                                                <i class="fas fa-bed"></i> {{ $c->dias_reposo }}d
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-user-md"></i> Dr. {{ $c->medico->name." ".$c->medico->last_name ?? 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('medicina.consultas.show', $c->id) }}" 
                                       class="btn btn-primary btn-sm shadow-sm">
                                        <i class="fas fa-eye"></i> Ver Detalle
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay consultas registradas</h5>
                            <p class="text-muted">El historial de consultas médicas aparecerá aquí</p>
                            <a href="{{ route('medicina.consultas.create', ['paciente_id' => $paciente->id]) }}" 
                               class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> Registrar Primera Consulta
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>

                {{-- TAB: Accidentes --}}
                <div class="tab-pane fade" id="accidentes" role="tabpanel">
                    @forelse($paciente->accidentes as $acc)
                    <div class="card shadow-sm mb-3 border-left-danger">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="icon-circle bg-danger text-white" style="width: 50px; height: 50px;">
                                        <i class="fas fa-ambulance fa-lg"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Fecha del Evento</small>
                                            <strong class="text-danger">
                                                {{ \Carbon\Carbon::parse($acc->fecha_hora_accidente)->format('d/m/Y') }}
                                            </strong>
                                            <div class="small text-muted">
                                                {{ \Carbon\Carbon::parse($acc->fecha_hora_accidente)->format('h:i A') }}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Clasificación</small>
                                            <span class="badge badge-danger">{{ $acc->tipo_evento }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted d-block">Lugar</small>
                                            <strong>{{ $acc->lugar_exacto }}</strong>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            @if(isset($acc->gravedad))
                                            @php
                                                $gravedadColor = $acc->gravedad == 'Leve' ? 'success' : ($acc->gravedad == 'Grave' ? 'warning' : 'danger');
                                            @endphp
                                            <span class="badge badge-{{ $gravedadColor }}">
                                                {{ $acc->gravedad }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-band-aid"></i> {{ Str::limit($acc->lesion_detallada ?? 'Sin detalles', 60) }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('medicina.accidentes.show', $acc->id) }}" 
                                       class="btn btn-danger btn-sm shadow-sm">
                                        <i class="fas fa-eye"></i> Ver Detalle
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-shield-alt fa-4x text-success mb-3"></i>
                            <h5 class="text-success">Sin accidentes registrados</h5>
                            <p class="text-muted">Excelente registro de seguridad</p>
                            <a href="{{ route('medicina.accidentes.create', ['paciente_id' => $paciente->id]) }}" 
                               class="btn btn-danger mt-2">
                                <i class="fas fa-plus"></i> Registrar Primer Accidente
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>

                {{-- TAB: Dotaciones EPP --}}
                <div class="tab-pane fade" id="dotaciones" role="tabpanel">
                    @forelse($paciente->dotaciones as $d)
                    <div class="card shadow-sm mb-3 border-left-success">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="icon-circle bg-success text-white" style="width: 50px; height: 50px;">
                                        <i class="fas fa-tshirt fa-lg"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Fecha de Entrega</small>
                                            <strong class="text-success">{{ $d->created_at->format('d/m/Y') }}</strong>
                                        </div>
                                        <div class="col-md-5">
                                            <small class="text-muted d-block">Implementos Entregados</small>
                                            <div class="mt-1">
                                                @if($d->calzado_entregado)
                                                    <span class="badge badge-primary mr-1">
                                                        <i class="fas fa-shoe-prints"></i> Calzado T:{{ $d->calzado_talla }}
                                                    </span>
                                                @endif
                                                @if($d->pantalon_entregado)
                                                    <span class="badge badge-info mr-1">
                                                        <i class="fas fa-user-tie"></i> Pantalón T:{{ $d->pantalon_talla }}
                                                    </span>
                                                @endif
                                                @if($d->camisa_entregada)
                                                    <span class="badge badge-success mr-1">
                                                        <i class="fas fa-shirt"></i> Camisa T:{{ $d->camisa_talla }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted d-block">Estado</small>
                                            @if($d->entregado_en_almacen)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Entregado
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock"></i> Pendiente
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <a href="{{ route('medicina.imprimir.ticket', $d->id) }}" 
                                               class="btn btn-sm btn-outline-danger" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <a href="{{ route('medicina.dotaciones.validar', $d->qr_token) }}" 
                                               class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-qrcode"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay dotaciones registradas</h5>
                            <p class="text-muted">El historial de entregas de EPP aparecerá aquí</p>
                            <a href="{{ route('medicina.dotaciones.create', $paciente->id) }}" 
                               class="btn btn-success mt-2">
                                <i class="fas fa-plus"></i> Registrar Primera Dotación
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>

                {{-- TAB: Expediente Digital --}}
                <div class="tab-pane fade" id="archivos" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="card shadow-lg border-0">
                                <div class="card-header bg-gradient-primary text-white py-3">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas fa-cloud-upload-alt"></i> Subir Nuevo Documento
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('medicina.pacientes.subirArchivo') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                <i class="fas fa-tag"></i> Descripción
                                            </label>
                                            <input type="text" 
                                                   name="nombre_archivo" 
                                                   class="form-control" 
                                                   placeholder="Ej: Laboratorio Pre-empleo, Rx Tórax..."
                                                   required>
                                            <small class="text-muted">Nombre descriptivo del documento</small>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                <i class="fas fa-file"></i> Archivo
                                            </label>
                                            <div class="custom-file">
                                                <input type="file" 
                                                       name="archivo" 
                                                       class="custom-file-input" 
                                                       id="customFile" 
                                                       required
                                                       accept=".pdf,.jpg,.jpeg,.png">
                                                <label class="custom-file-label" for="customFile">Seleccionar archivo...</label>
                                            </div>
                                            <small class="text-muted d-block mt-1">PDF, JPG o PNG (Max 10MB)</small>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-block shadow">
                                            <i class="fas fa-upload"></i> Subir Documento
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card shadow-lg border-0">
                                <div class="card-header bg-gradient-info text-white py-3">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas fa-folder-open"></i> Documentos Almacenados
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $archivos = DB::table('med_paciente_archivos')
                                            ->where('paciente_id', $paciente->id)
                                            ->orderBy('created_at', 'desc')
                                            ->get();
                                    @endphp
                                    @forelse($archivos as $archivo)
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
                                                </div>
                                                <div class="col-auto">
                                                    <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" 
                                                       target="_blank" 
                                                       class="btn btn-info btn-sm shadow-sm">
                                                        <i class="fas fa-eye"></i> Ver/Descargar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-5">
                                        <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay documentos almacenados</h5>
                                        <p class="text-muted">Sube el primer documento usando el formulario de la izquierda</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Modal de Edición (Reutilizado) --}}
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
<script>
$(document).ready(function() {
    // Custom file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
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
});
</script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Logrado!',
            text: 'El documento se adjuntó al historial.',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    @endif
@endsection