@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Header Principal del Accidente --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-danger py-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-3x text-white"></i>
                        </div>
                        <div class="col">
                            <h2 class="h3 mb-1 text-white font-weight-bold">
                                <i class="fas fa-file-medical-alt"></i> Reporte de Accidente #{{ str_pad($accidente->id, 5, '0', STR_PAD_LEFT) }}
                            </h2>
                            <div class="text-white-50">
                                <i class="fas fa-calendar"></i> Registrado: {{ $accidente->created_at->format('d/m/Y H:i') }} | 
                                <i class="fas fa-user-md"></i> Por: {{ $accidente->user->name ?? 'Sistema' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            @php
                                $diasDesdeAccidente = \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->diffInDays(now());
                                $badgeClass = $diasDesdeAccidente < 7 ? 'danger' : ($diasDesdeAccidente < 30 ? 'warning' : 'success');
                            @endphp
                            <span class="badge badge-{{ $badgeClass }} badge-lg px-3 py-2">
                                <i class="fas fa-clock"></i> Hace {{ $diasDesdeAccidente }} días
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <img class="img-profile rounded-circle border border-danger border-3 mr-3" 
                                     src="{{ asset($accidente->paciente->foto) }}" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h5 class="mb-1 font-weight-bold text-gray-800">
                                        <a href="{{ route('medicina.pacientes.show', $accidente->paciente->id) }}" 
                                           class="text-primary">
                                            {{ $accidente->paciente->nombre_completo }}
                                        </a>
                                    </h5>
                                    <div class="text-muted small">
                                        <i class="fas fa-id-card"></i> CI: {{ $accidente->paciente->ci }} | 
                                        <i class="fas fa-briefcase"></i> {{ $accidente->paciente->des_cargo }} | 
                                        <i class="fas fa-map-marker-alt"></i> {{ $accidente->paciente->des_depart }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="btn-group" role="group">
                                <a href="{{ route('medicina.accidentes.edit', $accidente->id) }}" 
                                   class="btn btn-warning btn-sm shadow-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="{{ route('medicina.pacientes.show', $accidente->paciente->id) }}" 
                                   class="btn btn-secondary btn-sm shadow-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                                <button class="btn btn-danger btn-sm shadow-sm" onclick="confirmarEliminacion()">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjetas de Resumen --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tipo de Evento</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $accidente->tipo_evento }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Fecha del Suceso</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('d/m/Y') }}
                            </div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('h:i A') }}</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ubicación</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                {{ Str::limit($accidente->lugar_exacto, 30) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marker-alt fa-2x text-info"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Estado</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                @if($accidente->consulta_id)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Evaluado
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock"></i> Pendiente
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Consulta Vinculada --}}
    @if($accidente->consulta_id)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success shadow border-left-success">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fas fa-notes-medical fa-2x"></i>
                    </div>
                    <div class="col">
                        <h6 class="font-weight-bold mb-1">
                            <i class="fas fa-link"></i> Consulta Médica Vinculada
                        </h6>
                        <p class="mb-1">
                            Este accidente fue evaluado médicamente el {{ $accidente->consulta->created_at->format('d/m/Y H:i') }}
                        </p>
                        <div class="mt-2">
                            <strong>Diagnóstico:</strong> {{ $accidente->consulta->diagnostico_cie10 }}
                        </div>
                        @if($accidente->consulta->dias_reposo > 0)
                        <div class="mt-1">
                            <span class="badge badge-danger">
                                <i class="fas fa-bed"></i> Reposo: {{ $accidente->consulta->dias_reposo }} días
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('medicina.consultas.show', $accidente->consulta_id) }}" 
                           class="btn btn-success btn-sm shadow-sm">
                            <i class="fas fa-eye"></i> Ver Consulta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning shadow border-left-warning">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <div class="col">
                        <h6 class="font-weight-bold mb-1">Atención Médica Pendiente</h6>
                        <p class="mb-0">Este accidente aún no ha sido evaluado por el servicio médico.</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('medicina.consultas.create', ['paciente_id' => $accidente->paciente_id, 'accidente_id' => $accidente->id]) }}" 
                           class="btn btn-warning btn-sm shadow-sm">
                            <i class="fas fa-plus-circle"></i> Registrar Consulta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Detalles del Accidente --}}
    <div class="row">
        {{-- Datos del Suceso --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-danger">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-calendar-day"></i> Datos del Suceso
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="border-left border-danger pl-3">
                            <small class="text-muted text-uppercase d-block mb-1">
                                <i class="fas fa-clock"></i> Fecha y Hora del Evento
                            </small>
                            <strong class="text-gray-800 h5">
                                {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('d/m/Y') }}
                            </strong>
                            <span class="badge badge-danger ml-2">
                                {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('h:i A') }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="border-left border-warning pl-3">
                            <small class="text-muted text-uppercase d-block mb-1">
                                <i class="fas fa-map-marker-alt"></i> Lugar Exacto del Accidente
                            </small>
                            <strong class="text-gray-800">{{ $accidente->lugar_exacto }}</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="border-left border-info pl-3">
                            <small class="text-muted text-uppercase d-block mb-1">
                                <i class="fas fa-exclamation-circle"></i> Clasificación del Evento
                            </small>
                            <span class="badge badge-danger badge-lg">{{ $accidente->tipo_evento }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="border-left border-success pl-3">
                            <small class="text-muted text-uppercase d-block mb-1">
                                <i class="fas fa-calendar-check"></i> Tiempo Transcurrido
                            </small>
                            <strong class="text-gray-800">
                                {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->diffForHumans() }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Análisis de Causas --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-warning">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-microscope"></i> Análisis de Causas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="alert alert-danger mb-0">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-bolt fa-2x mr-3"></i>
                                <div>
                                    <strong class="text-uppercase small d-block mb-2">Causas Inmediatas</strong>
                                    <p class="mb-0">{{ $accidente->causas_inmediatas }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="alert alert-warning mb-0">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-search fa-2x mr-3"></i>
                                <div>
                                    <strong class="text-uppercase small d-block mb-2">Causas Raíz (Sistémicas)</strong>
                                    <p class="mb-0">{{ $accidente->causas_raiz }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Descripción y Lesiones --}}
    <div class="row">
        {{-- Relato del Accidente --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-info">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-file-alt"></i> Relato del Acontecimiento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="border-left border-info pl-3 mb-3">
                            <small class="text-muted text-uppercase d-block mb-2">
                                <i class="fas fa-comment-dots"></i> Descripción del Evento
                            </small>
                            <p class="text-gray-800 text-justify">{{ $accidente->descripcion_relato }}</p>
                        </div>
                    </div>

                    @if($accidente->testigos)
                    <div>
                        <div class="border-left border-secondary pl-3">
                            <small class="text-muted text-uppercase d-block mb-2">
                                <i class="fas fa-users"></i> Testigos Presenciales
                            </small>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0 small">{{ $accidente->testigos }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-user-slash fa-2x mb-2"></i>
                        <p class="mb-0">Sin testigos registrados</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Lesiones --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-gradient-danger">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-band-aid"></i> Lesiones y Daños
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger border-left-danger">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-heartbeat fa-3x mr-3 text-danger"></i>
                            <div>
                                <strong class="text-uppercase small d-block mb-2">
                                    <i class="fas fa-notes-medical"></i> Descripción Detallada de Lesiones
                                </strong>
                                <p class="mb-0 text-justify">{{ $accidente->lesion_detallada }}</p>
                            </div>
                        </div>
                    </div>

                    @if($accidente->consulta_id && $accidente->consulta->dias_reposo > 0)
                    <div class="text-center p-3 bg-danger text-white rounded">
                        <i class="fas fa-bed fa-2x mb-2"></i>
                        <div class="h4 mb-0">{{ $accidente->consulta->dias_reposo }} días</div>
                        <small>de reposo médico</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Plan de Acción Correctiva --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-gradient-success">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-check-double"></i> Plan de Acción Correctiva
                    </h6>
                </div>
                <div class="card-body">
                    <div class="border-left border-success pl-3">
                        <small class="text-muted text-uppercase d-block mb-2">
                            <i class="fas fa-tasks"></i> Medidas Implementadas para Prevención
                        </small>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0 text-justify">{{ $accidente->acciones_correctivas }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Información Adicional --}}
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
                                <i class="fas fa-hashtag fa-2x text-primary mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">ID del Reporte</div>
                                <div class="h5 mb-0 font-weight-bold text-primary">
                                    #{{ str_pad($accidente->id, 5, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-calendar-plus fa-2x text-success mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Fecha de Registro</div>
                                <div class="h6 mb-0 font-weight-bold text-success">
                                    {{ $accidente->created_at->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">{{ $accidente->created_at->format('h:i A') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-user-md fa-2x text-info mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Registrado Por</div>
                                <div class="h6 mb-0 font-weight-bold text-info">
                                    {{ $accidente->user->name ?? 'Sistema' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <div class="text-xs text-uppercase text-muted">Última Actualización</div>
                                <div class="h6 mb-0 font-weight-bold text-warning">
                                    {{ $accidente->updated_at->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">{{ $accidente->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Botones de Acción Flotantes --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('medicina.pacientes.show', $accidente->paciente->id) }}" 
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
                                <i class="fas fa-edit"></i> Editar Reporte
                            </a>
                            <button class="btn btn-danger shadow-sm" onclick="imprimirReporte()">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Form oculto para eliminación --}}
<form id="formEliminar" action="{{ route('medicina.accidentes.destroy', $accidente->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
function confirmarEliminacion() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará permanentemente el reporte de accidente #{{ str_pad($accidente->id, 5, '0', STR_PAD_LEFT) }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEliminar').submit();
        }
    });
}

function imprimirReporte() {
    window.print();
}

// Estilo de impresión
const style = document.createElement('style');
style.innerHTML = `
    @media print {
        .btn, .card-header, .alert-warning, .alert-info, 
        .navbar, .sidebar, footer, .no-print {
            display: none !important;
        }
        .card {
            page-break-inside: avoid;
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
        .card-body {
            padding: 15px !important;
        }
        body {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection