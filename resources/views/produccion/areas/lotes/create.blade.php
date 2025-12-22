@extends('layouts.app')
@section('title', 'Crear Lote Productivo')

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">➕ Crear Nuevo Lote</h1>
        <a href="{{ route('produccion.areas.lotes.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('crear_sectores')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Información del Lote</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('produccion.areas.lotes.store') }}" method="POST">
                    @csrf
                    
                    {{-- Campo 1: SECTOR PADRE --}}
                    <div class="form-group row">
                        <label for="sector_id" class="col-sm-3 col-form-label">Sector Padre: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control @error('sector_id') is-invalid @enderror" id="sector_id" name="sector_id" required>
                                <option value="">--- Seleccione un Sector ---</option>
                                @foreach ($sectores as $id => $nombre)
                                    <option value="{{ $id }}" {{ old('sector_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                            @error('sector_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 2: CÓDIGO INTERNO (clave para el código autogenerado) --}}
                    <div class="form-group row">
                        <label for="codigo_lote_interno" class="col-sm-3 col-form-label">Código Interno del Lote (Máx. 5 dígitos/letras): <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('codigo_lote_interno') is-invalid @enderror" id="codigo_lote_interno" name="codigo_lote_interno" value="{{ old('codigo_lote_interno') }}" required maxlength="5" placeholder="Ej: 02, B">
                            @error('codigo_lote_interno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Este código se combinará con el Código del Sector para formar el Código Único.</small>
                        </div>
                    </div>

                    {{-- Campo 3: NOMBRE --}}
                    <div class="form-group row">
                        <label for="nombre" class="col-sm-3 col-form-label">Nombre del Lote: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="100" placeholder="Ej: Lote 02">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 4: DESCRIPCIÓN --}}
                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-3 col-form-label">Descripción / Notas:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Guardar Lote</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para crear Lotes.</p>
    @endcan

</div>
@endsection