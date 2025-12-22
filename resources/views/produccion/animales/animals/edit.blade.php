@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-edit"></i> Editar Animal: {{ $animal->iron_id ?? 'N/D' }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Modificar Datos del Animal</h6>
        </div>
        <div class="card-body">
            
            {{-- Formulario de Edición --}}
            <form action="{{ route('animals.update', $animal->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- O PATCH, ambos son aceptados por Laravel para updates --}}

                {{-- Fila 1: Identificación y Lote --}}
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="iron_id">ID / Tatuaje / Chapa</label>
                        {{-- Usamos old() para mantener el valor en caso de error de validación, 
                             pero usamos $animal->iron_id como fallback para el primer cargado --}}
                        <input type="text" class="form-control @error('iron_id') is-invalid @enderror" id="iron_id" name="iron_id" value="{{ old('iron_id', $animal->iron_id) }}" placeholder="Ej: 1500 o S/I">
                        @error('iron_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="lot">Lote de Agrupación</label>
                        <input type="text" class="form-control @error('lot') is-invalid @enderror" id="lot" name="lot" value="{{ old('lot', $animal->lot) }}" placeholder="Ej: Lote 10">
                        @error('lot') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="sex">Sexo <span class="text-danger">*</span></label>
                        <select id="sex" name="sex" class="form-control @error('sex') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            <option value="Macho" {{ old('sex', $animal->sex) == 'Macho' ? 'selected' : '' }}>Macho</option>
                            <option value="Hembra" {{ old('sex', $animal->sex) == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                        </select>
                        @error('sex') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="birth_date">Fecha de Nacimiento</label>
                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date', optional($animal->birth_date)->format('Y-m-d')) }}">
                        @error('birth_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="specie_id">Especie <span class="text-danger">*</span></label>
                        <select id="specie_id" name="specie_id" class="form-control @error('specie_id') is-invalid @enderror" required>
                            <option value="">Seleccione la Especie...</option>
                            @foreach ($species as $item)
                                <option value="{{ $item->id }}" {{ old('specie_id', $animal->specie_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('species_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="category_id">Categoría (Clasificación/CeCo) <span class="text-danger">*</span></label>
                        <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">Seleccione la Categoría...</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}" {{ old('category_id', $animal->category_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} ({{ $item->cost_center_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="owner_id">Propietario <span class="text-danger">*</span></label>
                        <select id="owner_id" name="owner_id" class="form-control @error('owner_id') is-invalid @enderror" required>
                            <option value="">Seleccione el Propietario...</option>
                            @foreach ($owners as $item)
                                <option value="{{ $item->id }}" {{ old('owner_id', $animal->owner_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('owner_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="location_id">Ubicación Actual <span class="text-danger">*</span></label>
                        <select id="location_id" name="location_id" class="form-control @error('location_id') is-invalid @enderror" required>
                            <option value="">Seleccione la Ubicación...</option>
                            @foreach ($locations as $item)
                                <option value="{{ $item->id }}" {{ old('location_id', $animal->location_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row mb-4">
                    <div class="form-group col-md-4">
                        <label for="is_active">Estado del Animal</label>
                        {{-- Es preferible que este campo esté deshabilitado para forzar el uso del módulo de "Baja" --}}
                        <select id="is_active" name="is_active" class="form-control" disabled>
                            <option value="1" {{ old('is_active', $animal->is_active) ? 'selected' : '' }}>Activo (En Inventario)</option>
                            <option value="0" {{ old('is_active', $animal->is_active) ? '' : 'selected' }}>Inactivo (Dado de Baja/Vendido/Muerto)</option>
                        </select>
                        <small class="form-text text-muted">Use el módulo **Registro de Baja** para cambiar este estado de forma controlada.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('animals.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                        <span class="text">Guardar Cambios</span>
                    </button>
                </div>
            </form>
            
            <hr class="mt-4">
            
            <a href="{{ route('bajas.create') }}?iron_id={{ $animal->iron_id }}" class="btn btn-danger btn-sm float-right">
                <i class="fas fa-skull"></i> Registrar Baja / Mortalidad
            </a>

        </div>
    </div>
</div>
@endsection