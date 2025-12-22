@extends('layouts.app') 
@section('title', 'Gestión de Tablones')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🌱 Tablones de Siembra</h1>
        
        @can('crear_sectores')
        <a href="{{ route('produccion.areas.tablones.create') }}" class="d-none d-sm-inline-block btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Crear Nuevo Tablón
        </a>
        @endcan
        
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {!! $message !!} {{-- Usar {!! ... !!} para que el '<b>' se renderice si se usa --}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Tablones Registrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @can('ver_sectores')
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Área (Ha)</th>
                            <th>Variedad</th>
                            <th>F. Siembra</th>
                            <th>Estado</th>
                            <th>Lote Padre</th>
                            <th style="width: 150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tablones as $tablon)
                        <tr>
                            <td><a href="{{ route('produccion.areas.tablones.show', $tablon->id) }}">{{ $tablon->codigo_completo }}</a></td>
                            <td>{{ $tablon->nombre }}</td>
                            <td>{{ number_format($tablon->area_ha, 2, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $tablon->variedad->nombre ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $tablon->fecha_siembra ? $tablon->fecha_siembra->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ ($tablon->estado == 'Activo') ? 'success' : (($tablon->estado == 'Preparacion') ? 'info' : 'danger') }}">{{ $tablon->estado }}</span>
                            </td>
                            <td>{{ $tablon->lote->nombre }} ({{ $tablon->lote->sector->nombre }})</td>
                            <td>
                                @can('ver_sectores')
                                <a href="{{ route('produccion.areas.tablones.show', $tablon->id) }}" class="btn btn-info btn-sm" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                                @endcan

                                @can('editar_sectores')
                                <a href="{{ route('produccion.areas.tablones.edit', $tablon->id) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                @endcan

                                @can('eliminar_sectores')
                                <button type="button" class="btn btn-danger btn-sm delete-tablon" data-id="{{ $tablon->id }}" data-nombre="{{ $tablon->nombre }}" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $tablon->id }}" action="{{ route('produccion.areas.tablones.destroy', $tablon->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p class="alert alert-warning">No tiene permiso para ver este listado de tablones.</p>
                @endcan
            </div>
        </div>
    </div>

</div>

@push('scripts')
{{-- Asegúrate de incluir SweetAlert2 en tu layout principal --}}
<script>
    $(document).ready(function() {
        $('.delete-tablon').on('click', function() {
            const tablonId = $(this).data('id');
            const tablonNombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Está seguro?',
                text: `Se eliminará el tablón "${tablonNombre}". ¡Esta acción es irreversible y podría fallar si tiene cosechas registradas!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡Eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#delete-form-' + tablonId).submit();
                }
            });
        });
    });
</script>
@endpush
@endsection