@extends('layouts.app')

@section('title', 'Gestión de Flota y Activos')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Control de Activos</h1>
            <p class="text-muted small">Listado maestro de maquinaria, vehículos y equipos.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('activos.create') }}" class="btn btn-primary shadow-sm px-4">
                <i class="fas fa-plus-circle mr-2"></i> Registrar Nuevo Activo
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Flota</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }} Unidades</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-truck-monster fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Disponibles (Operativos)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['operativos'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En Taller / Fuera de Servicio</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['taller'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-tools fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabla-activos">
                    <thead class="bg-light text-primary small font-weight-bold text-uppercase">
                        <tr>
                            <th class="pl-4">Activo</th>
                            <th>Tipo / Marca</th>
                            <th>Ubicación / Depto</th>
                            <th>Estado</th>
                            <th>Lectura Actual</th>
                            <th>Actividad</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activos as $a)
                        <tr>
                            <td class="pl-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle mr-3 shadow-sm border" style="width: 45px; height: 45px; background: url('{{ $a->imagen ? asset('storage/'.$a->imagen) : asset('img/default-tractor.jpg') }}') center/cover"></div>
                                    <div>
                                        <div class="font-weight-bold text-dark">{{ $a->codigo }}</div>
                                        <div class="text-muted small">{{ $a->nombre }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $a->tipo }}</div>
                                <div class="text-muted small">{{ $a->marca }} {{ $a->modelo }}</div>
                            </td>
                            <td>
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                
                                <span class=" text-success small font-weight-bold">{{ $a->departamento_asignado }}</span>
                            </td>
                            <td>
                                @php
                                    $color = match($a->estado_operativo) {
                                        'Operativo' => 'success',
                                        'En Mantenimiento' => 'warning',
                                        'Fuera de Servicio', 'Desincorporado' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge badge-{{ $color }} px-2 py-1 rounded-pill small">
                                    {{ $a->estado_operativo }}
                                </span>
                            </td>
                            <td>
                                <div class="h6 mb-0 font-weight-bold">{{ number_format($a->lectura_actual, 0) }}</div>
                                <small class="text-muted text-uppercase">{{ $a->unidad_medida }}</small>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="small text-muted mb-1" title="Labores registradas">
                                        <i class="fas fa-tractor fa-fw text-primary"></i> {{ $a->labores_detalle_count }} lab.
                                    </span>
                                    <span class="small text-muted" title="Mantenimientos pendientes">
                                        <i class="fas fa-tools fa-fw text-warning"></i> {{ $a->programaciones_m_p_count }} mant.
                                    </span>
                                </div>
                            </td>
                            <td class="text-center pr-4">
                                <div class="btn-group">
                                    <a href="{{ route('activos.show', $a->id) }}" class="btn btn-outline-primary btn-sm" title="Ver Expediente">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('lecturas.show', $a->id) }}" class="btn btn-outline-info btn-sm" title="Historial Horómetros">
                                        <i class="fas fa-history"></i>
                                    </a>
                                    <a href="{{ route('activos.edit', $a->id) }}" class="btn btn-outline-secondary btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @can('eliminar_activos')
                                        @if ($a->estado_operativo != 'Desincorporado')
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#desincorporarModal{{ $a->id }}" title="Desincorporar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                        {{-- Se asume que el modal está incluido en la vista parcial si se necesita la desincorporación --}}
                                        {{-- @include('taller.activos.partials.desincorporar_modal', ['activo' => $activo]) --}}
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
</div>



@push('scripts')
<script>
    $(document).ready(function() {
        $('#tabla-activos').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" },
            "pageLength": 10,
            "order": [[ 0, "asc" ]],
            "responsive": true
        });
    });
</script>
@endpush

    {{-- Incluir modals de desincorporación si el permiso existe --}}
    @can('eliminar_activos')
        @foreach (($activos ?? []) as $activo)
            @if ($activo->estado_operativo != 'Desincorporado')
                @include('taller.activos.partials.desincorporar_modal', ['activo' => $activo])
            @endif
        @endforeach
    @endcan

@endsection