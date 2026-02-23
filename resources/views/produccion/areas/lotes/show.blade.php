@extends('layouts.app')
@section('title', 'Detalle de Lote: ' . $lote->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🔍 Detalle del Lote: **{{ $lote->nombre }}**</h1>
        <a href="{{ route('produccion.areas.lotes.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('produccion.areas.ver')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Información General del Lote</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Código Único Completo:</strong> <span class="badge badge-info">{{ $lote->codigo_completo }}</span></p>
                        <p><strong>Código Interno:</strong> {{ $lote->codigo_lote_interno }}</p>
                        <p><strong>Nombre:</strong> {{ $lote->nombre }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Sector Padre:</strong> {{ $lote->sector->codigo_sector }} - {{ $lote->sector->nombre }}</p>
                        <p><strong>Total de Tablones:</strong> <span class="badge badge-primary">{{ $lote->tablones->count() }}</span></p>
                    </div>
                </div>
                
                <hr>

                <h5>Descripción</h5>
                <p>{{ $lote->descripcion ?? 'N/A' }}</p>

                <div class="mt-4">
                    @can('produccion.areas.editar')
                        <a href="{{ route('produccion.areas.lotes.edit', $lote->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar Lote</a>
                    @endcan
                </div>
                
            </div>
        </div>
        
        {{-- Listado de Tablones asociados --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Tablones Pertenecientes a este Lote</h6>
            </div>
            <div class="card-body">
                @if($lote->tablones->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($lote->tablones as $tablon)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                **{{ $tablon->codigo_completo }}** - {{ $tablon->nombre }}
                                <span class="badge badge-warning badge-pill">{{ $tablon->hectareas }} Ha</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center">Aún no hay tablones registrados en este lote.</p>
                @endif
            </div>
        </div>

    @else
        <p class="alert alert-danger">Usted no tiene permisos para ver detalles de Lotes.</p>
    @endcan
</div>
@endsection