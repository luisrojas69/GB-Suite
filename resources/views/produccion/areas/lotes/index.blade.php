@extends('layouts.app') 
@section('title-page', 'Gestión de Lotes')

@section('styles')
<style>
    /* ========================================
       VARIABLES GLOBALES - TEMA AGRO/ECO
    ======================================== */
    :root {
        --agro-dark: #1b4332;      /* Verde Bosque Profundo */
        --agro-primary: #2d6a4f;   /* Verde Esmeralda */
        --agro-light: #d8f3dc;     /* Verde Suave / Pastel */
        --agro-accent: #52b788;    /* Verde Vibrante */
        --agro-earth: #bc6c25;     /* Tono Tierra */
        --agro-blue: #023e8a;      /* Azul profundo para contraste */
    }

    /* ========================================
       HEADER PRINCIPAL
    ======================================== */
    .page-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; 
        padding: 25px 30px; 
        border-radius: 15px;
        margin-bottom: 25px; 
        box-shadow: 0 8px 25px rgba(45, 106, 79, 0.25);
        position: relative; 
        overflow: hidden;
    }
    .page-header-agro::before {
        content: '\f5fd'; /* fa-layer-group */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute; 
        top: -15px; 
        right: 20px;
        font-size: 8rem; 
        color: rgba(255,255,255,0.06);
        transform: rotate(-10deg);
    }

    /* ========================================
       TARJETAS DE ESTADÍSTICAS (KPIs)
    ======================================== */
    .card-stat-agro {
        border: none;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        background: #fff;
    }
    .card-stat-agro:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .border-agro-1 { border-bottom: 4px solid var(--agro-blue); }
    .border-agro-2 { border-bottom: 4px solid var(--agro-accent); }
    .border-agro-3 { border-bottom: 4px solid var(--agro-dark); } 
    .border-agro-4 { border-bottom: 4px solid var(--agro-earth); } 
    
    .icon-circle-agro {
        width: 50px; height: 50px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }

    /* ========================================
       TABLA Y ESTRUCTURAS
    ======================================== */
    .table-agro thead th {
        background-color: #f8f9fc;
        color: var(--agro-dark);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--agro-light);
    }
    .table-agro tbody tr {
        transition: background-color 0.2s;
    }
    .table-agro tbody tr:hover {
        background-color: rgba(82, 183, 136, 0.05);
    }
    .badge-agro-soft {
        background-color: var(--agro-light);
        color: var(--agro-dark);
        font-weight: 600;
    }
</style>
@endsection

@section('content')

{{-- CÁLCULOS DINÁMICOS PARA LOS KPIs --}}
@php
    $totalLotes = $lotes->count();
    $totalTablones = 0;
    $totalHectareas = 0;
    $sectoresIds = [];

    foreach($lotes as $lote) {
        $totalTablones += $lote->tablones->count();
        $totalHectareas += $lote->tablones->sum('hectareas_documento');
        $sectoresIds[] = $lote->sector_id;
    }

    $sectoresAbarcados = count(array_unique($sectoresIds));
@endphp

