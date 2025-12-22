@extends('layouts.app')
@section('title', 'Editar Tablón: ' . $tablon->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">✍️ Editar Tablón: **{{ $tablon->nombre }}**</h1>
        <a href="{{ route('produccion.areas.tablones.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('editar_sectores') {{-- Asumo permiso 'editar_sectores' --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actualizar Información del Tablón (Código: {{ $tablon->codigo_completo }})</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('produccion.areas.tablones.update', $tablon->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Bloque Jerarquía y Código --}}
                    <div class="card-header bg-light py-2 mb-3"><h6 class="m-0 font-weight-bold text-dark">Jerarquía y Nomenclatura</h6></div>

                    {{-- Campo 1: LOTE PADRE --}}
                    <div class="form-group row">
                        <label for="lote_id" class="col-sm-3 col-form-label">Lote Padre: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control @error('lote_id') is-invalid @enderror" id="lote_id" name="lote_id">
                                @foreach ($lotes as $id => $display)
                                    <option value="{{ $id }}" {{ old('lote_id', $tablon->lote_id) == $id ? 'selected' : '' }}>{{ $display }}</option>
                                @endforeach
                            </select>
                            @error('lote_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 2: CÓDIGO INTERNO --}}
                    <div class="form-group row">
                        <label for="codigo_tablon_interno" class="col-sm-3 col-form-label">Código Interno Tablón: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('codigo_tablon_interno') is-invalid @enderror" id="codigo_tablon_interno" name="codigo_tablon_interno" value="{{ old('codigo_tablon_interno', $tablon->codigo_tablon_interno) }}" placeholder="Ej: 08, A1">
                            @error('codigo_tablon_interno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Código Actual Completo: **{{ $tablon->codigo_completo }}**</small>
                        </div>
                    </div>

                    {{-- Campo 3: NOMBRE --}}
                    <div class="form-group row">
                        <label for="nombre" class="col-sm-3 col-form-label">Nombre del Tablón: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $tablon->nombre) }}" placeholder="Ej: Tablón Principal">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 4: ÁREA EN HA (antes hectareas) --}}
                    <div class="form-group row">
                        <label for="area_ha" class="col-sm-3 col-form-label">Área (Hectáreas Netas): <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" class="form-control @error('area_ha') is-invalid @enderror" id="area_ha" name="area_ha" value="{{ old('area_ha', $tablon->area_ha) }}" placeholder="Ej: 3.50">
                            @error('area_ha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Bloque Siembra y Metas --}}
                    <div class="card-header bg-light py-2 mb-3 mt-4"><h6 class="m-0 font-weight-bold text-dark">Control de Siembra y Rendimiento (Caña)</h6></div>

                    {{-- Campo 5: VARIEDAD --}}
                    <div class="form-group row">
                        <label for="variedad_id" class="col-sm-3 col-form-label">Variedad de Caña:</label>
                        <div class="col-sm-9">
                            <select class="form-control @error('variedad_id') is-invalid @enderror" id="variedad_id" name="variedad_id">
                                <option value="">N/A (Sin sembrar)</option>
                                @foreach ($variedades as $id => $nombre)
                                    <option value="{{ $id }}" {{ old('variedad_id', $tablon->variedad_id) == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                            @error('variedad_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 6: FECHA DE SIEMBRA --}}
                    <div class="form-group row">
                        <label for="fecha_siembra" class="col-sm-3 col-form-label">Fecha de Siembra / Resoca:</label>
                        <div class="col-sm-9">
                            {{-- Formatear la fecha para que el input type="date" la acepte --}}
                            <input type="date" class="form-control @error('fecha_siembra') is-invalid @enderror" id="fecha_siembra" name="fecha_siembra" value="{{ old('fecha_siembra', $tablon->fecha_siembra ? $tablon->fecha_siembra->format('Y-m-d') : '') }}">
                            @error('fecha_siembra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 7: META T/HA --}}
                    <div class="form-group row">
                        <label for="meta_ton_ha" class="col-sm-3 col-form-label">Meta T/Ha (Toneladas/Hectárea):</label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" class="form-control @error('meta_ton_ha') is-invalid @enderror" id="meta_ton_ha" name="meta_ton_ha" value="{{ old('meta_ton_ha', $tablon->meta_ton_ha) }}" placeholder="Ej: 115.00">
                            @error('meta_ton_ha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Bloque Información Adicional --}}
                    <div class="card-header bg-light py-2 mb-3 mt-4"><h6 class="m-0 font-weight-bold text-dark">Información Adicional</h6></div>

                    {{-- Campo 8: TIPO DE SUELO --}}
                    <div class="form-group row">
                        <label for="tipo_suelo" class="col-sm-3 col-form-label">Tipo de Suelo:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('tipo_suelo') is-invalid @enderror" id="tipo_suelo" name="tipo_suelo" value="{{ old('tipo_suelo', $tablon->tipo_suelo) }}" placeholder="Ej: Arcilloso, Franco">
                            @error('tipo_suelo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 9: ESTADO --}}
                    <div class="form-group row">
                        <label for="estado" class="col-sm-3 col-form-label">Estado:</label>
                        <div class="col-sm-9">
                            <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado">
                                @foreach ($estados as $valor => $etiqueta)
                                    <option value="{{ $valor }}" {{ old('estado', $tablon->estado) == $valor ? 'selected' : '' }}>{{ $etiqueta }}</option>
                                @endforeach
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 10: DESCRIPCIÓN --}}
                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-3 col-form-label">Descripción / Notas:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $tablon->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sync-alt"></i> Actualizar Tablón</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para editar Tablones.</p>
    @endcan

</div>
@endsection