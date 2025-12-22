{{-- resources/views/taller/ordenes/index.blade.php --}}

@extends('layouts.app') 

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Órdenes de Servicio</h6>
            @can('gestionar_ordenes')
                <a href="{{ route('ordenes.create') }}" class="btn btn-success btn-sm"><i class="fas fa-wrench"></i> Nueva Orden (Solicitud)</a>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Activo</th>
                            <th>Tipo</th>
                            <th>Falla Inicial</th>
                            <th>Status</th>
                            <th>Días en Taller</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordenes as $orden)
                            <tr>
                                <td>{{ $orden->codigo_orden }}</td>
                                <td>{{ $orden->activo->codigo }} - {{ $orden->activo->nombre }}</td>
                                <td><span class="badge badge-{{ $orden->tipo_servicio == 'Correctivo' ? 'danger' : 'primary' }}">{{ $orden->tipo_servicio }}</span></td>
                                <td>{{ Str::limit($orden->descripcion_falla, 50) }}</td>
                                <td>
                                    <span class="badge badge-{{ $orden->status == 'Abierta' ? 'warning' : ($orden->status == 'En Proceso' ? 'info' : 'success') }}">
                                        {{ $orden->status }}
                                    </span>
                                </td>
                                <td>{{ $orden->created_at->diffInDays(now()) }} días</td>
                                <td>
                                    <a href="{{ route('ordenes.show', $orden) }}" class="btn btn-info btn-circle btn-sm" title="Detalle y Gestión"><i class="fas fa-folder-open"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $ordenes->links() }}
            </div>
        </div>
    </div>
@endsection