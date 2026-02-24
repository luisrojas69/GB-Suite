@extends('layouts.app') 
@section('title-page', 'Control de Tablones Productivos')

@section('styles')
<style>
    /* ========================================
       VARIABLES GLOBALES - TEMA AGRO PREMIUM
    ======================================== */
    :root {
        --agro-dark: #1b4332;      /* Verde Bosque */
        --agro-primary: #2d6a4f;   /* Verde Esmeralda */
        --agro-light: #d8f3dc;     /* Verde Pastel */
        --agro-accent: #52b788;    /* Verde Vibrante */
        --agro-earth: #bc6c25;     /* Tono Tierra */
        --agro-alert: #e63946;     /* Rojo Alerta */
        
        /* Colores de Estado de Cultivo */
        --status-prep: #4e73df;    /* Azul - Preparación */
        --status-crec: #1cc88a;    /* Verde - Crecimiento */
        --status-maduro: #f6c23e;  /* Amarillo - Maduro */
        --status-cosecha: #e74a3b; /* Rojo - Cosecha */
    }

    /* HEADER */
    .page-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; padding: 25px 30px; border-radius: 15px;
        margin-bottom: 25px; box-shadow: 0 8px 25px rgba(45, 106, 79, 0.25);
        position: relative; overflow: hidden;
    }
    .page-header-agro::before {
        content: '\f06c'; /* fa-leaf */
        font-family: 'Font Awesome 5 Free'; font-weight: 900;
        position: absolute; top: -10px; right: 20px;
        font-size: 8rem; color: rgba(255,255,255,0.06); transform: rotate(-15deg);
    }

    /* TARJETAS KPI */
    .card-stat-agro {
        border: none; border-radius: 12px;
        transition: transform 0.3s ease; background: #fff;
    }
    .card-stat-agro:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    
    .icon-circle-agro {
        width: 50px; height: 50px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
    }

    /* TABLA */
    .table-agro thead th {
        background-color: #f8f9fc; color: var(--agro-dark); font-weight: 700;
        text-transform: uppercase; font-size: 0.75rem; border-bottom: 2px solid var(--agro-light);
    }
    .table-agro tbody tr { transition: background-color 0.2s; }
    .table-agro tbody tr:hover { background-color: rgba(82, 183, 136, 0.05); }

    /* BADGES Y ETIQUETAS */
    .badge-estado { padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.75rem; }
    .status-Prep { background-color: rgba(78, 115, 223, 0.1); color: var(--status-prep); border: 1px solid var(--status-prep); }
    .status-Crec { background-color: rgba(28, 200, 138, 0.1); color: var(--status-crec); border: 1px solid var(--status-crec); }
    .status-Maduro { background-color: rgba(246, 194, 62, 0.1); color: #d49a15; border: 1px solid var(--status-maduro); }
    .status-Cose { background-color: rgba(231, 74, 59, 0.1); color: var(--status-cosecha); border: 1px solid var(--status-cosecha); }

    .chart-container-kpi { position: relative; height: 70px; width: 70px; }
</style>
@endsection

@section('content')

{{-- LÓGICA DE NEGOCIO PARA KPIs (Se ejecuta rápido en memoria) --}}
@php
    $totalTablones = $tablones->count();
    $totalHas = $tablones->sum('hectareas_documento');
    
    $estados = ['Preparacion' => 0, 'Crecimiento' => 0, 'Maduro' => 0, 'Cosecha' => 0];
    $ciclos = ['Plantilla' => 0, 'Soca' => 0];
    $alertasMaduracion = 0;

    foreach($tablones as $t) {
        // Conteo de Estados
        $estadoKey = match($t->estado) {
            'Preparacion' => 'Preparacion',
            'Crecimiento' => 'Crecimiento',
            'Maduro' => 'Maduro',
            'Cosecha' => 'Cosecha',
            default => 'Crecimiento' // Fallback
        };
        $estados[$estadoKey]++;

        // Conteo de Ciclos
        if($t->tipo_ciclo == 'Soca') $ciclos['Soca']++;
        else $ciclos['Plantilla']++;

        // Cálculo de Edad para Alerta (> 12 meses)
        if($t->fecha_inicio_ciclo && $t->estado == 'Crecimiento' || $t->estado == 'Maduro') {
            $meses = \Carbon\Carbon::parse($t->fecha_inicio_ciclo)->diffInMonths(now());
            if($meses >= 12) {
                $alertasMaduracion++;
            }
        }
    }
@endphp

<div class="container-fluid">

    <div class="page-header-agro d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div>
            <h2 class="font-weight-bold mb-1"><i class="fas fa-th mr-2"></i> Tablones Productivos</h2>
            <p class="mb-0 text-white-50" style="font-size: 1.1rem;">
                Centro de control agronómico y seguimiento de cultivos.
            </p>
        </div>
        @can('produccion.areas.crear')
        <div class="mt-3 mt-md-0 btn-group shadow-sm">
            <a href="{{ route('produccion.areas.tablones.create') }}" class="btn btn-light text-success font-weight-bold shadow-sm rounded-pill px-4">
                <i class="fas fa-plus-circle mr-1"></i> Registrar Tablón
            </a>
        </div>
        @endcan
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show border-left-success shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ $message }}
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro shadow-sm h-100 border-bottom-primary py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Superficie Activa</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ number_format($totalHas, 2) }} <small class="text-muted text-xs">Ha</small></div>
                            <div class="text-xs text-gray-500 font-weight-bold mt-1"><i class="fas fa-layer-group mr-1"></i>En {{ $totalTablones }} Tablones</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro bg-light text-primary"><i class="fas fa-ruler-combined"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro shadow-sm h-100 border-bottom-success py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Estado Fenológico</div>
                            <div class="mt-2">
                                <span class="badge status-Crec p-1 px-2 mb-1"><i class="fas fa-leaf"></i> {{ $estados['Crecimiento'] }} Crecimiento</span><br>
                                <span class="badge status-Maduro p-1 px-2"><i class="fas fa-sun"></i> {{ $estados['Maduro'] }} Maduros</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="chart-container-kpi">
                                <canvas id="estadoChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro shadow-sm h-100 border-bottom-info py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Renovación de Cepas</div>
                            <div class="h4 mb-0 font-weight-black text-gray-800">{{ $ciclos['Plantilla'] }} <small class="text-muted text-xs font-weight-normal">Plantillas</small></div>
                            <div class="text-xs text-gray-500 font-weight-bold mt-1"><i class="fas fa-sync-alt mr-1"></i>{{ $ciclos['Soca'] }} Socas registradas</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro bg-light text-info"><i class="fas fa-seedling"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro shadow-sm h-100 border-bottom-warning py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Atención Requerida</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ $alertasMaduracion }} <small class="text-muted text-xs">Lotes</small></div>
                            <div class="text-xs text-danger font-weight-bold mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>Superan los 12 meses</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(246, 194, 62, 0.1); color: #f6c23e;">
                                <i class="fas fa-stopwatch"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h6 class="m-0 font-weight-bold" style="color: var(--agro-dark);"><i class="fas fa-list-ul mr-2"></i> Directorio Agronómico</h6>
            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-sm"><i class="fas fa-filter mr-1"></i> Filtrar Datos</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                @can('produccion.areas.ver')
                <table class="table table-agro align-middle mb-0" id="dataTableTablones" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="20%" class="pl-4">Identificación</th>
                            <th width="20%">Ubicación (Sector/Lote)</th>
                            <th width="15%">Cultivo y Variedad</th>
                            <th width="10%" class="text-center">Área (Ha)</th>
                            <th width="15%" class="text-center">Edad / Ciclo</th>
                            <th width="10%" class="text-center">Estado</th>
                            <th width="10%" class="text-right pr-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tablones as $t)
                        <tr>
                            <td class="pl-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle-agro mr-3 shadow-sm border {{ $t->geometria ? 'bg-light text-success' : 'bg-light text-warning' }}" style="width: 40px; height: 40px;">
                                        <i class="fas {{ $t->geometria ? 'fa-draw-polygon' : 'fa-exclamation' }} fa-sm"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold text-dark">{{ $t->codigo_completo }}</h6>
                                        <small class="text-muted">{{ $t->nombre }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="font-weight-bold text-primary">{{ $t->lote->sector->nombre }}</span><br>
                                <small class="text-muted"><i class="fas fa-folder-open mr-1"></i>Lote: {{ $t->lote->codigo_completo }}</small>
                            </td>

                            <td>
                                @if($t->variedad)
                                    <span class="badge badge-dark p-1 px-2 mb-1">{{ $t->variedad->nombre }}</span><br>
                                @else
                                    <span class="badge badge-light border text-muted mb-1">Sin Variedad</span><br>
                                @endif
                                <small class="text-muted">Suelo: {{ $t->tipo_suelo ?? 'N/D' }}</small>
                            </td>

                            <td class="text-center">
                                <div class="h6 mb-0 font-weight-bold text-dark">{{ number_format($t->hectareas_documento, 2) }}</div>
                                @if($t->meta_ton_ha)
                                    <small class="text-success font-weight-bold" title="Rendimiento Estimado"><i class="fas fa-chart-line"></i> {{ $t->meta_ton_ha }} t/ha</small>
                                @endif
                            </td>

                            <td class="text-center">
                                @php
                                    $meses = 0;
                                    if($t->fecha_inicio_ciclo) {
                                        $meses = \Carbon\Carbon::parse($t->fecha_inicio_ciclo)->diffInMonths(now());
                                    }
                                @endphp
                                
                                <div class="font-weight-bold {{ $meses >= 12 ? 'text-danger' : 'text-primary' }}">
                                    {{ $meses }} Meses
                                </div>
                                
                                <span class="badge badge-light border shadow-sm mt-1">
                                    {{ $t->tipo_ciclo }} {{ $t->tipo_ciclo == 'Soca' ? '#'.$t->numero_soca : '' }}
                                </span>
                            </td>

                            <td class="text-center">
                                @php
                                    $colorClass = match($t->estado) {
                                        'Preparacion' => 'status-Prep',
                                        'Crecimiento' => 'status-Crec',
                                        'Maduro' => 'status-Maduro',
                                        'Cosecha' => 'status-Cose',
                                        default => 'badge-secondary'
                                    };
                                @endphp
                                <span class="badge-estado {{ $colorClass }} shadow-sm">
                                    {{ $t->estado }}
                                </span>
                            </td>

                            <td class="text-right pr-4">
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle btn btn-light btn-sm rounded-circle shadow-sm" href="#" role="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-gray-600"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                        <div class="dropdown-header">Opciones del Tablón</div>
                                        
                                        @can('produccion.areas.ver')
                                        <a class="dropdown-item text-info font-weight-bold" href="{{ route('produccion.areas.tablones.show', $t->id) }}">
                                            <i class="fas fa-search fa-sm fa-fw mr-2"></i> Ver Ficha Técnica
                                        </a>
                                        @endcan
                                        
                                        @can('produccion.areas.editar')
                                        <a class="dropdown-item" href="{{ route('produccion.areas.tablones.edit', $t->id) }}">
                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Actualizar Datos
                                        </a>
                                        @endcan
                                        
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-tractor fa-sm fa-fw mr-2 text-primary"></i> Registrar Labor
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-lock fa-3x text-gray-300 mb-3"></i>
                        <h5 class="font-weight-bold text-gray-600">Acceso Restringido</h5>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar DataTables
        $('#dataTableTablones').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" },
            "pageLength": 15,
            "order": [[ 4, "desc" ]] // Ordenar por edad por defecto
        });

        // Gráfico de Dona para los Estados
        const ctx = document.getElementById('estadoChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Preparación', 'Crecimiento', 'Maduro', 'Cosecha'],
                datasets: [{
                    data: [
                        {{ $estados['Preparacion'] }}, 
                        {{ $estados['Crecimiento'] }}, 
                        {{ $estados['Maduro'] }}, 
                        {{ $estados['Cosecha'] }}
                    ],
                    backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b'],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.raw + ' tablones';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush