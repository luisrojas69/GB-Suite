@extends('layouts.app')
@section('title', 'Editar Tarifa: ' . $tarifa->concepto)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">✍️ Editar Tarifa: **{{ $tarifa->concepto }}**</h1>
        <a href="{{ route('liquidacion.tarifas.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('gestionar_tarifas')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actualizar Información de la Tarifa #{{ $tarifa->id }}</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('liquidacion.tarifas.update', $tarifa->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Campo 1: CONCEPTO --}}
                    <div class="form-group row">
                        <label for="concepto" class="col-sm-3 col-form-label">Concepto: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('concepto') is-invalid @enderror" id="concepto" name="concepto" value="{{ old('concepto', $tarifa->concepto) }}" required placeholder="Ej: Precio Base T.T.P.">
                            @error('concepto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 2: VALOR --}}
                    <div class="form-group row">
                        <label for="valor" class="col-sm-3 col-form-label">Valor (4 decimales): <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" step="0.0001" min="0.0001" max="999999.9999" class="form-control @error('valor') is-invalid @enderror" id="valor" name="valor" value="{{ old('valor', $tarifa->valor) }}" required>
                            @error('valor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 3: UNIDAD --}}
                    <div class="form-group row">
                        <label for="unidad" class="col-sm-3 col-form-label">Unidad de Medida: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('unidad') is-invalid @enderror" id="unidad" name="unidad" value="{{ old('unidad', $tarifa->unidad) }}" required placeholder="Ej: USD/Ton, USD/QQ, %">
                            @error('unidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 4: FECHA VIGENCIA --}}
                    <div class="form-group row">
                        <label for="fecha_vigencia" class="col-sm-3 col-form-label">Fecha de Vigencia (Desde): <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control @error('fecha_vigencia') is-invalid @enderror" id="fecha_vigencia" name="fecha_vigencia" value="{{ old('fecha_vigencia', $tarifa->fecha_vigencia->format('Y-m-d')) }}" required>
                            <small class="form-text text-muted">No pueden existir dos tarifas con el mismo Concepto y Fecha de Vigencia.</small>
                            @error('fecha_vigencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 5: ESTADO --}}
                    <div class="form-group row">
                        <label for="estado" class="col-sm-3 col-form-label">Estado: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                @foreach ($estados as $key => $display)
                                    <option value="{{ $key }}" {{ old('estado', $tarifa->estado) == $key ? 'selected' : '' }}>{{ $display }}</option>
                                @endforeach
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 6: DESCRIPCIÓN --}}
                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-3 col-form-label">Descripción / Notas:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $tarifa->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sync-alt"></i> Actualizar Tarifa</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para editar Tarifas.</p>
    @endcan

</div>
@endsection