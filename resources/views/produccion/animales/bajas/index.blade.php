@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-history"></i> Historial de Bajas y Eventos de Animales</h1>
        <a href="{{ route('bajas.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
            <i class="fas fa-skull fa-sm text-white-50"></i> Registrar Nueva Baja / Venta
        </a>
    </div>

    {{-- Mensajes de Notificación --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Eventos Registrados (Mortalidad, Venta, Descarte, etc.)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Fecha Evento</th>
                            <th>ID Animal</th>
                            <th>Tipo de Evento</th>
                            <th>Causa/Detalle</th>
                            <th>Notas</th>
                            <th>Registrado</th>
                            {{-- <th>Acciones</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}</td>
                            <td>
                                {{-- Muestra el ID del animal, con un fallback si el animal fue eliminado --}}
                                <strong>{{ optional($event->animal)->iron_id ?? 'N/D (ID: '.$event->animal_id.')' }}</strong>
                            </td>
                            <td>
                                @if ($event->event_type === 'Mortalidad')
                                    <span class="badge badge-danger">{{ $event->event_type }}</span>
                                @elseif ($event->event_type === 'Venta')
                                    <span class="badge badge-warning">{{ $event->event_type }}</span>
                                @else
                                    <span class="badge badge-info">{{ $event->event_type }}</span>
                                @endif
                            </td>
                            <td>{{ $event->cause }}</td>
                            <td>{{ Illuminate\Support\Str::limit($event->notes, 50) }}</td>
                            <td>{{ $event->created_at->format('d/m/Y H:i') }}</td>
                            {{-- <td>
                                <a href="#" class="btn btn-sm btn-light btn-circle"><i class="fas fa-eye"></i></a>
                            </td> --}}
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                No hay eventos de baja registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Paginación --}}
            <div class="d-flex justify-content-center">
                {{ $events->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection