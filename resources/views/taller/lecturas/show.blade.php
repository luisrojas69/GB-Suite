@extends('layouts.app')

@section('title', 'Historial de Lecturas: ' . $activo->codigo)

@section('content')

<div class="container-fluid">
        {{-- Mostrar mensajes de sesión --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

    {{-- Encabezado y Resumen del Activo --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history"></i> Historial de Lecturas para: **{{ $activo->codigo }}**
            </h5>
            <a href="{{ route('activos.index', $activo->id) }}" class="btn btn-sm btn-info">
                <i class="fas fa-arrow-left"></i> Volver al Activo
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Nombre:</strong> {{ $activo->nombre }}</p>
                    <p><strong>Tipo:</strong> {{ $activo->tipo }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Unidad de Medida:</strong> <span class="badge badge-secondary">{{ $activo->unidad_medida }}</span></p>
                    <p><strong>Última Lectura Registrada:</strong> 
                        <span class="text-success font-weight-bold">{{ number_format($activo->lectura_actual) }} {{ $activo->unidad_medida }}</span>
                    </p>
                </div>
                <div class="col-md-4">
                    <p><strong>Departamento Asignado:</strong> {{ $activo->departamento_asignado }}</p>
                    <p><strong>Estado Operativo:</strong> {{ $activo->estado_operativo }}</p>
                </div>
            </div>
            <hr>
            <a href="{{ route('lecturas.create', ['activo' => $activo->id]) }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus-circle"></i> Registrar Nueva Lectura
            </a>
        </div>
    </div>

    {{-- Tabla de Historial de Lecturas --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-secondary">Registros Históricos (Total: {{ $lecturas->total() }})</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha Lectura</th>
                            <th>Valor Registrado</th>
                            <th>Registrado Por</th>
                            <th>Observaciones</th>
                            <th>Registro Creado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lecturas as $lectura)
                            <tr>
                                <td>{{ $lectura->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($lectura->fecha_lectura)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="font-weight-bold">
                                        {{ number_format($lectura->valor_lectura) }} {{ $activo->unidad_medida }}
                                    </span>
                                </td>
                                <td>{{ $lectura->registrador->name." ".$lectura->registrador->last_name ?? 'N/A' }}</td>
                                <td>{{ $lectura->observaciones ?? 'Sin Observaciones' }}</td>
                                <td>{{ $lectura->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay registros de lectura para este activo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center">
                {{ $lecturas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection