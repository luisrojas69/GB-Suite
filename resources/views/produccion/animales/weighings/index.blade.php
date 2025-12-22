@extends('layouts.app') 

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-balance-scale"></i> Historial de Pesajes Registrados</h1>
        <a href="{{ route('weighings.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Registrar Nuevo Pesaje
        </a>
    </div>

    {{-- Mensajes de Notificación --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detalle de Pesajes (Ordenados por fecha más reciente)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Fecha Pesaje</th>
                            <th>ID Animal</th>
                            <th>Peso (kg)</th>
                            <th>Notas</th>
                            <th>Registrado en Sistema</th>
                            {{-- <th>Acciones</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($weighings as $weighing)
                        <tr>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($weighing->weighing_date)->format('d/m/Y') }}</strong>
                            </td>
                            <td>
                                {{-- Usa la relación 'animal' para obtener el ID de hierro/tatuaje --}}
                                {{ optional($weighing->animal)->iron_id ?? 'Animal Eliminado' }}
                            </td>
                            <td>
                                <span class="badge badge-success" style="font-size: 1.1em;">
                                    {{ number_format($weighing->weight, 2, ',', '.') }} kg
                                </span>
                            </td>
                            <td>{{ $weighing->notes ?? 'N/A' }}</td>
                            <td>{{ $weighing->created_at->format('d/m/Y H:i') }}</td>
                            {{-- <td>
                                <a href="#" class="btn btn-sm btn-info btn-circle" title="Editar Pesaje"><i class="fas fa-edit"></i></a>
                            </td> --}}
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <i class="fas fa-exclamation-triangle text-warning"></i> No hay registros de pesajes disponibles.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Paginación --}}
            <div class="d-flex justify-content-center">
                {{ $weighings->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection