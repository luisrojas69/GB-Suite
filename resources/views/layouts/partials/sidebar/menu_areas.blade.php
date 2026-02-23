    @can('areas.menu')
        <div class="sidebar-heading">
            {{ __('Áreas de Producción') }}
        </div>
        {{-- ... Items Áreas ... --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAreas" aria-expanded="true" aria-controls="collapseAreas">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ __('Gestión de Áreas') }}</span>
            </a>
            <div id="collapseAreas" class="collapse 
                {{ Nav::isRoute('produccion.areas.sectores.index') ? 'show' : '' }}
                {{ Nav::isRoute('produccion.areas.lotes.index') ? 'show' : '' }}
                {{ Nav::isRoute('produccion.areas.tablones.index') ? 'show' : '' }}" 
                aria-labelledby="headingAreas" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Jerarquía de Campo:</h6>
                    @can('produccion.areas.ver')
                        <a class="collapse-item {{ Nav::isRoute('produccion.areas.sectores.index') }}" 
                            href="{{ route('produccion.areas.sectores.index') }}">Sectores</a>

                        <a class="collapse-item {{ Nav::isRoute('produccion.areas.lotes.index') }}" 
                            href="{{ route('produccion.areas.lotes.index') }}">Lotes</a>

                        <a class="collapse-item {{ Nav::isRoute('produccion.areas.tablones.index') }}" 
                            href="{{ route('produccion.areas.tablones.index') }}">Tablones</a>
                    @endcan
                </div>
            </div>
        </li>
        <hr class="sidebar-divider">
    @endcan 
