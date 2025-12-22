@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detalle de Investigación de Accidente</h1>
        <div>
            <a href="{{ route('medicina.accidentes.inpsasel', $accidente->id) }}" class="btn btn-danger btn-sm shadow-sm">
                <i class="fas fa-file-pdf"></i> Generar Reporte Legal
            </a>
            <a href="{{ route('medicina.pacientes.show', $accidente->paciente_id) }}" class="btn btn-secondary btn-sm shadow-sm">
                Volver al Paciente
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Lesionado</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $accidente->paciente->nombre_completo }}</div>
                            <div class="small text-muted">Cédula: {{ $accidente->paciente->ci }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-injured fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-gray-100 py-3 font-weight-bold text-primary">Información del Evento</div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 border-right">
                            <label class="small text-muted d-block">Fecha y Hora</label>
                            <strong>{{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('d/m/Y h:i A') }}</strong>
                        </div>
                        <div class="col-md-4 border-right">
                            <label class="small text-muted d-block">Tipo de Evento</label>
                            <span class="badge badge-{{ $accidente->tipo_evento == 'Accidente' ? 'danger' : 'warning' }}">
                                {{ $accidente->tipo_evento }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted d-block">Lugar Exacto</label>
                            <strong>{{ $accidente->lugar_exacto }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Relato de lo Ocurrido</h6>
                </div>
                <div class="card-body">
                    <p class="text-justify border-bottom pb-3">{{ $accidente->descripcion_relato }}</p>
                    <label class="font-weight-bold small">Lesión Detallada:</label>
                    <p class="text-danger">{{ $accidente->lesion_detallada }}</p>
                    <label class="font-weight-bold small">Testigos:</label>
                    <p class="text-muted">{{ $accidente->testigos ?? 'No se reportaron testigos' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-dark text-white">
                    <h6 class="m-0 font-weight-bold">Análisis Técnico y Cierre</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="font-weight-bold small text-warning text-uppercase">Causas Inmediatas / Raíz</label>
                        <p class="small bg-light p-2 rounded">{{ $accidente->causas_inmediatas }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold small text-success text-uppercase">Acciones Correctivas Tomadas</label>
                        <p class="small bg-light p-2 border-left-success rounded">{{ $accidente->acciones_correctivas }}</p>
                    </div>
                    <hr>
                    <div class="text-right small">
                        Investigador: {{ $accidente->user->name." ".$accidente->user->last_name }}<br>
                        Fecha de Investigación: {{ $accidente->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection