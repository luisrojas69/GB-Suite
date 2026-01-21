@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Editar Rol:') }} <span class="text-primary">{{ $role->name }}</span></h1>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Volver
        </a>
    </div>

    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('Datos Generales') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold small">{{ __('Nombre del Rol') }}</label>
                            <input type="text" name="name" class="form-control border-left-primary" 
                                   value="{{ old('name', $role->name) }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block shadow mt-3">
                            <i class="fas fa-sync-alt mr-1"></i> {{ __('Sincronizar Permisos') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('Asignación de Permisos') }}</h6>
                        <input type="text" id="filterPerms" class="form-control form-control-sm w-50" placeholder="Filtrar permisos...">
                    </div>
                    <div class="card-body bg-light">
                        @php
                            $groupedPermissions = $permissions->groupBy(fn($p) => $p->module ?: 'GLOBAL');
                        @endphp

                        @foreach ($groupedPermissions as $module => $modulePermissions)
                            <div class="mb-4 module-container">
                                <h6 class="text-uppercase font-weight-bold text-gray-600 mb-3">
                                    <i class="fas fa-folder-open text-warning mr-2"></i>{{ $module }} 
                                    <span class="badge badge-secondary ml-1">{{ $modulePermissions->count() }}</span>
                                </h6>
                                <div class="row">
                                    @foreach ($modulePermissions as $permission)
                                        <div class="col-md-6 mb-2 perm-card">
                                            <div class="custom-control custom-switch bg-white p-2 border rounded shadow-sm pl-5">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                       id="perm_{{ $permission->id }}" class="custom-control-input"
                                                       {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                <label class="custom-control-label small text-dark d-block cursor-pointer" for="perm_{{ $permission->id }}">
                                                    {{ str_replace('_', ' ', $permission->name) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Filtro JS rápido
    document.getElementById('filterPerms').addEventListener('keyup', function(e) {
        let val = e.target.value.toLowerCase();
        document.querySelectorAll('.perm-card').forEach(card => {
            let txt = card.textContent.toLowerCase();
            card.style.display = txt.includes(val) ? 'block' : 'none';
        });
    });
</script>
@endpush