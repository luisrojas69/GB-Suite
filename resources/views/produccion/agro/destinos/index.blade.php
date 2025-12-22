@extends('layouts.app') 
@section('title', 'Catálogo de Destinos (Centrales)')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📍 Catálogo de Destinos (Centrales)</h1>
        
        @can('crear_destinos') {{-- Asumimos un permiso 'crear_destinos' --}}
        <a href="{{ route('produccion.agro.destinos.create') }}" class="d-none d-sm-inline-block btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nuevo Destino
        </a>
        @endcan
        
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {!! $message !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Destinos Registrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @can('ver_destinos') {{-- Asumimos un permiso 'ver_destinos' --}}
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Moliendas Asignadas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($destinos as $destino)
                        <tr>
                            <td><span class="badge badge-primary">{{ $destino->codigo }}</span></td>
                            <td><a href="{{ route('produccion.agro.destinos.show', $destino->id) }}">{{ $destino->nombre }}</a></td>
                            {{-- Conteo de moliendas (uso lazy en la vista) --}}
                            <td>{{ $destino->moliendas()->count() }}</td> 
                            <td>
                                @can('ver_destinos')
                                <a href="{{ route('produccion.agro.destinos.show', $destino->id) }}" class="btn btn-info btn-sm" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                                @endcan

                                @can('editar_destinos')
                                <a href="{{ route('produccion.agro.destinos.edit', $destino->id) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                @endcan

                                @can('eliminar_destinos')
                                <button type="button" class="btn btn-danger btn-sm delete-destino" data-id="{{ $destino->id }}" data-nombre="{{ $destino->nombre }}" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p class="alert alert-warning">No tiene permiso para ver este listado de destinos.</p>
                @endcan
            </div>
        </div>
    </div>

</div>

@push('scripts')
{{-- Script para SweetAlert2 y AJAX para eliminar --}}
<script>
    $(document).ready(function() {
        // Inicializar DataTables
        $('#dataTable').DataTable({
             "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            }
        });

        $('.delete-destino').on('click', function() {
            const destinoId = $(this).data('id');
            const destinoNombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Eliminar Destino?',
                text: `¿Está seguro de eliminar el destino "${destinoNombre}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡Eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si confirma, enviamos la solicitud AJAX
                    $.ajax({
                        url: `/produccion/agro/destinos/${destinoId}`, // Usamos la URL de la ruta destroy
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    // Recargar la página para actualizar la tabla
                                    window.location.reload(); 
                                });
                            } else {
                                Swal.fire(
                                    'Error',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            // En caso de error de servidor
                            Swal.fire(
                                'Error',
                                'Ocurrió un error al intentar eliminar el destino.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
@endsection