@can('pluviometria.menu')
    <div class="sidebar-heading">
        {{ __('Registro de Lluvias') }}
    </div>
    {{-- ... Items Pluviometria ... --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAreas" aria-expanded="true" aria-controls="collapseAreas">
            <i class="fas fa-cloud-showers-heavy"></i>
            <span>{{ __('Gestión de Pluviometria') }}</span>
        </a>
        <div id="collapseAreas" class="collapse 
            {{ Nav::isRoute('produccion.pluviometria.index') ? 'show' : '' }}
            {{ Nav::isRoute('produccion.pluviometria.dashboard') ? 'show' : '' }}
            aria-labelledby="headingAreas" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @can('pluviometria.dashboard')
                    <a class="collapse-item {{ Nav::isRoute('produccion.pluviometria.dashboard') }}" 
                        href="{{ route('produccion.pluviometria.dashboard') }}"><i class="fas fa-tachometer-alt fa-sm text-gray-300"></i> <span>Dashboard</span></a>
                @endcan
                @can('produccion.pluviometria.ver')
                    <a class="collapse-item {{ Nav::isRoute('produccion.pluviometria.index') }}" 
                        href="{{ route('produccion.pluviometria.index') }}"><i class="fas fa-table fa-sm text-gray-300"></i> Matriz de Pluviometria</a>
                @endcan
            </div>
        </div>
    </li>
    <hr class="sidebar-divider">
@endcan 