@can('gestionar_pozos')
    <div class="sidebar-heading">
        {{ __('Gestionar Pozos') }}
    </div>
    {{-- ... Items Pozos ... --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePozos" aria-expanded="true" aria-controls="collapsePozos">
            <i class="fas fa-tarp-droplet"></i>
            <span>{{ __('Gestión de Pozos') }}</span>
        </a>
        <div id="collapsePozos" class="collapse 
            {{ Nav::isRoute('produccion.pozos.activos.index') ? 'show' : '' }}
            {{ Nav::isRoute('produccion.pozos.dashboard') ? 'show' : '' }}
            {{ Nav::isRoute('produccion.pozos.mantenimientos.index') ? 'show' : '' }}
            {{ Nav::isRoute('produccion.pozos.aforos.index') ? 'show' : '' }}
            aria-labelledby="headingAreas" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @can('gestionar_pozos')
                    <a class="collapse-item {{ Nav::isRoute('produccion.pozos.dashboard') }}" 
                        href="{{ route('produccion.pozos.dashboard') }}"><i class="fas fa-tachometer-alt fa-sm text-gray-300"></i> Dashboard de Pozos</a>

                    <a class="collapse-item {{ Nav::isRoute('produccion.pozos.activos.index') }}" 
                        href="{{ route('produccion.pozos.activos.index') }}"><i class="fas fa-list fa-sm text-gray-300"></i> <span>Inventario de Pozos</span></a>

                    <a class="collapse-item {{ Nav::isRoute('produccion.pozos.mantenimientos.index') }}" 
                        href="{{ route('produccion.pozos.mantenimientos.index') }}"><i class="fas fa-wrench fa-sm text-gray-300"></i> <span>Mantenimientos</span></a>
  
                    <a class="collapse-item {{ Nav::isRoute('produccion.pozos.aforos.index') }}" 
                        href="{{ route('produccion.pozos.aforos.index') }}"><i class="fas fa-water fa-sm text-gray-300"></i> Gestión de Aforos</a>          
                @endcan
            </div>
        </div>
    </li>
    <hr class="sidebar-divider">
@endcan 