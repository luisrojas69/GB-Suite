@extends('layouts.app') 
@section('title', 'Histórico de Liquidaciones')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">💰 Historial de Liquidaciones</h1>
        
        @can('generar_liquidaciones')
        <a href="{{ route('liquidacion.create') }}" class="d-none d-sm-inline-block btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Generar Nueva Liquidación
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
            <h6 class="m-0 font-weight-bold text-primary">Detalle de Liquidaciones Registradas</h6>
        </div>
        <div class="card-body">
            @can('ver_liquidaciones')
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Arrime Ref.</th>
                            <th>Fecha Cierre</th>
                            <th>Molienda (Tn)</th>
                            <th>Pol Caña (%)</th>
                            <th>Precio Base</th>
                            <th>Liquidación Neta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($liquidaciones as $liquidacion)
                        <tr>
                            <td>{{ $liquidacion->id }}</td>
                            <td><a href="#">#{{ $liquidacion->molienda_id }}</a></td>
                            <td>{{ $liquidacion->fecha_cierre ? $liquidacion->fecha_cierre->format('d/m/Y') : 'Pendiente' }}</td>
                            <td>{{ number_format($liquidacion->molienda->toneladas_arrimadas ?? 0, 2, ',', '.') }}</td>
                            <td>{{ number_format($liquidacion->pol_cana, 2, ',', '.') }}</td>
                            <td>{{ '$ ' . number_format($liquidacion->precio_base, 4, ',', '.') }}</td>
                            <td><span class="badge badge-success">{{ '$ ' . $liquidacion->liquidacion_neta }}</span></td>
                            <td>
                                @can('ver_liquidaciones')
                                    <a href="{{ route('liquidacion.show', $liquidacion->id) }}" class="btn btn-info btn-sm" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                                @endcan
                                
                                @can('generar_liquidaciones')
                                    <a href="{{ route('liquidacion.edit', $liquidacion->id) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                    
                                    <form action="{{ route('liquidacion.destroy', $liquidacion->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar la Liquidación #{{ $liquidacion->id }}?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="alert alert-warning">Usted no tiene permisos para ver este listado de liquidaciones.</p>
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
            // Inicializar Select2 si hay algún campo en la vista que lo necesite (Aunque no en index, es buena práctica)
            $('.select2').select2({
                theme: "bootstrap", 
                width: '100%'
            });
        });
    </script>
@endsection