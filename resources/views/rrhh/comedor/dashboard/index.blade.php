@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Panel de Control - Comedor</h1>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Servicios Hoy</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_count'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Costo Hoy</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['today_cost'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Servicios Mes</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['month_count'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Costo Acumulado Mes</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['month_cost'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tendencia de Consumo (Últimos 7 días)</h6>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribución por Servicio</h6>
                </div>
                <div class="card-body">
                    <canvas id="mealPieChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Costo por Departamento (Mes Actual)</h6>
            </div>
            <div class="card-body">
                @foreach($deptDistribution as $dept)
                <div class="mb-3">
                    <div class="small font-weight-bold">{{ $dept->department }} <span class="float-right">${{ number_format($dept->total_cost, 2) }}</span></div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 70%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Tendencia
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($lastSevenDays->pluck('day')) !!},
            datasets: [{
                label: 'Marcaciones',
                data: {!! json_encode($lastSevenDays->pluck('total')) !!},
                borderColor: '#4e73df',
                fill: true,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                tension: 0.3
            }]
        }
    });

    // Gráfico de Pastel
    new Chart(document.getElementById('mealPieChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($mealDistribution->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($mealDistribution->pluck('total')) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush