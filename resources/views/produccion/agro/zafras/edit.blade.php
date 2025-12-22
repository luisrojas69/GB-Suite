@extends('layouts.app')
@section('title', 'Editar Zafra: ' . $zafra->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">✍️ Editar Zafra: **{{ $zafra->nombre }}**</h1>
        <a href="{{ route('produccion.agro.zafras.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('editar_zafras')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actualizar Información de la Zafra</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('produccion.agro.zafras.update', $zafra->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Campo 1: Nombre --}}
                    <div class="form-group row">
                        <label for="nombre" class="col-sm-3 col-form-label">Nombre de la Zafra: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $zafra->nombre) }}" placeholder="Ej: Zafra 2025/2026" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 2 & 3: Año Inicio / Año Fin --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Periodo Anual: <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control @error('anio_inicio') is-invalid @enderror" id="anio_inicio" name="anio_inicio" value="{{ old('anio_inicio', $zafra->anio_inicio) }}" placeholder="Año de Inicio" required min="2000" max="2099">
                            @error('anio_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Año de Inicio</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" class="form-control @error('anio_fin') is-invalid @enderror" id="anio_fin" name="anio_fin" value="{{ old('anio_fin', $zafra->anio_fin) }}" placeholder="Año de Fin" required min="2001" max="2100">
                            @error('anio_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Año de Fin (Debe ser > Año de Inicio)</small>
                        </div>
                    </div>

                    {{-- Campo 4 & 5: Fecha Inicio / Fecha Fin --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Fechas Operacionales:</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', optional($zafra->fecha_inicio)->format('Y-m-d')) }}">
                            @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Fecha de inicio de corte (Opcional)</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', optional($zafra->fecha_fin)->format('Y-m-d')) }}">
                            @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Fecha de fin de corte (Opcional)</small>
                        </div>
                    </div>
                    
                    {{-- Campo 6: Estado --}}
                    <div class="form-group row">
                        <label for="estado" class="col-sm-3 col-form-label">Estado: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                @foreach ($estados as $key => $value)
                                    <option value="{{ $key }}" {{ old('estado', $zafra->estado) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Solo una zafra debe estar 'Activa' para el registro de moliendas.</small>
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sync-alt"></i> Actualizar Zafra</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para editar zafras.</p>
    @endcan

</div>
@endsection