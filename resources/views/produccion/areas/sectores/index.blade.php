@extends('layouts.app') 
@section('title', 'Gestión de Sectores')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🌳 Sectores Productivos</h1>
        
        {{-- PROTEGER BOTÓN CREAR --}}
        @can('crear_sectores')
        <a href="{{ route('produccion.areas.sectores.create') }}" class="d-none d-sm-inline-block btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Crear Nuevo Sector
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
            <h6 class="m-0 font-weight-bold text-primary">Listado de Sectores Registrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- La tabla solo se muestra si el usuario tiene permiso para verla --}}
                @can('ver_sectores')
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Lotes Asociados</th>
                            {{-- Solo mostrar la columna de acciones si hay permisos de editar o eliminar --}}
                            @if(Auth::user()->can('editar_sectores') || Auth::user()->can('eliminar_sectores'))
                                <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sectores as $sector)
                        <tr>
                            <td>**{{ $sector->codigo_sector }}**</td>
                            <td>{{ $sector->nombre }}</td>
                            <td>{{ $sector->descripcion ?? 'N/A' }}</td>
                            <td>{{ $sector->lotes->count() }}</td>
                            
                            @if(Auth::user()->can('ver_sectores') || Auth::user()->can('editar_sectores') || Auth::user()->can('eliminar_sectores'))
                            <td>
                                @can('ver_sectores')
                                {{-- La vista SHOW no fue creada, se redirige a EDIT o se puede crear una SHOW separada --}}
                                <a href="{{ route('produccion.areas.sectores.edit', $sector->id) }}" class="btn btn-info btn-sm" title="Ver/Editar"><i class="fas fa-eye"></i></a>
                                @endcan
                                
                                @can('editar_sectores')
                                <a href="{{ route('produccion.areas.sectores.edit', $sector->id) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                @endcan

                                @can('eliminar_sectores')
                                <form action="{{ route('produccion.areas.sectores.destroy', $sector->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar el sector {{ $sector->nombre }}? Esto eliminará todos sus lotes y tablones asociados.')">
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
                    <p class="alert alert-warning">No tiene permiso para ver este listado.</p>
                @endcan
            </div>
        </div>
    </div>

</div>
@endsection