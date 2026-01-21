@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Encabezado Dinámico --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas {{ $group == 'IT' ? 'fa-laptop' : 'fa-couch' }} mr-2"></i>
            {{ $group == 'IT' ? 'Inventario de Sistemas (IT)' : ($group == 'ALL' ? 'Inventario General de Activos' : 'Control de Activos Fijos') }}
        </h1>
        
        <div class="btn-group shadow-sm">
            <a href="{{ route('inv.export', ['group' => $group]) }}" class="btn btn-sm btn-success">
                <i class="fas fa-file-excel fa-sm"></i> Exportar
            </a>
            
            @if($group != 'ALL')
            <button class="btn btn-sm {{ $group == 'IT' ? 'btn-primary' : 'btn-success' }}" data-toggle="modal" data-target="#modalNuevoEquipo">
                <i class="fas fa-plus fa-sm text-white-50"></i> Nuevo {{ $group == 'IT' ? 'Equipo' : 'Activo' }}
            </button>
            @endif
        </div>
    </div>

    {{-- Tarjeta de Tabla --}}
    <div class="card shadow mb-4 border-bottom-{{ $group == 'IT' ? 'primary' : 'success' }}">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-{{ $group == 'IT' ? 'primary' : 'success' }}">
                Listado de Activos {{ $group != 'ALL' ? "($group)" : '' }}
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- Contenedor de Acciones Masivas --}}
                <div id="bulk-actions" class="mb-3" style="display: none;">
                    <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm">
                        <span>
                            <i class="fas fa-layer-group mr-2"></i> 
                            Has seleccionado <strong id="countSelected">0</strong> activos para asignar.
                        </span>
                        <button class="btn btn-primary btn-sm" onclick="openMassAssignModal()">
                            <i class="fas fa-user-plus mr-1"></i> Asignar Lote
                        </button>
                    </div>
                </div>

                <table class="table table-hover table-bordered" id="tablaInventario" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th width="30px"></th> {{-- Checkbox --}}
                            <th>Foto</th>
                            <th>Nro. Activo</th>
                            <th>Descripción</th>
                            <th>Marca/Modelo</th>
                            <th>Serial</th>
                            <th>Estado</th>
                            <th>Responsable</th>
                            <th>Ubicación</th>
                            <th width="10%">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Incluimos los Modales --}}
@include('sistemas.inventario.partials.modal_asignar')

{{-- Modal Editar (ID único corregido) --}}
<div class="modal fade" id="modalEditarEquipo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-left-secondary">
            <form id="formEditarEquipo">
                @csrf
                @method('PUT')
                <input type="hidden" name="item_id" id="edit_item_id">
                
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold text-secondary"><i class="fas fa-edit"></i> Editar Activo</h5>
                    <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small font-weight-bold">Descripción / Nombre</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold">Marca</label>
                            <input type="text" name="brand" id="edit_brand" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold">Modelo</label>
                            <input type="text" name="model" id="edit_model" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Número de Serial</label>
                        <input type="text" name="serial" id="edit_serial" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-secondary">Actualizar Datos</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Nuevo Equipo --}}
<div class="modal fade" id="modalNuevoEquipo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-left-{{ $group == 'IT' ? 'primary' : 'success' }}">
            <form id="formNuevoEquipo" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="item_group" value="{{ $group }}">

                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold text-{{ $group == 'IT' ? 'primary' : 'success' }}">
                        <i class="fas {{ $group == 'IT' ? 'fa-microchip' : 'fa-chair' }}"></i> Registrar en {{ $group }}
                    </h5>
                    <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7 form-group">
                            <label class="font-weight-bold">Descripción del Activo</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej: Laptop, Escritorio..." required>
                        </div>
                        <div class="col-md-5 form-group">
                            <label class="font-weight-bold">Categoría</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Marca</label>
                            <input type="text" name="brand" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Modelo</label>
                            <input type="text" name="model" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Estado Inicial</label>
                            <select name="status" class="form-control">
                                <option value="disponible">Disponible / Almacén</option>
                                <option value="dañado">Dañado</option>
                                <option value="mantenimiento">Mantenimiento</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Serial</label>
                            <input type="text" name="serial" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Placa Activo Fijo</label>
                            <input type="text" name="asset_tag" class="form-control">
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <label class="font-weight-bold">Foto Referencial</label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="inputFoto" accept="image/*">
                                <label class="custom-file-label" for="inputFoto">Elegir archivo...</label>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <img id="previewFoto" src="#" style="display: none; max-width: 80px; border-radius: 5px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                    <button class="btn {{ $group == 'IT' ? 'btn-primary' : 'btn-success' }}" type="submit">Guardar Activo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// Variable global para selección masiva
