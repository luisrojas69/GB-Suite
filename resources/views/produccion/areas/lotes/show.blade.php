@extends('layouts.app')
@section('title-page', 'Detalle del Lote: ' . $lote->nombre)

@section('styles')
<style>
    :root {
        --agro-dark: #1b4332;
        --agro-primary: #2d6a4f;
        --agro-accent: #52b788;
        --agro-bg: #f8f9fc;
    }

    /* HEADER CON GRADIENTE */
    .show-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; padding: 30px; border-radius: 15px;
        margin-bottom: 25px; box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        position: relative;
    }

    /* INFO CARDS (Resumen rápido) */
    .info-card-pill {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px; padding: 15px;
        backdrop-filter: blur(5px);
    }

    /* CONTENEDORES DE INFORMACIÓN */
    .detail-card {
        border: none; border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
    }

    .label-custom {
        font-size: 0.75rem; font-weight: 800;
        text-transform: uppercase; color: #858796;
        letter-spacing: 0.5px; margin-bottom: 5px;
    }

    .value-custom {
        font-size: 1.1rem; font-weight: 600; color: var(--agro-dark);
    }

    /* TABLA DE TABLONES */
    .table-tablones thead th {
        background-color: var(--agro-bg);
        color: var(--agro-primary);
        font-weight: 700; text-transform: uppercase;
        font-size: 0.8rem; border: none;
    }

    .badge-code {
        background: var(--agro-dark); color: white;
        padding: 5px 12px; border-radius: 6px;
        font-family: 'Courier New', Courier, monospace;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">

    <div class="mb-3">
        <a href="{{ route('produccion.areas.lotes.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left mr-1"></i> Volver al listado
        </a>
    </div>

    <div class="show-header-agro shadow">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-warning mr-3 px-3 py-2 shadow-sm text-dark font-weight-bold">
                        LOTE PRODUCTIVO
                    </span>
                    <h1 class="h2 font-weight-bold mb-0">{{ $lote->nombre }}</h1>
                </div>
                <p class="text-white-50 mb-0">
                    <i class="fas fa-map-marker-alt mr-1"></i> Pertenece al {{ $lote->sector->nombre }} (Código Sector: {{ $lote->sector->codigo_sector }})
                </p>
            </div>
            <div class="col-md-5 mt-3 mt-md-0">
                <div class="row">
                    <div class="col-6">
                        <div class="info-card-pill text-center">
                            <small class="d-block text-white-50 uppercase">Total Tablones</small>
                            <span class="h4 font-weight-bold">{{ $lote->tablones->count() }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-card-pill text-center">
                            <small class="d-block text-white-50 uppercase">Hectáreas Totales</small>
                            <span class="h4 font-weight-bold">{{ number_format($lote->tablones->sum('hectareas_documento'), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card detail-card mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle mr-2"></i> Información General</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="label-custom">Código Único Completo</div>
                        <div class="value-custom"><span class="badge-code">{{ $lote->codigo_completo }}</span></div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="label-custom">Código Interno</div>
                            <div class="value-custom text-info">{{ $lote->codigo_lote_interno }}</div>
                        </div>
                        <div class="col-6">
                            <div class="label-custom">Sector Padre</div>
                            <div class="value-custom">{{ $lote->sector->codigo_sector }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <div class="label-custom">Descripción / Observaciones</div>
                        <p class="text-muted" style="line-height: 1.6;">
                            {{ $lote->descripcion ?? 'Sin observaciones registradas para este lote.' }}
                        </p>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex justify-content-between">
                        @can('produccion.areas.editar')
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 btn-editar-lote" 
                            data-id="{{ $lote->id }}"
                            data-sector="{{ $lote->sector_id }}"
                            data-interno="{{ $lote->codigo_lote_interno }}"
                            data-nombre="{{ $lote->nombre }}"
                            data-descripcion="{{ $lote->descripcion }}"
                            data-completo="{{ $lote->codigo_completo }}">
                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Editar Lote
                        </button>
                        @endcan

                        <span class="small text-muted align-self-center">Creado: {{ $lote->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card detail-card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-th mr-2 text-success"></i> Tablones Asociados
                    </h6>
                    <span class="badge badge-light border px-3 py-2 text-primary font-weight-bold">
                        {{ $lote->tablones->count() }} Registrados
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-tablones mb-0">
                            <thead>
                                <tr>
                                    <th class="pl-4">Cód. Único</th>
                                    <th>Nombre / ID</th>
                                    <th class="text-center">Hectáreas (Doc)</th>
                                    <th class="text-center">Hectáreas (GIS)</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-right pr-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lote->tablones as $tablon)
                                <tr>
                                    <td class="pl-4 align-middle">
                                        <span class="font-weight-bold text-dark">{{ $tablon->codigo_completo }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="font-weight-bold text-primary">{{ $tablon->nombre }}</div>
                                        <small class="text-muted">Interno: {{ $tablon->codigo_tablon_interno }}</small>
                                    </td>
                                    <td class="text-center align-middle font-weight-bold">
                                        {{ number_format($tablon->hectareas_documento, 2) }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ number_format($tablon->hectareas_geometria ?? 0, 2) }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-pill badge-success shadow-sm px-3">Productivo</span>
                                    </td>
                                    <td class="text-right pr-4 align-middle">
                                        <a href="{{ route('produccion.areas.tablones.show', $tablon->id) }}" class="btn btn-circle btn-light btn-sm shadow-sm border" title="Ver Tablón">
                                            <i class="fas fa-search text-info"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-th fa-3x text-gray-200 mb-3"></i>
                                            <h5 class="text-gray-500">No hay tablones registrados en este lote.</h5>
                                            @can('produccion.areas.crear')
                                            <a href="#" class="btn btn-sm btn-success mt-2 rounded-pill">
                                                <i class="fas fa-plus mr-1"></i> Agregar primer tablón
                                            </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($lote->tablones->count() > 0)
                <div class="card-footer bg-white py-3">
                    <div class="row text-center text-uppercase small font-weight-bold">
                        <div class="col-4 border-right">
                            <span class="text-muted">Suma Documental:</span> <br>
                            <span class="text-dark">{{ number_format($lote->tablones->sum('hectareas_documento'), 2) }} Has</span>
                        </div>
                        <div class="col-4 border-right text-success">
                            <span class="text-muted">Área Utilizada:</span> <br>
                            <span>100%</span>
                        </div>
                        <div class="col-4">
                            <span class="text-muted text-info">Diferencia GIS:</span> <br>
                            <span class="text-dark">{{ number_format($lote->tablones->sum('hectareas_documento') - $lote->tablones->sum('hectareas_geometria'), 2) }} Has</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalLote" tabindex="-1" role="dialog" aria-labelledby="modalLoteTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            
            <div class="modal-header border-0 pb-3" style="background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%); color: white;">
                <h5 class="modal-title font-weight-bold d-flex align-items-center" id="modalLoteTitle">
                    <i class="fas fa-layer-group mr-2"></i> <span>Crear Nuevo Lote</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formLote">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="modal-body p-4 bg-light">
                    <div id="cajaCodigoActual" class="bg-white p-3 rounded shadow-sm border mb-4" style="display: none;">
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Código Único Actual</div>
                        <div class="h5 mb-0 font-weight-bold text-primary" id="lblCodigoCompleto"></div>
                        <small class="text-warning font-weight-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Modificar el sector o código interno cambiará este identificador y el de sus tablones.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="font-weight-bold text-dark small text-uppercase">Sector Padre <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text bg-white"><i class="fas fa-map-marker-alt text-primary"></i></span></div>
                                <select class="form-control" id="sector_id" name="sector_id" required>
                                    <option value="">--- Seleccione un Sector ---</option>
                                    @foreach ($sectores as $id => $nombre)
                                        <option value="{{ $id }}">{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="invalid-feedback error-sector_id font-weight-bold"></span>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label class="font-weight-bold text-dark small text-uppercase">Código Interno <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text bg-white"><i class="fas fa-hashtag text-info"></i></span></div>
                                <input type="text" class="form-control" id="codigo_lote_interno" name="codigo_lote_interno" required maxlength="5" placeholder="Ej: 02, B">
                            </div>
                            <span class="invalid-feedback error-codigo_lote_interno font-weight-bold"></span>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small text-uppercase">Nombre del Lote <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text bg-white"><i class="fas fa-signature text-success"></i></span></div>
                            <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="100" placeholder="Ej: Lote 02 - Zona Norte">
                        </div>
                        <span class="invalid-feedback error-nombre font-weight-bold"></span>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark small text-uppercase">Descripción / Notas</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Observaciones adicionales del lote..."></textarea>
                        <span class="invalid-feedback error-descripcion font-weight-bold"></span>
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 bg-light">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-5 rounded-pill shadow-sm font-weight-bold" id="btnGuardarLote">
                        <i class="fas fa-save mr-2"></i> <span id="btnGuardarText">Guardar Lote</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Variables globales para definir URLs
    const urlStore = "{{ route('produccion.areas.lotes.store') }}";
    // Dejamos un placeholder para el ID que luego reemplazaremos en edición
    const urlUpdateBase = "{{ route('produccion.areas.lotes.update', ':id') }}"; 
    let urlActionActual = '';

    // Limpiar alertas de error previas
    function limpiarErrores() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }

    // ABRIR MODAL PARA CREAR
    function abrirModalCrear() {
        limpiarErrores();
        $('#formLote')[0].reset(); // Limpia los inputs
        
        $('#modalLoteTitle span').text('Crear Nuevo Lote');
        $('#btnGuardarText').text('Guardar Lote');
        $('#formMethod').val('POST');
        urlActionActual = urlStore; // Apuntamos a la ruta Store
        
        $('#cajaCodigoActual').hide(); // Ocultamos el cuadro informativo
        
        $('#modalLote').modal('show');
    }

    // ABRIR MODAL PARA EDITAR (Usando delegación de eventos)
    $(document).on('click', '.btn-editar-lote', function() {
        limpiarErrores();
        
        // Obtener datos del botón pulsado
        let id = $(this).data('id');
        let sector = $(this).data('sector');
        let interno = $(this).data('interno');
        let nombre = $(this).data('nombre');
        let descripcion = $(this).data('descripcion');
        let completo = $(this).data('completo');

        // Llenar el formulario
        $('#sector_id').val(sector);
        $('#codigo_lote_interno').val(interno);
        $('#nombre').val(nombre);
        $('#descripcion').val(descripcion);
        
        // Ajustes visuales
        $('#modalLoteTitle span').text('Editar Lote: ' + nombre);
        $('#btnGuardarText').text('Actualizar Lote');
        $('#formMethod').val('PUT'); // Laravel interpreta esto para el Update
        urlActionActual = urlUpdateBase.replace(':id', id); // Reemplazamos el placeholder
        
        // Mostrar código actual
        $('#lblCodigoCompleto').text(completo);
        $('#cajaCodigoActual').show();

        $('#modalLote').modal('show');
    });

    // MANEJAR EL ENVÍO DEL FORMULARIO POR AJAX
    $('#formLote').on('submit', function(e) {
        e.preventDefault(); // Evitamos que la página se recargue
        limpiarErrores();
        
        let formData = $(this).serialize(); // Recopila todos los datos, incluido @csrf
        let btnGuardar = $('#btnGuardarLote');
        let btnText = $('#btnGuardarText').text();

        $.ajax({
            url: urlActionActual,
            method: 'POST', // Siempre enviamos por POST (Laravel lo lee como PUT gracias a _method)
            data: formData,
            beforeSend: function() {
                $('#btnGuardarLote').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...');
            },
            success: function(response) {
                $('#modalLote').modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: response.message || 'El lote ha sido guardado correctamente.',
                    confirmButtonColor: '#2d6a4f'
                }).then(() => {
                    location.reload(); // Recargamos para ver los cambios en la tabla
                });
            },
            error: function(xhr) {
                btnGuardar.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> ' + btnText);
                
                if (xhr.status === 422) { // Error de validación (Laravel form request)
                    let errors = xhr.responseJSON.errors;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Verifica los datos',
                        text: 'Algunos campos son inválidos o están vacíos.',
                        confirmButtonColor: '#bc6c25'
                    });
                    
                    // Mostrar errores debajo de cada input
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('is-invalid');
                        $('.error-' + key).text(value[0]).show();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error del Servidor',
                        text: 'No se pudo completar la acción. Intente nuevamente.',
                        confirmButtonColor: '#e74a3b'
                    });
                }
            }
        });
    });
</script>
@endpush