<div class="container-fluid">

    <div class="page-header-agro d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div>
            <h2 class="font-weight-bold mb-1"><i class="fas fa-layer-group mr-2"></i> Lotes Productivos</h2>
            <p class="mb-0 text-white-50" style="font-size: 1.1rem;">
                Agrupación intermedia de tablones y áreas de cultivo.
            </p>
        </div>
        @can('produccion.areas.crear')
        <div class="mt-3 mt-md-0 btn-group shadow-sm">
            <button type="button" class="btn btn-light text-success font-weight-bold shadow-sm rounded-pill px-4" onclick="abrirModalCrear()">
                <i class="fas fa-plus-circle mr-1"></i> Crear Lote
            </button>
        </div>
        @endcan
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show border-left-success shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-2"></i> <strong>¡Éxito!</strong> {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro border-agro-1 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--agro-blue);">Lotes Registrados</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ $totalLotes }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(2, 62, 138, 0.1); color: var(--agro-blue);">
                                <i class="fas fa-layer-group"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro border-agro-2 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--agro-accent);">Subdivisiones</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ $totalTablones }} <small class="text-muted text-xs">Tablones</small></div>
                            <div class="text-xs text-gray-500 font-weight-bold mt-1">Total contenido en lotes</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(82, 183, 136, 0.1); color: var(--agro-accent);">
                                <i class="fas fa-th"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro border-agro-3 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--agro-dark);">Superficie Total</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ number_format($totalHectareas, 2) }} <small class="text-muted text-xs">Has</small></div>
                            <div class="text-xs text-gray-500 font-weight-bold mt-1"><i class="fas fa-file-contract mr-1"></i>Basado en área documental</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(27, 67, 50, 0.1); color: var(--agro-dark);">
                                <i class="fas fa-ruler-combined"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro border-agro-4 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--agro-earth);">Sectores Abarcados</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ $sectoresAbarcados }} <small class="text-muted text-xs">Sectores</small></div>
                            <div class="text-xs text-gray-500 font-weight-bold mt-1">Orígenes de la tierra</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(188, 108, 37, 0.1); color: var(--agro-earth);">
                                <i class="fas fa-map"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h6 class="m-0 font-weight-bold" style="color: var(--agro-dark);">Directorio de Lotes</h6>
            <span class="badge badge-agro-soft px-3 py-2 rounded-pill"><i class="fas fa-list-ul mr-1"></i> {{ $totalLotes }} Registros</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                @can('produccion.areas.ver')
                <table class="table table-agro align-middle mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center"><i class="fas fa-folder text-muted"></i></th>
                            <th width="25%">Identificación del Lote</th>
                            <th width="25%">Sector Origen</th>
                            <th width="25%">Estructura y Superficie</th>
                            <th width="10%" class="text-center">Estado</th>
                            <th width="10%" class="text-right pr-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lotes as $lote)
                        <tr>
                            <td class="text-center">
                                <div class="icon-circle-agro mx-auto shadow-sm border" style="width: 40px; height: 40px; background: #fff; color: var(--agro-blue);">
                                    <h6 class="m-0 font-weight-bold">{{ $lote->codigo_lote_interno }}</h6>
                                </div>
                            </td>
                            
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge badge-dark p-1 mr-2" title="Código Completo">{{ $lote->codigo_completo }}</span>
                                        <h6 class="mb-0 font-weight-bold text-gray-800">{{ $lote->nombre }}</h6>
                                    </div>
                                    <small class="text-muted text-truncate" style="max-width: 250px;">
                                        {{ $lote->descripcion ?? 'Sin descripción adicional' }}
                                    </small>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-muted mr-2 fa-lg"></i>
                                    <div>
                                        <span class="font-weight-bold text-primary">{{ $lote->sector->nombre }}</span><br>
                                        <small class="text-muted">Cod. Sector: <strong>{{ $lote->sector->codigo_sector }}</strong></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3 text-center border-right pr-3">
                                        <div class="h5 mb-0 font-weight-bold text-dark">{{ $lote->tablones->count() }}</div>
                                        <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Tablones</small>
                                    </div>
                                    <div>
                                        @if($lote->tablones->count() > 0)
                                            <div class="font-weight-bold text-success"><i class="fas fa-chart-area mr-1"></i> {{ number_format($lote->tablones->sum('hectareas_documento'), 2) }} Has</div>
                                            <small class="text-muted">Suma documental</small>
                                        @else
                                            <span class="text-muted small italic"><i class="fas fa-exclamation-circle text-warning mr-1"></i> Lote vacío</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                @if($lote->tablones->count() > 0)
                                    <span class="badge badge-success px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-check-circle mr-1"></i> Activo</span>
                                @else
                                    <span class="badge badge-warning px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-pause-circle mr-1"></i> En Espera</span>
                                @endif
                            </td>

                            <td class="text-right pr-4">
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle btn btn-light btn-sm rounded-circle shadow-sm" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v text-gray-600"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                        <div class="dropdown-header">Gestión de Lote</div>
                                        
                                        @can('produccion.areas.ver')
                                        <a class="dropdown-item text-info font-weight-bold" href="{{ route('produccion.areas.lotes.show', $lote->id) }}">
                                            <i class="fas fa-eye fa-sm fa-fw mr-2"></i> Ver Detalles
                                        </a>
                                        @endcan
                                        
                                        @can('produccion.areas.editar')
                                        <button type="button" class="dropdown-item btn-editar-lote" 
                                            data-id="{{ $lote->id }}"
                                            data-sector="{{ $lote->sector_id }}"
                                            data-interno="{{ $lote->codigo_lote_interno }}"
                                            data-nombre="{{ $lote->nombre }}"
                                            data-descripcion="{{ $lote->descripcion }}"
                                            data-completo="{{ $lote->codigo_completo }}">
                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Editar Lote
                                        </button>
                                        @endcan
                                        
                                        @can('produccion.areas.eliminar')
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('produccion.areas.lotes.destroy', $lote->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Eliminar lote {{ $lote->nombre }}? Se borrará su jerarquía interna.')">
                                                <i class="fas fa-trash fa-sm fa-fw mr-2"></i> Eliminar Lote
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="text-center py-5">
                        <div class="icon-circle-agro mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2.5rem; background: #eaecf4; color: #b7b9cc;">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h5 class="font-weight-bold text-gray-700">Acceso Restringido</h5>
                        <p class="text-gray-500">No tienes los permisos necesarios para visualizar los lotes.</p>
                    </div>
                @endcan
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