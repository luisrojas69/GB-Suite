@extends('layouts.app') 

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">💀 Registro de Baja / Mortalidad</h1>
        
        {{-- NUEVO BOTÓN AGREGADO --}}
        @can('ver_bajas')
            <a href="{{ route('bajas.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-history fa-sm text-white-50"></i> Ir al Historial de Bajas
            </a>
        @endcan
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- 1. BUSCADOR --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">1. Buscar Animal a dar de Baja</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('bajas.search') }}" method="GET">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-4">
                        <label for="iron_id">ID/Tatuaje del Animal</label>
                        {{-- Preserva el valor si viene de un error o de un parámetro --}}
                        <input type="text" class="form-control" id="iron_id" name="iron_id" value="{{ request('iron_id') }}" required>
                    </div>
                    <div class="form-group col-md-auto">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50"><i class="fas fa-search"></i></span>
                            <span class="text">Buscar Animal Activo</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    {{-- 2. FORMULARIO DE CONFIRMACIÓN DE BAJA --}}
    @if (isset($animal))
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-danger">
            <h6 class="m-0 font-weight-bold text-white">2. Confirmar Baja para: {{ $animal->iron_id }}</h6>
        </div>
        <div class="card-body">
            
            <div class="alert alert-warning">
                <strong>Advertencia:</strong> Dar de baja a un animal lo excluye del inventario activo. Esta acción generará un registro en el historial.
            </div>

            <form action="{{ route('bajas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="animal_id" value="{{ $animal->id }}">
                
                <div class="form-row">
                    {{-- Tipo de Evento --}}
                    <div class="form-group col-md-4">
                        <label for="event_type">Motivo de la Baja <span class="text-danger">*</span></label>
                        <select id="event_type" name="event_type" class="form-control @error('event_type') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            <option value="Mortalidad" {{ old('event_type') == 'Mortalidad' ? 'selected' : '' }}>Mortalidad (Muerte)</option>
                            <option value="Venta" {{ old('event_type') == 'Venta' ? 'selected' : '' }}>Venta</option>
                            <option value="Traslado" {{ old('event_type') == 'Traslado' ? 'selected' : '' }}>Traslado (Fuera de Finca)</option>
                            <option value="Descarte" {{ old('event_type') == 'Descarte' ? 'selected' : '' }}>Descarte/Consumo Interno</option>
                        </select>
                        @error('event_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- Fecha del Evento --}}
                    <div class="form-group col-md-4">
                        <label for="event_date">Fecha del Evento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('event_date') is-invalid @enderror" id="event_date" name="event_date" value="{{ old('event_date', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                        @error('event_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- Causa de Mortalidad/Detalle --}}
                    <div class="form-group col-md-4">
                        <label for="cause">Detalle de la Baja / Causa <span class="text-muted">(Opcional)</span></label>
                        <input type="text" class="form-control @error('cause') is-invalid @enderror" id="cause" name="cause" value="{{ old('cause') }}" placeholder="Ej: Traumatismo, Neumonía, Venta a X cliente">
                        @error('cause') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Notas Adicionales</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" class="btn btn-danger btn-icon-split mt-3">
                    <span class="icon text-white-50"><i class="fas fa-arrow-alt-circle-down"></i></span>
                    <span class="text">Confirmar Baja del Animal y Guardar Evento</span>
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection