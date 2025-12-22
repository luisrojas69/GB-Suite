@extends('layouts.app')
@section('title', 'Generar Nueva Liquidación')

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📝 Generar Nueva Liquidación</h1>
        <a href="{{ route('liquidacion.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('generar_liquidaciones')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Datos de Calidad y Valoración (Boleta Central)</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('liquidacion.store') }}" method="POST">
                    @csrf
                    
                    {{-- Campo 1: MOLIENDA REF --}}
                    <div class="form-group row">
                        <label for="molienda_id" class="col-sm-3 col-form-label">Arrime de Molienda: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control select2 @error('molienda_id') is-invalid @enderror" id="molienda_id" name="molienda_id" required>
                                <option value="">Seleccione un Arrime pendiente...</option>
                                @foreach ($moliendas_sin_liquidar as $id => $display)
                                    <option value="{{ $id }}" {{ old('molienda_id') == $id ? 'selected' : '' }}>{{ $display }}</option>
                                @endforeach
                            </select>
                            @error('molienda_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary mt-4 mb-3">Datos de Calidad (%)</h6>
                    
                    {{-- Campo 2: POL CAÑA --}}
                    <div class="form-group row">
                        <label for="pol_cana" class="col-sm-3 col-form-label">Polarización (%) en caña: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('pol_cana') is-invalid @enderror" id="pol_cana" name="pol_cana" value="{{ old('pol_cana') }}" required>
                            @error('pol_cana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo 3: FIBRA CAÑA --}}
                    <div class="form-group row">
                        <label for="fibra_cana" class="col-sm-3 col-form-label">Fibra (%) en caña: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('fibra_cana') is-invalid @enderror" id="fibra_cana" name="fibra_cana" value="{{ old('fibra_cana') }}" required>
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
                            <input type="number" step="0.0001" min="0" max="999999" class="form-control @error('precio_base') is-invalid @enderror" id="precio_base" name="precio_base" value="{{ old('precio_base') }}" required>
                            @error('precio_base')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 5: DEDUCIBLES --}}
                    <div class="form-group row">
                        <label for="deducibles" class="col-sm-3 col-form-label">Total Deducibles (Fletes, etc.):</label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" min="0" max="999999" class="form-control @error('deducibles') is-invalid @enderror" id="deducibles" name="deducibles" value="{{ old('deducibles', 0) }}">
                            @error('deducibles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo 6: LIQUIDACIÓN NETA --}}
                    <div class="form-group row">
                        <label for="liquidacion_neta" class="col-sm-3 col-form-label">Valor Neto a Liquidar: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" min="0" max="999999" class="form-control @error('liquidacion_neta') is-invalid @enderror" id="liquidacion_neta" name="liquidacion_neta" value="{{ old('liquidacion_neta') }}" required>
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
                            <input type="date" class="form-control @error('fecha_cierre') is-invalid @enderror" id="fecha_cierre" name="fecha_cierre" value="{{ old('fecha_cierre', date('Y-m-d')) }}">
                            @error('fecha_cierre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-money-bill-wave"></i> Generar Liquidación</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para generar Liquidaciones.</p>
    @endcan

</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap", 
                width: '100%',
                placeholder: "Seleccione una opción",
                allowClear: true 
            });
        });
    </script>
@endsection