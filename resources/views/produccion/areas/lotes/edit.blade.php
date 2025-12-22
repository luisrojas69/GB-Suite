@extends('layouts.app')
@section('title', 'Editar Lote: ' . $lote->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">✍️ Editar Lote: **{{ $lote->nombre }}**</h1>
        <a href="{{ route('produccion.areas.lotes.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('editar_sectores')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actualizar Información del Lote</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('produccion.areas.lotes.update', $lote->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Campo 1: SECTOR PADRE (Puede ser modificado) --}}
                    <div class="form-group row">
                        <label for="sector_id" class="col-sm-3 col-form-label">Sector Padre: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control @error('sector_id') is-invalid @enderror" id="sector_id" name="sector_id" required>
                                <option value="">--- Seleccione un Sector ---</option>
                                @foreach ($sectores as $id => $nombre)
                                    <option value="{{ $id }}" {{ old('sector_id', $lote->sector_id) == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                            @error('sector_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 2: CÓDIGO INTERNO (Puede ser modificado) --}}
                    <div class="form-group row">
                        <label for="codigo_lote_interno" class="col-sm-3 col-form-label">Código Interno del Lote (Máx. 5 dígitos/letras): <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('codigo_lote_interno') is-invalid @enderror" id="codigo_lote_interno" name="codigo_lote_interno" value="{{ old('codigo_lote_interno', $lote->codigo_lote_interno) }}" required maxlength="5" placeholder="Ej: 02, B">
                            @error('codigo_lote_interno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Cambiar este código o el Sector Padre actualizará el código único de este lote y el de sus tablones asociados.</small>
                        </div>
                    </div>
                    
                    {{-- Campo 3: CÓDIGO ACTUAL (Solo informativo) --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Código Único Actual:</label>
                        <div class="col-sm-9">
                            <p class="form-control-static font-weight-bold text-primary">{{ $lote->codigo_completo }}</p>
                        </div>
                    </div>

                    {{-- Campo 4: NOMBRE --}}
                    <div class="form-group row">
                        <label for="nombre" class="col-sm-3 col-form-label">Nombre del Lote: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $lote->nombre) }}" required maxlength="100" placeholder="Ej: Lote 02">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 5: DESCRIPCIÓN --}}
                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-3 col-form-label">Descripción / Notas:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $lote->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sync-alt"></i> Actualizar Lote</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para editar Lotes.</p>
    @endcan

</div>
@endsection