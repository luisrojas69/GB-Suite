@extends('layouts.app')

@section('title', 'Gestión de Activos')

@section('content')

    <div class="container-fluid">
        {{-- Mostrar mensajes de sesión --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Activos (Maquinaria y Vehículos)</h6>
                @can('crear_activos')
                    <a href="{{ route('activos.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Activo
                    </a>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Lectura Actual</th>
                                <th>Departamento</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- USAMOS @forelse PARA MANEJAR EL CASO VACÍO --}}
                            @forelse ($activos as $activo)
                                <tr class="{{ $activo->estado_operativo == 'Desincorporado' ? 'table-secondary' : '' }}">
                                    <td>{{ $activo->codigo }}</td>
                                    <td>{{ $activo->nombre }}</td>
                                    <td>{{ $activo->tipo }}</td>
                                    <td>{{ number_format($activo->lectura_actual, 0) }} {{ $activo->unidad_medida }}</td>
                                    <td>{{ $activo->departamento_asignado }}</td>
                                    <td>
                                        @php
                                            $badge_class = 'secondary';
                                            if ($activo->estado_operativo == 'Operativo') {
                                                $badge_class = 'success';
                                            } elseif ($activo->estado_operativo == 'En Mantenimiento') {
                                                $badge_class = 'warning';
                                            } elseif ($activo->estado_operativo == 'Fuera de Servicio' || $activo->estado_operativo == 'Desincorporado') {
                                                $badge_class = 'danger';
                                            }
                                        @endphp
                                        <span class="badge badge-{{ $badge_class }}">
                                            {{ $activo->estado_operativo }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- Botón VER/SHOW --}}
                                        @can('ver_activos')
                                            <a href="{{ route('activos.show', $activo) }}" class="btn btn-success btn-circle btn-sm" title="Ver Detalles"><i class="fas fa-eye"></i></a>
                                        @endcan

                                        @can('registrar_lecturas')

                                            <a href="{{ route('activos.lecturas.historial', $activo) }}" class="btn btn-info btn-circle btn-sm" title="Ver Historial de Lecturas"><i class="fas fa-book"></i></a>
                                        @endcan

                                        {{-- Botón EDITAR --}}
                                        @can('editar_activos')
                                            <a href="{{ route('activos.edit', $activo) }}" class="btn btn-warning btn-circle btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        
                                        {{-- Botón DESINCORPORAR --}}
                                        @can('eliminar_activos')
                                            @if ($activo->estado_operativo != 'Desincorporado')
                                                <button type="button" class="btn btn-danger btn-circle btn-sm" data-toggle="modal" data-target="#desincorporarModal{{ $activo->id }}" title="Desincorporar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                            {{-- Se asume que el modal está incluido en la vista parcial si se necesita la desincorporación --}}
                                            {{-- @include('taller.activos.partials.desincorporar_modal', ['activo' => $activo]) --}}
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No se encontraron activos registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Paginación --}}
                <div class="d-flex justify-content-center">
                    @if (isset($activos) && method_exists($activos, 'links'))
                        {{ $activos->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {{-- Incluir modals de desincorporación si el permiso existe --}}
    @can('eliminar_activos')
        @foreach (($activos ?? []) as $activo)
            @if ($activo->estado_operativo != 'Desincorporado')
                @include('taller.activos.partials.desincorporar_modal', ['activo' => $activo])
            @endif
        @endforeach
    @endcan

@endsection