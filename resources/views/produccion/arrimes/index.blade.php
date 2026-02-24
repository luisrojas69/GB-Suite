@extends('layouts.app')
@section('title-page', 'Historial de Arrime')

@section('styles')
<style>
    :root {
        --agro-dark: #1b4332;
        --agro-primary: #2d6a4f;
    }
    .page-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; padding: 25px 30px; border-radius: 15px; margin-bottom: 25px;
    }
    .card-kpi { transition: transform 0.2s; border: none; }
    .card-kpi:hover { transform: translateY(-5px); }
    .icon-circle-custom {
        width: 45px; height: 45px; background: rgba(255,255,255,0.2);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header-agro shadow">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 font-weight-bold mb-1"><i class="fas fa-truck-loading mr-2"></i> Control de Arrime</h1>
                <p class="mb-0">Zafra Actual: <strong>{{ $zafraActiva->nombre }}</strong></p>
            </div>
            <a href="{{ route('produccion.arrimes.importar') }}" class="btn btn-light btn-icon-split shadow-sm">
                <span class="icon text-gray-600"><i class="fas fa-file-upload"></i></span>
                <span class="text">Importar Boletos</span>
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-kpi shadow h-100 py-2 bg-primary text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Toneladas</div>
                            <div class="h5 mb-0 font-weight-bold">{{ number_format($kpis['total_ton'], 2) }} TNS</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-custom"><i class="fas fa-weight fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-kpi shadow h-100 py-2 bg-success text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Arrimado Hoy</div>
                            <div class="h5 mb-0 font-weight-bold">{{ number_format($kpis['ton_hoy'], 2) }} TNS</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-custom"><i class="fas fa-calendar-day fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-kpi shadow h-100 py-2 bg-info text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Rendimiento Prom.</div>
                            <div class="h5 mb-0 font-weight-bold">{{ number_format($kpis['rendimiento_avg'], 2) }}%</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-custom"><i class="fas fa-chart-line fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-kpi shadow h-100 py-2 bg-dark text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Boletos Procesados</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $kpis['total_boletos'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-custom"><i class="fas fa-ticket-alt fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Detalle de Boletos Arrimados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="dataTableArrimes" width="100%" cellspacing="0">
                    <thead class="bg-agro-dark text-white">
                        <tr>
                            <th>Boleto</th>
                            <th>Fecha Arrime</th>
                            <th>Sector / Tablón</th>
                            <th>Equipo (Jaiba)</th>
                            <th>Ton. Netas</th>
                            <th>Rdto.</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($boletos as $b)
                        <tr>
                            <td><strong>{{ $b->boleto }}</strong></td>
                            <td>{{ $b->fecha_arrime->format('d/m/Y h:i A') }}</td>
                            <td>
                                <small class="text-muted d-block">{{ $b->tablon->lote->sector->nombre }}</small>
                                {{ $b->tablon->codigo_completo }}
                            </td>
                            <td>{{ $b->jaiba->codigo ?? 'N/A' }}</td>
                            <td class="font-weight-bold text-primary">{{ number_format($b->toneladas_netas, 3) }}</td>
                            <td>{{ number_format($b->rendimiento_real, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $b->estado == 'Liquidado' ? 'success' : 'primary' }}">
                                    {{ $b->estado }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info btn-circle"><i class="fas fa-eye"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTableArrimes').DataTable({
            "language": { "url": "/js/lang/Spanish.json" },
            "order": [[ 1, "desc" ]],
            "pageLength": 25,
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });
    });
</script>
@endpush