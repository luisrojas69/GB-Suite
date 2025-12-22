@extends('layouts.app') 
@section('title', 'Gestión de Zafras (Campañas)')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📅 Gestión de Zafras (Campañas)</h1>
        
        @can('crear_zafras') {{-- Asumimos un permiso 'crear_zafras' --}}
        <a href="{{ route('produccion.agro.zafras.create') }}" class="d-none d-sm-inline-block btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nueva Zafra
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
            <h6 class="m-0 font-weight-bold text-primary">Listado de Zafras Registradas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @can('ver_zafras') {{-- Asumimos un permiso 'ver_zafras' --}}
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Periodo</th>
                            <th>Fechas de Operación</th>
                            <th>Estado</th>
                            <th>Moliendas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($zafras as $zafra)
                        <tr>
                            <td><a href="{{ route('produccion.agro.zafras.show', $zafra->id) }}">{{ $zafra->nombre }}</a></td>
                            <td>{{ $zafra->anio_inicio }} - {{ $zafra->anio_fin }}</td>
                            <td>
                                **Inicio:** {{ $zafra->fecha_inicio ? \Carbon\Carbon::parse($zafra->fecha_inicio)->format('d/m/Y') : 'N/A' }}<br>
                                **Fin:** {{ $zafra->fecha_fin ? \Carbon\Carbon::parse($zafra->fecha_fin)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($zafra->estado) {
                                        'Activa' => 'badge-success',
                                        'Cerrada' => 'badge-secondary',
                                        'Planeada' => 'badge-warning',
                                        default => 'badge-info'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $zafra->estado }}</span>
                            </td>
                            {{-- Conteo de moliendas (uso lazy en la vista) --}}
                            <td>{{ $zafra->moliendas()->count() }}</td> 
                            <td>
                                @can('ver_zafras')
                                <a href="{{ route('produccion.agro.zafras.show', $zafra->id) }}" class="btn btn-info btn-sm" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                                @endcan

                                @can('editar_zafras')
                                <a href="{{ route('produccion.agro.zafras.edit', $zafra->id) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                @endcan

                                @can('eliminar_zafras')
                                <button type="button" class="btn btn-danger btn-sm delete-zafra" data-id="{{ $zafra->id }}" data-nombre="{{ $zafra->nombre }}" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p class="alert alert-warning">No tiene permiso para ver este listado de zafras.</p>
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
             "order": [[1, "desc"]], // Ordenar por Periodo (columna 1) descendente
             "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            }
        });

        $('.delete-zafra').on('click', function() {
            const zafraId = $(this).data('id');
            const zafraNombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Eliminar Zafra?',
                text: `¿Está seguro de eliminar la zafra "${zafraNombre}"?`,
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
                        url: `/produccion/agro/zafras/${zafraId}`, // Usamos la URL de la ruta destroy
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
                                'Ocurrió un error al intentar eliminar la Zafra.',
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