let selectedItems = [];

$(document).ready(function() {
    const lenguajeCastellano = {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_",
        "sSearch": "Buscar:",
        "oPaginate": { "sNext": "Sig.", "sPrevious": "Ant." }
    };

    var table = $('#tablaInventario').DataTable({
        language: lenguajeCastellano,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('inventario.index') }}",
            data: function (d) { d.group = "{{ $group }}"; }
        },
        columns: [
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    // Solo permitimos check si está disponible
                    if (row.status !== 'disponible') return '';
                    const checked = selectedItems.includes(data.toString()) ? 'checked' : '';
                    return `<div class="custom-control custom-checkbox ml-2">
                                <input type="checkbox" class="custom-control-input item-checkbox" id="check_${data}" value="${data}" ${checked}>
                                <label class="custom-control-label" for="check_${data}"></label>
                            </div>`;
                }
            },
            { 
                data: 'image_path', 
                render: function(data) {
                    let img = data ? `/storage/${data}` : '/img/no-image.png';
                    return `<img src="${img}" class="img-thumbnail" width="50" style="cursor:pointer" onclick="verImagen('${img}')">`;
                }
            },
            { data: 'asset_tag', name: 'asset_tag' },
            { data: 'name', name: 'name' },
            { data: 'brand_model', name: 'brand' },
            { data: 'serial', name: 'serial' },
            { data: 'status_badge', name: 'status', orderable: false },
            { data: 'responsable', name: 'responsable' },
            { data: 'ubicacion', name: 'ubicacion' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        drawCallback: function() {
            // Asegurar que los checks se mantengan al navegar
            selectedItems.forEach(id => {
                $(`#check_${id}`).prop('checked', true);
            });
        }
    });

    // Select2 Remoto (Configuración única)
    $('#selectResponsable').select2({
        dropdownParent: $('#modalAsignar'),
        placeholder: "Buscar empleado o depto...",
        minimumInputLength: 3,
        ajax: {
            url: "{{ route('inventario.buscarResponsable') }}",
            dataType: 'json',
            delay: 300,
            processResults: function (data) { return { results: data }; }
        }
    }).on('select2:select', function (e) {
        $('#target_type_input').val(e.params.data.type);
    });

    // --- MANEJO DE SELECCIÓN MASIVA ---
    $('#tablaInventario').on('change', '.item-checkbox', function() {
        const id = $(this).val();
        if ($(this).is(':checked')) {
            if(!selectedItems.includes(id)) selectedItems.push(id);
        } else {
            selectedItems = selectedItems.filter(item => item !== id);
        }
        updateBulkUI();
    });

    // --- ASIGNACIÓN INDIVIDUAL ---
    $(document).on('click', '.btn-asignar', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        
        // Limpiamos selección masiva para evitar confusiones
        selectedItems = []; 
        updateBulkUI();
        
        $('#item_id_input').val(id);
        $('#nombreEquipoModal').text(nombre);
        
        // Configuramos el submit para UN solo item
        $('#formAsignar').off('submit').on('submit', function(e) {
            e.preventDefault();
            procesarAsignacion("{{ route('inventario.assign') }}", $(this).serialize());
        });
        
        $('#modalAsignar').modal('show');
    });

    // Función genérica para AJAX de asignación (Evita repetir código)
    function procesarAsignacion(url, data) {
        Swal.fire({
            title: 'Procesando...',
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: url,
            method: "POST",
            data: data,
            success: function(res) {
                $('#modalAsignar').modal('hide');
                table.ajax.reload(null, false);
                
                Swal.fire({
                    title: '¡Éxito!',
                    text: res.message || "Asignación completada",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Ver Acta',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Lógica dinámica para acta individual o lote
                        let urlActa = res.assignment_id ? `/inventario/download-acta/${res.assignment_id}` : `/inventario/download-acta-lote/${res.user_id}`;
                        window.open(urlActa, '_blank');
                    }
                });
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Error en el servidor', 'error');
            }
        });
    }

    // --- NUEVO EQUIPO ---
    $('#formNuevoEquipo').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: "{{ route('inventario.store') }}",
            method: "POST",
            data: formData,
            processData: false, contentType: false,
            success: function(res) {
                $('#modalNuevoEquipo').modal('hide');
                $('#formNuevoEquipo')[0].reset();
                table.ajax.reload();
                Swal.fire('¡Éxito!', 'Registrado correctamente', 'success');
            }
        });
    });

    // --- RETORNO A ALMACÉN ---
    $(document).on('click', '.btn-retornar', function() {
        let itemId = $(this).data('id');
        Swal.fire({
            title: '¿Retornar a almacén?',
            input: 'textarea',
            inputPlaceholder: 'Observaciones del estado...',
            showCancelButton: true,
            confirmButtonText: 'Confirmar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('inventario.return') }}", {
                    _token: "{{ csrf_token() }}",
                    item_id: itemId,
                    notes: result.value 
                }, function(res) {
                    table.ajax.reload(null, false);
                    Swal.fire('¡Retornado!', 'El equipo vuelve a estar disponible', 'success');
                    if(res.assignment_id) window.open(`/inventario/download-acta-retorno/${res.assignment_id}`, '_blank');
                });
            }
        });
    });

    // --- EDICIÓN ---
    $(document).on('click', '.btn-editar', function() {
        let id = $(this).data('id');
        $.get(`/inventario/${id}/edit`, function(data) {
            $('#edit_item_id').val(data.id);
            $('#edit_name').val(data.name);
            $('#edit_brand').val(data.brand);
            $('#edit_model').val(data.model);
            $('#edit_serial').val(data.serial);
            $('#modalEditarEquipo').modal('show');
        });
    });

    $('#formEditarEquipo').on('submit', function(e) {
        e.preventDefault();
        $.post(`/inventario/${$('#edit_item_id').val()}`, $(this).serialize() + "&_method=PUT", function() {
            $('#modalEditarEquipo').modal('hide');
            table.ajax.reload(null, false);
            Swal.fire('¡Éxito!', 'Datos actualizados', 'success');
        });
    });

    // Preview de foto
    $('#inputFoto').on('change', function() {
        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = e => $('#previewFoto').attr('src', e.target.result).show();
            reader.readAsDataURL(file);
        }
    });
});

