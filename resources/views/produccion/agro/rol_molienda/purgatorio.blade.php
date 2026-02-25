@extends('layouts.app')
@section('title-page', 'Mapeo de Rol de Molienda')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    :root {
        --agro-dark: #1b4332;
        --agro-primary: #2d6a4f;
    }
    .row-rojo { border-left: 5px solid #e74a3b !important; background-color: rgba(231, 74, 59, 0.03); }
    .row-amarillo { border-left: 5px solid #f6c23e !important; background-color: rgba(246, 194, 62, 0.03); }
    .row-verde { border-left: 5px solid #1cc88a !important; }
    
    .table thead th {
        background-color: var(--agro-dark);
        color: white;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        border: none;
    }
    .badge-status { width: 12px; height: 12px; border-radius: 50%; display: inline-block; box-shadow: 0 0 5px rgba(0,0,0,0.2); }
    
    /* Ajuste para que el Select2 se vea bien dentro del Input Group con el botón "+" */
    .input-group > .select2-container--bootstrap4 { flex: 1 1 auto; width: 1% !important; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-body bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="font-weight-bold text-primary mb-1">
                        <i class="fas fa-tasks mr-2"></i>Validación del Plan de Zafra
                    </h4>
                    <p class="text-muted mb-0">Zafra Activa: <strong>{{ $zafraActiva->nombre }}</strong>. Mapea los tablones y variedades antes de guardar.</p>
                </div>
                <div class="col-auto">
                    <button type="button" onclick="confirmarProcesamiento()" class="btn btn-success shadow">
                        <i class="fas fa-save mr-1"></i> Guardar Planificación
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        @php $counts = collect($purgatorio)->countBy('status_color'); @endphp
        <div class="col-md-4">
            <div class="card border-left-success shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Coincidencia Exacta</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['verde'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Se Actualizarán</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['amarillo'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-danger shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Requieren Mapeo Manual</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="count-errores">{{ $counts['rojo'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-5">
        <div class="card-body p-0">
            <form id="form-purgatorio" action="{{ route('rol_molienda.process') }}" method="POST">
                @csrf
                <input type="hidden" name="zafra_id" value="{{ $zafraActiva->id }}">
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="dataTablePurgatorio">
                        <thead>
                            <tr>
                                <th class="text-center">St</th>
                                <th>Sector / Tablón (Excel)</th>
                                <th>Asignación de Tablón</th>
                                <th>Variedad (Excel)</th>
                                <th>Asignación Variedad</th>
                                <th>Estimación</th>
                                <th>Mensaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purgatorio as $index => $item)
                            <tr class="row-{{ $item['status_color'] }}">
                                <td class="text-center">
                                    <span class="badge-status bg-{{ $item['status_color'] == 'verde' ? 'success' : ($item['status_color'] == 'amarillo' ? 'warning' : 'danger') }}"></span>
                                </td>
                                
                                <td>
                                    <span class="badge badge-light border">{{ $item['sector_csv'] }}</span>
                                    <i class="fas fa-arrow-right mx-1 text-muted small"></i>
                                    <span class="badge badge-light border">{{ $item['tablon_csv'] }}</span>
                                </td>
                                
                                <td>
                                    @if(empty($item['tablon_id']))
                                        <select name="correccion_tablon[{{ $index }}]" class="form-control form-control-sm select2-agro select-tablon" required>
                                            <option value="">⚠️ Buscar Tablón...</option>
                                            @foreach($todosLosTablones as $t)
                                                <option value="{{ $t->id }}">{{ $t->lote->sector->nombre }} - {{ $t->codigo_tablon_interno }} ({{ $t->nombre }})</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success mr-2"></i>
                                            <span class="text-dark font-weight-bold small">{{ $item['tablon_nombre_completo'] }}</span>
                                            <input type="hidden" name="tablon_id[{{ $index }}]" value="{{ $item['tablon_id'] }}">
                                        </div>
                                    @endif
                                </td>

                                <td><span class="font-weight-bold text-dark">{{ $item['variedad_csv'] }}</span></td>

                                <td style="min-width: 250px;">
                                    @if(empty($item['variedad_id']))
                                        <div class="input-group input-group-sm">
                                            <select name="correccion_variedad[{{ $index }}]" class="form-control select2-agro select-variedad" id="select-var-{{ $index }}" required>
                                                <option value="">⚠️ Buscar Variedad...</option>
                                                @foreach($todasLasVariedades as $v)
                                                    <option value="{{ $v->id }}">{{ $v->nombre }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-success btn-add-variedad" type="button" data-select-id="select-var-{{ $index }}" data-nombre-sugerido="{{ $item['variedad_csv'] }}">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-leaf text-success mr-2"></i>
                                            <span class="text-dark font-weight-bold small">{{ $item['variedad_nombre'] }}</span>
                                            <input type="hidden" name="variedad_id[{{ $index }}]" value="{{ $item['variedad_id'] }}">
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <small class="d-block text-primary"><strong>{{ number_format($item['toneladas_estimadas'], 2) }} Tns</strong></small>
                                    <small class="text-muted">{{ $item['area_estimada_has'] }} Ha × {{ $item['ton_ha_estimadas'] }} T/Ha</small>
                                </td>
                                
                                <td>
                                    <small class="{{ $item['status_color'] == 'rojo' ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                        {{ $item['mensajes_error'] }}
                                    </small>
                                </td>
                            </tr>
                            
                            <input type="hidden" name="data[{{ $index }}][clase_ciclo]" value="{{ $item['clase_ciclo'] }}">
                            <input type="hidden" name="data[{{ $index }}][area_estimada_has]" value="{{ $item['area_estimada_has'] }}">
                            <input type="hidden" name="data[{{ $index }}][ton_ha_estimadas]" value="{{ $item['ton_ha_estimadas'] }}">
                            <input type="hidden" name="data[{{ $index }}][toneladas_estimadas]" value="{{ $item['toneladas_estimadas'] }}">
                            <input type="hidden" name="data[{{ $index }}][rendimiento_esperado]" value="{{ $item['rendimiento_esperado'] }}">
                            <input type="hidden" name="data[{{ $index }}][fecha_corte_proyectada]" value="{{ $item['fecha_corte_proyectada'] }}">
                            <input type="hidden" name="data[{{ $index }}][status_color]" value="{{ $item['status_color'] }}">
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevaVariedad" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-bottom-success">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-success font-weight-bold"><i class="fas fa-leaf mr-2"></i>Nueva Variedad de Caña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-ajax-variedad">
                <div class="modal-body">
                    <input type="hidden" id="target_select_id">
                    
                    <div class="form-group">
                        <label>Nombre de la Variedad <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nombre" id="ajax_var_nombre" required placeholder="Ej: V-99236">
                        <small class="text-muted">Procura usar el mismo nombre del Excel para futuros mapeos.</small>
                    </div>
                    <div class="form-group">
                        <label>Código (Opcional)</label>
                        <input type="text" class="form-control" name="codigo" placeholder="Ej: V99">
                    </div>
                    <div class="form-group">
                        <label>Meta de Polarización (Opcional)</label>
                        <input type="number" step="0.01" class="form-control" name="meta_pol_cana" placeholder="Ej: 12.50">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btn-save-var">Guardar Variedad</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar Select2
        $('.select2-agro').select2({ theme: 'bootstrap4' });

        // Inicializar DataTables
        $('#dataTablePurgatorio').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json" },
            "pageLength": 50,
            "dom": '<"p-3 d-flex justify-content-between"fB>rtip',
        });

        // Setup AJAX CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 1. Abrir Modal de Variedad y preparar los datos
        $('.btn-add-variedad').on('click', function() {
            let targetSelect = $(this).data('select-id');
            let nombreSugerido = $(this).data('nombre-sugerido');
            
            // Llenar el formulario del modal
            $('#target_select_id').val(targetSelect);
            $('#ajax_var_nombre').val(nombreSugerido);
            
            // Mostrar Modal
            $('#modalNuevaVariedad').modal('show');
        });

        // 2. Procesar Formulario AJAX
        $('#form-ajax-variedad').on('submit', function(e) {
            e.preventDefault();
            
            let btnSubmit = $('#btn-save-var');
            btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            let formData = $(this).serialize();
            
            // IMPORTANTE: Asegúrate de tener esta ruta configurada en tu web.php
            $.ajax({
                url: "{{ route('variedades.storeAjax') }}", 
                type: "POST",
                data: formData,
                success: function(response) {
                    if(response.success) {
                        // 1. Obtener los datos de la nueva variedad
                        let nuevaVarId = response.data.id;
                        let nuevaVarNombre = response.data.nombre;
                        
                        // 2. Crear la nueva etiqueta <option>
                        let newOption = new Option(nuevaVarNombre, nuevaVarId, true, true);
                        
                        // 3. Añadir la opción a TODOS los selects de variedades de la tabla
                        $('.select-variedad').append(new Option(nuevaVarNombre, nuevaVarId, false, false));
                        
                        // 4. Seleccionar la opción en el Select específico que invocó el modal
                        let targetSelectId = $('#target_select_id').val();
                        $('#' + targetSelectId).append(newOption).trigger('change');
                        
                        // Cerrar modal y limpiar
                        $('#modalNuevaVariedad').modal('hide');
                        $('#form-ajax-variedad')[0].reset();
                        
                        toastr.success('Variedad creada y asignada exitosamente.');
                    }
                },
                error: function(xhr) {
                    let errorMessage = "Ocurrió un error al guardar.";
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                },
                complete: function() {
                    btnSubmit.prop('disabled', false).html('Guardar Variedad');
                }
            });
        });
    });

    // Función SweetAlert antes de enviar el formulario principal
    function confirmarProcesamiento() {
        // Validar HTML5 nativo primero (campos required)
        var form = document.getElementById('form-purgatorio');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: '¿Guardar Plan de Zafra?',
            text: "Se registrarán todas las asignaciones correctas.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2d6a4f',
            confirmButtonText: '<i class="fas fa-save mr-1"></i> Confirmar',
            cancelButtonText: 'Revisar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endpush