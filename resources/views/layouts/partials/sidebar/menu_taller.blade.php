    @canany(['acceder_menu_logistica'])
        <div class="sidebar-heading">
            {{ __('Módulo Logística (Taller)') }}
        </div>
        {{-- ... Items Logística ... --}}
        @can('gestionar_activos')
            <li class="nav-item {{ Nav::isRoute('activos.index') }}">
                <a class="nav-link" href="{{ route('activos.index') }}">
                    <i class="fas fa-truck-monster"></i>
                    <span>{{ __('Inventario de Activos') }}</span>
                </a>
            </li>
        @endcan
        @can('gestionar_lecturas')
            <li class="nav-item {{ Nav::isRoute('lecturas.index') }}">
                <a class="nav-link" href="{{ route('lecturas.index') }}">
                    <i class="fas fa-running"></i>
                    <span>{{ __('Registro de Uso (KM/HRS)') }}</span>
                </a>
            </li>
        @endcan
        @can('gestionar_ordenes')
            <li class="nav-item {{ Nav::isRoute('ordenes.index') }}">
                <a class="nav-link" href="{{ route('ordenes.index') }}">
                    <i class="fas fa-tools"></i>
                    <span>{{ __('Órdenes de Servicio') }}</span>
                </a>
            </li>
        @endcan
        @can('programar_mp')
            <li class="nav-item {{ Nav::isRoute('checklists.index') }}">
                <a class="nav-link" href="{{ route('checklists.index') }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>{{ __('Plantillas MP') }}</span>
                </a>
            </li>
        @endcan
        @can('ver_reportes_taller')
            <li class="nav-item {{ Nav::isRoute('reportes.gerencial') }}">
                <a class="nav-link" href="{{ route('reportes.gerencial') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>{{ __('Reporte Gerencial') }}</span>
                </a>
            </li>
        @endcan
        <hr class="sidebar-divider">
    @endcanany