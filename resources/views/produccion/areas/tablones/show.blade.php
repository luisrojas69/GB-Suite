@extends('layouts.app')
@section('title-page', 'Ficha Técnica: ' . $tablon->nombre)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    :root {
        --agro-dark: #1b4332;
        --agro-primary: #2d6a4f;
        --agro-success: #1cc88a;
        --agro-warning: #f6c23e;
        --agro-info: #4e73df;
    }

    /* HEADER CON ESTILO DASHBOARD */
    .show-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; padding: 40px 30px; border-radius: 20px;
        margin-bottom: -50px; /* Overlap effect */
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        position: relative; z-index: 1;
    }

    /* TARJETAS DE INDICADORES (Pills superiores) */
    .kpi-pill {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px; padding: 15px;
        transition: all 0.3s ease;
    }
    .kpi-pill:hover { background: rgba(255, 255, 255, 0.25); transform: translateY(-3px); }

    /* CONTENEDOR PRINCIPAL */
    .main-content-wrapper { padding-top: 60px; }

    /* MAPA PROFESIONAL */
    #map-show { 
        height: 450px; width: 100%; border-radius: 15px; 
        border: 4px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* ESTILO DE LISTAS Y TABS */
    .nav-pills .nav-link.active {
        background-color: var(--agro-primary);
        box-shadow: 0 4px 12px rgba(45, 106, 79, 0.3);
    }
    .nav-link { color: var(--agro-dark); font-weight: 600; }

    .detail-item { padding: 12px 0; border-bottom: 1px solid #f1f1f1; }
    .detail-label { 
        font-size: 0.75rem; text-transform: uppercase; 
        color: #858796; font-weight: 800; letter-spacing: 0.5px;
    }
    .detail-value { font-size: 1rem; color: #2d3436; font-weight: 600; }

    /* BADGES PERSONALIZADOS */
    .badge-agro-lg { padding: 8px 16px; border-radius: 50px; font-size: 0.9rem; }
</style>
@endpush

@section('content')
<div class="container-fluid mb-5">

    {{-- Lógica de Estado y Edad --}}
    @php
        $statusConfig = [
            'Preparacion' => ['color' => 'info', 'icon' => 'fa-seedling', 'label' => 'Preparación'],
            'Crecimiento' => ['color' => 'success', 'icon' => 'fa-leaf', 'label' => 'Crecimiento'],
            'Maduro'      => ['color' => 'warning', 'icon' => 'fa-certificate', 'label' => 'Maduro'],
            'Cosecha'     => ['color' => 'danger',  'icon' => 'fa-tractor', 'label' => 'Cosecha'],
            'Inactivo'    => ['color' => 'secondary','icon' => 'fa-pause', 'label' => 'Inactivo'],
        ];
        $config = $statusConfig[$tablon->estado] ?? $statusConfig['Inactivo'];

        $mesesTotales = 0;
        $diasRestantes = 0;
        if($tablon->fecha_inicio_ciclo) {
            $inicio = \Carbon\Carbon::parse($tablon->fecha_inicio_ciclo);
            $diff = $inicio->diff(now());
            $mesesTotales = ($diff->y * 12) + $diff->m;
            $diasRestantes = $diff->d;
        }
    @endphp

    <div class="show-header-agro shadow">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('produccion.areas.tablones.index') }}" class="text-white-50 small">Tablones</a></li>
                        <li class="breadcrumb-item active text-white small" aria-current="page">{{ $tablon->codigo_completo }}</li>
                    </ol>
                </nav>
                <h1 class="display-5 font-weight-bold mb-1">
                    <i class="fas fa-th-large mr-2"></i>{{ $tablon->nombre }}
                </h1>
                <div class="d-flex align-items-center mt-3">
                    <span class="badge-agro-lg badge-{{ $config['color'] }} shadow-sm mr-3">
                        <i class="fas {{ $config['icon'] }} mr-1"></i> {{ $config['label'] }}
                    </span>
                    <span class="text-white-50"><i class="fas fa-barcode mr-1"></i> ID Interno: {{ $tablon->codigo_tablon_interno }}</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="kpi-pill text-center">
                            <small class="d-block text-white-50 text-uppercase font-weight-bold">Superficie</small>
                            <span class="h4 font-weight-bold text-white">{{ number_format($tablon->hectareas_documento, 2) }} <small>Ha</small></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="kpi-pill text-center">
                            <small class="d-block text-white-50 text-uppercase font-weight-bold">Edad Actual</small>
                            <span class="h4 font-weight-bold text-white">{{ $mesesTotales }}<small>m</small> {{ $diasRestantes }}<small>d</small></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="kpi-pill text-center">
                            <small class="d-block text-white-50 text-uppercase font-weight-bold">Rendimiento</small>
                            <span class="h4 font-weight-bold text-white">{{ $tablon->meta_ton_ha ?? '0' }} <small>T/Ha</small></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content-wrapper">
        <div class="row">
            
            <div class="col-xl-7 col-lg-6">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <ul class="nav nav-pills" id="tablonTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab"><i class="fas fa-info-circle mr-2"></i>Datos Generales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="agronomico-tab" data-toggle="pill" href="#agronomico" role="tab"><i class="fas fa-seedling mr-2"></i>Agronómico</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="acciones-tab" data-toggle="pill" href="#acciones" role="tab"><i class="fas fa-cog mr-2"></i>Gestión</a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="tab-content" id="tablonTabContent">
                            
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <div class="detail-label">Código Único (Nomenclatura)</div>
                                            <div class="detail-value text-primary font-weight-bold">{{ $tablon->codigo_completo }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Lote Perteneciente</div>
                                            <div class="detail-value">{{ $tablon->lote->nombre }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Sector / Zona</div>
                                            <div class="detail-value">{{ $tablon->lote->sector->nombre }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <div class="detail-label">Tipo de Suelo</div>
                                            <div class="detail-value text-muted">{{ $tablon->tipo_suelo ?? 'No especificado' }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Estimado Total Molienda</div>
                                            <div class="detail-value text-success">
                                                {{ $tablon->meta_ton_ha ? number_format($tablon->meta_ton_ha * $tablon->hectareas_documento, 2) : '---' }} Toneladas
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 p-3 bg-light rounded">
                                    <div class="detail-label mb-2"><i class="fas fa-sticky-note mr-1"></i> Notas del Tablón</div>
                                    <p class="mb-0 text-dark" style="line-height: 1.5;">{{ $tablon->descripcion ?? 'Sin observaciones adicionales registradas.' }}</p>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="agronomico" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <div class="detail-label">Variedad Cultivada</div>
                                            <div class="detail-value"><span class="badge badge-primary px-3">{{ $tablon->variedad->nombre ?? 'Sin Variedad' }}</span></div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Ciclo Actual</div>
                                            <div class="detail-value">
                                                {{ $tablon->tipo_ciclo }} 
                                                @if($tablon->tipo_ciclo == 'Soca') <span class="badge badge-dark">Corte #{{ $tablon->numero_soca }}</span> @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <div class="detail-label">Fecha de Inicio Ciclo</div>
                                            <div class="detail-value">{{ $tablon->fecha_inicio_ciclo ? $tablon->fecha_inicio_ciclo->format('d/m/Y') : 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($mesesTotales >= 12)
                                <div class="alert alert-warning border-left-warning mt-4 shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                                        <div>
                                            <strong class="d-block">¡Atención: Caña Madura!</strong>
                                            Este tablón ha superado los 12 meses ({{ $mesesTotales }} meses). Se recomienda programar quema o cosecha pronto.
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="acciones" role="tabpanel">
                                <div class="text-center py-4">
                                    <h5 class="text-muted mb-4">Acciones Administrativas</h5>
                                    <div class="d-flex justify-content-center flex-wrap" style="gap: 15px;">
                                        @can('produccion.areas.editar')
                                        <a href="{{ route('produccion.areas.tablones.edit', $tablon->id) }}" class="btn btn-outline-primary btn-lg rounded-pill shadow-sm px-4">
                                            <i class="fas fa-edit mr-2"></i> Editar Datos
                                        </a>
                                        @endcan
                                        <a href="#" class="btn btn-outline-success btn-lg rounded-pill shadow-sm px-4">
                                            <i class="fas fa-tractor mr-2"></i> Registrar Labor
                                        </a>
                                        <a href="{{ route('produccion.areas.tablones.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill shadow-sm px-4">
                                            <i class="fas fa-undo mr-2"></i> Volver al Listado
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- AYUDA CONTEXTUAL --}}
                <div class="card border-left-info shadow-sm py-2 mb-4">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Información de Sistema</div>
                                <div class="h6 mb-0 text-gray-800" style="font-size: 0.9rem;">
                                    Este tablón es la unidad base. Aquí se registrarán los movimientos de <strong>Molienda/Cosecha</strong>, lo que activará el control de <strong>Soca</strong>.
                                </div>
                            </div>
                            <div class="col-auto"><i class="fas fa-info-circle fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-lg-6">
                <div class="card shadow-sm border-0 overflow-hidden mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-map-marked-alt mr-2"></i>Ubicación GIS</h6>
                        @if($tablon->geometria)
                            <span class="badge badge-success px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-check-circle mr-1"></i>Georreferenciado</span>
                        @else
                            <span class="badge badge-light text-muted border px-3 py-2 rounded-pill"><i class="fas fa-times-circle mr-1"></i>Sin Polígono</span>
                        @endif
                    </div>
                    <div class="card-body p-0 position-relative">
                        <div id="map-show"></div>
                        <div class="position-absolute bg-white p-2 rounded shadow-sm" style="bottom: 10px; right: 10px; z-index: 1000; opacity: 0.9;">
                            <small class="text-dark font-weight-bold"><i class="fas fa-crosshairs mr-1"></i> Finca: Granja Boraure</small>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 py-3 text-center">
                        <div class="row">
                            <div class="col-6 border-right">
                                <small class="detail-label d-block">Coordenadas Centro</small>
                                <span class="small font-weight-bold text-dark">{{ $tablon->geometria ? 'Punto Capturado' : 'Pendiente' }}</span>
                            </div>
                            <div class="col-6">
                                <small class="detail-label d-block">Referencia</small>
                                <span class="small font-weight-bold text-dark">{{ $tablon->lote->codigo_completo }} - {{ $tablon->codigo_tablon_interno }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    $(document).ready(function() {
        // Inicialización del Mapa con estilo Satelital
        const map = L.map('map-show');
        L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3'],
            attribution: 'Google Satellite'
        }).addTo(map);

        @if($tablon->geometria_objeto && method_exists($tablon->geometria_objeto, 'toJson'))
            const geojsonData = {!! $tablon->geometria_objeto->toJson() !!};
            const layer = L.geoJSON(geojsonData, {
                style: { 
                    color: '#1cc88a', 
                    weight: 3, 
                    fillOpacity: 0.3,
                    fillColor: '#1cc88a'
                }
            }).addTo(map);
            map.fitBounds(layer.getBounds(), { padding: [30, 30] });
        @else
            // Coordenadas por defecto (Carora)
            map.setView([9.960669, -70.234770], 15);
            L.marker([9.960669, -70.234770]).addTo(map)
                .bindPopup('<strong>{{ $tablon->nombre }}</strong><br>Polígono no definido.');
        @endif
    });
</script>
@endpush
@endsection