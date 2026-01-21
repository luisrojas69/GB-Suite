@extends('layouts.app') 

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Seguridad: Asignar Roles') }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow mb-4 border-bottom-primary">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-tag mr-2"></i>{{ __('Gestionando accesos para:') }} 
                        <span class="text-dark">{{ $user->name }}</span> 
                        <small class="text-muted">({{ $user->email }})</small>
                    </h6>
                </div>
                <div class="card-body bg-light">
                    <form method="POST" action="{{ route('admin.users.update-roles', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="text-center mb-4">
                            <h5 class="text-gray-800 font-weight-bold">{{ __('Roles Disponibles en el Sistema') }}</h5>
                            <p class="text-muted">{{ __('Active los switches para otorgar el conjunto de permisos asociados a cada rol.') }}</p>
                        </div>

                        <div class="row px-4">
                            @foreach ($roles as $role)
                                <div class="col-xl-4 col-md-6 mb-3">
                                    {{-- Estructura de Switch Corregida --}}
                                    <div class="custom-control custom-switch bg-white p-3 border rounded shadow-sm pl-5">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                               id="role_{{ $role->id }}" class="custom-control-input"
                                               {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                        
                                        <label class="custom-control-label d-block cursor-pointer" for="role_{{ $role->id }}">
                                            <span class="font-weight-bold text-primary">{{ strtoupper($role->name) }}</span>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-shield-alt mr-1"></i>{{ $role->permissions->count() }} permisos incluidos
                                            </small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-success btn-icon-split shadow">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">{{ __('Guardar y Actualizar Permisos') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- Info Box --}}
            <div class="alert alert-info border-0 shadow-sm">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Nota:</strong> Al asignar un rol, el usuario heredará automáticamente todos los permisos contenidos en dicho rol. No es necesario asignar permisos individuales a menos que sea un caso excepcional.
            </div>
        </div>
    </div>
</div>
@endsection