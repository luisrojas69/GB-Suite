@extends('layouts.app') 

@section('content')

    <h1 class="h3 mb-4 text-gray-800">
        {{ __('Detalle del Animal:') }} <span class="text-primary">{{ $animal->identifier }}</span>
    </h1>

    <div class="row mb-4">
        <div class="col-lg-12 text-right">

            {{-- BOTÓN: CREAR NUEVO PESAJE --}}
            {{-- Asumimos el permiso 'crear_pesaje' para esta acción --}}
            @can('crear_pesaje')
                <a href="{{ route('weighings.create', ['iron_id' => $animal->iron_id]) }}" class="btn btn-success btn-icon-split shadow-sm ml-2">
                    <span class="icon text-white-50">
                        <i class="fas fa-balance-scale"></i>
                    </span>
                    <span class="text">{{ __('Registrar Pesaje') }}</span>
                </a>
            @endcan

            {{-- BOTÓN: DAR DE BAJA (Acción Crítica) --}}
            {{-- Asumimos el permiso 'eliminar_animal' o 'crear_baja' --}}
            @can('crear_baja') 
                <a href="{{ route('bajas.search', ['iron_id' => $animal->iron_id]) }}" class="btn btn-danger btn-icon-split shadow-sm ml-2">
                    <span class="icon text-white-50">
                        <i class="fas fa-heart-broken"></i>
                    </span>
                    <span class="text">{{ __('Dar de Baja') }}</span>
                </a>
            @endcan

            {{-- BOTÓN: EDITAR ANIMAL --}}
            @can('editar_animal')
                <a href="{{ route('animals.edit', $animal) }}" class="btn btn-info btn-icon-split shadow-sm ml-2">
                    <span class="icon text-white-50">
                        <i class="fas fa-edit"></i>
                    </span>
                    <span class="text">{{ __('Editar Animal') }}</span>
                </a>
            @endcan
        </div>
    </div>

    <div class="row">
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('Peso Actual (Kg)') }}</div>
                            {{-- Asumimos que el modelo tiene el último pesaje cargado --}}
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $animal->latest_weight ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-weight-hanging fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('Edad') }}</div>
                            {{-- Lógica simple para calcular edad (depende de su campo 'birth_date') --}}
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $animal->birth_date ? $animal->birth_date->diff(now())->format('%y años, %m meses') : 'Desconocida' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-birthday-cake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('Especie / Raza') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $animal->specie->name ?? 'N/A' }} / {{ $animal->breed ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paw fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $animal->is_active ? 'success' : 'danger' }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $animal->is_active ? 'success' : 'danger' }} text-uppercase mb-1">{{ __('Estado') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $animal->is_active ? 'Activo' : 'DADO DE BAJA' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Datos Generales') }}</h6>
                </div>
                <div class="card-body">
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr><th>{{ __('Identificador') }}:</th><td>{{ $animal->iron_id }}</td></tr>
                                <tr><th>{{ __('Especie') }}:</th><td>{{ $animal->specie->name ?? 'N/A' }}</td></tr>
                                <tr><th>{{ __('Sexo') }}:</th><td>{{ $animal->sex ?? 'N/A' }}</td></tr>
                                <tr><th>{{ __('Fecha Nacimiento') }}:</th><td>{{ $animal->birth_date ? $animal->birth_date->format('d/m/Y') : 'N/A' }}</td></tr>
                                <tr><th>{{ __('Categoría') }}:</th><td>{{ $animal->category->name ?? 'N/A' }}</td></tr>
                                <tr><th>{{ __('Propiertario') }}:</th><td>{{ $animal->owner->name ?? 'N/A' }}</td></tr>
                                <tr><th>{{ __('Ubicación Actual') }}:</th><td>{{ $animal->location->name ?? 'N/A' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Observaciones e Historial') }}</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">{{ __('Notas de registro:') }}</p>
                    <p>{{ $animal->notes ?? 'Sin observaciones registradas.' }}</p>

                    <h6 class="mt-4 font-weight-bold text-secondary">{{ __('Próximas Acciones Pendientes') }}</h6>
                    {{-- Aquí podría listar tareas pendientes (vacunas, chequeos) --}}
                    <div class="alert alert-warning small">
                        Próxima Vacunación: 15/03/{{ date('Y') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection