    <div class="sidebar-heading">
        {{ __('Configuraciones') }}
    </div>
    @can('gestionar_seguridad')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.roles.index') }}">
                <i class="fas fa-user-lock"></i>
                <span>{{ __('Roles') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.permissions.index') }}">
                <i class="fas fa-user-shield"></i>
                <span>{{ __('Permisos') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="fas fa-user-gear"></i>
                <span>{{ __('Usuarios') }}</span>
            </a>
        </li>
    @endcan
    <hr class="sidebar-divider d-none d-md-block">