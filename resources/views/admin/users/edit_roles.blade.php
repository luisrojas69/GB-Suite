@extends('layouts.app') 

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('Asignar Roles') }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ __('Modificando Roles para el Usuario:') }} **{{ $user->name }}**
            </h6>
        </div>
        <div class="card-body">
            
            <form method="POST" action="{{ route('admin.users.update-roles', $user) }}">
                @csrf
                @method('PUT')

                <h4 class="mb-3 text-gray-700">{{ __('Selecciona los Roles a Asignar') }}</h4>
                <p class="text-muted small">{{ __('Marque las casillas para asignar el rol al usuario. Desmarque para revocarlo.') }}</p>

                <div class="row">
                    @foreach ($roles as $role)
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                {{-- Comprueba si el usuario tiene el rol para marcar la casilla --}}
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                    id="role_{{ $role->id }}" class="form-check-input"
                                    {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                    
                                <label class="form-check-label font-weight-bold" for="role_{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <hr class="sidebar-divider">

                <div class="mt-4">
                    <button type="submit" class="btn btn-success btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-check"></i>
                        </span>
                        <span class="text">{{ __('Actualizar Roles') }}</span>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2">
                        {{ __('Cancelar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection