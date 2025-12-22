@extends('layouts.app') 

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">➕ Crear Nueva Categoría</h1>
        
        {{-- NUEVO BOTÓN AGREGADO --}}
        <a href="{{ route('categories.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-list fa-sm text-white-50"></i> Ir a la Lista de Categorías
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
            <h6 class="m-0 font-weight-bold text-primary">Asignación de Clasificación y CeCo</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                
                <div class="form-row">
                    {{-- 1. Especie --}}
                    <div class="form-group col-md-6">
                        <label for="species_id">Especie <span class="text-danger">*</span></label>
                        <select id="species_id" name="species_id" class="form-control @error('species_id') is-invalid @enderror" required>
                            <option value="">Seleccione Especie...</option>
                            @foreach ($species as $item)
                                <option value="{{ $item->id }}" {{ old('species_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('species_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- 2. Nombre de la Categoría --}}
                    <div class="form-group col-md-6">
                        <label for="name">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Ej: Vaca, Novillo, Cordero, Potro">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    {{-- 3. Centro de Costo (CeCo) --}}
                    <div class="form-group col-md-6">
                        <label for="cost_center_id">Código de Centro de Costo (Profit) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cost_center_id') is-invalid @enderror" id="cost_center_id" name="cost_center_id" value="{{ old('cost_center_id') }}" required maxlength="6" placeholder="Ej: 524101">
                        @error('cost_center_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <small class="form-text text-muted">Debe ser el código exacto (ej: 6 dígitos) usado en su sistema contable.</small>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-icon-split mr-2">
                        <span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
                        <span class="text">Cancelar</span>
                    </a>
                    
                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                        <span class="text">Guardar Categoría</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection