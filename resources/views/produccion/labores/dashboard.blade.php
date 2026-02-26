@extends('layouts.app')
@section('title-page', 'Dashboard de Labores de Campo')

@section('styles')
<style>
    :root {
        --agro-bg: #f4f7f6;
        --card-shadow: 0 10px 20px rgba(0,0,0,0.05);
        --agro-gradient: linear-gradient(135deg, #1b4332 0%, #2d6a4f 100%);
    }
    body { background-color: var(--agro-bg); }
    
    .kpi-card {
        border: none; border-radius: 15px; background: white;
        transition: transform 0.3s; overflow: hidden;
    }
    .kpi-card:hover { transform: translateY(-5px); }
    .kpi-icon {
        width: 50px; height: 50px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
    }
    .chart-container { position: relative; height: 300px; width: 100%; }
    .header-dashboard {
        background: var(--agro-gradient); color: white;
        padding: 40px 20px; border-radius: 0 0 30px 30px; margin-bottom: -50px;
    }
</style>
@endsection

@section('content')
<div class="header-dashboard shadow-lg">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-weight-bold mb-1">Panel de Control Agrícola</h2>
                <p class="opacity-75 mb-0">Monitoreo en tiempo real de labores de campo</p>
            </div>
            <div class="text-right">
                <span class="badge badge-light px-3 py-2 rounded-pill text-dark">
                    <i class="far fa-calendar-alt mr-1"></i> Zafra {{ now()->year }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 mt-5">
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card shadow-sm h-100 py-2 border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Has. Logradas (Mes)</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ number_format($totalHectareas, 1) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="kpi-icon bg-light-success text-success"><i class="fas fa-seedling"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card shadow-sm h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jornadas Ejecutadas</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $totalJornadas }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="kpi-icon bg-light-info text-info"><i class="fas fa-clipboard-check"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card shadow-sm h-100 py-2 border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Maquinaria en Uso</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $maquinariasActivas }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="kpi-icon bg-light-warning text-warning"><i class="fas fa-tractor"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card shadow-sm h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Meta Mensual</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">75%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-primary" style="width: 75%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="kpi-icon bg-light-primary text-primary"><i class="fas fa-bullseye"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Avance de Superficie (Últimos 15 días)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="lineChartAvance"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribución por Labor</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="pieChartLabores"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Eficiencia de Equipos (Horas de Uso)</h6>
                </div>
                <div class="card-body">
                    <canvas id="barChartEquipos"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ranking de Operadores</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <span><i class="fas fa-medal text-warning mr-2"></i> Juan Pérez</span>
                            <span class="badge badge-success badge-pill">142.5 Ha</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <span><i class="fas fa-medal text-secondary mr-2"></i> Marco Rivas</span>
                            <span class="badge badge-success badge-pill">128.0 Ha</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <span><i class="fas fa-medal text-bronze mr-2" style="color: #cd7f32;"></i> Luis Gomez</span>
                            <span class="badge badge-success badge-pill">115.2 Ha</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Gráfico de Avance (Líneas)
    const ctxLine = document.getElementById('lineChartAvance').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($avanceDiario->pluck('fecha')) !!},
            datasets: [{
                label: 'Has. Logradas',
                data: {!! json_encode($avanceDiario->pluck('total')) !!},
                backgroundColor: 'rgba(45, 106, 79, 0.1)',
                borderColor: '#2d6a4f',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#2d6a4f'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { display: false } } }
        }
    });

    // 2. Gráfico de Labores (Doughnut)
    const ctxPie = document.getElementById('pieChartLabores').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($laboresPorTipo->pluck('nombre')) !!},
            datasets: [{
                data: {!! json_encode($laboresPorTipo->pluck('total')) !!},
                backgroundColor: ['#1b4332', '#2d6a4f', '#40916c', '#52b788', '#74c69d'],
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // 3. Gráfico de Equipos (Bar)
    const ctxBar = document.getElementById('barChartEquipos').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: {!! json_encode($usoMaquinaria->pluck('codigo')) !!},
            datasets: [{
                label: 'Horas Totales',
                data: {!! json_encode($usoMaquinaria->pluck('horas')) !!},
                backgroundColor: '#52b788',
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush