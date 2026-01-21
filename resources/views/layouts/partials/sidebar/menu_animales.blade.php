    @can('acceder_menu_produccion')
        <div class="sidebar-heading">
            {{ __('Módulo Pecuario') }}
        </div>
        {{-- ... Items Pecuario ... --}}
        @can('crear_animal')
            <li class="nav-item {{ Nav::isRoute('animals.create') }}">
                <a class="nav-link" href="{{ route('animals.create') }}">
                    <i class="fas fa-plus-square"></i>
                    <span>{{ __('Registro de Animal') }}</span>
                </a>
            </li>
        @endcan
        @can('crear_pesaje')
            <li class="nav-item {{ Nav::isRoute('weighings.create') }}">
                <a class="nav-link" href="{{ route('weighings.create') }}">
                    <i class="fas fa-balance-scale"></i>
                    <span>{{ __('Registro de Pesaje') }}</span>
                </a>
            </li>
        @endcan
        @can('crear_baja')
            <li class="nav-item {{ Nav::isRoute('bajas.create') }}">
                <a class="nav-link" href="{{ route('bajas.create') }}">
                    <i class="fas fa-skull"></i>
                    <span>{{ __('Registro de Baja') }}</span>
                </a>
            </li>
        @endcan
        @can('ver_bajas')
            <li class="nav-item {{ Nav::isRoute('bajas.index') }}">
                <a class="nav-link" href="{{ route('bajas.index') }}">
                    <i class="fas fa-history"></i>
                    <span>{{ __('Historial de Bajas') }}</span>
                </a>
            </li>
        @endcan
        <hr class="sidebar-divider">
    @endcan

    {{-- MENU DE TABLAS MAESTRAS MODULO PECUARIO --}}
    
    @canany(['ver_animales', 'gestionar_especies', 'gestionar_categorias', 'gestionar_ubicaciones', 'gestionar_dueños'])
        <div class="sidebar-heading">
            {{ __('Inventario y Maestras') }}
        </div>
        {{-- ... Items Inventario y Maestras ... --}}
        @can('ver_animales')
            <li class="nav-item {{ Nav::isRoute('animals.index') }}">
                <a class="nav-link" href="{{ route('animals.index') }}">
                    <i class="fas fa-boxes"></i>
                    <span>{{ __('Inventario General') }}</span>
                </a>
            </li>
        @endcan
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaestras" aria-expanded="true" aria-controls="collapseMaestras">
                <i class="fas fa-database"></i>
                <span>{{ __('Tablas Maestras') }}</span>
            </a>
            <div id="collapseMaestras" class="collapse
                {{ Nav::isRoute('categories.index') ? 'show' : '' }}
                {{ Nav::isRoute('locations.index') ? 'show' : '' }}
                {{ Nav::isRoute('species.index') ? 'show' : '' }}
                {{ Nav::isRoute('owners.index') ? 'show' : '' }}"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Configuración Pecuaria:</h6>
                    @can('gestionar_especies')
                        <a class="collapse-item {{ Nav::isRoute('species.index') }}" href="{{ route('species.index') }}">Especies</a>
                    @endcan
                    @can('gestionar_categorias')
                        <a class="collapse-item {{ Nav::isRoute('categories.index') }}" href="{{ route('categories.index') }}">Categorías (CeCo)</a>
                    @endcan
                    @can('gestionar_ubicaciones')
                        <a class="collapse-item {{ Nav::isRoute('locations.index') }}" href="{{ route('locations.index') }}">Ubicaciones</a>
                    @endcan
                    
                    @can('gestionar_dueños')
                        <a class="collapse-item {{ Nav::isRoute('owners.index') }}" href="{{ route('owners.index') }}">Propietarios</a>
                    @endcan
                </div>
            </div>
        </li>
    @endcanany

    <hr class="sidebar-divider">