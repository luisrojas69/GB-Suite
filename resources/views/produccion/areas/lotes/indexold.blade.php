@extends('layouts.app') 
@section('title', 'Gestión de Lotes')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-layer-group text-primary"></i> Lotes Productivos</h1>
        
        @can('produccion.areas.crear')
        <a href="{{ route('produccion.areas.lotes.create') }}" class="btn btn-success btn-icon-split shadow-sm">
            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
            <span class="text">Crear Nuevo Lote</span>
        </a>
        @endcan
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4 border-bottom-info">
        <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Lotes por Sector</h6>
            <span class="badge badge-info">{{ $lotes->count() }} Lotes en total</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @can('produccion.areas.ver')
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="15%">Código Completo</th>
                            <th width="25%">Sector de Origen</th>
                            <th width="25%">Nombre del Lote</th>
                            <th width="15%" class="text-center">Estructura</th>
                            <th width="20%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lotes as $lote)
                        <tr>
                            <td class="align-middle text-center">
                                <span class="badge badge-dark p-2" style="font-size: 0.9rem;">
                                    {{ $lote->codigo_completo }}
                                </span>
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary text-white mr-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                        <i class="fas fa-map"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold text-dark">{{ $lote->sector->nombre }}</span><br>
                                        <small class="text-muted">Cod: {{ $lote->sector->codigo_sector }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <span class="font-weight-bold text-primary">{{ $lote->nombre }}</span><br>
                                <small class="text-muted">ID Interno: {{ $lote->codigo_lote_interno }}</small>
                            </td>

                            <td class="align-middle text-center">
                                <div class="badge badge-pill badge-light border border-info px-3 py-2">
                                    <i class="fas fa-th-large text-info mr-1"></i> 
                                    <strong>{{ $lote->tablones->count() }}</strong> 
                                    <small class="text-muted text-uppercase">Tablones</small>
                                </div>
                                @if($lote->tablones->count() > 0)
                                    <br>
                                    <small class="text-success font-weight-bold">
                                        {{ number_format($lote->tablones->sum('hectareas_documento'), 2) }} Has
                                    </small>
                                @endif
                            </td>

                            <td class="align-middle text-center">
                                @if(Auth::user()->can('produccion.areas.ver') || Auth::user()->can('produccion.areas.editar') || Auth::user()->can('produccion.areas.eliminar'))
                                <div class="btn-group shadow-sm" role="group">
                                    @can('produccion.areas.ver')
                                    <a href="{{ route('produccion.areas.lotes.show', $lote->id) }}" class="btn btn-white btn-sm border" title="Ver Detalle">
                                        <i class="fas fa-eye text-info"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('produccion.areas.editar')
                                    <a href="{{ route('produccion.areas.lotes.edit', $lote->id) }}" class="btn btn-white btn-sm border" title="Editar">
                                        <i class="fas fa-edit text-primary"></i>
                                    </a>
                                    @endcan

                                   @can('produccion.areas.eliminar')
                                    <form action="{{ route('produccion.areas.lotes.destroy', $lote->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-white btn-sm border" title="Eliminar" onclick="return confirm('¿Eliminar lote {{ $lote->nombre }}?')">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-lock fa-3x text-gray-200 mb-3"></i>
                        <p class="text-muted">No tiene permisos para ver esta sección.</p>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection