@extends('layouts.app') 

@section('title', 'Detalle del Checklist: ' . $checklist->nombre)

@section('content')

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Plan de Mantenimiento: {{ $checklist->nombre }}</h1>
        <div class="d-flex">
            @can('gestionar_checklists')
                <a href="{{ route('checklists.edit', $checklist->id) }}" class="btn btn-warning btn-sm shadow-sm mr-2" title="Editar Plan">
                    <i class="fas fa-edit fa-sm text-white-50"></i> Editar
                </a>
            @endcan
            <a href="{{ route('checklists.index') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver al Listado
            </a>
        </div>
    </div>

    <div class="row">
        
        {{-- COLUMNA 1: Datos Generales --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Información Básica</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Tipo de Activo:</strong> 
                            <span class="badge badge-info">{{ $checklist->tipo_activo }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Intervalo de Referencia:</strong> 
                            <span class="badge badge-secondary">{{ $checklist->intervalo_referencia }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Creado el:</strong> 
                            {{ \Carbon\Carbon::parse($checklist->created_at)->format('d/m/Y H:i') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Última Actualización:</strong> 
                            {{ \Carbon\Carbon::parse($checklist->updated_at)->format('d/m/Y H:i') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- COLUMNA 2: Lista de Tareas --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Tareas del Mantenimiento</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Las tareas se listan tal como fueron ingresadas, una por línea.</p>
                    
                    <ul class="list-group">
                        @php
                            // Divide las tareas por salto de línea (\n)
                            $tareas = explode("\n", $checklist->descripcion_tareas);
                        @endphp
                        
                        @forelse (array_filter($tareas) as $tarea)
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                <span>{{ trim($tarea) }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-danger">Este plan no tiene tareas definidas.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection