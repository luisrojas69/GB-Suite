@extends('layouts.app') 
@section('title', 'Histórico de Arrimes de Molienda')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📋 Histórico de Arrimes de Molienda</h1>
        
        @can('crear_moliendas')
        <a href="{{ route('produccion.agro.moliendas.create') }}" class="d-none d-sm-inline-block btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Registrar Nuevo Arrime
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
            <h6 class="m-0 font-weight-bold text-primary">Listado de Arrimes por Zafra</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @can('ver_moliendas')
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Boleto</th>
                            <th>Fecha/Hora</th>
                            <th>Tablón</th>
                            <th>Contratista</th>
                            <th>Peso Neto (kg)</th>
                            <th>Rendimiento (%)</th>
                            <th>Zafra</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($moliendas as $molienda)
                        <tr>
                            <td>
                                <a href="{{ route('produccion.agro.moliendas.show', $molienda->id) }}" class="font-weight-bold">{{ $molienda->boleto_remesa }}</a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($molienda->fecha_arrime)->format('d/m/Y H:i') }}</td>
                            <td>{{ $molienda->tablon->codigo_completo }} - {{ $molienda->tablon->nombre }}</td>
                            <td>{{ $molienda->contratista->nombre }}</td>
                            <td><span class="badge badge-info p-2">{{ number_format($molienda->toneladas, 2, ',', '.') }}</span></td>
                            <td>{{ $molienda->rendimiento ? number_format($molienda->rendimiento, 2, ',', '.') . '%' : 'N/A' }}</td>
                            <td><span class="badge badge-secondary">{{ $molienda->zafra->nombre }}</span></td>
                            <td>
                                <a href="{{ route('produccion.agro.moliendas.show', $molienda->id) }}" class="btn btn-info btn-sm" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                                
                                @can('editar_moliendas')
                                <a href="{{ route('produccion.agro.moliendas.edit', $molienda->id) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                @endcan

                                @can('eliminar_moliendas')
                                <button type="button" class="btn btn-danger btn-sm delete-molienda" data-id="{{ $molienda->id }}" data-boleto="{{ $molienda->boleto_remesa }}" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $moliendas->links('pagination::bootstrap-4') }}
                </div>
                
                @else
                    <p class="alert alert-warning">No tiene permiso para ver este listado de arrimes.</p>
                @endcan
            </div>
        </div>
    </div>

</div>

@push('scripts')
{{-- Script para SweetAlert2 y AJAX para eliminar --}}
<script>
    $(document).ready(function() {
        // Inicializar DataTables (Solo si no estás usando paginación manual de Laravel)
        // Si usas paginación de Laravel, comenta o ajusta esto para no paginar doble.
        // $('#dataTable').DataTable({
        //      "language": {
        //         "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        //     },
        //     "paging": false, // Deshabilitar si se usa la paginación de Laravel
        // });

        $('.delete-molienda').on('click', function() {
            const moliendaId = $(this).data('id');
            const boleto = $(this).data('boleto');
            
            Swal.fire({
                title: '¿Eliminar Arrime?',
                text: `¿Está seguro de eliminar el registro de molienda con Boleto **${boleto}**?`,
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
                        url: `/produccion/agro/moliendas/${moliendaId}`, // Usamos la URL de la ruta destroy
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
                            Swal.fire(
                                'Error',
                                'Ocurrió un error al intentar eliminar el registro.',
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