@extends('layouts.app')
@section('title', 'Editar Destino: ' . $destino->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">✍️ Editar Destino: **{{ $destino->nombre }}**</h1>
        <a href="{{ route('produccion.agro.destinos.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('editar_destinos')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actualizar Información del Destino</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('produccion.agro.destinos.update', $destino->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Campo 1: Nombre --}}
                    <div class="form-group row">
                        <label for="nombre" class="col-sm-3 col-form-label">Nombre del Destino: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $destino->nombre) }}" placeholder="Ej: Central La Pastora" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 2: Código --}}
                    <div class="form-group row">
                        <label for="codigo" class="col-sm-3 col-form-label">Código Único: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror" id="codigo" name="codigo" value="{{ old('codigo', $destino->codigo) }}" placeholder="Ej: CLP" required>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sync-alt"></i> Actualizar Destino</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para editar destinos.</p>
    @endcan

</div>
@endsection