// Funciones Globales
function updateBulkUI() {
    const count = selectedItems.length;
    if (count > 0) {
        $('#bulk-actions').fadeIn();
        $('#countSelected').text(count);
    } else {
        $('#bulk-actions').fadeOut();
    }
}

function openMassAssignModal() {
    $('#item_id_input').val(''); 
    $('#nombreEquipoModal').html(`<span class="badge badge-info">Asignación Masiva: ${selectedItems.length} activos</span>`);
    
    $('#formAsignar').off('submit').on('submit', function(e) {
        e.preventDefault();
        const data = {
            _token: "{{ csrf_token() }}",
            items: selectedItems,
            assignable_id: $('#selectResponsable').val(),
            location_id: $('select[name="location_id"]').val(),
            target_type: $('#target_type_input').val()
        };
        
        if(!data.assignable_id || !data.location_id) {
            Swal.fire('Error', 'Complete los campos', 'error');
            return;
        }

        // Llamamos a la misma función de procesamiento
        $.ajax({
            url: "{{ route('inventario.massAssignment') }}",
            method: "POST",
            data: data,
            success: function(res) {
                $('#modalAsignar').modal('hide');
                selectedItems = [];
                updateBulkUI();
                $('#tablaInventario').DataTable().ajax.reload(null, false);
                Swal.fire('¡Éxito!', res.message, 'success');
            }
        });
    });
    
    $('#modalAsignar').modal('show');
}

function verImagen(url) {
    Swal.fire({ imageUrl: url, showConfirmButton: false, showCloseButton: true });
}
</script>
@endsection