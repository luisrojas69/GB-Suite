@extends('layouts.app') 

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">➕ Crear Nueva Especie</h1>
        
        {{-- NUEVO BOTÓN AGREGADO --}}
        <a href="{{ route('species.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-list fa-sm text-white-50"></i> Ir a la Lista de Especies
        </a>
    </div>

    {{-- Mensajes de Éxito/Error (Añadidos por consistencia) --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detalles de la Especie</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('species.store') }}" method="POST">
                @csrf
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Nombre de la Especie <span class="text-danger">*</span></label>
                        {{-- Aplicando clases de validación de Bootstrap --}}
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Ej: Bovino, Ovino, Equino">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <small class="form-text text-muted">Asegúrese de que el nombre sea único.</small>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    {{-- Botón de Cancelar/Ir a Lista --}}
                    <a href="{{ route('species.index') }}" class="btn btn-secondary btn-icon-split mr-2">
                        <span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
                        <span class="text">Cancelar</span>
                    </a>

                    {{-- Botón de Guardar --}}
                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                        <span class="text">Guardar Especie</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection