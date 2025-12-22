@extends('layouts.app') 
@section('title', 'Gestión de Tarifas de Liquidación')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">💲 Gestión de Tarifas y Precios Base</h1>
        
        @can('gestionar_tarifas')
        <a href="{{ route('liquidacion.tarifas.create') }}" class="d-none d-sm-inline-block btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Registrar Nueva Tarifa
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
    
    @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Tarifas Registradas</h6>
        </div>
        <div class="card-body">
            @can('gestionar_tarifas')
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Concepto</th>
                            <th>Valor</th>
                            <th>Unidad</th>
                            <th>Vigencia Desde</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tarifas as $tarifa)
                        <tr>
                            <td>{{ $tarifa->id }}</td>
                            <td>{{ $tarifa->concepto }}</td>
                            <td>{{ $tarifa->valor_formateado }}</td>
                            <td>{{ $tarifa->unidad }}</td>
                            <td>{{ $tarifa->fecha_vigencia->format('d/m/Y') }}</td>
                            <td>
                                @if ($tarifa->estado == 'Activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-warning">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('liquidacion.tarifas.edit', $tarifa->id) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                
                                <form action="{{ route('liquidacion.tarifas.destroy', $tarifa->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar esta tarifa? Solo elimine si no está relacionada con liquidaciones generadas.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="alert alert-warning">Usted no tiene permisos para gestionar estas tarifas.</p>
            @endcan
        </div>
    </div>

</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "order": [[ 0, "desc" ]], // Ordenar por ID descendente
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });
        });
    </script>
@endsection