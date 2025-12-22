@extends('layouts.app') 

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('Crear Nuevo Rol y Asignar Permisos') }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Definición del Rol') }}</h6>
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

            <form method="POST" action="{{ route('admin.roles.store') }}">
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label text-md-right">{{ __('Nombre del Rol') }}</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name') }}" required autofocus placeholder="Ej: gerente_logistica">
                    </div>
                </div>

                <hr class="sidebar-divider">

                <h4 class="mb-3 text-gray-700">{{ __('Permisos Disponibles') }}</h4>
                <p class="text-muted small mb-4">{{ __('Marque los permisos que tendrá este nuevo rol. Recuerde que si el rol no tiene el permiso de acceso al menú (acceder_menu_...), las otras acciones no serán visibles.') }}</p>

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
                                                           id="perm_{{ $permission->id }}" class="form-check-input">
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
                            <i class="fas fa-save"></i>
                        </span>
                        <span class="text">{{ __('Guardar Rol y Permisos') }}</span>
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-lg ml-2">
                        {{ __('Cancelar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection