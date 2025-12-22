@extends('layouts.app')
@section('title', 'Editar Liquidación: #' . $liquidacion->id)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">✍️ Editar Liquidación: **#{{ $liquidacion->id }}**</h1>
        <a href="{{ route('liquidacion.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Histórico
        </a>
    </div>

    @can('generar_liquidaciones')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actualizar Datos de Calidad y Valoración</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('liquidacion.update', $liquidacion->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Campo 1: MOLIENDA REF (Solo lectura) --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Arrime de Molienda:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="Arrime #{{ $liquidacion->molienda_id }}" disabled>
                            <small class="form-text text-muted">La referencia del arrime no se puede modificar.</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary mt-4 mb-3">Datos de Calidad (%)</h6>
                    
                    {{-- Campo 2: POL CAÑA --}}
                    <div class="form-group row">
                        <label for="pol_cana" class="col-sm-3 col-form-label">Polarización (%) en caña: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('pol_cana') is-invalid @enderror" id="pol_cana" name="pol_cana" value="{{ old('pol_cana', $liquidacion->pol_cana) }}" required>
                            @error('pol_cana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 3: FIBRA CAÑA --}}
                    <div class="form-group row">
                        <label for="fibra_cana" class="col-sm-3 col-form-label">Fibra (%) en caña: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('fibra_cana') is-invalid @enderror" id="fibra_cana" name="fibra_cana" value="{{ old('fibra_cana', $liquidacion->fibra_cana) }}" required>
                            @error('fibra_cana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary mt-4 mb-3">Datos Financieros</h6>
                    
                    {{-- Campo 4: PRECIO BASE --}}
                    <div class="form-group row">
                        <label for="precio_base" class="col-sm-3 col-form-label">Precio Base (T.T.P. / Azúcar): <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" step="0.0001" min="0" max="999999" class="form-control @error('precio_base') is-invalid @enderror" id="precio_base" name="precio_base" value="{{ old('precio_base', $liquidacion->precio_base) }}" required>
                            @error('precio_base')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 5: DEDUCIBLES --}}
                    <div class="form-group row">
                        <label for="deducibles" class="col-sm-3 col-form-label">Total Deducibles (Fletes, etc.):</label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" min="0" max="999999" class="form-control @error('deducibles') is-invalid @enderror" id="deducibles" name="deducibles" value="{{ old('deducibles', $liquidacion->deducibles) }}">
                            @error('deducibles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 6: LIQUIDACIÓN NETA --}}
                    <div class="form-group row">
                        <label for="liquidacion_neta" class="col-sm-3 col-form-label">Valor Neto a Liquidar: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" min="0" max="999999" class="form-control @error('liquidacion_neta') is-invalid @enderror" id="liquidacion_neta" name="liquidacion_neta" value="{{ old('liquidacion_neta', $liquidacion->liquidacion_neta) }}" required>
                            <small class="form-text text-muted">Este es el valor final a pagar por el arrime.</small>
                            @error('liquidacion_neta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 7: FECHA CIERRE --}}
                    <div class="form-group row">
                        <label for="fecha_cierre" class="col-sm-3 col-form-label">Fecha de Cierre:</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control @error('fecha_cierre') is-invalid @enderror" id="fecha_cierre" name="fecha_cierre" value="{{ old('fecha_cierre', $liquidacion->fecha_cierre ? $liquidacion->fecha_cierre->format('Y-m-d') : null) }}">
                            @error('fecha_cierre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sync-alt"></i> Actualizar Liquidación</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para editar Liquidaciones.</p>
    @endcan

</div>
@endsection