@extends('layouts.app') 

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('Editar Rol y Sincronizar Permisos') }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Editando Rol:') }} {{ $role->name }}</h6>
        </div>
        <div class="card-body">
            
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                @csrf
                @method('PUT') {{-- Usamos PUT para actualizar --}}

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label text-md-right">{{ __('Nombre del Rol') }}</label>
                    <div class="col-sm-9">
                        {{-- Usamos $role->name o old('name') si hubo un error de validación --}}
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name', $role->name) }}" required>
                    </div>
                </div>

                <hr class="sidebar-divider">

                <h4 class="mb-3 text-gray-700">{{ __('Sincronizar Permisos') }}</h4>
                <p class="text-muted small mb-4">{{ __('Marque los permisos que desea que este rol tenga. Al guardar, los permisos no marcados serán revocados.') }}</p>

                <div class="row">
                    @php
                        // Agrupamos permisos por el campo 'module' (o 'GLOBAL' si es nulo)
                        $groupedPermissions = $permissions->groupBy(function ($item) {
                            return $item->module ?: 'GLOBAL';
                        });
                    @endphp

                    @foreach ($groupedPermissions as $module => $modulePermissions)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card bg-light border-left-primary h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __("MÓDULO: {$module}") }}</div>
                                            
                                            @foreach ($modulePermissions as $permission)
                                                <div class="form-check mt-1">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                           id="perm_{{ $permission->id }}" class="form-check-input"
                                                           {{-- Lógica CLAVE: Marcar si el permiso está en el array $rolePermissions --}}
                                                           {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                           
                                                    <label class="form-check-label small" for="perm_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr class="sidebar-divider">

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-sync-alt"></i>
                        </span>
                        <span class="text">{{ __('Sincronizar y Actualizar Rol') }}</span>
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-lg ml-2">
                        {{ __('Cancelar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection