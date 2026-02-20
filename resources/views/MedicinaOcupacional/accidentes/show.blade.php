@extends('layouts.app')
@section('title-page', 'Detalles del Accidente # : '.$accidente->id )
@section('styles')
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
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}

@media print {
    .btn, .card-header, .alert-warning, .no-print,
    .navbar, .sidebar, footer {
        display: none !important;
    }
    .card {
        page-break-inside: avoid;
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}

    /* ========================================
       MODAL MEJORADO
    ======================================== */
    .modal-enhanced .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-enhanced .modal-header {
        background: linear-gradient(135deg, #5a5c69, #373840);
        color: white;
        padding: 20px 25px;
        border-radius: 12px 12px 0 0;
        border: none;
    }

    .modal-enhanced .modal-title {
        font-size: 18px;
        font-weight: 700;
    }

    .modal-enhanced .modal-body {
        padding: 0;
    }

    .modal-detail-section {
        padding: 25px;
    }

    .detail-section-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: #858796;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .detail-value-primary {
        font-size: 20px;
        font-weight: 700;
        color: #4e73df;
        margin-bottom: 10px;
    }

    .detail-item-modal {
        margin-bottom: 10px;
        font-size: 13px;
    }

    .detail-item-modal strong {
        color: #2c3e50;
        font-weight: 700;
    }

    .items-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .items-list li {
        padding: 12px 15px;
        background: #f8f9fc;
        border-radius: 6px;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .items-list li:last-child {
        margin-bottom: 0;
    }

    .observations-box {
        background: #f8f9fc;
        padding: 18px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .signature-box {
        text-align: center;
        padding: 20px;
        background: #f8f9fc;
        border-radius: 8px;
    }

    .signature-box img {
        max-height: 150px;
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

</style>

@endsection
@section('content')
<div class="container-fluid">
    {{-- Mensajes de sesión --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Header Principal --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-danger py-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-danger">
                                <i class="fas fa-clipboard-list fa-3x"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h1 class="h3 mb-1 text-white font-weight-bold">
                                <i class="fas fa-search"></i> Investigación de Accidente Laboral
                            </h1>
                            <p class="text-white-50 mb-0">
                                Reporte #{{ str_pad($accidente->id, 5, '0', STR_PAD_LEFT) }} | 
                                Fecha de Investigación: {{ $accidente->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('medicina.accidentes.inpsasel', $accidente->id) }}" 
                                   class="btn btn-light btn-sm shadow-sm" target="_blank">
                                    <i class="fas fa-file-pdf text-danger"></i> Reporte Legal INPSASEL
                                </a>
                                <a href="{{ route('medicina.accidentes.edit', $accidente->id) }}" 
                                   class="btn btn-warning btn-sm shadow-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="{{ route('medicina.pacientes.show', $accidente->paciente_id) }}" 
                                   class="btn btn-secondary btn-sm shadow-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerta de Consulta Médica Vinculada --}}
    @if($accidente->consulta_id)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="icon-circle bg-success text-white">
                                <i class="fas fa-stethoscope fa-2x"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="font-weight-bold text-success mb-2">
                                <i class="fas fa-link"></i> Evaluación Médica Asociada
                            </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted d-block text-uppercase">Atendido Por</small>
                                    <strong class="text-gray-800">
                                        <i class="fas fa-user-md text-info"></i> 
                                        {{ $accidente->consulta->medico->name." ".$accidente->consulta->medico->last_name }}
                                    </strong>
                                </div>
                                <div class="col-md-5">
                                    <small class="text-muted d-block text-uppercase">Diagnóstico CIE-10</small>
                                    <strong class="text-gray-800">
                                        <i class="fas fa-notes-medical text-danger"></i> 
                                        {{ $accidente->consulta->diagnostico_cie10 }}
                                    </strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block text-uppercase">Fecha de Consulta</small>
                                    <strong class="text-gray-800">
                                        <i class="fas fa-calendar text-primary"></i> 
                                        {{ $accidente->consulta->created_at->format('d/m/Y') }}
                                    </strong>
                                </div>
                            </div>
                            @if($accidente->consulta->dias_reposo > 0)
                            <div class="mt-2">
                                <span class="badge badge-danger badge-lg px-3 py-2">
                                    <i class="fas fa-bed"></i> Reposo Médico: {{ $accidente->consulta->dias_reposo }} días
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('medicina.consultas.show', $accidente->consulta_id) }}" 
                               class="btn btn-success shadow-sm">
                                <i class="fas fa-eye"></i> Ver Consulta Completa
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
                            <h6 class="font-weight-bold text-warning mb-1">Atención Médica Pendiente</h6>
                            <p class="mb-0">Este accidente aún no cuenta con evaluación médica formal.</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('medicina.consultas.create', ['paciente_id' => $accidente->paciente_id, 'accidente_id' => $accidente->id]) }}" 
                               class="btn btn-warning shadow-sm">
                                <i class="fas fa-plus-circle"></i> Registrar Consulta Médica
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Cards de Resumen --}}
    <div class="row">
        {{-- Datos del Lesionado --}}
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-left-danger shadow h-100">
                <div class="card-header py-3 bg-gradient-danger">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-user-injured"></i> Trabajador Lesionado
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="img-profile rounded-circle border border-danger border-3" 
                             src="{{ asset($accidente->paciente->foto) }}" 
                             style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                    <div class="text-center">
                        <h5 class="font-weight-bold text-gray-800 mb-1">
                            <a href="{{ route('medicina.pacientes.show', $accidente->paciente_id) }}" 
                               class="text-primary">
                                {{ $accidente->paciente->nombre_completo }}
                            </a>
                        </h5>
                        <div class="mb-2">
                            <span class="badge badge-secondary">
                                <i class="fas fa-id-card"></i> CI: {{ $accidente->paciente->ci }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="small">
                        <div class="mb-2">
                            <i class="fas fa-briefcase text-info"></i> 
                            <strong>Cargo:</strong> {{ $accidente->paciente->des_cargo }}
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-building text-success"></i> 
                            <strong>Departamento:</strong> {{ $accidente->paciente->des_depart }}
                        </div>
                        @if($accidente->paciente->tipo_sangre)
                        <div class="mb-2">
                            <i class="fas fa-tint text-danger"></i> 
                            <strong>Tipo de Sangre:</strong> 
                            <span class="badge badge-danger">{{ $accidente->paciente->tipo_sangre }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Información del Evento --}}
        <div class="col-xl-8 col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-warning">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-info-circle"></i> Información del Evento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="border-left border-primary pl-3">
                                <small class="text-muted text-uppercase d-block mb-1">
                                    <i class="fas fa-calendar-alt"></i> Fecha del Suceso
                                </small>
                                <strong class="text-gray-800 h5">
                                    {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('d/m/Y') }}
                                </strong>
                                <div class="mt-1">
                                    <span class="badge badge-primary">
                                        <i class="fas fa-clock"></i> 
                                        {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="border-left border-danger pl-3">
                                <small class="text-muted text-uppercase d-block mb-1">
                                    <i class="fas fa-exclamation-circle"></i> Tipo de Evento
                                </small>
                                <span class="badge badge-danger badge-lg px-3 py-2">
                                    {{ $accidente->tipo_evento }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="border-left border-info pl-3">
                                <small class="text-muted text-uppercase d-block mb-1">
                                    <i class="fas fa-map-marker-alt"></i> Lugar Exacto
                                </small>
                                <strong class="text-gray-800">{{ $accidente->lugar_exacto }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="border-left border-success pl-3">
                                <small class="text-muted text-uppercase d-block mb-1">
                                    <i class="fas fa-hourglass-half"></i> Tiempo Transcurrido
                                </small>
                                <strong class="text-gray-800">
                                    {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->diffForHumans() }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex justify-content-around text-center">
                                    <div>
                                        <i class="fas fa-calendar-plus fa-2x text-primary mb-2"></i>
                                        <div class="small text-muted">Registrado</div>
                                        <strong class="small">{{ $accidente->created_at->format('d/m/Y') }}</strong>
                                    </div>
                                    <div class="border-left border-right px-3">
                                        <i class="fas fa-user-secret fa-2x text-info mb-2"></i>
                                        <div class="small text-muted">Investigador</div>
                                        <strong class="small">{{ $accidente->user->name ." ".$accidente->user->last_name }}</strong>
                                    </div>
                                    <div>
                                        <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                        <div class="small text-muted">Actualizado</div>
                                        <strong class="small">{{ $accidente->updated_at->diffForHumans() }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Relato y Lesiones --}}
    <div class="row">
        {{-- Relato Detallado --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-primary">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-file-alt"></i> Relato de lo Ocurrido
                    </h6>
                </div>
                <div class="card-body">
                    {{-- Descripción del Evento --}}
                    <div class="mb-4">
                        <div class="border-left border-primary pl-3">
                            <small class="text-muted text-uppercase d-block mb-2">
                                <i class="fas fa-comment-dots"></i> Descripción del Suceso
                            </small>
                            <p class="text-gray-800 text-justify mb-0">{{ $accidente->descripcion_relato }}</p>
                        </div>
                    </div>

                    {{-- Lesión Detallada --}}
                    <div class="mb-4">
                        <div class="alert alert-danger border-left-danger mb-0">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-band-aid fa-2x mr-3 text-danger"></i>
                                <div>
                                    <strong class="text-uppercase small d-block mb-2">
                                        <i class="fas fa-notes-medical"></i> Lesión Detallada
                                    </strong>
                                    <p class="mb-0 text-justify">{{ $accidente->lesion_detallada }}</p>
                                    <hr class="sidebar-divider">
                                    <p class="mb-0 text-justify"><strong>Partes Lesionadas:</strong> 
                                        <span class="badge badge-info badge-lg px-3 py-2">
                                            {{ $accidente->parte_lesionada }}
                                        </span>
                                    </p>
                                    <hr class="sidebar-divider">
                                    <p class="mb-0 text-justify"><strong>Gravedad:</strong> 
                                        <span class="badge badge-warning badge-lg px-3 py-2">
                                            {{ $accidente->gravedad }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Testigos --}}
                    <div>
                        <div class="border-left border-secondary pl-3">
                            <small class="text-muted text-uppercase d-block mb-2">
                                <i class="fas fa-users"></i> Testigos Presenciales
                            </small>
                            @if($accidente->testigos)
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0">{{ $accidente->testigos }}</p>
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-user-slash fa-2x mb-2"></i>
                                    <p class="mb-0">No se reportaron testigos presenciales</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Análisis Técnico --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-dark">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-microscope"></i> Análisis Técnico y Cierre
                    </h6>
                </div>
                <div class="card-body">
                    {{-- Causas Inmediatas --}}
                    <div class="mb-4">
                        <div class="alert alert-danger mb-0">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-bolt fa-2x mr-3"></i>
                                <div>
                                    <strong class="text-uppercase small d-block mb-2">
                                        <i class="fas fa-exclamation-triangle"></i> Causas Inmediatas
                                    </strong>
                                    <p class="mb-0 small">{{ $accidente->causas_inmediatas }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Causas Raíz --}}
                    @if($accidente->causas_raiz)
                    <div class="mb-4">
                        <div class="alert alert-warning mb-0">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-search fa-2x mr-3"></i>
                                <div>
                                    <strong class="text-uppercase small d-block mb-2">
                                        <i class="fas fa-project-diagram"></i> Causas Raíz (Sistémicas)
                                    </strong>
                                    <p class="mb-0 small">{{ $accidente->causas_raiz }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Acciones Correctivas --}}
                    <div class="mb-4">
                        <div class="alert alert-success border-left-success mb-0">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-double fa-2x mr-3 text-success"></i>
                                <div>
                                    <strong class="text-uppercase small d-block mb-2">
                                        <i class="fas fa-tshirt"></i> Acciones Correctivas Implementadas
                                    </strong>
                                    <p class="mb-0 small">{{ $accidente->acciones_correctivas }}</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Ultima Dotacion --}}
                    <div>
                        <div class="alert alert-info border-left-info mb-0">
                            <div class="d-flex align-items-center">
                                
                                <i class="fas fa-box-open fa-2x mr-3 text-info"></i>
                                
                                <div>
                                    <strong class="text-uppercase small d-block mb-1">
                                        <i class="fas fa-tshirt"></i> Última Dotación
                                    </strong>
                                    <p class="mb-0 small">Historial de entregas del paciente</p>
                                </div>

                                @php
                                    $ultimaDotacion = \App\Models\MedicinaOcupacional\Dotacion::where('paciente_id', $accidente->paciente->id)
                                        ->where('entregado_en_almacen', true)
                                        ->latest()
                                        ->first();
                                @endphp

                                <div class="ml-auto">
                                    @if($ultimaDotacion)
                                        <button type="button" class="btn btn-sm btn-info shadow-sm btnShow" data-id="{{ $ultimaDotacion->id }}" title="Ver Ultima Dotacion">
                                            <i class="fas fa-history"></i> Ver EPP / Última Dotación
                                        </button>
                                    @else
                                        <span class="badge badge-secondary">Sin registros</span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Información del Investigador --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-gradient-secondary">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-user-shield"></i> Datos de la Investigación
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-user-md fa-2x text-primary mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Investigador</div>
                                <div class="h6 mb-0 font-weight-bold text-primary">
                                    {{ $accidente->user->name." ".$accidente->user->last_name }}
                                </div>
                                <small class="text-muted">{{ $accidente->user->email }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Fecha de Investigación</div>
                                <div class="h6 mb-0 font-weight-bold text-success">
                                    {{ $accidente->created_at->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">{{ $accidente->created_at->format('h:i A') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-edit fa-2x text-info mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Última Modificación</div>
                                <div class="h6 mb-0 font-weight-bold text-info">
                                    {{ $accidente->updated_at->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">{{ $accidente->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-hashtag fa-2x text-warning mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">N° de Reporte</div>
                                <div class="h6 mb-0 font-weight-bold text-warning">
                                    #{{ str_pad($accidente->id, 5, '0', STR_PAD_LEFT) }}
                                </div>
                                <small class="text-muted">Sistema de Registro</small>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('medicina.pacientes.show', $accidente->paciente_id) }}" 
                               class="btn btn-secondary shadow-sm">
                                <i class="fas fa-arrow-left"></i> Volver al Paciente
                            </a>
                            <a href="{{ route('medicina.accidentes.index') }}" 
                               class="btn btn-outline-secondary shadow-sm">
                                <i class="fas fa-list"></i> Lista de Accidentes
                            </a>
                        </div>
                        <div>
                            @if(!$accidente->consulta_id)
                            <a href="{{ route('medicina.consultas.create', ['paciente_id' => $accidente->paciente_id, 'accidente_id' => $accidente->id]) }}" 
                               class="btn btn-primary shadow-sm">
                                <i class="fas fa-plus-circle"></i> Registrar Evaluación Médica
                            </a>
                            @endif
                            <a href="{{ route('medicina.accidentes.edit', $accidente->id) }}" 
                               class="btn btn-warning shadow-sm">
                                <i class="fas fa-edit"></i> Editar Investigación
                            </a>
                            <a href="{{ route('medicina.accidentes.inpsasel', $accidente->id) }}" 
                               class="btn btn-danger shadow-sm" target="_blank">
                                <i class="fas fa-file-pdf"></i> Generar Reporte INPSASEL
                            </a>
                            <button class="btn btn-info shadow-sm" onclick="window.print()">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MODAL DE DETALLES -->
<div class="modal fade modal-enhanced" id="modalShowDotacion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle mr-2"></i>Detalles de la Ultima Dotación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalleContenido"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Animación de entrada suave
$(document).ready(function() {
    $('.card').hide().fadeIn(800);


// ========================================
// VER DETALLES (MODAL)
// ========================================
$(document).on('click', '.btnShow', function() {
    let id = $(this).data('id');
    $.get(`/medicina/dotaciones/${id}`, function(data) {
        let itemsHtml = '';
        if(data.co_art_calzado) itemsHtml += `<li><i class="fas fa-shoe-prints text-success mr-2"></i><strong>Calzado:</strong> ${data.co_art_calzado} (Talla: ${data.calzado_talla})</li>`;
        if(data.co_art_pantalon) itemsHtml += `<li><i class="fas fa-user-tag text-primary mr-2"></i><strong>Pantalón:</strong> ${data.co_art_pantalon} (Talla: ${data.pantalon_talla})</li>`;
        if(data.co_art_camisa) itemsHtml += `<li><i class="fas fa-tshirt text-warning mr-2"></i><strong>Camisa:</strong> ${data.co_art_camisa} (Talla: ${data.camisa_talla})</li>
             <div class="detail-item-modal"><strong>Fecha Despacho Almacén:</strong> ${data.fecha_despacho_almacen}</div>`;

        let html = `
            <div class="modal-detail-section">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-section-title">Información del Trabajador</div>
                        <div class="detail-value-primary">${data.paciente.nombre_completo}</div>
                        <div class="detail-item-modal"><strong>Cédula:</strong> ${data.paciente.ci}</div>
                        <div class="detail-item-modal"><strong>Departamento:</strong> ${data.paciente.des_depart}</div>
                        <div class="detail-item-modal"><strong>Cargo:</strong> ${data.paciente.des_cargo || 'No especificado'}</div>
                        <div class="detail-item-modal"><strong>Motivo:</strong> ${data.motivo}</div>
                        <div class="detail-item-modal"><strong>Fecha Entrega SSL:</strong> ${data.fecha_entrega}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-section-title">Items Autorizados</div>
                        <ul class="items-list">
                            ${itemsHtml || '<li>No se especificaron items</li>'}
                        </ul>
                    </div>
                </div>

                <div class="observations-box">
                    <div class="detail-section-title">Observaciones del Servicio SSL</div>
                    <p class="mb-0">${data.observaciones || 'Sin observaciones registradas.'}</p>
                </div>

                <div class="signature-box mt-3">
                    <div class="detail-section-title">Firma de Conformidad del Trabajador</div>
                    <img src="${data.firma_digital}" class="img-fluid" alt="Firma">
                </div>
            </div>
        `;
        $('#detalleContenido').html(html);
        $('#modalShowDotacion').modal('show');
    });
});

});
</script>
@endsection