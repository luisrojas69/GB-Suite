@extends('layouts.app') 
@section('title', 'Gestión de Sectores')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-map-marked-alt text-primary"></i> Sectores Productivos</h1>
        @can('crear_sectores')
        <a href="{{ route('produccion.areas.sectores.create') }}" class="btn btn-success btn-icon-split shadow-sm">
            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
            <span class="text">Crear Nuevo Sector</span>
        </a>
        @endcan
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show border-left-success" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Sectores</h6>
            <span class="badge badge-primary">{{ $sectores->count() }} Sectores registrados</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @can('ver_sectores')
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%" class="text-center"><i class="fas fa-map text-secondary"></i></th>
                            <th width="5%">Cod.</th>
                            <th width="20%">Nombre Sector</th>
                            <th width="15%">Estructura</th>
                            <th width="15%">Extensión</th>
                            <th width="20%">Climatología</th>
                            <th width="10%">Estado</th>
                            <th width="15%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sectores as $sector)
                        <tr>
                            <td class="align-middle text-middle">
                                @if($sector->geometria)
                                    {{-- Icono verde si tiene coordenadas --}}
                                    <i class="fas fa-check-circle text-success" title="Georreferenciado"></i>
                                @else
                                    {{-- Icono de advertencia si falta el dibujo --}}
                                    <i class="fas fa-exclamation-triangle text-warning" title="Sin geometría - Pendiente por dibujar"></i>
                                @endif
                            </td>
                            <td class="align-middle"><strong>{{ $sector->codigo_sector }}</strong></td>
                            <td class="align-middle">
                                <span class="text-dark font-weight-bold">{{ $sector->nombre }}</span><br>
                                <small class="text-muted text-truncate" style="max-width: 150px;">{{ $sector->descripcion ?? 'Sin descripción' }}</small>
                            </td>
                            <td class="align-middle">
                                <div class="badge badge-info p-2 mb-1" title="Lotes">
                                    <i class="fas fa-layer-group mr-1"></i> {{ $sector->lotes_count }} Lotes
                                </div><br>
                                <div class="badge badge-secondary p-2" title="Tablones">
                                    <i class="fas fa-th-large mr-1"></i> {{ $sector->tablones_count }} Tablones
                                </div>
                            </td>
                            <td class="align-middle text-middle">
                                <span class="h6 font-weight-bold text-success">
                                    {{ number_format($sector->tablones->sum('hectareas_documento'), 2) }} 
                                </span> 
                                <small class="text-gray-600">Has</small>
                            </td>
                            <td class="align-middle">
                                @if($sector->ultimaLluvia)
                                    <div class="text-primary">
                                        <i class="fas fa-cloud-rain mr-1"></i> <strong>{{ $sector->ultimaLluvia->cantidad_mm }} mm</strong>
                                    </div>
                                    <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i>{{ $sector->ultimaLluvia->fecha->diffForHumans() }}</small>
                                @else
                                    <small class="text-muted">Sin registros recientes</small>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-pill badge-success shadow-sm">Operativo</span>
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group" role="group">
                                    @can('ver_sectores')
                                    <a href="{{ route('produccion.areas.sectores.show', $sector->id) }}" class="btn btn-outline-info btn-sm" title="Vista Satelital">
                                        <i class="fas fa-map"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('editar_sectores')
                                    <a href="{{ route('produccion.areas.sectores.edit', $sector->id) }}" class="btn btn-outline-primary btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan

                                    @can('eliminar_sectores')
                                    <form action="{{ route('produccion.areas.sectores.destroy', $sector->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar sector? Esto afectará toda la jerarquía.')" title="Borrar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-lock fa-3x text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No tiene permisos para gestionar áreas.</p>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection