    @can('medicina.menu')
        <div class="sidebar-heading">
            {{ __('Seguridad y Salud Laboral') }}
        </div>

        @can('medicina.dashboard')
            <li class="nav-item {{ Nav::isRoute('medicina.dashboard') }}">
                <a class="nav-link" href="{{ route('medicina.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>{{ __('Dashboard Medicina/SSL') }}</span>
                </a>
            </li>
        @endcan

        @can('medicina.pacientes.gestionar')
            <li class="nav-item {{ Nav::isRoute('medicina.pacientes.*') }}">
                <a class="nav-link" href="{{ route('medicina.pacientes.index') }}">
                    <i class="fas fa-users-cog"></i>
                    <span>{{ __('Expediente de Personal') }}</span>
                </a>
            </li>
        @endcan

        @can('medicina.consultas.gestionar')
            <li class="nav-item {{ Nav::isRoute('medicina.consultas.*') }}">
                <a class="nav-link" href="{{ route('medicina.consultas.index') }}">
                    <i class="fas fa-notes-medical"></i>
                    <span>{{ __('Consultas y Atención') }}</span>
                </a>
            </li>
  

            @php
                // Calculamos las alertas directamente para el Sidebar
                $hoy_badge = now()->format('Y-m-d');
                
                $countReposo = App\Models\MedicinaOcupacional\Consulta::where('genera_reposo', 1)->where('reincorporado', 0)
                    ->whereRaw("CAST(DATEADD(day, dias_reposo,fecha_consulta) AS DATE) <= ?", [$hoy_badge])
                    ->count();
                    
                $countVacas = App\Models\MedicinaOcupacional\Paciente::where('de_vacaciones', 1)
                 ->whereDate('fecha_retorno_vacaciones', '<=', $hoy_badge)->count();
                
                $totalAlertasBadge = $countReposo + $countVacas;
            @endphp

            <li class="nav-item {{ Nav::isRoute('medicina.alertas.*') }}">
                <a class="nav-link" href="{{ route('medicina.alertas.index') }}">
                    <i class="fas fa-clock"></i>
                    <span>{{ __('Panel de Retornos') }}</span>
                    @if($totalAlertasBadge > 0)
                        <span class="badge badge-danger badge-counter">{{ $totalAlertasBadge }}</span>
                    @endif
                </a>
            </li>


            <li class="nav-item {{ Nav::isRoute('medicina.ordenes.*') }}">
                <a class="nav-link" href="{{ route('medicina.ordenes.index') }}">
                    <i class="fas fa-list-alt"></i>
                    <span>{{ __('Órdenes de Exámenes') }}</span>
                </a>
            </li>

       @endcan
    @endcan

    @can('ssl.menu')
        @can('ssl.dashboard')
            <li class="nav-item {{ Nav::isRoute('medicina.dashboard') }}">
                <a class="nav-link" href="{{ route('medicina.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>{{ __('Dashboard Medicina/SSL') }}</span>
                </a>
            </li>
        @endcan
        @can('ssl.accidentes.gestionar')
            <li class="nav-item {{ Nav::isRoute('medicina.accidentes.*') }}">
                <a class="nav-link" href="{{ route('medicina.accidentes.index') }}">
                    <i class="fas fa-ambulance"></i>
                    <span>{{ __('Accidentes (INPSASEL)') }}</span>
                </a>
            </li>
        @endcan

        @can('ssl.dotaciones.gestionar')
            <li class="nav-item {{ Nav::isRoute('medicina.dotaciones.*') }}">
                <a class="nav-link" href="{{ route('medicina.dotaciones.index') }}">
                    <i class="fas fa-tshirt"></i>
                    <span>{{ __('Dotación de Uniformes') }}</span>
                </a>
            </li>
        @endcan


            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                {{ __('Reportes y Administración') }}
            </div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReportesMed" aria-expanded="true" aria-controls="collapseReportesMed">
                    <i class="fas fa-chart-line"></i>
                    <span>{{ __('Reportes') }}</span>
                </a>
                <div id="collapseReportesMed" class="collapse 
                    {{ Nav::isRoute('medicina.reportes.*') ? 'show' : '' }}" 
                    aria-labelledby="headingReportes" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Integración Profit:</h6>
                            <a class="collapse-item {{ Nav::isRoute('medicina.reportes.profit') }}" href="{{ route('medicina.reportes.profit') }}">
                                <i class="fas fa-file-export mr-1"></i> {{ __('Cierre Diario Profit') }}
                            </a>                    
                        <h6 class="collapse-header">Estadísticas Salud:</h6>
                        @can('ssl.reportes')
                        <a class="collapse-item" target="_blank" href="{{ route('medicina.reportes.accidentalidad') }}">Accidentalidad Mensual</a>
                        @endcan
                        @can('medicina.reportes')
                        <a class="collapse-item" target="_blank" href="{{ route('medicina.reportes.morbilidad') }}">Morbilidad Mensual</a>
                        <a class="collapse-item" target="_blank" href="{{ route('medicina.reportes.vigilancia') }}">Vigilancia Epidemiologica</a>
                        @endcan
                    </div>
                </div>
            </li>

        <hr class="sidebar-divider">
    @endcan