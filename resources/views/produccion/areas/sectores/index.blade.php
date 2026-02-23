@extends('layouts.app') 
@section('title-page', 'Gestión de Sectores')

@section('styles')
<style>
    /* ========================================
       VARIABLES GLOBALES - TEMA AGRO/ECO
    ======================================== */
    :root {
        --agro-dark: #1b4332;      /* Verde Bosque Profundo */
        --agro-primary: #2d6a4f;   /* Verde Esmeralda */
        --agro-light: #d8f3dc;     /* Verde Suave / Pastel */
        --agro-accent: #52b788;    /* Verde Vibrante */
        --agro-earth: #bc6c25;     /* Tono Tierra para contrastes */
    }

    /* ========================================
       HEADER PRINCIPAL
    ======================================== */
    .page-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; 
        padding: 25px 30px; 
        border-radius: 15px;
        margin-bottom: 25px; 
        box-shadow: 0 8px 25px rgba(45, 106, 79, 0.25);
        position: relative; 
        overflow: hidden;
    }
    .page-header-agro::before {
        content: '\f5a0'; /* fa-map-marked-alt */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute; 
        top: -15px; 
        right: 15px;
        font-size: 8rem; 
        color: rgba(255,255,255,0.06);
        transform: rotate(-10deg);
    }

    /* ========================================
       TARJETAS DE ESTADÍSTICAS (KPIs)
    ======================================== */
    .card-stat-agro {
        border: none;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        background: #fff;
    }
    .card-stat-agro:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .border-agro-1 { border-bottom: 4px solid var(--agro-dark); }
    .border-agro-2 { border-bottom: 4px solid var(--agro-accent); }
    .border-agro-3 { border-bottom: 4px solid #f6c23e; } 
    .border-agro-4 { border-bottom: 4px solid var(--agro-earth); } 
    
    .icon-circle-agro {
        width: 50px; height: 50px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }

    /* ========================================
       TABLA Y ESTRUCTURAS
    ======================================== */
    .table-agro thead th {
        background-color: #f8f9fc;
        color: var(--agro-dark);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--agro-light);
    }
    .table-agro tbody tr {
        transition: background-color 0.2s;
    }
    .table-agro tbody tr:hover {
        background-color: rgba(82, 183, 136, 0.05);
    }
    .badge-agro-soft {
        background-color: var(--agro-light);
        color: var(--agro-dark);
        font-weight: 600;
    }
    .btn-agro {
        background-color: var(--agro-primary);
        color: white;
    }
    .btn-agro:hover {
        background-color: var(--agro-dark);
        color: white;
    }
</style>
@endsection

@section('content')

{{-- CÁLCULOS DINÁMICOS PARA LOS KPIs --}}
@php
    $totalSectores = $sectores->count();
    $totalHasGeo = $sectores->sum('hectareas_geometria');
    
    // Calculamos el total de hectáreas de documento sumando los tablones de cada sector
    $totalHasDoc = 0;
    foreach($sectores as $s) {
        $totalHasDoc += $s->tablones->sum('hectareas_documento');
    }

    $sectoresMapeados = $sectores->filter(function($s) { return !is_null($s->geometria); })->count();
    $porcentajeMapeo = $totalSectores > 0 ? round(($sectoresMapeados / $totalSectores) * 100) : 0;
    
    $totalLotes = $sectores->sum('lotes_count');
    $totalTablones = $sectores->sum('tablones_count');
@endphp

<div class="container-fluid">

    <div class="page-header-agro d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div>
            <h2 class="font-weight-bold mb-1"><i class="fas fa-layer-group mr-2"></i> Sectores Productivos</h2>
            <p class="mb-0 text-white-50" style="font-size: 1.1rem;">
                Administración de jerarquías y polígonos agrícolas.
            </p>
        </div>
        @can('produccion.areas.crear')
        <div class="mt-3 mt-md-0 btn-group shadow-sm">
            <a href="{{ route('produccion.areas.sectores.create') }}" class="btn btn-light text-success font-weight-bold shadow-sm rounded-pill px-4">
                <i class="fas fa-plus-circle mr-1"></i> Crear Sector
            </a>
        </div>
        @endcan
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show border-left-success shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-2"></i> <strong>¡Éxito!</strong> {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card card-stat-agro border-agro-1 shadow-sm h-100">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--agro-dark);">Sectores Activos</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ $totalSectores }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(27, 67, 50, 0.1); color: var(--agro-dark);">
                                <i class="fas fa-map"></i>
                            </div>
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
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--agro-accent);">Superficie Georreferenciada</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ number_format($totalHasGeo, 1) }} <small class="text-muted text-xs">Has</small></div>
                            <div class="text-xs text-gray-500 font-weight-bold"><i class="fas fa-file-contract mr-1"></i>Doc: {{ number_format($totalHasDoc, 1) }} Has</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(82, 183, 136, 0.1); color: var(--agro-accent);">
                                <i class="fas fa-draw-polygon"></i>
                            </div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Cobertura Satelital</div>
                            <div class="d-flex align-items-center mb-1">
                                <div class="h4 mb-0 font-weight-black text-gray-800 mr-2">{{ $porcentajeMapeo }}%</div>
                            </div>
                            <div class="progress progress-sm" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $porcentajeMapeo }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 font-weight-bold mt-1">{{ $sectoresMapeados }} de {{ $totalSectores }} mapeados</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(246, 194, 62, 0.1); color: #f6c23e;">
                                <i class="fas fa-satellite"></i>
                            </div>
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
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--agro-earth);">Divisiones Internas</div>
                            <div class="h3 mb-0 font-weight-black text-gray-800">{{ $totalTablones }} <small class="text-muted text-xs">Tablones</small></div>
                            <div class="text-xs text-gray-500 font-weight-bold"><i class="fas fa-th-large mr-1"></i>En {{ $totalLotes }} Lotes</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-agro" style="background: rgba(188, 108, 37, 0.1); color: var(--agro-earth);">
                                <i class="fas fa-tractor"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h6 class="m-0 font-weight-bold" style="color: var(--agro-dark);">Catálogo de Áreas</h6>
            <span class="badge badge-agro-soft px-3 py-2 rounded-pill"><i class="fas fa-list-ul mr-1"></i> {{ $totalSectores }} Registros</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                @can('produccion.areas.ver')
                <table class="table table-agro align-middle mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center"><i class="fas fa-map-pin"></i></th>
                            <th width="25%">Sector</th>
                            <th width="15%">Estructura</th>
                            <th width="15%">Superficie (Has)</th>
                            <th width="20%">Pluviometría Reciente</th>
                            <th width="10%" class="text-center">Estado</th>
                            <th width="10%" class="text-right pr-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sectores as $sector)
                        <tr>
                            <td class="text-center">
                                @if($sector->geometria)
                                    <div class="icon-circle-agro mx-auto" style="width: 35px; height: 35px; background: rgba(82,183,136,0.1); color: var(--agro-accent);" title="Georreferenciado">
                                        <i class="fas fa-check"></i>
                                    </div>
                                @else
                                    <div class="icon-circle-agro mx-auto" style="width: 35px; height: 35px; background: rgba(246,194,62,0.1); color: #f6c23e;" title="Falta polígono">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                @endif
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="badge badge-dark p-2 rounded">{{ $sector->codigo_sector }}</div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold text-gray-800">{{ $sector->nombre }}</h6>
                                        <small class="text-muted d-block text-truncate" style="max-width: 200px;">{{ $sector->descripcion ?? 'Sin descripción' }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span class="badge badge-light border text-left mb-1 py-1 px-2"><i class="fas fa-layer-group text-info mr-1"></i> {{ $sector->lotes_count }} Lotes</span>
                                    <span class="badge badge-light border text-left py-1 px-2"><i class="fas fa-th text-secondary mr-1"></i> {{ $sector->tablones_count }} Tablones</span>
                                </div>
                            </td>

                            <td>
                                <div class="font-weight-bold text-dark" title="Hectáreas según Geometría Satelital">
                                    <i class="fas fa-satellite text-success mr-1"></i> {{ number_format($sector->hectareas_geometria, 2) }}
                                </div>
                                <div class="small text-muted font-weight-bold mt-1" title="Hectáreas Documentales Totales">
                                    <i class="fas fa-file-alt text-secondary mr-1"></i> {{ number_format($sector->tablones->sum('hectareas_documento'), 2) }} Doc.
                                </div>
                            </td>

                            <td>
                                @if($sector->ultimaLluvia)
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2" style="color: #00b4d8;"><i class="fas fa-cloud-showers-heavy fa-lg"></i></div>
                                        <div>
                                            <div class="font-weight-bold text-gray-800">{{ $sector->ultimaLluvia->cantidad_mm }} mm</div>
                                            <div class="text-xs text-muted">{{ $sector->ultimaLluvia->fecha->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small"><i class="fas fa-sun text-warning mr-1"></i> Sin registros</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <span class="badge badge-success px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-seedling mr-1"></i> Operativo</span>
                            </td>

                            <td class="text-right pr-4">
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle btn btn-light btn-sm rounded-circle shadow-sm" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v text-gray-600"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                        <div class="dropdown-header">Acciones del Sector</div>
                                        
                                        @can('produccion.areas.ver')
                                        <a class="dropdown-item text-info font-weight-bold" href="{{ route('produccion.areas.sectores.show', $sector->id) }}">
                                            <i class="fas fa-map-marked-alt fa-sm fa-fw mr-2"></i> Ver Mapa Satelital
                                        </a>
                                        @endcan
                                        
                                        @can('produccion.areas.editar')
                                        <a class="dropdown-item" href="{{ route('produccion.areas.sectores.edit', $sector->id) }}">
                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Editar Datos
                                        </a>
                                        @endcan
                                        
                                        @can('produccion.areas.eliminar')
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('produccion.areas.sectores.destroy', $sector->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Eliminar sector? Se borrará la geometría y afectará la jerarquía.')">
                                                <i class="fas fa-trash fa-sm fa-fw mr-2"></i> Eliminar Sector
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="text-center py-5">
                        <div class="icon-circle-agro mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2.5rem; background: #eaecf4; color: #b7b9cc;">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h5 class="font-weight-bold text-gray-700">Acceso Restringido</h5>
                        <p class="text-gray-500">No tienes los permisos necesarios para visualizar las áreas.</p>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection