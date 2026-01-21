@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Pluviométrico - {{ now()->translatedFormat('F Y') }}</h1>
        <a href="{{ route('produccion.pluviometria.index') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-table fa-sm text-white-50"></i> Volver a Matriz
        </a>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tendencia de Lluvia Diaria (mm)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartTendencia"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Acumulado por Sector</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="chartSectores"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Comparativo Mensual: {{ $anioAnterior }} vs {{ $anioActual }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="chartComparativo"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Frecuencia del Mes (Días)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="chartFrecuencia"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-primary"></i> Lluvia</span>
                        <span class="mr-2"><i class="fas fa-circle text-gray-400"></i> Seco</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Configuración Gráfico de Tendencia
    const ctxTendencia = document.getElementById('chartTendencia');
    new Chart(ctxTendencia, {
        type: 'line',
        data: {
            labels: {!! json_encode($diasMes) !!},
            datasets: [{
                label: 'Lluvia Total (mm)',
                data: {!! json_encode($totalesDia) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                fill: true,
                tension: 0.3
            }]
        },
        options: { maintainAspectRatio: false }
    });

    // 2. Configuración Gráfico de Sectores
    const ctxSectores = document.getElementById('chartSectores');
    new Chart(ctxSectores, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($sectoresNombres) !!},
            datasets: [{
                data: {!! json_encode($sectoresValores) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: { maintainAspectRatio: false }
    });

    // 3. Gráfico Comparativo Anual
    const ctxComp = document.getElementById('chartComparativo');
    new Chart(ctxComp, {
        type: 'bar',
        data: {
            labels: {!! json_encode($mesesLabels) !!},
            datasets: [
                {
                    label: '{{ $anioAnterior }}',
                    data: {!! json_encode($datosAnioAnterior) !!},
                    backgroundColor: '#858796',
                },
                {
                    label: '{{ $anioActual }}',
                    data: {!! json_encode($datosAnioActual) !!},
                    backgroundColor: '#4e73df',
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Milímetros (mm)' } }
            }
        }
    });

    // 4. Gráfico de Frecuencia (Dona)
    const ctxFreq = document.getElementById('chartFrecuencia');
    new Chart(ctxFreq, {
        type: 'doughnut',
        data: {
            labels: ['Días con Lluvia', 'Días Secos'],
            datasets: [{
                data: [{{ $diasConLluvia }}, {{ $diasSecos }}],
                backgroundColor: ['#4e73df', '#eaecf4'],
                hoverBackgroundColor: ['#2e59d9', '#d1d3e2'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection