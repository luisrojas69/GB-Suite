@extends('layouts.app') 
@section('title-page', 'Plan de Zafra / Rol de Molienda')

@section('styles')
<style>
    /* ========================================\
       VARIABLES GLOBALES - TEMA AGRO PREMIUM
    ======================================== */
    :root {
        --agro-dark: #1b4332;      
        --agro-primary: #2d6a4f;   
        --agro-light: #d8f3dc;     
        --agro-accent: #52b788;    
        --agro-earth: #bc6c25;     
    }

    /* HEADER */
    .page-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; 
        padding: 25px 30px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(27, 67, 50, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .page-header-agro h2 { font-weight: 700; font-size: 1.5rem; margin: 0; }
    .page-header-agro p { margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem; }

    /* KPI CARDS */
    .kpi-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: transform 0.2s;
    }
    .kpi-card:hover { transform: translateY(-3px); }
    .border-left-agro { border-left: 4px solid var(--agro-accent) !important; }
    .border-left-earth { border-left: 4px solid var(--agro-earth) !important; }

    /* TABLA */
    .table-agro thead th {
        background-color: var(--agro-dark);
        color: white;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: none;
    }
    .table-agro tbody td {
        vertical-align: middle;
        font-size: 0.9rem;
    }
    .badge-variedad {
        background-color: var(--agro-light);
        color: var(--agro-dark);
        border: 1px solid var(--agro-accent);
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="page-header-agro">
        <div>
            <h2><i class="fas fa-calendar-alt mr-2"></i> Rol de Molienda Estimado</h2>
            <p>Zafra Activa: <strong>{{ $zafraActiva->nombre ?? 'Sin Zafra Activa' }}</strong> | Resumen General de Planificación</p>
        </div>
        <div>
            <a href="{{ route('rol_molienda.importar') }}" class="btn btn-light text-success font-weight-bold shadow-sm">
                <i class="fas fa-file-excel mr-1"></i> Importar / Actualizar Rol
            </a>
        </div>
    </div>

    @if($planes->isEmpty())
        <div class="text-center py-5">
            <img src="{{ asset('img/empty-data.svg') }}" alt="Sin Datos" style="height: 150px; opacity: 0.5; margin-bottom: 20px;">
            <h4 class="text-muted">No hay tablones planificados para esta Zafra aún.</h4>
            <p class="text-muted">Comienza importando el archivo Excel del Jefe de Cosecha.</p>
            <a href="{{ route('rol_molienda.importar') }}" class="btn btn-outline-success mt-3">Importar Ahora</a>
        </div>
    @else
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card kpi-card border-left-agro h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Área Estimada (Has)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($kpis['total_has'], 2) }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-map mr-2 fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card kpi-card border-left-earth h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Toneladas Estimadas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($kpis['total_tons'], 2) }} Tns</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-weight-hanging fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card kpi-card border-left-info h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rendimiento Prom.</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ number_format($kpis['rendimiento_avg'], 2) }}%</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ min(($kpis['rendimiento_avg']/12)*100, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto"><i class="fas fa-chart-line fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card kpi-card border-left-primary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tablones Mapeados</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kpis['tablones_planificados'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-layer-group fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-light">
                <h6 class="m-0 font-weight-bold text-primary">Detalle de Tablones a Cosechar</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="table table-hover table-agro w-100" id="rolMoliendaTable">
                        <thead>
                            <tr>
                                <th>Hacienda / Sector</th>
                                <th>Tablón</th>
                                <th>Variedad</th>
                                <th>Ciclo</th>
                                <th class="text-right">Área (Ha)</th>
                                <th class="text-right">Tons/Ha</th>
                                <th class="text-right text-success">Total Tons</th>
                                <th class="text-right">Rend. (%)</th>
                                <th class="text-center">Corte Proyectado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($planes as $plan)
                            <tr>
                                <td><span class="font-weight-bold text-dark">{{ $plan->tablon->lote->sector->nombre ?? 'N/A' }}</span></td>
                                <td>
                                    {{ $plan->tablon->codigo_tablon_interno ?? 'N/A' }}
                                    <small class="d-block text-muted">{{ $plan->tablon->nombre ?? '' }}</small>
                                </td>
                                <td><span class="badge badge-variedad px-2 py-1">{{ $plan->variedad->nombre ?? 'N/A' }}</span></td>
                                <td>{{ $plan->clase_ciclo }}</td>
                                <td class="text-right">{{ number_format($plan->area_estimada_has, 2) }}</td>
                                <td class="text-right">{{ number_format($plan->ton_ha_estimadas, 2) }}</td>
                                <td class="text-right font-weight-bold text-success">{{ number_format($plan->toneladas_estimadas, 2) }}</td>
                                <td class="text-right">{{ number_format($plan->rendimiento_esperado, 2) }}</td>
                                <td class="text-center">
                                    @if($plan->fecha_corte_proyectada)
                                        {{ \Carbon\Carbon::parse($plan->fecha_corte_proyectada)->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted"><i class="fas fa-minus"></i></span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#rolMoliendaTable').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json" },
            "pageLength": 25,
            "order": [[ 0, "asc" ], [ 1, "asc" ]], // Ordenar por Hacienda y luego Tablón
            "dom": '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
                    className: 'btn btn-sm btn-success shadow-sm',
                    title: 'Rol de Molienda - Zafra {{ $zafraActiva->nombre ?? "Actual" }}'
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-sm btn-danger shadow-sm',
                    title: 'Rol de Molienda - Zafra {{ $zafraActiva->nombre ?? "Actual" }}'
                }
            ]
        });
    });
</script>
@endpush