@extends('layouts.app')
@section('title', 'Editar Contratista: ' . $contratista->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">✍️ Editar Contratista: **{{ $contratista->nombre }}**</h1>
        <a href="{{ route('produccion.agro.contratistas.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('editar_contratistas')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actualizar Datos del Contratista</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('produccion.agro.contratistas.update', $contratista->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Campo 1: Nombre --}}
                    <div class="form-group row">
                        <label for="nombre" class="col-sm-3 col-form-label">Nombre / Razón Social: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $contratista->nombre) }}" placeholder="Ej: Servicios de Cosecha El Cañal C.A." required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 2: RIF --}}
                    <div class="form-group row">
                        <label for="rif" class="col-sm-3 col-form-label">RIF / Identificación:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('rif') is-invalid @enderror" id="rif" name="rif" value="{{ old('rif', $contratista->rif) }}" placeholder="Ej: J-00000000-0">
                            @error('rif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 3: Persona Contacto --}}
                    <div class="form-group row">
                        <label for="persona_contacto" class="col-sm-3 col-form-label">Persona de Contacto:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('persona_contacto') is-invalid @enderror" id="persona_contacto" name="persona_contacto" value="{{ old('persona_contacto', $contratista->persona_contacto) }}" placeholder="Ej: Juan Pérez">
                            @error('persona_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 4: Teléfono --}}
                    <div class="form-group row">
                        <label for="telefono" class="col-sm-3 col-form-label">Teléfono:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $contratista->telefono) }}" placeholder="Ej: 0414-0000000">
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sync-alt"></i> Actualizar Contratista</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para editar contratistas.</p>
    @endcan

</div>
@endsection