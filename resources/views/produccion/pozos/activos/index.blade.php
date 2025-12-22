@extends('layouts.app')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Control de Pozos y Estaciones</h1>
    <a href="{{ route('produccion.pozos.activos.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Agregar Activo
    </a>
</div>

    {{-- Mensajes de Notificación --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Listado de Activos (Pozos y Estaciones)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Subtipo</th>
                        <th>Ubicación</th>
                        <th>Estatus</th>
                        <th>Último Cambio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activos as $activo)
                    <tr>
                        <td>{{ $activo->id }}</td>
                        <td><a href="{{ route('produccion.pozos.activos.show', $activo) }}">{{ $activo->nombre }}</a></td>
                        <td><span class="badge badge-info">{{ $activo->tipo_activo }}</span></td>
                        <td>{{ $activo->subtipo_pozo ?? 'N/A' }}</td>
                        <td>{{ $activo->ubicacion }}</td>
                        <td>
                            @php
                                $color = '';
                                switch ($activo->estatus_actual) {
                                    case 'OPERATIVO': $color = 'success'; break;
                                    case 'PARADO': $color = 'danger'; break;
                                    case 'EN_MANTENIMIENTO': $color = 'warning'; break;
                                }
                            @endphp
                            <span class="badge badge-{{ $color }}">{{ $activo->estatus_actual }}</span>
                        </td>
                        <td>{{ $activo->fecha_ultimo_cambio->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group">
                                
                            @can('editar_pozos')
                                 <a href="{{ route('produccion.pozos.activos.edit', $activo) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit" title="Editar Pozo"></i></a>
                            @endcan

                                <a href="{{ route('produccion.pozos.activos.show', $activo) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-eye" title="Ver detalles del Pozo"></i></a>

                            @can('eliminar_pozos')
                                <a href="#" class="btn btn-sm btn-outline-danger eliminar-pozo-btn" data-nombre="{{ $activo->nombre }}" data-id="{{ $activo->id }}" title="Eliminar Pozo"><i class="fas fa-trash"></i></a>
                            @endcan
                                
                            @if($activo->estatus_actual != 'EN_MANTENIMIENTO')
                                @can('crear_aforos')
                                @endcan

                                @can('crear_mtto_pozos')
                                    <a href="{{ route('produccion.pozos.activos.mantenimientos.create', $activo) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-wrench" title="Registrar Mantenimiento"></i></a>
                                @endcan
                                @if($activo->tipo_activo == 'POZO')
                                    @can('crear_aforos')
                                         <a href="{{ route('produccion.pozos.aforos.create', $activo) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-water" title="Registrar Aforo"></i></a>
                                    @endcan
                                @endif                              
                            @endif                     
                                

                                <form action="{{ route('produccion.pozos.activos.destroy', $activo->id) }}" id="form-destroy-pozo" method="POST" class="d-inline" onsubmit="return">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @can('cambiar_status_pozos')
                                    <button class="btn btn-sm btn-outline-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Estatus
                                </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item cambiar-estatus-btn" href="#" data-id="{{ $activo->id }}" data-estatus="OPERATIVO">Operativo (🟢)</a>
                                        <a class="dropdown-item cambiar-estatus-btn" href="#" data-id="{{ $activo->id }}" data-estatus="PARADO">Parado (🔴)</a>
                                        <a class="dropdown-item cambiar-estatus-btn" href="#" data-id="{{ $activo->id }}" data-estatus="EN_MANTENIMIENTO">En Mantenimiento (🟡)</a>
                                    </div>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Inicializar DataTables (Si es necesario)
    $(document).ready(function() {
        $('#dataTable').DataTable();

        // Lógica AJAX y SweetAlert2 para cambiar estatus
        $('.cambiar-estatus-btn').on('click', function(e) {
            e.preventDefault();
            var activoId = $(this).data('id');
            var nuevoEstatus = $(this).data('estatus');
            var url = '{{ url("produccion/pozos/activos") }}/' + activoId + '/cambiar-estatus';
            var token = '{{ csrf_token() }}';

            Swal.fire({
                title: '¿Estás seguro?',
                text: "El estatus del activo cambiará a " + nuevoEstatus + ".",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar estatus',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: token,
                            estatus: nuevoEstatus
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('¡Éxito!', response.message, 'success')
                                    .then(() => {
                                        location.reload(); // Recargar para ver el cambio
                                    });
                            } else {
                                Swal.fire('Error', 'No se pudo actualizar el estatus.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Hubo un error en la solicitud AJAX.', 'error');
                        }
                    });
                }
            });
        });



        $('.eliminar-pozo-btn').on('click', function(e) {
            e.preventDefault();
            var activoId = $(this).data('id');
            var nombrePozo = $(this).data('nombre');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se eliminará el Pozo: " + nombrePozo + ".",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                 $('#form-destroy-pozo').submit();   
                }
            });
        });
    });
</script>
@endsection