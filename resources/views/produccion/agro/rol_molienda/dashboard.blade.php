@extends('layouts.app')
@section('title-page', 'Control de Molienda: Proyectado vs Real')

@section('styles')
<style>
    :root {
        --agro-primary: #2d6a4f;
        --agro-dark: #1b4332;
        --real-color: #1cc88a;
        --plan-color: #4e73df;
    }
    .page-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; padding: 30px; border-radius: 15px; margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(45, 106, 79, 0.2);
    }
    .card-kpi { border: none; border-radius: 15px; transition: 0.3s; }
    .card-kpi:hover { transform: translateY(-5px); }
    .progress-agro { height: 8px; border-radius: 5px; background: #eaecf4; }
    .nav-calendar { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; }
    .nav-calendar:hover { background: white; color: var(--agro-dark); }
</style>
@endsection

@section('content')
<div class="container-fluid pb-5">

    <div class="page-header-agro d-flex justify-content-between align-items-center shadow">
        <div>
            <h2 class="font-weight-bold mb-0"><i class="fas fa-chart-line mr-2"></i> Monitor "Así Vamos" Molienda</h2>
            <p class="mb-0 opacity-75">Cumplimiento del Plan de Zafra vs Ejecución en Campo</p>
        </div>
        
        <div class="btn-group shadow-sm">
            <button class="btn nav-calendar px-3" onclick="cambiarMes('{{ $fechaConsulta->copy()->subMonth()->format('Y-m') }}')">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="btn btn-white font-weight-bold disabled" style="background: white; color: var(--agro-dark);">
                {{ $fechaConsulta->isoFormat('MMMM YYYY') }}
            </button>
            <button class="btn nav-calendar px-3" onclick="cambiarMes('{{ $fechaConsulta->copy()->addMonth()->format('Y-m') }}')">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-kpi shadow h-100 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Molienda del Mes (Tons)</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ number_format($ejecutadoMes, 0) }}</div>
                            <div class="text-xs text-muted mt-2">
                                Objetivo: <strong>{{ number_format($proyectadoMes, 0) }} Tns</strong>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-weight-hanging fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-kpi shadow h-100 border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">% Cumplimiento de Plan</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h3 mb-0 mr-3 font-weight-bold text-gray-800">{{ number_format($cumplimientoTons, 1) }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-agro mr-2">
                                        <div class="progress-bar bg-success" style="width: {{ $cumplimientoTons }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullseye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-kpi shadow h-100 border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Rendimiento Esperado (AVG)</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ number_format($rendimientoPlan, 2) }}%</div>
                            <div class="text-xs text-muted mt-2"><i class="fas fa-info-circle mr-1"></i> Basado en madurez proyectada</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tendencia de Molienda Acumulada: Plan vs Real</h6>
                    <div class="small">
                        <span class="mr-2"><i class="fas fa-circle text-primary"></i> Proyectado</span>
                        <span><i class="fas fa-circle text-success"></i> Ejecutado</span>
                    </div>
                </div>
                <div class="card-body">
                    <div style="height: 350px;">
                        <canvas id="chartTendenciaMolienda"></canvas>
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
    function cambiarMes(fechaStr) {
        let p = fechaStr.split('-');
        window.location.href = `{{ route('rol_molienda.dashboard') }}?anio=${p[0]}&mes=${p[1]}`;
    }

    const ctx = document.getElementById('chartTendenciaMolienda').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($diasMesLabels) !!},
            datasets: [
                {
                    label: 'Plan (Tons Acum)',
                    data: {!! json_encode($dataProyectada) !!},
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderDash: [5, 5], // Línea punteada para el plan
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Real (Tons Acum)',
                    data: {!! json_encode($dataReal) !!},
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#1cc88a',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2] } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection