@extends('layouts.app') 

@section('title', 'Historial de Lecturas de Activos')

@section('content')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Historial de Lecturas (KM / HRS)</h6>
            
            @can('crear_lecturas')
                <a href="{{ route('lecturas.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Registrar Nueva Lectura
                </a>
            @endcan
        </div>
        <div class="card-body">
            
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Activo</th>
                            <th>Unidad</th>
                            <th>Valor Lectura</th>
                            <th>Fecha Registro</th>
                            <th>Registrador</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lecturas as $lectura)
                            @php
                                // Determinar si esta es la última lectura para permitir EDICIÓN/ELIMINACIÓN
                                $isLastReading = (
                                    $lectura->id == \App\Models\Logistica\Taller\LecturaActivo::where('activo_id', $lectura->activo_id)
                                                                                            ->latest('fecha_lectura')
                                                                                            ->latest('id')
                                                                                            ->value('id')
                                );
                            @endphp
                            <tr class="{{ $isLastReading ? 'table-info' : '' }}">
                                <td>{{ $lectura->activo->codigo ?? 'N/A' }}</td>
                                <td>{{ $lectura->unidad_medida }}</td>
                                <td>**{{ number_format($lectura->valor_lectura, 0, ',', '.') }}**</td>
                                <td>{{ \Carbon\Carbon::parse($lectura->fecha_lectura)->format('d/m/Y') }}</td>
                                <td>{{ $lectura->registrador->name." ".$lectura->registrador->last_name ?? 'Sistema' }}</td>
                                <td>
                                    @can('ver_lecturas')
                                        {{-- Botón VER --}}
                                        <a href="{{ route('activos.lecturas.historial', $lectura->activo->id) }}" class="btn btn-info btn-circle btn-sm" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                                    @endcan   

                                        {{-- Botón EDITAR (Solo si es la última lectura) --}}
                                    @can('editar_lecturas')
                                        @if ($isLastReading)
                                            <a href="{{ route('lecturas.edit', $lectura->id) }}" class="btn btn-warning btn-circle btn-sm" title="Editar Última Lectura"><i class="fas fa-edit"></i></a>
                                        @else
                                            <button class="btn btn-secondary btn-circle btn-sm disabled" title="Solo se edita la última"><i class="fas fa-edit"></i></button>
                                        @endif
                                    @endcan
                                        {{-- Botón ELIMINAR (Solo si es la última lectura) --}}
                                    @can('eliminar_lecturas')
                                        @if ($isLastReading)
                                            <button type="button" class="btn btn-danger btn-circle btn-sm" data-toggle="modal" data-target="#deleteModal{{ $lectura->id }}" title="Eliminar Última Lectura"><i class="fas fa-trash"></i></button>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                            
                            {{-- Modal de Eliminación (Solo si es la última lectura) --}}
                            @if ($isLastReading)
                            <div class="modal fade" id="deleteModal{{ $lectura->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea eliminar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Confirme la eliminación de la lectura **{{ number_format($lectura->valor_lectura) }} {{ $lectura->unidad_medida }}** del activo **{{ $lectura->activo->codigo }}**. Esta acción revertirá la lectura actual del activo a la anterior.
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('lecturas.destroy', $lectura->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Eliminar Lectura</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay lecturas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $lecturas->links() }}
            </div>
            
        </div>
    </div>
</div>
@endsection