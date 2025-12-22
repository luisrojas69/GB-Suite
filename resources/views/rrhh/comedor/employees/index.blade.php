@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Planilla - Comedor</h1>
        <a class="btn btn-primary btn-sm" href="{{ route('comedor.employees.index') }}" ><i class="fas fa-sync-alt fa-sm"></i> Refrescar</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('comedor.employees.index') }}" class="row align-items-end">
                <div class="col-md-4">
                    <label class="small">Buscar por Nombre o ID</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Ej: Juan Pérez o 105">
                </div>
                <div class="col-md-3">
                    <label class="small">Departamento</label>
                    <select name="department" class="form-control">
                        <option value="">Todos</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info btn-block">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm" width="100%" cellspacing="0">
                    <thead class="bg-light text-center">
                        <tr>
                            <th>ID Biométrico</th>
                            <th>Nombre</th>
                            <th>Departamento</th>
                            <th>N° Tarjeta</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach($employees as $emp)
                        <tr>
                            <td><strong>{{ $emp->biometric_id }}</strong></td>
                            <td class="text-left">{{ $emp->name }}</td>
                            <td><span class="badge badge-light border">{{ $emp->department ?? 'N/A' }}</span></td>
                            <td>{{ $emp->card_number ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $emp->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $emp->is_active ? 'ACTIVO' : 'INACTIVO' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-circle btn-primary" onclick="editEmployee({{ $emp->id }})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <button class="btn btn-sm btn-circle {{ $emp->is_active ? 'btn-danger' : 'btn-success' }}" 
                                        onclick="toggleStatus({{ $emp->id }})" title="{{ $emp->is_active ? 'Inactivar' : 'Activar' }}">
                                    <i class="fas {{ $emp->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                </button>

                                <button class="btn btn-sm btn-circle btn-warning" onclick="pushToDevice({{ $emp->id }})" title="Sincronizar con Hardware">
                                    <i class="fas fa-upload"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $employees->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Editar Empleado</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="editForm">
                @csrf
                <input type="hidden" id="emp_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre Completo (Máx 24 caracteres para ZK)</label>
                        <input type="text" name="name" id="name" class="form-control" maxlength="24" required>
                    </div>
                    <div class="form-group">
                        <label>Departamento</label>
                        <input type="text" name="department" id="department" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Número de Tarjeta (RFID)</label>
                        <input type="number" name="card_number" id="card_number" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Actualizar Localmente</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editEmployee(id) {
        $.get(`/RRHH/Comedor/employees/${id}/edit`, function(data) {
            $('#emp_id').val(data.id);
            $('#name').val(data.name);
            $('#department').val(data.department);
            $('#card_number').val(data.card_number);
            $('#editModal').modal('show');
        });
    }

    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#emp_id').val();
        $.ajax({
            url: `/RRHH/Comedor/employees/${id}`,
            type: 'PUT',
            data: $(this).serialize() + '&_token={{ csrf_token() }}',
            success: function(res) {
                $('#editModal').modal('hide');
                Swal.fire('Actualizado', res.success, 'success').then(() => location.reload());
            }
        });
    });

    function toggleStatus(id) {
        Swal.fire({
            title: '¿Cambiar estado?',
            text: "Esto afectará el registro de comidas.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cambiar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`/RRHH/Comedor/employees/${id}/toggle`, { _token: '{{ csrf_token() }}' }, function(res) {
                    Swal.fire('Hecho', res.success, 'success').then(() => location.reload());
                });
            }
        });
    }

    function pushToDevice(id) {
        Swal.fire({
            title: 'Sincronizar con ZK',
            text: "¿Enviar los datos actuales al equipo biométrico?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Sincronizando...', didOpen: () => Swal.showLoading() });
                $.post("{{ route('comedor.device.push') }}", { _token: '{{ csrf_token() }}', employee_id: id }, function(res) {
                    Swal.fire('Éxito', res.success, 'success');
                }).fail(() => Swal.fire('Error', 'No se pudo conectar con el equipo', 'error'));
            }
        });
    }
</script>
@endpush