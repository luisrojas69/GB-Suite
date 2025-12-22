@extends('layouts.app')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Análisis de Aforo ({{ $aforo->fecha_medicion->format('d/m/Y') }})</h1>
    <div class="d-flex">
        <a href="{{ route('produccion.pozos.aforos.edit', $aforo) }}" class="btn btn-sm btn-info shadow-sm mr-2">
            <i class="fas fa-edit fa-sm text-white-50"></i> Editar Aforo
        </a>
        <a href="{{ route('produccion.pozos.activos.show', $pozo) }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver al Pozo
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 d-flex flex-column">
        <div class="card border-left-primary shadow py-2 flex-fill">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Caudal Medido
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $aforo->caudal_medido_lts_seg }} L/s</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-water fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card shadow mb-4 mt-4 flex-fill">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">Detalles Hidrológicos</h6>
            </div>
            <div class="card-body">
                <p><strong>Pozo:</strong> <a href="{{ route('produccion.pozos.activos.show', $pozo) }}">{{ $pozo->nombre }}</a></p>
                <p><strong>Fecha de Medición:</strong> {{ $aforo->fecha_medicion->format('d/m/Y') }}</p>
                <p><strong>Nivel Estático:</strong> {{ $aforo->nivel_estatico ?? 'N/A' }} m</p>
                <p><strong>Nivel Dinámico:</strong> {{ $aforo->nivel_dinamico ?? 'N/A' }} m</p>
                <p><strong>Observaciones:</strong> {{ $aforo->observaciones ?? 'Ninguna' }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tendencia Histórica de Caudal ({{ $pozo->nombre }})</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="caudalChart"></canvas>
                </div>
                <hr>
                <i class="fas fa-chart-line"></i> Este gráfico muestra la evolución del caudal medido, ayudando a identificar el deterioro o problemas potenciales antes de una falla total.
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

<script>
    // Preparación de datos para Chart.js
    var historicoData = @json($historico);
    
    var fechas = historicoData.map(function(item) {
        // Formato de fecha corto para el eje X
        return new Date(item.fecha_medicion).toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
    });
    
    var caudales = historicoData.map(function(item) {
        return item.caudal_medido_lts_seg;
    });

    var ctx = document.getElementById("caudalChart").getContext('2d');
    var caudalChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [{
                label: "Caudal (Lts/Seg)",
                lineTension: 0.3,
                backgroundColor: "rgba(2,117,216,0.2)", // Azul claro
                borderColor: "rgba(2,117,216,1)", // Azul fuerte
                pointRadius: 5,
                pointBackgroundColor: "rgba(2,117,216,1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(2,117,216,1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: caudales,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: { unit: 'month' },
                    gridLines: { display: false, drawBorder: false },
                    ticks: { maxTicksLimit: 7 }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        callback: function(value, index, values) { return value + ' L/s'; } // Etiqueta L/s
                    },
                    gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                }],
            },
            legend: { display: false },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + tooltipItem.yLabel + ' L/s';
                    }
                }
            }
        }
    });
</script>
@endsection