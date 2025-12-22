@extends('layouts.app') 
@section('title', 'Catálogo de Variedades de Caña')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📋 Catálogo de Variedades</h1>
        
        @can('crear_variedades') {{-- Asumimos un permiso 'crear_variedades' --}}
        <a href="{{ route('produccion.agro.variedades.create') }}" class="d-none d-sm-inline-block btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nueva Variedad
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
            <h6 class="m-0 font-weight-bold text-primary">Listado de Variedades Registradas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @can('ver_variedades') {{-- Asumimos un permiso 'ver_variedades' --}}
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Meta POL (%)</th>
                            <th>Tablones Asignados</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($variedades as $variedad)
                        <tr>
                            <td>{{ $variedad->codigo ?? 'N/A' }}</td>
                            <td><a href="{{ route('produccion.agro.variedades.show', $variedad->id) }}">{{ $variedad->nombre }}</a></td>
                            <td>{{ $variedad->meta_pol_cana ? number_format($variedad->meta_pol_cana, 2, ',', '.') . ' %' : 'N/A' }}</td>
                            {{-- Se asume una relación 'tablones' en el modelo Variedad, aunque no se cargó aquí (uso lazy en la vista) --}}
                            <td>{{ $variedad->tablones()->count() }}</td> 
                            <td>
                                @can('ver_variedades')
                                <a href="{{ route('produccion.agro.variedades.show', $variedad->id) }}" class="btn btn-info btn-sm" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                                @endcan

                                @can('editar_variedades')
                                <a href="{{ route('produccion.agro.variedades.edit', $variedad->id) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                @endcan

                                @can('eliminar_variedades')
                                <button type="button" class="btn btn-danger btn-sm delete-variedad" data-id="{{ $variedad->id }}" data-nombre="{{ $variedad->nombre }}" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p class="alert alert-warning">No tiene permiso para ver este listado de variedades.</p>
                @endcan
            </div>
        </div>
    </div>

</div>

@push('scripts')
{{-- Script para SweetAlert2 y AJAX para eliminar --}}
<script>
    $(document).ready(function() {
        // Inicializar DataTables (asumo que se carga en layouts.app)
        $('#dataTable').DataTable({
             "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            }
        });

        $('.delete-variedad').on('click', function() {
            const variedadId = $(this).data('id');
            const variedadNombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Eliminar Variedad?',
                text: `¿Está seguro de eliminar la variedad "${variedadNombre}"?`,
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
                        url: `/produccion/agro/variedades/${variedadId}`, // Usamos la URL de la ruta destroy
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
                                'Ocurrió un error al intentar eliminar la variedad.',
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