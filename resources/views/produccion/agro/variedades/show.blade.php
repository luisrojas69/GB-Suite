@extends('layouts.app')
@section('title', 'Detalle de Variedad: ' . $variedad->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🔍 Detalle de Variedad: **{{ $variedad->nombre }}**</h1>
        <a href="{{ route('produccion.agro.variedades.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('ver_variedades')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Información Detallada</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Columna de Datos --}}
                    <div class="col-md-6 border-right">
                        <p><strong>ID:</strong> {{ $variedad->id }}</p>
                        <p><strong>Nombre:</strong> {{ $variedad->nombre }}</p>
                        <p><strong>Código Corto:</strong> <span class="badge badge-info">{{ $variedad->codigo ?? 'N/A' }}</span></p>
                        <p><strong>Fecha de Creación:</strong> {{ $variedad->created_at->format('d/m/Y h:i A') }}</p>
                        <p><strong>Última Actualización:</strong> {{ $variedad->updated_at->format('d/m/Y h:i A') }}</p>
                    </div>
                    
                    {{-- Columna de Metas --}}
                    <div class="col-md-6">
                        <h6>Objetivos y Metas</h6>
                        <hr class="mt-0">
                        <p><strong>Meta Polarización (POL):</strong> 
                            <span class="badge badge-success">{{ $variedad->meta_pol_cana ? number_format($variedad->meta_pol_cana, 2, ',', '.') . ' %' : 'No Definida' }}</span>
                        </p>
                        <p class="text-muted">Este valor se utiliza como referencia para la liquidación de la caña cosechada de esta variedad.</p>
                        <hr>
                        <p><strong>Tablones Asignados:</strong> {{ $variedad->tablones()->count() }}</p>
                    </div>
                </div>
                
                <hr>

                <h5>Descripción / Notas</h5>
                <p>{{ $variedad->descripcion ?? 'N/A' }}</p>

                <div class="mt-4">
                    @can('editar_variedades')
                        <a href="{{ route('produccion.agro.variedades.edit', $variedad->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar Variedad</a>
                    @endcan
                </div>
                
            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para ver el detalle de esta variedad.</p>
    @endcan
</div>
@endsection