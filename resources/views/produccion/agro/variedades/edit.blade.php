@extends('layouts.app')
@section('title', 'Editar Variedad: ' . $variedad->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">✍️ Editar Variedad: **{{ $variedad->nombre }}**</h1>
        <a href="{{ route('produccion.agro.variedades.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('editar_variedades')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actualizar Información de la Variedad</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('produccion.agro.variedades.update', $variedad->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Campo 1: Nombre --}}
                    <div class="form-group row">
                        <label for="nombre" class="col-sm-3 col-form-label">Nombre de la Variedad: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $variedad->nombre) }}" placeholder="Ej: V 98120, CC 8592" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 2: Código --}}
                    <div class="form-group row">
                        <label for="codigo" class="col-sm-3 col-form-label">Código Corto:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror" id="codigo" name="codigo" value="{{ old('codigo', $variedad->codigo) }}" placeholder="Ej: V98, C85">
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 3: Meta de POL --}}
                    <div class="form-group row">
                        <label for="meta_pol_cana" class="col-sm-3 col-form-label">Meta Polarización (%):</label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" class="form-control @error('meta_pol_cana') is-invalid @enderror" id="meta_pol_cana" name="meta_pol_cana" value="{{ old('meta_pol_cana', $variedad->meta_pol_cana) }}" placeholder="Ej: 16.50">
                            @error('meta_pol_cana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 4: Descripción --}}
                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-3 col-form-label">Descripción / Notas:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $variedad->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sync-alt"></i> Actualizar Variedad</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para editar variedades.</p>
    @endcan

</div>
@endsection