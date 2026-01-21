
@extends('layouts.app') 

@section('content')
    {{-- Mensajes de Notificación --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h1 class="h3 mb-4 text-gray-800">{{ __('Gestión de Usuarios y Asignación de Roles') }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Listado de Usuarios') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Roles Asignados') }}</th>
                            <th>{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name." ".$user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @forelse ($user->roles as $role)
                                    {{-- Usamos la clase de SB Admin 2 para badges (badge-info y text-white) --}}
                                    <span class="badge badge-info text-white me-1">{{ $role->name }}</span>
                                @empty
                                    <span class="badge badge-warning">Sin Rol</span>
                                @endforelse
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit-roles', $user) }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-user-tag fa-sm text-white-50"></i> {{ __('Asignar Roles') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    {{-- Si planea usar DataTables, debe incluir también <tfoot> --}}
                    {{-- <tfoot> 
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Roles Asignados</th>
                            <th>Acciones</th>
                        </tr>
                    </tfoot> --}}
                </table>
            </div>
        </div>
    </div>
@endsection

{{-- Ejemplo de cómo se verían los scripts para DataTables si los necesitara --}}
@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endpush