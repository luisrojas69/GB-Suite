@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-key mr-2"></i>Diccionario de Permisos</h1>
        <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalPermiso">
            <i class="fas fa-plus fa-sm text-white-50"></i> Crear Permiso
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-left-success shadow">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach ($groupedPermissions as $module => $perms)
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card shadow h-100 border-top-primary">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">{{ $module }}</h6>
                        <span class="badge badge-primary badge-pill">{{ $perms->count() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush small">
                            @foreach($perms as $p)
                                <div class="list-group-item d-flex justify-content-between align-items-center bg-light mb-1 border-0 rounded">
                                    <span class="text-dark font-weight-bold">{{ $p->name }}</span>
                                    <div class="btn-group">
                                        <button class="btn btn-sm text-info btn-edit-perm" data-id="{{ $p->id }}" 
                                                data-name="{{ $p->name }}" data-module="{{ $p->module }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.permissions.destroy', $p) }}" method="POST" onsubmit="return confirm('¿Eliminar permiso?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="modalPermiso" tabindex="-1">
    <div class="modal-dialog">
        <form id="formPermiso" action="{{ route('admin.permissions.store') }}" method="POST" class="modal-content border-left-primary">
            @csrf
            <div id="methodField"></div>
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="modalTitle">Nuevo Permiso</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="small font-weight-bold">Módulo (Grupo)</label>
                    <input type="text" name="module" id="inputModule" class="form-control text-uppercase" placeholder="EJ: INVENTARIO" required>
                </div>
                <div class="form-group">
                    <label class="small font-weight-bold">Nombre del Permiso</label>
                    <input type="text" name="name" id="inputName" class="form-control" placeholder="ej: crear_item" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary shadow">Guardar Permiso</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Lógica para reutilizar el modal en edición
    $('.btn-edit-perm').on('click', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let module = $(this).data('module');
        
        $('#modalTitle').text('Editar Permiso');
        $('#formPermiso').attr('action', `/admin/permisos/${id}`);
        $('#methodField').html('@method("PUT")');
        $('#inputName').val(name);
        $('#inputModule').val(module);
        $('#modalPermiso').modal('show');
    });

    // Resetear modal al cerrar
    $('#modalPermiso').on('hidden.bs.modal', function () {
        $('#modalTitle').text('Nuevo Permiso');
        $('#formPermiso').attr('action', "{{ route('admin.permissions.store') }}");
        $('#methodField').html('');
        $('#inputName, #inputModule').val('');
    });
</script>
@endpush