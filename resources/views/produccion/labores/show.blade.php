@extends('layouts.app')
@section('title', 'Detalle de Labor: ' . $registro->labor->nombre)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-labor { height: 350px; width: 100%; border-radius: 12px; z-index: 1; }
    .border-left-primary { border-left: .25rem solid #4e73df!important; }
    .border-left-success { border-left: .25rem solid #1cc88a!important; }
    .stat-label { font-size: 0.75rem; text-transform: uppercase; font-weight: bold; color: #858796; }
    .stat-value { font-size: 1.25rem; font-weight: bold; color: #4e73df; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-check text-primary mr-2"></i>Jornada #{{ $registro->id }}
            </h1>
            <p class="text-muted mb-0">Registrado por: {{ $registro->usuario->name ?? 'Sistema' }} el {{ $registro->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="btn-group shadow-sm">
            <button onclick="window.print()" class="btn btn-sm btn-white border"><i class="fas fa-print"></i> Imprimir</button>
            <a href="{{ route('produccion.labores.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Resumen de Ejecución</h6>
                    <span class="badge badge-primary px-3">{{ $registro->labor->nombre }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 border-right text-center">
                            <div class="stat-label">Fecha</div>
                            <div class="stat-value text-dark">{{ \Carbon\Carbon::parse($registro->fecha_ejecucion)->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-3 border-right text-center">
                            <div class="stat-label">Hectáreas</div>
                            <div class="stat-value">{{ number_format($registro->tablones->sum('pivot.hectareas_logradas'), 2) }}</div>
                        </div>
                        <div class="col-md-3 border-right text-center">
                            <div class="stat-label">Ejecutor</div>
                            <div class="stat-value" style="font-size: 1rem;">
                                @if($registro->tipo_ejecutor == 'Propio')
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Propio</span>
                                @else
                                    <span class="text-info"><i class="fas fa-truck-loading"></i> {{ $registro->contratista_nombre }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="stat-label">Tablones</div>
                            <div class="stat-value text-dark">{{ $registro->tablones->count() }}</div>
                        </div>
                    </div>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold" id="tablones-tab" data-toggle="tab" href="#tablones" role="tab">Tablones Trabajados</a>
                        </li>
                        @if($registro->maquinarias->count() > 0)
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="maquinaria-tab" data-toggle="tab" href="#maquinaria" role="tab">Uso de Maquinaria</a>
                        </li>
                        @endif
                    </ul>
                    <div class="tab-content border-left border-right border-bottom p-3 bg-light" id="myTabContent">
                        <div class="tab-pane fade show active" id="tablones" role="tabpanel">
                            <table class="table table-sm table-hover bg-white mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Sector / Lote</th>
                                        <th class="text-right">Has. Logradas</th>
                                        <th class="text-center">Estado Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registro->tablones as $t)
                                    <tr>
                                        <td class="font-weight-bold text-primary">
                                            <a href="{{ route('produccion.areas.tablones.show', $t->id) }}" title="Ver detalle del Tablon {{ $t->codigo_completo }}">{{ $t->codigo_completo }}</a> 
                                        </td>
                                        <td>{{ $t->lote->sector->nombre }} / {{ $t->lote->nombre }}</td>
                                        <td class="text-right font-weight-bold">{{ number_format($t->pivot->hectareas_logradas, 2) }} Has</td>
                                        <td class="text-center">
                                            <span class="badge badge-secondary">{{ $t->estado }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="maquinaria" role="tabpanel">
                            <table class="table table-sm table-hover bg-white mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Equipo</th>
                                        <th>Operador</th>
                                        <th class="text-center">H. Inicial</th>
                                        <th class="text-center">H. Final</th>
                                        <th class="text-right text-primary text-uppercase" style="font-size: 0.7rem;">Total Horas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registro->maquinarias as $m)
                                    <tr>
                                        <td><strong><a href="{{ route('activos.show', $m->activo->id) }}">{{ $m->activo->nombre }}</a></strong> <small class="text-muted">({{ $m->activo->codigo }})</small></td>
                                        <td>{{ $m->operador->nombre_completo ?? 'N/A' }}</td>
                                        <td class="text-center">{{ number_format($m->horometro_inicial, 1) }}</td>
                                        <td class="text-center">{{ number_format($m->horometro_final, 1) }}</td>
                                        <td class="text-right font-weight-bold text-primary">
                                            {{ number_format($m->horometro_final - $m->horometro_inicial, 1) }} hrs
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="font-weight-bold">Observaciones:</h6>
                        <div class="p-3 border rounded bg-light italic">
                            {{ $registro->observaciones ?? 'Sin observaciones registradas.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-map-marked-alt"></i> Cobertura de la Labor</h6>
                </div>
                <div class="card-body p-0">
                    <div id="map-labor"></div>
                </div>
                <div class="card-footer bg-white">
                    <small class="text-muted">Se muestran los tablones intervenidos en esta labor.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    $(document).ready(function() {
        const map = L.map('map-labor');
        
        L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);

        const featureGroup = L.featureGroup().addTo(map);
        
        @foreach($registro->tablones as $t)
            @if($t->geometria_render)
                try {
                    // Usamos el objeto convertido a GeoJSON
                    let geoData = {!! $t->geometria_render->toJson() !!};
                    let layer = L.geoJSON(geoData, {
                        style: { 
                            color: '#28a745', // Verde para resaltar la labor cumplida
                            weight: 3, 
                            fillOpacity: 0.4 
                        }
                    })
                    .bindTooltip("Tablón: {{ $t->codigo_completo }}")
                    .bindPopup('<strong>Tablón: {{ $t->codigo_completo }}</strong><br>Has: {{ $t->pivot->hectareas_logradas }}');
                    
                    featureGroup.addLayer(layer);
                } catch (e) {
                    console.error("Error en JS para tablón {{ $t->codigo_completo }}:", e);
                }
            @endif
        @endforeach

        if (featureGroup.getLayers().length > 0) {
            map.fitBounds(featureGroup.getBounds(), { padding: [30, 30] });
        } else {
            console.error("Error cargando geometría del tablón {{ $t->id }}:", e);
            map.setView([9.9606, -70.2347], 14); // Coordenadas de contingencia
        }
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        title: '¡Labor Registrada Exitosamente!',
        text: "¿Desea agregar mas labores.?",
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#858796',
        confirmButtonText: '<i class="fas fa-print"></i> Crear otra Labor',
        cancelButtonText: 'Cerrar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("{{ route('produccion.labores.create') }}");
        }

    });
</script>
@endif
@endpush