@extends('layouts.app') 

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('Gestión de Roles del Sistema') }}</h1>

    <div class="mb-4">
        <a href="{{ route('admin.roles.create') }}" class="btn btn-success btn-icon-split shadow-sm">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">{{ __('Crear Nuevo Rol') }}</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Roles Definidos') }}</h6>
        </div>
        <div class="card-body">
            
            @if (session('success'))
                {{-- Alerta de éxito al guardar o eliminar --}}
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Nombre del Rol') }}</th>
                            <th>{{ __('Permisos Asignados') }}</th>
                            <th>{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                {{-- Contamos y listamos solo los primeros 3 permisos para no saturar --}}
                                @forelse ($role->permissions->take(3) as $permission)
                                    <span class="badge badge-primary text-white me-1">{{ $permission->name }}</span>
                                @empty
                                    <span class="badge badge-secondary">Ninguno</span>
                                @endforelse
                                
                                @if ($role->permissions->count() > 3)
                                    <span class="badge badge-light text-dark">
                                        + {{ $role->permissions->count() - 3 }} más
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{-- Botón de Edición (Asignar permisos) --}}
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-info shadow-sm me-2" title="Editar Permisos">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Botón de Eliminación (Acción Crítica) --}}
                                @if ($role->name !== 'super_administrador') 
                                {{-- Evitar que el rol principal sea eliminado --}}
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Está seguro de eliminar el rol \'{{ $role->name }}\'? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Eliminar Rol">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection