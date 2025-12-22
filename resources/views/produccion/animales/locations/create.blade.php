@extends('layouts.app') 

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">➕ Registrar Nueva Ubicación</h1>
        
        {{-- NUEVO BOTÓN AGREGADO --}}
        <a href="{{ route('locations.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-list fa-sm text-white-50"></i> Ir a la Lista de Ubicaciones
        </a>
    </div>

    {{-- Mensajes de Éxito/Error (Recomendado) --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detalles del Potrero/Área</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('locations.store') }}" method="POST">
                @csrf
                
                <div class="form-row">
                    {{-- 1. Nombre de la Ubicación --}}
                    <div class="form-group col-md-4">
                        <label for="name">Nombre de la Ubicación <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Ej: Caimana, Potrero La Uva, Haras">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group col-md-2">
                        <label for="name">CeCO: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cost_center_id') is-invalid @enderror" id="cost_center_id" name="cost_center_id" value="{{ old('cost_center_id') }}" required placeholder="Ej: 5241">
                        @error('cost_center_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- 2. Estado Activo --}}
                    <div class="form-group col-md-6 pt-md-4">
                        <div class="custom-control custom-checkbox">
                            {{-- Si old('is_active') es null, por defecto es checked (1) --}}
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Ubicación Activa</label>
                        </div>
                        <small class="form-text text-muted">Las ubicaciones inactivas no aparecerán en los formularios de registro de animales.</small>
                        @error('is_active') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    {{-- Botón de Cancelar/Ir a Lista --}}
                    <a href="{{ route('locations.index') }}" class="btn btn-secondary btn-icon-split mr-2">
                        <span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
                        <span class="text">Cancelar / Ir a la Lista</span>
                    </a>

                    {{-- Botón de Guardar --}}
                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                        <span class="text">Guardar Ubicación</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection