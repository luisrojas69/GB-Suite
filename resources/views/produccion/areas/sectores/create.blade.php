@extends('layouts.app')
@section('title', 'Crear Sector Productivo')

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">➕ Crear Nuevo Sector</h1>
        <a href="{{ route('produccion.areas.sectores.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    {{-- Aunque la ruta ya lo protege, es buena práctica hacer una doble verificación visual --}}
    @can('crear_sectores')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Información del Sector</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('produccion.areas.sectores.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group row">
                        <label for="codigo_sector" class="col-sm-3 col-form-label">Código del Sector (Máx. 5 dígitos/letras): <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('codigo_sector') is-invalid @enderror" id="codigo_sector" name="codigo_sector" value="{{ old('codigo_sector') }}" required maxlength="5" placeholder="Ej: 01, S1, A">
                            @error('codigo_sector')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="nombre" class="col-sm-3 col-form-label">Nombre del Sector: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="100" placeholder="Ej: Sector Charco">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

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
                            <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Guardar Sector</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para crear Sectores.</p>
    @endcan

</div>
@endsection