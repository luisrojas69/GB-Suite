@extends('layouts.app')
@section('title', 'Detalle de Destino: ' . $destino->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🔍 Detalle de Destino: **{{ $destino->nombre }}**</h1>
        <a href="{{ route('produccion.agro.destinos.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('ver_destinos')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Información Detallada</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Columna de Datos --}}
                    <div class="col-md-6 border-right">
                        <p><strong>ID:</strong> {{ $destino->id }}</p>
                        <p><strong>Nombre:</strong> {{ $destino->nombre }}</p>
                        <p><strong>Código Único:</strong> <span class="badge badge-info">{{ $destino->codigo }}</span></p>
                    </div>
                    
                    {{-- Columna de Relación --}}
                    <div class="col-md-6">
                        <h6>Estadísticas</h6>
                        <hr class="mt-0">
                        <p><strong>Moliendas Registradas:</strong> 
                            <span class="badge badge-success">{{ $destino->moliendas()->count() }}</span>
                        </p>
                        <p class="text-muted">Cantidad de arrimos de caña dirigidos a este destino.</p>
                    </div>
                </div>
                
                <hr>
                
                <p><strong>Fecha de Creación:</strong> {{ $destino->created_at->format('d/m/Y h:i A') }}</p>
                <p><strong>Última Actualización:</strong> {{ $destino->updated_at->format('d/m/Y h:i A') }}</p>


                <div class="mt-4">
                    @can('editar_destinos')
                        <a href="{{ route('produccion.agro.destinos.edit', $destino->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar Destino</a>
                    @endcan
                </div>
                
            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para ver el detalle de este destino.</p>
    @endcan
</div>
@endsection