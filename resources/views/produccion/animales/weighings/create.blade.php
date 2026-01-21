@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">⚖️ Registro de Pesajes</h1>
        
        {{-- NUEVO BOTÓN AGREGADO --}}
        <a href="{{ route('weighings.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-history fa-sm text-white-50"></i> Ir al Historial de Pesajes
        </a>
    </div>

    {{-- Mensajes de Éxito/Error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">1. Buscar Animal por ID/Tatuaje</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('weighings.create') }}" method="GET">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-4">
                        <label for="iron_id">ID/Tatuaje del Animal</label>
                        {{-- Se mantiene la clase is-invalid y se usa $ironId del controlador --}}
                        <input type="text" class="form-control @error('iron_id') is-invalid @enderror" id="iron_id" name="iron_id" value="{{ old('iron_id', $ironId) }}" required>
                        @error('iron_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-auto">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50"><i class="fas fa-search"></i></span>
                            <span class="text">Buscar</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    @if ($animal)
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-success">
            <h6 class="m-0 font-weight-bold text-white">2. Animal Encontrado: {{ $animal->iron_id }}</h6>
        </div>
        <div class="card-body">
            
            <div class="row mb-4">
                <div class="col-md-3">
                    {{-- Nota: Asumiendo que usa la relación 'species' si el modelo Animal usa 'species_id' --}}
                    <strong>Especie:</strong> <span class="badge badge-secondary">{{ optional($animal->specie)->name ?? 'N/D' }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Categoría:</strong> <span class="badge badge-info">{{ optional($animal->category)->name ?? 'N/D' }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Ubicación:</strong> {{ optional($animal->location)->name ?? 'N/D' }}
                </div>
                <div class="col-md-3">
                    <strong>Último Peso:</strong> 
                    @if ($lastWeighing)
                        {{-- ADVERTENCIA: Se ajusta a 'weight' para coincidir con el modelo Weighing proporcionado anteriormente --}}
                        <span class="text-success">{{ number_format($lastWeighing->weight, 2, ',', '.') }} kg ({{ \Carbon\Carbon::parse($lastWeighing->weighing_date)->format('d/m/Y') }})</span>
                    @else
                        <span class="text-danger">Sin registro</span>
                    @endif
                </div>
            </div>

            <hr>
            <form action="{{ route('weighings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="animal_id" value="{{ $animal->id }}">
                
                <div class="form-row">
                    {{-- Fecha de Pesaje --}}
                    <div class="form-group col-md-4">
                        <label for="weighing_date">Fecha de Pesaje <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('weighing_date') is-invalid @enderror" id="weighing_date" name="weighing_date" value="{{ old('weighing_date', Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                        @error('weighing_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- Peso en Kilogramos --}}
                    <div class="form-group col-md-4">
                        {{-- ADVERTENCIA: Se ajusta a 'weight' para coincidir con el modelo Weighing proporcionado anteriormente --}}
                        <label for="weight_kg">Peso (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('weight_kg') is-invalid @enderror" id="weight_kg" name="weight_kg" value="{{ old('weight_kg') }}" required min="0.01">
                        @error('weight_kg') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- Notas --}}
                    <div class="form-group col-md-4">
                        <label for="notes">Notas / Observaciones</label>
                        <input type="text" class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" value="{{ old('notes') }}">
                        @error('notes') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success btn-icon-split mt-3">
                    <span class="icon text-white-50"><i class="fas fa-balance-scale"></i></span>
                    <span class="text">Guardar Registro de Peso</span>
                </button>
            </form>
        </div>
    </div>
    @elseif ($ironId && !$animal)
    <div class="alert alert-warning">El ID/Tatuaje **{{ $ironId }}** no se encontró en el inventario o está inactivo. Por favor, verifique el ID.</div>
    @endif
    
</div>
@endsection