@extends('layouts.app')

@section('content')
{{-- Mostrar mensajes de sesión --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@php
    $color = '';
    switch ($activo->estatus_actual) {
        case 'OPERATIVO': $color = 'success'; break;
        case 'PARADO': $color = 'danger'; break;
        case 'EN_MANTENIMIENTO': $color = 'warning'; break;
    }
@endphp

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detalle de Activo: {{ $activo->nombre }}</h1>
    <div class="d-flex">
        @can('editar_pozos')
            <a href="{{ route('produccion.pozos.activos.edit', $activo) }}" class="btn btn-sm btn-info shadow-sm mr-2">
                <i class="fas fa-edit fa-sm text-white-50"></i> Editar Datos Maestros
            </a>
        @endcan
        @can('ver_pozos')
            <a href="{{ route('produccion.pozos.activos.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver al Listado
            </a>
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-{{ $color }}">
                <h6 class="m-0 font-weight-bold text-white">
                    Estatus Actual: <span id="current_status_display">{{ $activo->estatus_actual }}</span>
                </h6>
            </div>
            <div class="card-body">
                <p><strong>Tipo:</strong> <span class="badge badge-primary">{{ $activo->tipo_activo }}</span></p>
                @if ($activo->tipo_activo == 'POZO')
                    <p><strong>Subtipo:</strong> {{ $activo->subtipo_pozo }}</p>
                @else
                    <p><strong>Pozo Asociado:</strong> {{ $activo->pozoAsociado ? $activo->pozoAsociado->nombre : 'N/A' }}</p>
                @endif
                <p><strong>Ubicación:</strong> {{ $activo->ubicacion }}</p>
                <p><strong>Último Cambio de Estatus:</strong> {{ $activo->fecha_ultimo_cambio->diffForHumans() }} ({{ $activo->fecha_ultimo_cambio->format('d/m/Y H:i') }})</p>

                <hr>

                @can('cambiar_status_pozos')
                    <h6 class="mt-4 mb-2">Acciones Rápidas</h6>
                    <div class="btn-group w-100" role="group">
                        <button class="btn btn-success cambiar-estatus-btn" data-id="{{ $activo->id }}" data-estatus="OPERATIVO" {{ $activo->estatus_actual == 'OPERATIVO' ? 'disabled' : '' }}>
                            <i class="fas fa-play"></i> Operativo
                        </button>
                        <button class="btn btn-warning cambiar-estatus-btn" data-id="{{ $activo->id }}" data-estatus="EN_MANTENIMIENTO" {{ $activo->estatus_actual == 'EN_MANTENIMIENTO' ? 'disabled' : '' }}>
                            <i class="fas fa-tools"></i> Mantenimiento
                        </button>
                        <button class="btn btn-danger cambiar-estatus-btn" data-id="{{ $activo->id }}" data-estatus="PARADO" {{ $activo->estatus_actual == 'PARADO' ? 'disabled' : '' }}>
                            <i class="fas fa-stop"></i> Parado
                        </button>
                    </div>
                @endcan

                @can('crear_aforos')
                    @if ($activo->tipo_activo == 'POZO')
                        <hr>
                        <a href="{{ route('produccion.pozos.aforos.create', ['pozo_id' => $activo->id]) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-water"></i> Registrar Nuevo Aforo
                        </a>
                    @endif
                <hr>
                 @endcan
                 @can('crear_mtto_pozos')
                    <a href="{{ route('produccion.pozos.activos.mantenimientos.create', $activo) }}" class="btn btn-info btn-block">
                    <i class="fas fa-wrench"></i> Registrar Mantenimiento Correctivo
                </a>
                @endcan
                
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Log Histórico del Activo</h6>
            </div>
            <div class="card-body">
                
                <ul class="timeline">
                    @php
                        $eventos = collect();
                        $eventos = $eventos->concat($activo->mantenimientos->map(function ($m) { 
                            return ['tipo' => 'MANTENIMIENTO', 'fecha' => $m->fecha_falla_reportada, 'data' => $m]; 
                        }));
                        if ($activo->tipo_activo == 'POZO' && $activo->aforos) {
                            $eventos = $eventos->concat($activo->aforos->map(function ($a) { 
                                return ['tipo' => 'AFORO', 'fecha' => $a->fecha_medicion, 'data' => $a]; 
                            }));
                        }
                        $eventos = $eventos->sortByDesc('fecha');
                    @endphp

                    @forelse ($eventos as $evento)
                        @if ($evento['tipo'] == 'MANTENIMIENTO')
                            <li class="timeline-item timeline-item-danger">
                                <span class="timeline-point">
                                    <i class="fas fa-wrench"></i>
                                </span>
                                <div class="timeline-content">
                                    <small class="text-muted float-right">{{ $evento['data']->fecha_falla_reportada->format('d/m/Y H:i') }}</small>

                                    <p class="mb-0 text-danger font-weight-bold">Falla Reportada</p>
                                    <p class="text-xs mb-1">Sintoma: {{ $evento['data']->sintoma_falla }}</p>
                                    @if ($evento['data']->trabajo_realizado)
                                        <p class="text-xs mb-1">Trabajo: {{ Str::limit($evento['data']->trabajo_realizado, 50) }}</p>
                                        <small class="text-success">Operación restablecida el {{ $evento['data']->fecha_reinicio_operacion->format('d/m/Y') }}</small>
                                    @else
                                        <small class="text-warning">Mantenimiento Pendiente de Cerrar</small>
                                    @endif
                                 @can('ver_mantenimientos')   
                                    <a href="{{ route('produccion.pozos.mantenimientos.show', $evento['data']) }}" class="btn btn-primary btn-sm float-right">
                                            <i class="fas fa-eye"></i> Ver Detalle
                                        </a>
                                    </div>
                                @endcan
                                <hr class="sidebar-divider d-none d-md-block">
                            </li>
                        @elseif ($evento['tipo'] == 'AFORO')
                            <li class="timeline-item timeline-item-info">
                                <span class="timeline-point">
                                    <i class="fas fa-water"></i>
                                </span>
                                <div class="timeline-content">
                                    <small class="text-muted float-right">{{ $evento['data']->fecha_medicion->format('d/m/Y') }}</small>
                                    <p class="mb-0 text-info font-weight-bold">Aforo Registrado</p>
                                    <p class="text-xs mb-1">Caudal: **{{ $evento['data']->caudal_medido_lts_seg }} L/s**</p>
                                    <p class="text-xs mb-1">Nivel Dinámico: {{ $evento['data']->nivel_dinamico }}m</p>
                                    <a href="{{ route('produccion.pozos.aforos.show', $evento['data']) }}" class="btn btn-sm btn-link p-0 float-right">Ver Detalle</a>
                                </div>
                            </li>
                        @endif
                    @empty
                        <div class="alert alert-info">Aún no hay eventos registrados para este activo.</div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.timeline {
    list-style-type: none;
    position: relative;
    padding-left: 20px;
}
.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 2px;
    background: #e3e6f0; /* Color gris de SBAdmin2 */
}
.timeline-item {
    margin-bottom: 20px;
    position: relative;
}
.timeline-point {
    position: absolute;
    top: 0;
    left: -12px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: #fff;
    border: 3px solid;
    text-align: center;
    line-height: 18px;
    color: #fff;
    font-size: 10px;
}
.timeline-item-danger .timeline-point { border-color: #e74a3b; background-color: #e74a3b; } /* Rojo para Mantenimiento */
.timeline-item-info .timeline-point { border-color: #36b9cc; background-color: #36b9cc; } /* Azul para Aforo */
.timeline-content {
    padding: 0 0 0 15px;
    border-radius: .35rem;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Lógica AJAX y SweetAlert2 para cambiar estatus (replicado del index)
    $(document).ready(function() {
        $('.cambiar-estatus-btn').on('click', function(e) {
            e.preventDefault();
            var activoId = $(this).data('id');
            var nuevoEstatus = $(this).data('estatus');
            var url = '{{ route("produccion.pozos.activos.cambiarEstatus", $activo) }}';
            var token = '{{ csrf_token() }}';

            Swal.fire({
                title: '¿Confirmar cambio?',
                text: "El estatus del activo cambiará a " + nuevoEstatus + ".",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar',
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
                                        location.reload(); 
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
    });
</script>
@endsection