@extends('layouts.app')
@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📋 Nuevo Registro de Animal</h1>
        
        {{-- NUEVO BOTÓN AGREGADO --}}
        <a href="{{ route('animals.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-list fa-sm text-white-50"></i> Ir a la lista de Animales
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Información del Semoviente</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('animals.store') }}" method="POST">
                @csrf
                
                <div class="form-row">
                    {{-- 1. ID / Tatuaje --}}
                    <div class="form-group col-md-4">
                        <label for="iron_id">ID / Tatuaje <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('iron_id') is-invalid @enderror" id="iron_id" name="iron_id" value="{{ old('iron_id') }}" required>
                        @error('iron_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- 2. Lote --}}
                    <div class="form-group col-md-4">
                        <label for="lot">Lote</label>
                        <input type="text" class="form-control @error('lot') is-invalid @enderror" id="lot" name="lot" value="{{ old('lot') }}">
                        @error('lot') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- 3. Fecha de Nacimiento --}}
                    <div class="form-group col-md-4">
                        <label for="birth_date">Fecha de Nacimiento</label>
                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                        @error('birth_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    {{-- 4. Especie (Maestra) --}}
                    <div class="form-group col-md-3">
                        {{-- CORRECCIÓN: 'specie_id' cambiado a 'specie_id' --}}
                        <label for="specie_id">Especie <span class="text-danger">*</span></label>
                        <select id="specie_id" name="specie_id" class="form-control @error('specie_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach ($species as $item)
                                <option value="{{ $item->id }}" {{ old('specie_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('specie_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- 5. Categoría (Maestra) --}}
                    <div class="form-group col-md-3">
                        <label for="category_id">Categoría <span class="text-danger">*</span></label>
                        <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}" {{ old('category_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Ej: Vaca, Becerro, Potro. Define el CeCo.</small>
                        @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- 6. Sexo --}}
                    <div class="form-group col-md-3">
                        <label for="sex">Sexo <span class="text-danger">*</span></label>
                        <select id="sex" name="sex" class="form-control @error('sex') is-invalid @enderror" required>
                            <option value="Macho" {{ old('sex') == 'Macho' ? 'selected' : '' }}>Macho</option>
                            <option value="Hembra" {{ old('sex') == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                        </select>
                        @error('sex') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    {{-- 7. Propietario (Maestra) --}}
                    <div class="form-group col-md-6">
                        <label for="owner_id">Propietario <span class="text-danger">*</span></label>
                        <select id="owner_id" name="owner_id" class="form-control @error('owner_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach ($owners as $item)
                                <option value="{{ $item->id }}" {{ old('owner_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('owner_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- 8. Ubicación (Maestra) --}}
                    <div class="form-group col-md-6">
                        <label for="location_id">Ubicación Actual <span class="text-danger">*</span></label>
                        <select id="location_id" name="location_id" class="form-control @error('location_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach ($locations as $item)
                                <option value="{{ $item->id }}" {{ old('location_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('location_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                {{-- Controles de Botones --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('animals.index') }}" class="btn btn-secondary btn-icon-split mr-2">
                        <span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
                        <span class="text">Cancelar / Ir a la Lista</span>
                    </a>

                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                        <span class="text">Registrar Animal</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection