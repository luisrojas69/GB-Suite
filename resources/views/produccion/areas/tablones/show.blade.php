@extends('layouts.app')
@section('title', 'Detalle de Tablón: ' . $tablon->nombre)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-show { height: 400px; width: 100%; border-radius: 8px; border: 2px solid #4e73df; }
</style>
@endpush

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
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-map-marker-alt text-primary"></i> Tablón: <strong>{{ $tablon->nombre }}</strong> 
            <small class="text-muted">({{ $tablon->codigo_completo }})</small>
        </h1>
        <div class="btn-group shadow-sm">
            @can('produccion.areas.editar')
                <a href="{{ route('produccion.areas.tablones.edit', $tablon->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Editar Tabl&oacute;n
                </a>
            @endcan
            @can('produccion.areas.ver')
                <a href="{{ route('produccion.areas.tablones.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-list fa-sm text-white-50"></i> Lista de Tablones
                </a>
            @endcan
        </div>
        
    </div>


        <div class="row">
            {{-- Columna de Información --}}
            <div class="col-xl-7 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Información General</h6>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 border-right">
                               <h6>Jerarquía y Nomenclatura</h6>
                                <hr class="mt-0">
                                <p><strong>Código Único:</strong> <span class="badge badge-info">{{ $tablon->codigo_completo }}</span></p>
                                <p><strong>Código Interno:</strong> {{ $tablon->codigo_tablon_interno }}</p>
                                <p><strong>Lote Padre:</strong> {{ $tablon->lote->nombre }} ({{ $tablon->lote->codigo_completo }})</p>
                                <p><strong>Sector:</strong> {{ $tablon->lote->sector->nombre }}</p>
                                <p><strong>Finca:</strong> {{ $tablon->lote->sector->finca->nombre ?? 'Granja Boraure' }}</p> {{-- Asumiendo que Sector tiene relación con Finca --}}
                            </div>

                            <div class="col-md-4 border-right">
                                <h6>Área y Metas</h6>
                                <hr class="mt-0">
                                <p><strong>Superficie:</strong> <span class="badge badge-warning">{{ number_format($tablon->hectareas_documento, 2, ',', '.') }} Has</span></p>
                                <p><strong>Tipo de Suelo:</strong> {{ $tablon->tipo_suelo ?? 'N/A' }}</p>


                                @php
                                    $statusConfig = [
                                        'Preparacion' => ['color' => 'primary', 'icon' => 'fa-seedling', 'label' => 'En Preparación'],
                                        'Crecimiento' => ['color' => 'success', 'icon' => 'fa-leaf', 'label' => 'En Crecimiento'],
                                        'Maduro'      => ['color' => 'warning', 'icon' => 'fa-certificate', 'label' => 'Maduro'],
                                        'Cosecha'     => ['color' => 'danger',  'icon' => 'fa-tractor', 'label' => 'En Cosecha'],
                                        'Inactivo'    => ['color' => 'secondary','icon' => 'fa-pause', 'label' => 'Inactivo'],
                                    ];
                                    $config = $statusConfig[$tablon->estado] ?? $statusConfig['Inactivo'];
                                @endphp

                                <p><strong>Estado:</strong> <span class="badge badge-{{ $config['color'] }}"><i class="fas {{ $config['icon'] }}"></i> {{ $config['label'] }}</span></p>
                                <p><strong>Meta T/Ha:</strong> {{ $tablon->meta_ton_ha ? number_format($tablon->meta_ton_ha, 2, ',', '.') . ' T/Ha' : 'No Definida' }}</p>

                                <p><strong>Estimado Total:</strong>
                                    <span class="info-value text-success font-weight-bold">
                                        {{ $tablon->meta_ton_ha ? number_format($tablon->meta_ton_ha * $tablon->hectareas_documento, 2) : '---' }} T
                                    </span>
                                </p>
                            </div>

                            <div class="col-md-4">
                                <h6>Control de Caña (Siembra Actual)</h6>
                                <hr class="mt-0">
                                <p><strong>Ciclo Actual:</strong> 
                                    {{ $tablon->tipo_ciclo }} 
                                        @if($tablon->tipo_ciclo == 'Soca') 
                                            <span class="badge badge-dark">#{{ $tablon->numero_soca }}</span> 
                                        @endif</p>
                                <p><strong>Variedad:</strong> <span class="badge badge-primary">{{ $tablon->variedad->nombre ?? 'Sin Siembra Asignada' }}</span></p>
                                <p><strong>Edad del Cultivo:</strong> 
                                    @if($tablon->fecha_inicio_ciclo)
                                        @php
                                            $inicio = \Carbon\Carbon::parse($tablon->fecha_inicio_ciclo);
                                            $diff = $inicio->diff(now());
                                            // Sumamos años a meses para el formato estándar de caña (ej: 14 meses)
                                            $mesesTotales = ($diff->y * 12) + $diff->m;
                                        @endphp
                                    <span class="text-primary font-weight-bold">
                                        {{ $mesesTotales }}m {{ $diff->d }}d
                                    </span>
                                    <small class="text-muted d-block">Desde el {{ $tablon->fecha_inicio_ciclo->format('d/m/Y') }}</small>

                                  
                                    @if($mesesTotales >= 12)
                                        <div class="alert alert-warning">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Recordatorio de Edad</div>
                                            <small class="text-muted d-block">⚠️ Este Tabl&oacute;n supera los 12 meses. <br> Evaluar curva de maduración. </small>
                                        </div>
                                    @else
                                    <div class="alert alert-success">
                                        <small class="text-muted d-block text-justify">🌿 En periodo de desarrollo vegetativo 🌿</small>
                                    </div>                                         
                                    @endif
                                    
                                        
                                @else
                                    <span class="text-muted">Fecha no registrada</span>
                                @endif
                                </p>

                            </div>
                        </div>
                        <hr>
                        <h6><strong>Descripción / Notas</strong></h6>
                        <p>{{ $tablon->descripcion ?? 'Sin observaciones adicionales.' }}</p>
                    </div>
                </div>

            </div>

            {{-- Columna del Mapa --}}
            <div class="col-xl-5 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-map"></i> Ubicación Geográfica</h6>
                        @if($tablon->geometria)
                            <span class="badge badge-success">Georreferenciado</span>
                        @else
                            <span class="badge badge-danger">Sin Mapa</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div id="map-show"></div>
                        <div class="p-2 text-center">
                             <small class="text-muted">Centro del tablón: {{ $tablon->geometria ? 'Coordenadas capturadas' : 'No disponible' }}</small>
                        </div>
                    </div>
                    {{-- Widget de Ayuda Crítica --}}

                </div>
            </div>

            <div class="card shadow col-lg-12 border-left-info">
                <div class="card-body">
                    <p class="mb-0 text-info">Este tablón es la unidad base. Aquí se registrarán los movimientos de **Molienda/Cosecha**, lo que activará el control de **Soca** y el cálculo de **Rendimiento**.</p>
                </div>
            </div>
        </div>    
 
</div>


@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    $(document).ready(function() {
        const map = L.map('map-show');
        L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);

       @if($tablon->geometria_objeto && method_exists($tablon->geometria_objeto, 'toJson'))
            const geojsonData = {!! $tablon->geometria_objeto->toJson() !!};
            const layer = L.geoJSON(geojsonData, {
                style: { 
                    color: '{{ $config["color"] == "success" ? "#1cc88a" : ($config["color"] == "warning" ? "#f6c23e" : "#4e73df") }}', 
                    weight: 3, 
                    fillOpacity: 0.4 
                }
            }).addTo(map);
            map.fitBounds(layer.getBounds());
        @else
            map.setView([9.960669, -70.234770], 15);
            L.marker([9.960669, -70.234770]).addTo(map).bindPopup('Sin polígono definido');
        @endif
    });
</script>
@endpush




@endsection