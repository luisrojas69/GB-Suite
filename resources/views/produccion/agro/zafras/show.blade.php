@extends('layouts.app')
@section('title', 'Detalle de Zafra: ' . $zafra->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🔍 Detalle de Zafra: **{{ $zafra->nombre }}**</h1>
        <a href="{{ route('produccion.agro.zafras.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('ver_zafras')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Información Detallada de la Campaña</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Columna de Periodo y Estado --}}
                    <div class="col-md-6 border-right">
                        <p><strong>Nombre:</strong> {{ $zafra->nombre }}</p>
                        <p><strong>Periodo:</strong> 
                            <span class="badge badge-info">{{ $zafra->anio_inicio }} - {{ $zafra->anio_fin }}</span>
                        </p>
                        <p><strong>Estado Actual:</strong> 
                            @php
                                $badgeClass = match($zafra->estado) {
                                    'Activa' => 'badge-success',
                                    'Cerrada' => 'badge-secondary',
                                    'Planeada' => 'badge-warning',
                                    default => 'badge-info'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} badge-lg">{{ $zafra->estado }}</span>
                        </p>
                    </div>
                    
                    {{-- Columna de Fechas y Trazabilidad --}}
                    <div class="col-md-6">
                        <h6>Fechas Operacionales</h6>
                        <hr class="mt-0">
                        <p><strong>Fecha de Inicio:</strong> 
                            {{ $zafra->fecha_inicio ? \Carbon\Carbon::parse($zafra->fecha_inicio)->format('d/m/Y') : 'No Definida' }}
                        </p>
                        <p><strong>Fecha de Fin:</strong> 
                            {{ $zafra->fecha_fin ? \Carbon\Carbon::parse($zafra->fecha_fin)->format('d/m/Y') : 'No Definida' }}
                        </p>
                        <hr>
                        <p><strong>Moliendas Registradas:</strong> 
                            <span class="badge badge-primary">{{ $zafra->moliendas()->count() }}</span>
                        </p>
                    </div>
                </div>
                
                <hr>
                
                <p><strong>ID:</strong> {{ $zafra->id }}</p>
                <p><strong>Fecha de Creación:</strong> {{ $zafra->created_at->format('d/m/Y h:i A') }}</p>
                <p><strong>Última Actualización:</strong> {{ $zafra->updated_at->format('d/m/Y h:i A') }}</p>


                <div class="mt-4">
                    @can('editar_zafras')
                        <a href="{{ route('produccion.agro.zafras.edit', $zafra->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar Zafra</a>
                    @endcan
                </div>
                
            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para ver el detalle de esta zafra.</p>
    @endcan
</div>
@endsection