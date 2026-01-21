@extends('layouts.app')

@section('content')
    {{-- Mensajes de Notificación --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Administración de Roles') }}</h1>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nuevo Rol
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-light text-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Rol</th>
                            <th>Permisos</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td class="align-middle font-weight-bold">#{{ $role->id }}</td>
                            <td class="align-middle">
                                <span class="badge badge-dark p-2">{{ $role->name }}</span>
                            </td>
                            <td class="align-middle">
                                @forelse ($role->permissions->take(4) as $permission)
                                    <span class="badge badge-light border text-primary">{{ str_replace('_', ' ', $permission->name) }}</span>
                                @empty
                                    <span class="text-muted small italic">Sin permisos asignados</span>
                                @endforelse
                                
                                @if ($role->permissions->count() > 4)
                                    <span class="badge badge-info">+ {{ $role->permissions->count() - 4 }} más</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group shadow-sm" role="group">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if ($role->name !== 'super_administrador')
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('¿Eliminar el rol {{ $role->name }}?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection