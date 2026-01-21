@extends('layouts.app')
@section('title', 'Sector: ' . $sector->nombre)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-show { height: 400px; width: 100%; border-radius: 12px; z-index: 1; }
    .kpi-card { border-left: 4px solid; transition: transform 0.2s; }
    .kpi-card:hover { transform: translateY(-5px); }
    .label-sector-tag { 
        background: rgba(255,255,255,0.9); border: 2px solid #4e73df; 
        padding: 3px 8px; border-radius: 5px; font-weight: bold; color: #2e59d9; 
    }
    .weather-widget { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; }
</style>
@endpush

@section('content')

@section('content')
{{-- Mostrar mensajes de sesión --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Sector: <strong>{{ $sector->nombre }}</strong></h1>
            <span class="text-muted">ID: {{ $sector->codigo_sector }} | Registrado: {{ $sector->created_at->format('d/m/Y') }}</span>
        </div>
        <div>
            <a href="{{ route('produccion.areas.sectores.edit', $sector->id) }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-edit fa-sm"></i> Editar
            </a>
            <a href="{{ route('produccion.areas.sectores.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-list fa-sm"></i> Listado
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Área Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($hectareasTotales, 2) }} Ha</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-ruler-combined fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Estructura</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $sector->lotes->count() }} Lotes / {{ $conteoTablones }} Tablones</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-layer-group fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Lluvia del Mes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $acumuladoMes }} mm</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-cloud-showers-heavy fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Días sin Lluvia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $diasSinLluvia }} Días</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-sun fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-map-marked-alt"></i> Geolocalización</h6>
                </div>
                <div class="card-body p-2">
                    <div id="map-show"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card weather-widget shadow mb-4">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-3"><i class="fas fa-satellite"></i> Datos Satelitales (AgroMonitoring)</h6>
                    <div id="agromonitoring-data">
                        @if($clima)
                            <div class="text-center">
                                <h2 class="display-4 font-weight-bold mb-0">{{ round($clima['main']['temp']) }}°C</h2>
                                <p class="text-capitalize">{{ $clima['weather'][0]['description'] }}</p>
                            </div>
                            <hr class="border-light">
                            <div class="row text-center small">
                                <div class="col-4">
                                    <i class="fas fa-tint"></i><br>
                                    {{ $clima['main']['humidity'] }}%<br>Hum.
                                </div>
                                <div class="col-4">
                                    <i class="fas fa-wind"></i><br>
                                    {{ $clima['wind']['speed'] }} m/s<br>Viento
                                </div>
                                <div class="col-4">
                                    <i class="fas fa-compress-arrows-alt"></i><br>
                                    {{ $clima['main']['pressure'] }}<br>hPa
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                <p>No se pudieron cargar los datos climáticos.</p>
                            </div>
                        @endif

                        <h6 class="font-weight-bold mt-4 mb-2"><i class="fas fa-calendar-alt"></i> Pronóstico próximos días</h6>
                        <div class="d-flex justify-content-between overflow-auto">
                            @if($pronostico)
                                @foreach(array_slice($pronostico, 0, 4) as $dia)
                                    <div class="text-center px-2">
                                        <small class="d-block">{{ date('d/m', $dia['dt']) }}</small>
                                        <img src="http://openweathermap.org/img/wn/{{ $dia['weather'][0]['icon'] }}.png" width="35">
                                        <small class="d-block font-weight-bold">{{ round($dia['main']['temp']) }}°</small>
                                    </div>
                                @endforeach
                            @else
                                <p class="small text-white-50">Pronóstico no disponible</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-dark">Notas del Sector</h6></div>
                <div class="card-body">
                    <p class="small text-muted">{{ $sector->descripcion ?? 'Sin observaciones registradas.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map-show').setView([9.960669, -70.234770], 14);
        
        L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);

        // 1. DIBUJAR EL LÍMITE DEL SECTOR (Borde azul grueso)
        @if($sector->geometria_objeto)
            const sectorLayer = L.geoJSON({!! $sector->geometria_objeto->toJson() !!}, {
                style: { 
                    color: '#4e73df', 
                    weight: 5, 
                    fillOpacity: 0, // Sin relleno para ver los tablones dentro
                    dashArray: '10, 10' 
                }
            }).addTo(map);
            
            map.fitBounds(sectorLayer.getBounds());
        @endif

        // 2. DIBUJAR LOS TABLONES INTERNOS (Relleno verde)
        @foreach($sector->lotes as $lote)
            @foreach($lote->tablones as $tablon)
                @if($tablon->geometria_objeto)
                    L.geoJSON({!! $tablon->geometria_objeto->toJson() !!}, {
                        style: {
                            color: '#1cc88a', // Verde éxito
                            weight: 1.5,
                            fillOpacity: 0.4,
                            fillColor: '#28a745'
                        }
                    })
                    .addTo(map)
                    .bindTooltip("Tablón: {{ $tablon->codigo_completo }}")
                    .bindPopup(`
                        <div class="text-center">
                            <strong>Tablón: {{ $tablon->codigo_completo }}</strong><br>
                            <small>Lote: {{ $lote->codigo_completo }}</small><hr class="my-1">
                            Área: {{ number_format($tablon->hectareas_documento, 2) }} Ha<br>
                            <a href="{{ route('produccion.areas.tablones.show', $tablon->id) }}" class="btn btn-xs btn-primary text-white mt-1">Ver Detalle</a>
                        </div>
                    `);
                @endif
            @endforeach
        @endforeach
    });

</script>
@endpush