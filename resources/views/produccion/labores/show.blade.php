@extends('layouts.app')
@section('title-page', 'Certificado de Labor: ' . ($registro->labor->nombre ?? 'N/A'.'| Jornada # '.$registro->id))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-labor { height: 350px; width: 100%; border-radius: 12px; z-index: 1; }
    .border-left-primary { border-left: .25rem solid #4e73df!important; }
    .border-left-success { border-left: .25rem solid #1cc88a!important; }
    .stat-label { font-size: 0.75rem; text-transform: uppercase; font-weight: bold; color: #858796; }
    .stat-value { font-size: 1.25rem; font-weight: bold; color: #4e73df; }

    :root {
        --agro-dark: #1b4332;
        --agro-primary: #2d6a4f;
        --agro-accent: #52b788;
        --agro-light: #f8f9fa;
    }

    /* Contenedores Principales */
    .show-header {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; padding: 30px; border-radius: 15px; margin-bottom: 25px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .card-premium {
        border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: transform 0.2s; background: white; height: 100%;
    }

    /* Mapa con Estilo */
    #map-labor { 
        height: 400px; width: 100%; border-radius: 15px; 
        z-index: 1; border: 4px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* Widgets de Estadísticas */
    .stat-card {
        padding: 20px; border-radius: 12px; background: white;
        border-left: 5px solid var(--agro-accent);
    }
    .stat-icon { font-size: 2rem; opacity: 0.2; position: absolute; right: 20px; top: 20px; }
    .stat-label { font-size: 0.75rem; text-transform: uppercase; font-weight: 800; color: #6c757d; letter-spacing: 0.5px; }
    .stat-value { font-size: 1.5rem; font-weight: bold; color: var(--agro-dark); display: block; }

    /* Línea de Tiempo / Detalles */
    .resource-item {
        padding: 15px; border-radius: 10px; background: var(--agro-light);
        margin-bottom: 10px; border: 1px solid #e9ecef;
    }

    .badge-agro {
        padding: 6px 12px; border-radius: 50px; font-weight: 600; font-size: 0.75rem;
    }

    /* Botonera Float */
    .action-buttons .btn { border-radius: 50px; padding: 10px 20px; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="show-header d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('produccion.labores.index') }}" class="text-white opacity-75">Labores</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Jornada #{{ $registro->id }}</li>
                </ol>
            </nav>
            <h1 class="h2 font-weight-bold mb-0">
                <i class="fas fa-check-circle mr-2"></i>{{ $registro->labor->nombre }}
            </h1>
            <p class="mb-0 opacity-75 mt-1">
                <i class="far fa-calendar-alt mr-1"></i> Ejecutado el: <strong>{{ $registro->fecha_ejecucion->format('d/m/Y') }}</strong> | 
                <i class="far fa-user mr-1"></i> Supervisor: {{ $registro->usuario->full_name ?? 'Admin' }}
            </p>
        </div>
        <div class="action-buttons d-none d-md-block">
            <a href="{{ route('produccion.labores.index') }}" class="btn btn-light text-dark mr-2">
                <i class="fas fa-list mr-1"></i> Ver Historial
            </a>
            <button onclick="window.print()" class="btn btn-outline-light">
                <i class="fas fa-print mr-1"></i> Imprimir Reporte
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card shadow-sm position-relative">
                        <i class="fas fa-layer-group stat-icon text-success"></i>
                        <span class="stat-label">Área Total</span>
                        <span class="stat-value">{{ number_format($registro->tablones->sum('pivot.hectareas_logradas'), 2) }} Ha</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card shadow-sm position-relative" style="border-left-color: #36b9cc;">
                        <i class="fas fa-th-large stat-icon text-info"></i>
                        <span class="stat-label">Tablones Intervenidos</span>
                        <span class="stat-value">{{ $registro->tablones->count() }} Unidades</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card shadow-sm position-relative" style="border-left-color: #f6c23e;">
                        <i class="fas fa-users-cog stat-icon text-warning"></i>
                        <span class="stat-label">Tipo de Ejecución</span>
                        <span class="stat-value text-uppercase" style="font-size: 1.1rem;">{{ $registro->tipo_ejecutor }}</span>
                    </div>
                </div>
            </div>
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
                            <table class="table table-sm table-hover bg-white mb-0 align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Sector / Lote</th>
                                        <th class="text-center">Estado Final</th>
                                        <th class="text-right">Has. Logradas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registro->tablones as $t)
                                    <tr>
                                        <td class="font-weight-bold text-primary">
                                            <a href="{{ route('produccion.areas.tablones.show', $t->id) }}" title="Ver detalle del Tablon {{ $t->codigo_completo }}">{{ $t->codigo_completo }}</a> 
                                        </td>
                                        <td>{{ $t->lote->sector->nombre }} / {{ $t->lote->nombre }}</td>
                                        
                                        <td class="text-center">
                                            <span class="badge badge-secondary">{{ $t->estado }}</span>
                                        </td>
                                        <td class="text-right font-weight-bold">{{ number_format($t->pivot->hectareas_logradas, 2) }} Has</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="4" class="text-right font-weight-bold text-success" style="font-size: 1.1rem;">TOTAL EJECUTADO:  {{ number_format($registro->tablones->sum('pivot.hectareas_logradas'), 2) }} Ha</td>
                                    </tr>
                                </tfoot>
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
                <div class="card-footer bg-white">
                    <small class="text-muted">Información detalada de labores Granja Boraure</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-premium mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-map-marked-alt mr-2 text-success"></i>Cobertura de la Labor</h5>
                    <span class="badge badge-pill badge-light border">{{ $registro->tablones->first()->lote->sector->nombre ?? 'N/A' }}</span>
                </div>                
                <div class="card-body p-2">
                    <div id="map-labor"></div>
                     <div class="card-footer bg-white">
                        <small class="text-muted">Se muestran los tablones intervenidos en esta labor.</small>
                    </div>
                    <div class="mt-4">
                        <div class="card card-premium mb-4">
                            <div class="card-body">
                                <h6 class="font-weight-bold mb-3"><i class="fas fa-user-tie mr-2 text-success"></i>Recurso Humano</h6>
                                @if($registro->tipo_ejecutor == 'Contratista')
                                    <div class="d-flex align-items-center p-3 bg-light rounded border border-warning">
                                        <div class="icon-circle bg-warning text-white mr-3">
                                            <i class="fas fa-handshake"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Empresa Externa</small>
                                            <span class="font-weight-bold">{{ $registro->contratista->nombre ?? $registro->contratista_nombre }}</span>
                                            <small class="d-block text-muted">RIF: {{ $registro->contratista->rif ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center p-3 bg-light rounded border border-primary">
                                        <div class="icon-circle bg-primary text-white mr-3">
                                            <i class="fas fa-house-user"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Ejecución</small>
                                            <span class="font-weight-bold text-primary">PERSONAL PROPIO</span>
                                            <small class="d-block text-muted">Operaciones In-House</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">                      
                        <div class="card card-premium">
                            <div class="card-body">
                                <h6 class="font-weight-bold mb-3 d-flex justify-content-between">
                                    <span><i class="fas fa-tractor mr-2 text-success"></i>Equipos Utilizados</span>
                                    <span class="badge badge-pill badge-success">{{ $registro->maquinarias->count() }}</span>
                                </h6>
                                
                                @forelse($registro->maquinarias as $maq)
                                <div class="resource-item border-left-primary">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span class="font-weight-bold text-dark">{{ $maq->activo->codigo }}</span>
                                        <span class="badge badge-agro bg-primary text-white">
                                            {{ number_format($maq->horometro_final - $maq->horometro_inicial, 2) }} Hrs
                                        </span>
                                    </div>
                                    <small class="text-muted d-block mt-1"><i class="fas fa-user-alt mr-1"></i> {{ $maq->operador->nombre_completo ?? 'N/A' }}</small>
                                    <hr class="my-2">
                                    <div class="row no-gutters text-center">
                                        <div class="col-6 border-right">
                                            <small class="d-block text-muted x-small">INICIO</small>
                                            <span class="font-weight-bold">{{ number_format($maq->horometro_inicial, 2) }}</span>
                                        </div>
                                        <div class="col-6">
                                            <small class="d-block text-muted x-small">FINAL</small>
                                            <span class="font-weight-bold">{{ number_format($maq->horometro_final, 2) }}</span>
                                        </div>
                                    </div>
                                    @if($maq->horas_desfase_uso > 0)
                                        <div class="mt-2 text-center p-1 bg-warning-light rounded" style="background: #fff4e5;">
                                            <small class="text-warning-emphasis font-weight-bold" style="font-size: 0.7rem;">
                                                <i class="fas fa-exclamation-triangle"></i> Desfase: {{ $maq->horas_desfase_uso }} hrs detectadas
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-walking fa-2x mb-2 opacity-25"></i>
                                    <p class="small mb-0">Esta labor se realizó de forma manual.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <small class="text-muted">Datos extraidos de GB-SUITE.</small>
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