@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tipos de Comida</h1>
        @can('crear_meal_types')
        <button class="btn btn-primary btn-sm shadow-sm" onclick="createMealType()">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nuevo Tipo
        </button>
        @endcan
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Configuración de Horarios y Precios</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="mealTypesTable" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark text-center">
                        <tr>
                            <th>Nombre</th>
                            <th>Código ZK (F)</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mealTypes as $type)
                        <tr class="text-center">
                            <td>{{ $type->name }}</td>
                            <td><span class="badge badge-dark">F{{ $type->status_code + 1 }} (Code: {{ $type->status_code }})</span></td>
                            <td>{{ $type->start_time }}</td>
                            <td>{{ $type->end_time }}</td>
                            <td>${{ number_format($type->price, 2) }}</td>
                            <td>
                                <span class="badge {{ $type->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $type->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                @can('editar_meal_types')
                                <button class="btn btn-info btn-circle btn-sm" onclick="editMealType({{ $type->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endcan
                                
                                @can('eliminar_meal_types')
                                <button class="btn btn-danger btn-circle btn-sm" onclick="deleteMealType({{ $type->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mealTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-left-primary shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Tipo de Comida</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="mealTypeForm">
                @csrf
                <input type="hidden" id="meal_type_id" name="meal_type_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Servicio</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Código ZK (Status)</label>
                            <input type="number" class="form-control" name="status_code" id="status_code" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Precio ($)</label>
                            <input type="number" step="0.01" class="form-control" name="price" id="price" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Hora Inicio</label>
                            <input type="time" class="form-control" name="start_time" id="start_time" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Hora Fin</label>
                            <input type="time" class="form-control" name="end_time" id="end_time" required>
                        </div>
                    </div>
                    <div class="form-group custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                        <label class="custom-control-label" for="is_active">Servicio Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function createMealType() {
        $('#mealTypeForm')[0].reset();
        $('#meal_type_id').val('');
        $('#modalTitle').text('Nuevo Tipo de Comida');
        $('#mealTypeModal').modal('show');
    }

    function editMealType(id) {
        $.get(`/RRHH/Comedor/meal_types/${id}/edit`, function(data) {
            $('#modalTitle').text('Editar Tipo de Comida');
            $('#meal_type_id').val(data.id);
            $('#name').val(data.name);
            $('#status_code').val(data.status_code);
            $('#price').val(data.price);
            $('#start_time').val(data.start_time);
            $('#end_time').val(data.end_time);
            $('#is_active').prop('checked', data.is_active);
            $('#mealTypeModal').modal('show');
        });
    }

    $('#mealTypeForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#meal_type_id').val();
        let url = id ? `/RRHH/Comedor/meal_types/${id}` : '/RRHH/Comedor/meal_types';
        let type = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: type,
            data: $(this).serialize(),
            success: function(response) {
                $('#mealTypeModal').modal('hide');
                Swal.fire('¡Éxito!', response.success, 'success').then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message || 'Ocurrió un error', 'error');
            }
        });
    });

    function deleteMealType(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/RRHH/Comedor/meal_types/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire('Eliminado', response.success, 'success').then(() => location.reload());
                    }
                });
            }
        });
    }
</script>
@endpush