@extends('layouts.app')

@section('content')
{{-- Mostrar mensajes de sesión --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<h1 class="h3 mb-4 text-gray-800">Histórico de Mantenimientos Correctivos</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Listado de Todos los Eventos de Falla</h6>
    </div>

    <div class="card-body">
            {{-- Mensajes de Notificación --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Activo Afectado</th>
                        <th>Reportado</th>
                        <th>Cierre</th>
                        <th>Tiempo Parada (Hrs)</th>
                        <th>Costo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mantenimientos as $mantenimiento)
                    <tr>
                        <td>{{ $mantenimiento->id }}</td>
                        <td><a href="{{ route('produccion.pozos.activos.show', $mantenimiento->activo) }}">{{ $mantenimiento->activo->nombre }}</a></td>
                        <td>{{ $mantenimiento->fecha_falla_reportada->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($mantenimiento->fecha_reinicio_operacion)
                                <span class="text-success">{{ $mantenimiento->fecha_reinicio_operacion->format('d/m/Y H:i') }}</span>
                            @else
                                <span class="text-warning">PENDIENTE</span>
                            @endif
                        </td>
                        <td>{{ $mantenimiento->tiempo_parada_horas ?? 'N/A' }}</td>
                        <td>${{ number_format($mantenimiento->costo_asociado ?? 0, 2) }}</td>
                        <td>
                            @if($mantenimiento->fecha_reinicio_operacion)
                                <span class="badge badge-success">CERRADO</span>
                            @else
                                <span class="badge badge-danger">ABIERTO</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('produccion.pozos.mantenimientos.show', $mantenimiento) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection