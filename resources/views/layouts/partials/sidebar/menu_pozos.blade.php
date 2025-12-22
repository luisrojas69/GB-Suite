    @canany(['acceder_menu_pozos'])
        <div class="sidebar-heading">
            {{ __('Módulo Logística (Taller)') }}
        </div>
        {{-- ... Items Logística ... --}}
        @can('gestionar_pozos')
            <li class="nav-item {{ Nav::isRoute('produccion.pozos.activos.index') }}">
                <a class="nav-link" href="{{ route('produccion.pozos.activos.index') }}">
                    <i class="fas fa-truck-monster"></i>
                    <span>{{ __('Inventario de Pozos') }}</span>
                </a>
            </li>
        @endcan
        @can('gestionar_mtto_pozos')
            <li class="nav-item {{ Nav::isRoute('produccion.pozos.mantenimientos.index') }}">
                <a class="nav-link" href="{{ route('produccion.pozos.mantenimientos.index') }}">
                    <i class="fas fa-running"></i>
                    <span>{{ __('Mantenimientos de Pozos)') }}</span>
                </a>
            </li>
        @endcan

       @can('gestionar_aforos')
            <li class="nav-item {{ Nav::isRoute('produccion.pozos.aforos.index') }}">
                <a class="nav-link" href="{{ route('produccion.pozos.aforos.index') }}">
                    <i class="fas fa-running"></i>
                    <span>{{ __('Gestión de Aforos') }}</span>
                </a>
            </li>
        @endcan

        @can('ver_dashboard_pozos')
            <li class="nav-item {{ Nav::isRoute('produccion.pozos.dashboard') }}">
                <a class="nav-link" href="{{ route('produccion.pozos.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>{{ __('Dashboard Pozos') }}</span>
                </a>
            </li>
        @endcan


        <hr class="sidebar-divider">
    @endcanany