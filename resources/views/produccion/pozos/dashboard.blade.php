@extends('layouts.app')

@section('content')
{{-- Mostrar mensajes de sesión --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Centro de Mando de Pozos y Estaciones</h1>
    <a href="{{ route('produccion.pozos.activos.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
        <i class="fas fa-list fa-sm text-white-50"></i> Ver Listado
    </a>
</div>

<div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total de Activos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalActivos }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-industry fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $parados = $estatusData['PARADO'] ?? 0;
        $mantenimiento = $estatusData['EN_MANTENIMIENTO'] ?? 0;
        $inactivos = $parados + $mantenimiento;
        $porcentaje_inactivo = $totalActivos > 0 ? round(($inactivos / $totalActivos) * 100) : 0;
    @endphp
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Activos Críticos (Parados + Mantto.)
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $inactivos }}</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $porcentaje_inactivo }}%" aria-valuenow="{{ $porcentaje_inactivo }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-stop-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Mantenimientos Abiertos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mantenimientosAbiertos }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Tiempo Medio de Parada (MTTR)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ round($mttr ?? 0, 1) }} hrs</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Distribución de Estatus de Activos</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="estatusPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2"><i class="fas fa-circle text-success"></i> Operativo</span>
                    <span class="mr-2"><i class="fas fa-circle text-warning"></i> En Mantenimiento</span>
                    <span class="mr-2"><i class="fas fa-circle text-danger"></i> Parado</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-danger">
                <h6 class="m-0 font-weight-bold text-white">⚠️ Alerta Hidrológica: Caudal Más Bajo</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Pozo</th>
                                <th>Último Caudal (L/s)</th>
                                <th>Fecha</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($aforosRecientes as $aforo)
                            <tr>
                                <td>{{ $aforo->pozo_nombre }}</td>
                                <td><span class="text-danger font-weight-bold">{{ $aforo->caudal_medido_lts_seg }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($aforo->fecha_medicion)->format('d/m/Y') }}</td>
                                <td><a href="{{ route('produccion.pozos.activos.show', $aforo->id_pozo) }}" class="btn btn-sm btn-danger">Revisar</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="4">No hay datos de aforo para mostrar alertas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    // Configuración del Gráfico de Pastel (Pie Chart)
    var estatusLabels = [];
    var estatusData = [];
    var estatusColors = [];

    // Mapeo de Estatus a Colores de SBAdmin2
    var statusMap = {
        'OPERATIVO': { label: 'Operativo', color: '#1cc88a' }, // Success
        'EN_MANTENIMIENTO': { label: 'Mantenimiento', color: '#f6c23e' }, // Warning
        'PARADO': { label: 'Parado', color: '#e74a3b' } // Danger
    };

    // Procesar datos de PHP
    var rawData = @json($estatusData);
    for (var status in rawData) {
        if (rawData.hasOwnProperty(status) && statusMap[status]) {
            estatusLabels.push(statusMap[status].label + ' (' + rawData[status] + ')');
            estatusData.push(rawData[status]);
            estatusColors.push(statusMap[status].color);
        }
    }

    var ctx = document.getElementById("estatusPieChart");
    var estatusPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: estatusLabels,
            datasets: [{
                data: estatusData,
                backgroundColor: estatusColors,
                hoverBackgroundColor: ['#17a673', '#f0ad4e', '#d9534f'], // Tonos más oscuros al pasar el ratón
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });
</script>
@endsection