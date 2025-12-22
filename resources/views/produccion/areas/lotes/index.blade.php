@extends('layouts.app') 
@section('title', 'Gestión de Lotes')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📋 Lotes Productivos</h1>
        
        @can('crear_sectores') {{-- Usamos el permiso general de creación para Lotes --}}
        <a href="{{ route('produccion.areas.lotes.create') }}" class="d-none d-sm-inline-block btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Crear Nuevo Lote
        </a>
        @endcan
        
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Lotes Registrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @can('ver_sectores') {{-- Usamos el permiso general de visualización --}}
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código Completo</th>
                            <th>Sector</th>
                            <th>Código Interno</th>
                            <th>Nombre</th>
                            <th>Tablones</th>
                            {{-- Solo mostrar la columna de acciones si hay permisos de editar o eliminar --}}
                            @if(Auth::user()->can('editar_sectores') || Auth::user()->can('eliminar_sectores'))
                                <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lotes as $lote)
                        <tr>
                            <td>**{{ $lote->codigo_completo }}**</td>
                            <td>{{ $lote->sector->codigo_sector }} - {{ $lote->sector->nombre }}</td>
                            <td>{{ $lote->codigo_lote_interno }}</td>
                            <td>{{ $lote->nombre }}</td>
                            <td>{{ $lote->tablones->count() }}</td>
                            
                                @if(Auth::user()->can('ver_sectores') || Auth::user()->can('editar_sectores') || Auth::user()->can('eliminar_sectores'))
                                <td>
                                    @can('ver_sectores')
                                    <a href="{{ route('produccion.areas.lotes.show', $lote->id) }}" class="btn btn-info btn-sm" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                                    @endcan
                                    
                                    @can('editar_sectores')
                                    <a href="{{ route('produccion.areas.lotes.edit', $lote->id) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                    @endcan

                                    @can('eliminar_sectores')
                                    <form action="{{ route('produccion.areas.lotes.destroy', $lote->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar el lote {{ $lote->nombre }}? Esto eliminará todos sus tablones asociados.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                                @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p class="alert alert-warning">No tiene permiso para ver este listado de lotes.</p>
                @endcan
            </div>
        </div>
    </div>

</div>
@endsection