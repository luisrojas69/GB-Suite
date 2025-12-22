    @can('gestionar_agro')
        <div class="sidebar-heading">
            {{ __('Módulo Agroindustrial (Caña)') }}
        </div>
        {{-- 1. Arrime de Molienda (Transacciones) --}}
        @canany(['ver_moliendas', 'crear_moliendas'])
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMolienda" aria-expanded="true" aria-controls="collapseMolienda">
                    <i class="fas fa-truck-loading"></i>
                    <span>{{ __('Molienda de Caña') }}</span>
                </a>
                <div id="collapseMolienda" class="collapse 
                    {{ Nav::isRoute('produccion.agro.moliendas.index') ? 'show' : '' }}
                    {{ Nav::isRoute('produccion.agro.moliendas.create') ? 'show' : '' }}" 
                    aria-labelledby="headingMolienda" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Operaciones:</h6>
                        @can('ver_moliendas')
                            <a class="collapse-item {{ Nav::isRoute('produccion.agro.moliendas.index') }}" 
                                href="{{ route('produccion.agro.moliendas.index') }}">Histórico de Arrimes</a>
                        @endcan
                        @can('crear_moliendas')
                            <a class="collapse-item {{ Nav::isRoute('produccion.agro.moliendas.create') }}" 
                                href="{{ route('produccion.agro.moliendas.create') }}">Registrar Nuevo Arrime</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcanany
        {{-- 2. Reportes --}}
        @can('ver_reportes_molienda')
            <li class="nav-item {{ Nav::isRoute('produccion.agro.reportes.index') }}">
                <a class="nav-link" href="{{ route('produccion.agro.reportes.index') }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>{{ __('Reportes y KPIs') }}</span>
                </a>
            </li>
        @endcan
        {{-- 3. Maestras Agroindustriales --}}
        @canany(['gestionar_zafras', 'gestionar_contratistas', 'gestionar_variedades', 'gestionar_destinos'])
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaestrasAgro" aria-expanded="true" aria-controls="collapseMaestrasAgro">
                    <i class="fas fa-seedling"></i>
                    <span>{{ __('Maestras de Campo') }}</span>
                </a>
                <div id="collapseMaestrasAgro" class="collapse 
                    {{ Nav::isRoute('produccion.agro.maestras.zafras.index') ? 'show' : '' }}
                    {{ Nav::isRoute('produccion.agro.maestras.contratistas.index') ? 'show' : '' }}
                    {{ Nav::isRoute('produccion.agro.maestras.variedades.index') ? 'show' : '' }}
                    {{ Nav::isRoute('produccion.agro.maestras.destinos.index') ? 'show' : '' }}" 
                    aria-labelledby="headingMaestrasAgro" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Catalogos Agro:</h6>
                        @can('gestionar_zafras')
                            <a class="collapse-item {{ Nav::isRoute('produccion.agro.maestras.zafras.index') }}" 
                                href="{{ route('produccion.agro.maestras.zafras.index') }}">Zafras</a>
                        @endcan
                        @can('gestionar_contratistas')
                            <a class="collapse-item {{ Nav::isRoute('produccion.agro.maestras.contratistas.index') }}" 
                                href="{{ route('produccion.agro.maestras.contratistas.index') }}">Contratistas</a>
                        @endcan
                        @can('gestionar_variedades')
                            <a class="collapse-item {{ Nav::isRoute('produccion.agro.maestras.variedades.index') }}" 
                                href="{{ route('produccion.agro.maestras.variedades.index') }}">Variedades de Caña</a>
                        @endcan
                        @can('gestionar_destinos')
                            <a class="collapse-item {{ Nav::isRoute('produccion.agro.maestras.destinos.index') }}" 
                                href="{{ route('produccion.agro.maestras.destinos.index') }}">Centros de Acopio</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcanany

        <hr class="sidebar-divider">
    @endcan

  
    @can('acceder_menu_liquidacion')
        <div class="sidebar-heading">
            {{ __('Módulo de Liquidación') }}
        </div>
        
        {{-- 1. Generación y Listado de Liquidaciones --}}
        @canany(['ver_liquidaciones', 'generar_liquidaciones'])
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLiquidacion" aria-expanded="true" aria-controls="collapseLiquidacion">
                    <i class="fas fa-money-check-alt"></i>
                    <span>{{ __('Liquidación de Pagos') }}</span>
                </a>
                <div id="collapseLiquidacion" class="collapse 
                    {{ Nav::isRoute('liquidacion.index') ? 'show' : '' }}
                    {{ Nav::isRoute('liquidacion.create') ? 'show' : '' }}" 
                    aria-labelledby="headingLiquidacion" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Operaciones:</h6>
                        
                        @can('ver_liquidaciones')
                            <a class="collapse-item {{ Nav::isRoute('liquidacion.index') }}" 
                                href="{{ route('liquidacion.index') }}">Histórico de Liquidaciones</a>
                        @endcan
                        
                        @can('generar_liquidaciones')
                            <a class="collapse-item {{ Nav::isRoute('liquidacion.create') }}" 
                                href="{{ route('liquidacion.create') }}">Generar Nueva Liquidación</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcanany

        {{-- 2. Configuración (Maestras de Liquidación, ej: Tarifas) --}}
        @can('gestionar_tarifas') {{-- Asumimos un permiso para tarifas --}}
            <li class="nav-item {{ Nav::isRoute('liquidacion.tarifas.index') }}">
                <a class="nav-link" href="{{ route('liquidacion.tarifas.index') }}">
                    <i class="fas fa-dollar-sign"></i>
                    <span>{{ __('Gestión de Tarifas') }}</span>
                </a>
            </li>
        @endcan

        <hr class="sidebar-divider">
    @endcan
