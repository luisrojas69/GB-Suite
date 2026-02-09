    @can('gestionar_areas')
        <div class="sidebar-heading">
            {{ __('Menu Labores') }}
        </div>
        {{-- ... Items Áreas ... --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLabores" aria-expanded="true" aria-controls="collapseLabores">
                <i class="fas fa-tools"></i>
                <span>{{ __('Gestión de Labores') }}</span>
            </a>
            <div id="collapseLabores" class="collapse 
                {{ Nav::isRoute('produccion.labores.index') ? 'show' : '' }}
                {{ Nav::isRoute('produccion.labores.create') ? 'show' : '' }}
                aria-labelledby="headingLabores" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @can('ver_sectores')
                        <a class="collapse-item {{ Nav::isRoute('produccion.labores.index') }}" 
                            href="{{ route('produccion.labores.index') }}">Tabla de Labores</a>
                    @endcan
                    @can('ver_sectores')
                        <a class="collapse-item {{ Nav::isRoute('produccion.labores.create') }}" 
                            href="{{ route('produccion.labores.create') }}">Nueva Labor</a>
                    @endcan
                </div>
            </div>
        </li>
        <hr class="sidebar-divider">
    @endcan 
