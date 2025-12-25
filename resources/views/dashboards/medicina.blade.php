@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Gestión Salud y Seguridad</h1>
        <span class="text-muted">{{ date('l, d F Y') }}</span>
    </div>

    @if(($alertas_reposo + $alertas_vacas) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-warning text-white shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="h5 font-weight-bold mb-0">Atención de Retornos Pendiente</div>
                            <p class="mb-0 small">Hay {{ $alertas_reposo }} reposos vencidos y {{ $alertas_vacas }} retornos de vacaciones para hoy.</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('medicina.alertas.index') }}" class="btn btn-light btn-sm font-weight-bold text-warning shadow-sm">Atender Ahora</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Consultas (Mes Actual)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $consultas_mes }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-notes-medical fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Accidentes Reportados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $accidentes_mes }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Dotaciones Realizadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dotaciones_mes }}</div> </div>
                        <div class="col-auto"><i class="fas fa-tshirt fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Personal en Sistema</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_personal }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Tendencia de Morbilidad (Consultas)</h6>
                        <a href="{{ route('medicina.reportes.morbilidad') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Prevalencia de Diagnósticos (Top 5)</h6>
                </div>
                    <div class="card-body">
                        @php $colores = ['bg-danger', 'bg-warning', 'bg-primary', 'bg-info', 'bg-success']; @endphp
                        
                        @foreach($topDiagnosticos as $index => $diag)
                            @php 
                                $porcentaje = ($diag->total / $topDiagnosticos->sum('total')) * 100; 
                            @endphp
                            <h4 class="small font-weight-bold">
                                {{ $diag->diagnostico_cie10 }} <span class="float-right">{{ number_format($porcentaje, 0) }}%</span>
                            </h4>
                            <div class="progress mb-4">
                                <div class="progress-bar {{ $colores[$index] ?? 'bg-secondary' }}" 
                                     role="progressbar" 
                                     style="width: {{ $porcentaje }}%">
                                </div>
                            </div>
                        @endforeach
                    </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Pacientes con Mayor Frecuencia (Mes Actual)</h6>
                    <i class="fas fa-user-clock text-gray-300"></i>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <thead>
                                <tr class="small text-muted text-uppercase">
                                    <th>Paciente</th>
                                    <th class="text-center">Consultas</th>
                                    <th>Barra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topPacientes as $tp)
                                @php $max_consultas = $topPacientes->first()->total ?? 1; @endphp
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $tp->paciente->nombre_completo }}</td>
                                    <td class="text-center"><span class="badge badge-primary px-2">{{ $tp->total }}</span></td>
                                    <td style="width: 40%;">
                                        <div class="progress progress-sm mt-1">
                                            <div class="progress-bar bg-primary" role="progressbar" 
                                                 style="width: {{ ($tp->total / $max_consultas) * 100 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-danger">Mapa de Riesgo: Lugares con más Accidentes</h6>
                    <i class="fas fa-map-marker-alt text-gray-300"></i>
                </div>
                <div class="card-body">
                    @forelse($topLugares as $lugar)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="font-weight-bold text-gray-800">{{ $lugar->lugar_exacto }}</span>
                                <span class="small font-weight-bold text-danger">{{ $lugar->total }} Incidentes</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-danger" role="progressbar" 
                                     style="width: {{ ($lugar->total / ($topLugares->sum('total') ?: 1)) * 100 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                            <p class="text-muted small">Sin accidentes registrados este mes.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myAreaChart').getContext('2d');
        
        // Obtenemos los datos desde PHP
        const labels = @json($labelsMeses);
        const dataValues = @json($dataValores);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Consultas Totales",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: dataValues,
                }],
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // Para que no muestre decimales en el conteo de personas
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection