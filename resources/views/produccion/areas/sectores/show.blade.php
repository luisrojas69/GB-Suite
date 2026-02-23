@extends('layouts.app')
@section('title-page', 'Sector: ' . $sector->nombre)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* ========================================
       VARIABLES GLOBALES - TEMA AGRO/ECO
    ======================================== */
    :root {
        --agro-dark: #1b4332;
        --agro-primary: #2d6a4f;
        --agro-light: #d8f3dc;
        --agro-accent: #52b788;
        --agro-earth: #bc6c25;
        --weather-bg: linear-gradient(135deg, #0a9396 0%, #005f73 100%);
    }

    /* HEADER */
    .page-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; padding: 25px 30px; border-radius: 15px;
        margin-bottom: 25px; box-shadow: 0 8px 25px rgba(45, 106, 79, 0.25);
        position: relative; overflow: hidden;
    }
    .page-header-agro::before {
        content: '\f5a0'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        position: absolute; top: -15px; right: 15px;
        font-size: 8rem; color: rgba(255,255,255,0.06); transform: rotate(-10deg);
    }

    /* MAPA */
    #map-show { height: 480px; width: 100%; border-radius: 12px; z-index: 1; border: 2px solid #e3e6f0; }
    
    /* WIDGET CLIMA */
    .weather-widget { 
        background: var(--weather-bg); color: white; border-radius: 15px; border: none; 
        box-shadow: 0 10px 20px rgba(0, 95, 115, 0.2);
    }
    .weather-forecast-item {
        background: rgba(255,255,255,0.1); border-radius: 10px; padding: 8px 5px;
        backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.2);
    }

    /* KPIs */
    .card-stat-agro { border: none; border-radius: 12px; transition: transform 0.3s ease; }
    .card-stat-agro:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    .icon-circle-agro { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    
    .border-agro-1 { border-bottom: 4px solid var(--agro-dark); }
    .border-agro-2 { border-bottom: 4px solid var(--agro-accent); }
    .border-agro-3 { border-bottom: 4px solid #00b4d8; }
    .border-agro-4 { border-bottom: 4px solid #f6c23e; }

    /* LIST GROUP PERSONALIZADO */
    .list-group-agro .list-group-item { border-left: 4px solid transparent; transition: all 0.2s; }
    .list-group-agro .list-group-item:hover { border-left-color: var(--agro-accent); background-color: #f8f9fc; }
</style>
@endpush

@section('content')
<div class="container-fluid">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-left-success" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-left-danger" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="page-header-agro d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div>
            <div class="d-flex align-items-center mb-1">
                <span class="badge badge-light text-dark px-3 py-1 mr-3 shadow-sm" style="font-size: 0.9rem; letter-spacing: 1px;">
                    <i class="fas fa-fingerprint text-success mr-1"></i> {{ $sector->codigo_sector }}
                </span>
                <h2 class="font-weight-bold mb-0">{{ $sector->nombre }}</h2>
            </div>
            <p class="mb-0 text-white-50 mt-2">
                <i class="far fa-calendar-alt mr-1"></i> Registrado el {{ $sector->created_at->format('d M, Y') }}
            </p>
        </div>
        <div class="mt-3 mt-md-0 btn-group shadow-sm">
            <a href="{{ route('produccion.areas.sectores.index') }}" class="btn btn-dark bg-gradient-dark border-0 px-4">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
            <a href="{{ route('produccion.areas.sectores.edit', $sector->id) }}" class="btn btn-light text-primary font-weight-bold px-4">
                <i class="fas fa-edit mr-1"></i> Editar Sector
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro border-agro-1 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--agro-dark);">Superficie Total</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ number_format($hectareasTotales, 2) }} <small class="text-muted text-xs">Ha</small></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(27, 67, 50, 0.1); color: var(--agro-dark);"><i class="fas fa-ruler-combined"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro border-agro-2 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--agro-accent);">Estructura Interna</div>
                            <div class="h4 mb-0 font-weight-black text-gray-800">{{ $sector->lotes->count() }} <small class="text-muted text-xs font-weight-normal">Lotes</small></div>
                            <div class="text-xs font-weight-bold text-gray-500 mt-1"><i class="fas fa-th-large mr-1"></i>{{ $conteoTablones }} Tablones activos</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(82, 183, 136, 0.1); color: var(--agro-accent);"><i class="fas fa-layer-group"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro border-agro-3 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #00b4d8;">Pluviometría (Mes)</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ $acumuladoMes }} <small class="text-muted text-xs">mm</small></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(0, 180, 216, 0.1); color: #00b4d8;"><i class="fas fa-tint"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro border-agro-4 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Días Secos</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ $diasSinLluvia }} <small class="text-muted text-xs">Días</small></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(246, 194, 62, 0.1); color: #f6c23e;"><i class="fas fa-sun"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="m-0 font-weight-bold" style="color: var(--agro-dark);"><i class="fas fa-satellite mr-2"></i> Visor Geoespacial del Sector</h6>
                    @if($sector->geometria)
                        <span class="badge badge-success px-3 py-1 rounded-pill"><i class="fas fa-check-circle mr-1"></i> Polígono Validado</span>
                    @else
                        <span class="badge badge-warning px-3 py-1 rounded-pill"><i class="fas fa-exclamation-triangle mr-1"></i> Requiere Dibujo</span>
                    @endif
                </div>
                <div class="card-body p-2 bg-light">
                    <div id="map-show" class="shadow-sm"></div>
                </div>
                <div class="card-footer bg-white text-muted small py-2 d-flex justify-content-between">
                    <span><i class="fas fa-info-circle text-primary"></i> Haga clic en los tablones (verde) para ver detalles y accesos rápidos.</span>
                    <span class="font-weight-bold">Superficie Satelital: {{ number_format($sector->hectareas_geometria, 2) }} Has</span>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-sitemap mr-2 text-primary"></i> Desglose de Lotes y Tablones</h6>
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="accordionEstructura">
                        @forelse($sector->lotes as $lote)
                        <div class="card border-0 border-bottom rounded-0">
                            <div class="card-header bg-white p-0" id="heading-{{ $lote->id }}">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-dark font-weight-bold text-decoration-none d-flex justify-content-between align-items-center py-3" type="button" data-toggle="collapse" data-target="#collapse-{{ $lote->id }}">
                                        <span><i class="fas fa-folder text-warning mr-2"></i> Lote: {{ $lote->codigo_completo }} <small class="text-muted ml-2">({{ $lote->nombre }})</small></span>
                                        <span class="badge badge-light border text-muted">{{ $lote->tablones->count() }} Tablones <i class="fas fa-chevron-down ml-2 text-xs"></i></span>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse-{{ $lote->id }}" class="collapse" data-parent="#accordionEstructura">
                                <div class="card-body p-0 bg-light">
                                    <ul class="list-group list-group-flush list-group-agro">
                                        @foreach($lote->tablones as $tablon)
                                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-4">
                                            <div>
                                                <i class="fas fa-leaf text-success mr-2"></i> <strong>{{ $tablon->codigo_completo }}</strong>
                                                <span class="text-muted small ml-2">{{ $tablon->variedad->nombre ?? 'Sin Siembra' }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-white border shadow-sm px-2 py-1 mr-3">{{ number_format($tablon->hectareas_documento, 2) }} Ha</span>
                                                <a href="{{ route('produccion.areas.tablones.show', $tablon->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Ver Tablón</a>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 text-gray-300"></i>
                            <p>No hay lotes registrados en este sector.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xl-4 col-lg-5">
            
            <div class="card weather-widget mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="font-weight-bold text-uppercase text-white-50 small m-0"><i class="fas fa-satellite-dish mr-1"></i> AgroMonitoring Data</h6>
                        <i class="fas fa-cloud-sun fa-2x opacity-50"></i>
                    </div>
                    
                    @if($clima)
                        <div class="text-center my-4">
                            <h1 class="display-3 font-weight-bold mb-0" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">{{ round($clima['main']['temp']) }}°</h1>
                            <p class="text-capitalize h5 font-weight-light">{{ $clima['weather'][0]['description'] }}</p>
                        </div>
                        
                        <div class="row text-center mb-4">
                            <div class="col-4 border-right border-white-50">
                                <i class="fas fa-tint mb-1 text-info"></i>
                                <h6 class="font-weight-bold mb-0">{{ $clima['main']['humidity'] }}%</h6>
                                <small class="text-white-50">Humedad</small>
                            </div>
                            <div class="col-4 border-right border-white-50">
                                <i class="fas fa-wind mb-1 text-white-50"></i>
                                <h6 class="font-weight-bold mb-0">{{ $clima['wind']['speed'] }} <small>m/s</small></h6>
                                <small class="text-white-50">Viento</small>
                            </div>
                            <div class="col-4">
                                <i class="fas fa-compress-arrows-alt mb-1 text-white-50"></i>
                                <h6 class="font-weight-bold mb-0">{{ $clima['main']['pressure'] }}</h6>
                                <small class="text-white-50">hPa</small>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-circle fa-2x mb-2 text-warning"></i>
                            <p class="mb-0">Conexión climática no disponible.</p>
                        </div>
                    @endif

                    <h6 class="font-weight-bold border-bottom border-white-50 pb-2 mb-3 mt-2 small text-uppercase text-white-50">Pronóstico (4 Días)</h6>
                    <div class="d-flex justify-content-between">
                        @if($pronostico)
                            @foreach(array_slice($pronostico, 0, 4) as $dia)
                                <div class="text-center weather-forecast-item w-100 mx-1 shadow-sm">
                                    <small class="d-block font-weight-bold mb-1">{{ date('d/m', $dia['dt']) }}</small>
                                    <img src="http://openweathermap.org/img/wn/{{ $dia['weather'][0]['icon'] }}.png" width="35" class="mb-1" style="filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.3));">
                                    <span class="d-block font-weight-bold">{{ round($dia['main']['temp']) }}°</span>
                                </div>
                            @endforeach
                        @else
                            <p class="small text-white-50 text-center w-100">Sin datos de pronóstico.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 border-bottom-primary">
                <div class="card-header bg-white py-3"><h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-info-circle mr-2 text-primary"></i> Ficha Técnica</h6></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">ID Sistema:</span>
                            <span class="font-weight-bold">{{ $sector->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Código:</span>
                            <span class="font-weight-bold badge badge-dark px-2">{{ $sector->codigo_sector }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Has Documentales:</span>
                            <span class="font-weight-bold text-primary">{{ number_format($hectareasTotales, 2) }} Ha</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Has Satelitales:</span>
                            <span class="font-weight-bold text-success">{{ number_format($sector->hectareas_geometria, 2) }} Ha</span>
                        </li>
                    </ul>
                    <hr class="mt-2 mb-3">
                    <h6 class="small font-weight-bold text-muted text-uppercase mb-2">Descripción / Notas</h6>
                    <div class="p-3 bg-light rounded italic text-muted small border">
                        {{ $sector->descripcion ?? 'El sector no posee anotaciones u observaciones registradas.' }}
                    </div>
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

        // 1. DIBUJAR EL LÍMITE DEL SECTOR (Verde Oscuro - Estilo Agro)
        @if($sector->geometria_objeto)
            const sectorLayer = L.geoJSON({!! $sector->geometria_objeto->toJson() !!}, {
                style: { 
                    color: '#28a745', /* var(--agro-dark) */
                    weight: 4, 
                    fillOpacity: 0, 
                    dashArray: '8, 8' 
                }
            }).addTo(map);
            
            map.fitBounds(sectorLayer.getBounds(), { padding: [30, 30] });
        @endif

        // 2. DIBUJAR LOS TABLONES INTERNOS
        @foreach($sector->lotes as $lote)
            @foreach($lote->tablones as $tablon)
                @if($tablon->geometria_objeto)
                    L.geoJSON({!! $tablon->geometria_objeto->toJson() !!}, {
                        style: {
                            color: '#52b788', /* var(--agro-accent) */
                            weight: 2,
                            fillOpacity: 0.35,
                            fillColor: '#2d6a4f' /* var(--agro-primary) */
                        }
                    })
                    .addTo(map)
                    .bindTooltip("<strong>{{ $tablon->codigo_completo }}</strong>", { permanent: false, direction: "center", className: 'bg-white border-0 shadow-sm text-dark font-weight-bold px-2 py-1 rounded' })
                    .bindPopup(`
                        <div class="text-center p-1">
                            <h6 class="font-weight-bold text-success border-bottom pb-2 mb-2">Tablón: {{ $tablon->codigo_completo }}</h6>
                            <p class="small mb-1 text-muted">Lote Padre: {{ $lote->codigo_completo }}</p>
                            <p class="font-weight-bold mb-2">Área: {{ number_format($tablon->hectareas_documento, 2) }} Ha</p>
                            <a href="{{ route('produccion.areas.tablones.show', $tablon->id) }}" class="btn btn-sm btn-block btn-primary shadow-sm mt-2"><i class="fas fa-eye mr-1"></i> Ver Tablón</a>
                        </div>
                    `);
                @endif
            @endforeach
        @endforeach
    });
</script>
@endpush