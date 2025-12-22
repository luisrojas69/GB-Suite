@extends('layouts.app')
@section('title', 'Detalle de Sector: ' . $sector->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🔍 Detalle del Sector: **{{ $sector->nombre }}**</h1>
        <a href="{{ route('produccion.areas.sectores.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('ver_sectores')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Información General</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Código Único:</strong> {{ $sector->codigo_sector }}</p>
                        <p><strong>Nombre:</strong> {{ $sector->nombre }}</p>
                        <p><strong>Total de Lotes:</strong> <span class="badge badge-info">{{ $sector->lotes->count() }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fecha de Creación:</strong> {{ $sector->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Última Actualización:</strong> {{ $sector->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                <hr>

                <h5>Descripción</h5>
                <p>{{ $sector->descripcion ?? 'No hay descripción disponible para este sector.' }}</p>

                <div class="mt-4">
                    @can('editar_sectores')
                        <a href="{{ route('produccion.areas.sectores.edit', $sector->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar Sector</a>
                    @endcan
                </div>
                
            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para ver detalles de Sectores.</p>
    @endcan
</div>
@endsection