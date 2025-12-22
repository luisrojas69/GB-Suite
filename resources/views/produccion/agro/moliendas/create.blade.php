@extends('layouts.app')
@section('title', 'Registrar Nuevo Arrime de Molienda')

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🚚 Registro de Arrime de Molienda</h1>
        <a href="{{ route('produccion.agro.moliendas.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Histórico
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-warning">
            Por favor, corrija los siguientes errores antes de guardar el registro.
        </div>
    @endif

    @can('crear_moliendas')
        <form id="molienda-form" action="{{ route('produccion.agro.moliendas.store') }}" method="POST">
            @csrf
            <input type="hidden" name="zafra_id" value="{{ $zafra_activa->id }}">

            <div class="row">
                
                {{-- Columna 1: Información de Ubicación y Contrato (60%) --}}
                <div class="col-lg-8">
                    <div class="card shadow mb-4 border-left-success">
                        <div class="card-header py-3 bg-success text-white">
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-map-marker-alt"></i> Datos de Origen y Destino</h6>
                        </div>
                        <div class="card-body">

                            {{-- Fila 1: Zafra y Fecha --}}
                            <div class="form-row mb-3">
                                <div class="col-md-6">
                                    <label>Zafra Activa:</label>
                                    <input type="text" class="form-control" value="{{ $zafra_activa->nombre }} ({{ $zafra_activa->anio_inicio }}/{{ $zafra_activa->anio_fin }})" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha">Fecha de Arrime: <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d\TH:i')) }}" required>
                                    @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Fila 2: Tablón y Destino --}}
                            <div class="form-row mb-3">
                                <div class="col-md-4">
                                    <label for="tablon_id">Tablón de Origen: <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('tablon_id') is-invalid @enderror" id="tablon_id" name="tablon_id" required>
                                        <option value="">Seleccione Tablón...</option>
                                        @foreach ($tablones as $id => $display)
                                            <option value="{{ $id }}" {{ old('tablon_id') == $id ? 'selected' : '' }}>{{ $display }}</option>
                                        @endforeach
                                    </select>
                                    @error('tablon_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="destino_id">Destino (Central): <span class="text-danger">*</span></label>
                                    <select class="form-control @error('destino_id') is-invalid @enderror" id="destino_id" name="destino_id" required>
                                        <option value="">Seleccione Destino...</option>
                                        @foreach ($destinos as $id => $nombre)
                                            <option value="{{ $id }}" {{ old('destino_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('destino_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="variedad_id">Variedad Arrimada: <span class="text-danger">*</span></label>
                                    <select class="form-control @error('variedad_id') is-invalid @enderror" id="variedad_id" name="variedad_id" required>
                                        <option value="">Seleccione Variedad...</option>
                                        @foreach ($variedades as $id => $nombre)
                                            <option value="{{ $id }}" {{ old('variedad_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('variedad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>


                        {{-- Fila 3: Conductor y Placa --}}
                            <div class="form-row mb-3">
                                <div class="col-md-4">
                                    <label for="contratista_id">Contratista de Cosecha: <span class="text-danger">*</span></label>
                                    <select class="form-control @error('contratista_id') is-invalid @enderror" id="contratista_id" name="contratista_id" required>
                                        <option value="">Seleccione Contratista...</option>
                                        @foreach ($contratistas as $id => $nombre)
                                            <option value="{{ $id }}" {{ old('contratista_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('contratista_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                                          
                                <div class="col-md-4">
                                    <label for="boleto">Nombre del Conductor:</label>
                                    <input type="text" class="form-control @error('conductor_nombre') is-invalid @enderror" id="conductor_nombre" name="conductor_nombre" value="{{ old('conductor_nombre') }}" placeholder="Ej: Luis Rojas- (Opcional)" required>
                                    @error('conductor_nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="boleto">Placa del Veh&iacute;culo:</label>
                                    <input type="text" class="form-control @error('vehiculo_placa') is-invalid @enderror" id="vehiculo_placa" name="vehiculo_placa" value="{{ old('vehiculo_placa') }}" placeholder="Ej: ABC123 - (Opcional)" required>
                                    @error('vehiculo_placa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                               
     
                            </div>
                            
                            {{-- Fila 4: Boleto --}}
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="boleto">Número de Boleto / Remesa: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('boleto_remesa') is-invalid @enderror" id="boleto_remesa" name="boleto_remesa" value="{{ old('boleto_remesa') }}" placeholder="Ej: 015350" required>
                                    @error('boleto_remesa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="form-text text-muted">Este número debe ser único para evitar duplicados.</small>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                {{-- Columna 2: Datos de Peso y Calidad (40%) --}}
                <div class="col-lg-4">
                    <div class="card shadow mb-4 border-left-primary">
                        <div class="card-header py-3 bg-primary text-white">
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-balance-scale"></i> Pesaje y Calidad</h6>
                        </div>
                        <div class="card-body">
                            
                            {{-- Peso Bruto --}}
                            <div class="form-group">
                                <label for="peso_bruto">Peso Bruto (kg): <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('peso_bruto') is-invalid @enderror" id="peso_bruto" name="peso_bruto" value="{{ old('peso_bruto') }}" required min="0.01">
                                @error('peso_bruto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Peso Tara --}}
                            <div class="form-group">
                                <label for="peso_tara">Peso Tara (kg): <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('peso_tara') is-invalid @enderror" id="peso_tara" name="peso_tara" value="{{ old('peso_tara') }}" required min="0">
                                @error('peso_tara') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Peso Neto (Calculado y Estilizado) --}}
                            <div class="form-group">
                                <label>Peso Neto (kg):</label>
                                <div class="card bg-info text-white shadow p-3 text-center">
                                    <h4 class="font-weight-bold mb-0" id="peso_neto_display">0.00 kg</h4>
                                </div>
                                <input type="hidden" name="toneladas" id="peso_neto_input">
                            </div>
                            
                            <hr>

                            {{-- Brix, Pol, Rendimiento (Opcionales) --}}
                            <label class="d-block mb-2 text-primary"><i class="fas fa-flask"></i> Análisis de Calidad (Opcional)</label>
                            <div class="form-row">
                                <div class="col-4">
                                    <input type="number" step="0.01" class="form-control @error('brix') is-invalid @enderror" id="brix" name="brix" value="{{ old('brix') }}" placeholder="Brix %">
                                    @error('brix') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="form-text text-muted text-center">Brix</small>
                                </div>
                                <div class="col-4">
                                    <input type="number" step="0.01" class="form-control @error('pol') is-invalid @enderror" id="pol" name="pol" value="{{ old('pol') }}" placeholder="Pol %">
                                    @error('pol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="form-text text-muted text-center">Pol</small>
                                </div>
                                <div class="col-4">
                                    <input type="number" step="0.01" class="form-control @error('rendimiento') is-invalid @enderror" id="rendimiento" name="rendimiento" value="{{ old('rendimiento') }}" placeholder="Rendimiento %">
                                    @error('rendimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="form-text text-muted text-center">Rendimiento</small>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botón de Guardar --}}
            <div class="row mb-5">
                <div class="col-lg-12 text-right">
                    <button type="submit" class="btn btn-success btn-lg shadow-sm" id="btn-submit">
                        <i class="fas fa-truck-loading"></i> Finalizar y Registrar Arrime
                    </button>
                </div>
            </div>

        </form>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para registrar arrimes de molienda.</p>
    @endcan

</div>

@push('scripts')
<script>
    $(document).ready(function() {
        
        // 1. Cálculo de Peso Neto en Tiempo Real
        function calcularPesoNeto() {
            const bruto = parseFloat($('#peso_bruto').val()) || 0;
            const tara = parseFloat($('#peso_tara').val()) || 0;
            let neto = bruto - tara;

            if (neto < 0) neto = 0; // Evitar netos negativos visualmente

            // Formatear y actualizar display
            const netoFormateado = neto.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' kg';
            $('#peso_neto_display').text(netoFormateado);
            $('#peso_neto_input').val(neto.toFixed(2));
        }

        // Ejecutar el cálculo al cargar y al cambiar los campos
        calcularPesoNeto();
        $('#peso_bruto, #peso_tara').on('input', calcularPesoNeto);

        // 2. Manejo del Formulario con AJAX y SweetAlert2
        $('#molienda-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitButton = $('#btn-submit');

            // Mostrar spinner y deshabilitar botón
            submitButton.html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
            submitButton.prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Importante para el Controlador
                },
                success: function(response) {
                    submitButton.html('<i class="fas fa-truck-loading"></i> Finalizar y Registrar Arrime');
                    submitButton.prop('disabled', false);

                    if (response.success) {
                        Swal.fire({
                            title: response.title,
                            html: response.message,
                            icon: 'success',
                            confirmButtonText: 'Ver Histórico'
                        }).then(() => {
                            window.location.href = response.redirect;
                        });
                    }
                },
                error: function(xhr) {
                    submitButton.html('<i class="fas fa-truck-loading"></i> Finalizar y Registrar Arrime');
                    submitButton.prop('disabled', false);
                    
                    let errorMsg = 'Error al procesar la solicitud.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Limpiar errores previos
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        
                        // Mostrar errores de validación en el formulario
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            let input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                        });
                        errorMsg = 'Revise los campos marcados en rojo.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    Swal.fire(
                        '¡Atención!',
                        errorMsg,
                        'warning'
                    );
                }
            });
        });
    });
</script>
{{-- Nota: Necesitas incluir la librería SweetAlert2 y la librería Select2 (si se usa en Tablón) --}}
@endpush
@endsection