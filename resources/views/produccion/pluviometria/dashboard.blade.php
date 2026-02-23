@extends('layouts.app')

@section('title-page', 'Dashboard Pluviométrico')

@section('styles')
<style>
    /* ========================================
       VARIABLES GLOBALES - TEMA WATER/RAIN
    ======================================== */
    :root {
        --water-dark: #005f73;
        --water-primary: #0a9396;
        --water-light: #94d2bd;
        --water-accent: #00b4d8;
    }

    /* HEADER */
    .page-header-water {
        background: linear-gradient(135deg, var(--water-dark) 0%, var(--water-primary) 100%);
        color: white; padding: 25px 30px; border-radius: 15px; margin-bottom: 25px; 
        box-shadow: 0 8px 25px rgba(10, 147, 150, 0.25); position: relative; overflow: hidden;
    }
    .page-header-water::before {
        content: '\f0e4'; /* fa-tachometer-alt */
        font-family: 'Font Awesome 5 Free'; font-weight: 900; position: absolute; 
        top: -20px; right: 20px; font-size: 8rem; color: rgba(255,255,255,0.05); transform: rotate(-15deg);
    }

    /* NAVEGACIÓN Y BOTONES */
    .btn-outline-water { border-color: rgba(255,255,255,0.5); color: white; background: rgba(255,255,255,0.1); }
    .btn-outline-water:hover { background: white; color: var(--water-dark); }
    .btn-water-active { background: white; color: var(--water-dark); font-weight: 800; }

    /* KPIs */
    .card-stat-water {
        border: none; border-radius: 12px; transition: all 0.3s ease;
        background: white; position: relative; overflow: hidden;
    }
    .card-stat-water:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
    .border-water-1 { border-bottom: 4px solid var(--water-dark); }
    .border-water-2 { border-bottom: 4px solid var(--water-primary); }
    .border-water-3 { border-bottom: 4px solid var(--water-accent); }
    .border-water-4 { border-bottom: 4px solid var(--water-light); }
    
    .icon-circle-water {
        width: 50px; height: 50px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
    }

    /* TARJETAS DE GRÁFICOS */
    .card-chart { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
    .card-chart-header { background: white; border-bottom: 1px solid #eaecf4; padding: 15px 20px; border-radius: 12px 12px 0 0; }
    .card-chart-title { font-weight: 800; color: #4a5568; font-size: 14px; text-transform: uppercase; margin: 0; }
</style>
@endsection

@section('content')
<div class="container-fluid pb-5">

    <div class="page-header-water d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="mb-3 mb-md-0 position-relative z-index-2">
            <h2 class="font-weight-bold mb-1"><i class="fas fa-chart-line mr-2"></i> Dashboard Pluviométrico</h2>
            <p class="mb-0 text-white-50">Análisis y tendencias de precipitaciones de Granja Boraure.</p>
        </div>
        
        <div class="d-flex flex-column align-items-end position-relative z-index-2">
            <div class="btn-group shadow-sm rounded-pill overflow-hidden mb-2">
                <button type="button" class="btn btn-outline-water px-3" onclick="cambiarMes('{{ $fechaConsulta->copy()->subMonth()->format('Y-m') }}')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="btn btn-water-active text-capitalize px-4 disabled" style="opacity: 1;">
                    <i class="far fa-calendar-alt mr-2"></i>{{ $fechaConsulta->isoFormat('MMMM YYYY') }}
                </button>
                <button type="button" class="btn btn-outline-water px-3" onclick="cambiarMes('{{ $fechaConsulta->copy()->addMonth()->format('Y-m') }}')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            @can('produccion.pluviometria.ver')
                <a href="{{ route('produccion.pluviometria.index') }}" class="text-white small font-weight-bold" style="text-decoration: underline;">
                    <i class="fas fa-table mr-1"></i> Ir a la Matriz de Datos
                </a>
            @endcan
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-water border-water-1 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--water-dark);">Acumulado Mensual</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ number_format($acumuladoMes, 1) }} <small class="text-muted text-xs">mm</small></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-water" style="background: rgba(0, 95, 115, 0.1); color: var(--water-dark);">
                                <i class="fas fa-tint"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-water border-water-2 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--water-primary);">Pico Máximo Diario</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ $maximaLluvia }} <small class="text-muted text-xs">mm</small></div>
                            <div class="text-xs text-muted font-weight-bold text-truncate" title="{{ $nombreSectorMax }}"><i class="fas fa-map-marker-alt text-danger mr-1"></i>{{ Str::limit($nombreSectorMax, 15) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-water" style="background: rgba(10, 147, 150, 0.1); color: var(--water-primary);">
                                <i class="fas fa-cloud-showers-heavy"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-water border-water-3 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--water-accent);">Frecuencia de Lluvia</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ $diasConLluvia }} <small class="text-muted text-xs">días</small></div>
                            <div class="text-xs text-muted font-weight-bold">{{ $diasSecos }} días secos</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-water" style="background: rgba(0, 180, 216, 0.1); color: var(--water-accent);">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-water border-water-4 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--water-dark);">Promedio por Evento</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ number_format($promedioLluvia, 1) }} <small class="text-muted text-xs">mm/día</small></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-water" style="background: rgba(148, 210, 189, 0.2); color: var(--water-dark);">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card card-chart h-100">
                <div class="card-chart-header">
                    <h6 class="card-chart-title"><i class="fas fa-water mr-2"></i> Evolución Diaria ({{ $fechaConsulta->isoFormat('MMMM') }})</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px;">
                        <canvas id="chartTendencia"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card card-chart h-100">
                <div class="card-chart-header">
                    <h6 class="card-chart-title"><i class="fas fa-map mr-2"></i> Aporte por Sector</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="chart-pie" style="height: 250px;">
                        <canvas id="chartSectores"></canvas>
                    </div>
                    <div class="mt-4 text-center small text-muted font-weight-bold">
                        Distribución total del mes de {{ $fechaConsulta->isoFormat('MMMM') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card card-chart h-100">
                <div class="card-chart-header">
                    <h6 class="card-chart-title"><i class="fas fa-chart-bar mr-2"></i> Comparativo Interanual ({{ $anioAnterior }} vs {{ $anioActual }})</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="height: 300px;">
                        <canvas id="chartComparativo"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card card-chart h-100 bg-gradient-light">
                <div class="card-chart-header">
                    <h6 class="card-chart-title"><i class="fas fa-sun mr-2"></i> Lluvia vs Sequía</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="chart-pie" style="height: 220px;">
                        <canvas id="chartFrecuencia"></canvas>
                    </div>
                    <div class="mt-4 text-center small font-weight-bold">
                        <span class="mr-3"><i class="fas fa-circle" style="color: var(--water-primary);"></i> Días con Lluvia</span>
                        <span><i class="fas fa-circle" style="color: #e3e6f0;"></i> Días Secos</span>
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
    // Navegación JS para recargar la página con parámetros GET
    function cambiarMes(fechaStr) {
        let partes = fechaStr.split('-');
        window.location.href = `{{ route('produccion.pluviometria.dashboard') }}?anio=${partes[0]}&mes=${partes[1]}`;
    }

    // Configuración Global para ChartJS para que luzca Premium
    Chart.defaults.font.family = "'Nunito', '-apple-system', 'system-ui', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'";
    Chart.defaults.color = '#858796';

    // 1. Gráfico de Tendencia (Lineal con Degradado)
    const ctxTendencia = document.getElementById('chartTendencia').getContext('2d');
    let gradientLine = ctxTendencia.createLinearGradient(0, 0, 0, 300);
    gradientLine.addColorStop(0, 'rgba(10, 147, 150, 0.4)'); // Color primary trans
    gradientLine.addColorStop(1, 'rgba(10, 147, 150, 0.0)');

    new Chart(ctxTendencia, {
        type: 'line',
        data: {
            labels: {!! json_encode($diasMesLabels) !!},
            datasets: [{
                label: 'Milímetros (mm)',
                data: {!! json_encode($totalesDia) !!},
                backgroundColor: gradientLine,
                borderColor: '#0a9396',
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0a9396',
                pointHoverBackgroundColor: '#0a9396',
                pointHoverBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // Curvas suaves
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false, drawBorder: false } },
                y: { grid: { color: "rgb(234, 236, 244)", borderDash: [2], drawBorder: false }, beginAtZero: true }
            }
        }
    });

    // 2. Gráfico de Sectores (Doughnut Moderno)
    const ctxSectores = document.getElementById('chartSectores');
    new Chart(ctxSectores, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($sectoresNombres) !!},
            datasets: [{
                data: {!! json_encode($sectoresValores) !!},
                backgroundColor: ['#005f73', '#0a9396', '#94d2bd', '#00b4d8', '#e9d8a6', '#ee9b00', '#ca6702', '#bb3e03'],
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 5
            }]
        },
        options: { 
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { position: 'right', labels: { usePointStyle: true, padding: 20 } }
            }
        }
    });

    // 3. Gráfico Comparativo Interanual (Bar)
    const ctxComp = document.getElementById('chartComparativo');
    new Chart(ctxComp, {
        type: 'bar',
        data: {
            labels: {!! json_encode($mesesLabels) !!},
            datasets: [
                {
                    label: 'Año {{ $anioAnterior }}',
                    data: {!! json_encode($datosAnioAnterior) !!},
                    backgroundColor: '#eaecf4',
                    hoverBackgroundColor: '#d1d3e2',
                    borderRadius: 4
                },
                {
                    label: 'Año {{ $anioActual }}',
                    data: {!! json_encode($datosAnioActual) !!},
                    backgroundColor: '#0a9396',
                    hoverBackgroundColor: '#005f73',
                    borderRadius: 4
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', align: 'end' } },
            scales: {
                x: { grid: { display: false, drawBorder: false } },
                y: { grid: { color: "rgb(234, 236, 244)", borderDash: [2], drawBorder: false }, beginAtZero: true }
            }
        }
    });

    // 4. Gráfico de Frecuencia Lluvia vs Seco
    const ctxFreq = document.getElementById('chartFrecuencia');
    new Chart(ctxFreq, {
        type: 'doughnut',
        data: {
            labels: ['Días con Lluvia', 'Días Secos'],
            datasets: [{
                data: [{{ $diasConLluvia }}, {{ $diasSecos }}],
                backgroundColor: ['#0a9396', '#eaecf4'],
                hoverBackgroundColor: ['#005f73', '#d1d3e2'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection