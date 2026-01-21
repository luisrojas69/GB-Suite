@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Expediente del Activo: <span class="text-primary">#{{ $item->asset_tag }}</span></h1>
        <a href="{{ route('inventario.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver al Listado
        </a>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-{{ $item->status == 'asignado' ? 'info' : 'primary' }}">
                    <h6 class="m-0 font-weight-bold text-white text-center text-uppercase">Información Actual</h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $item->image_path ? asset('storage/'.$item->image_path) : asset('img/no-image.png') }}" 
                         class="img-fluid rounded mb-3 border shadow-sm" style="max-height: 200px;">
                    
                    <h4 class="font-weight-bold mb-0">{{ $item->name }}</h4>
                    <p class="text-muted mb-3">{{ $item->brand }} - {{ $item->model }}</p>
                    
                    @php
                        $statusDisplay = [
                            'mantenimiento' => ['Dañado', 'warning'],
                            'disponible'    => ['Disponible', 'success'],
                            'asignado'      => ['Asignado', 'info'],
                            'desincorporado'=> ['Desincorporado', 'danger']
                        ][$item->status] ?? [$item->status, 'secondary'];
                    @endphp

                    <span class="badge badge-{{ $statusDisplay[1] }} px-3 py-2 mb-4">
                        {{ strtoupper($statusDisplay[0]) }}
                    </span>

                    <hr>

                    <div class="text-left mt-3">
                        <p><strong><i class="fas fa-barcode mr-2 text-gray-500"></i>Serial:</strong> {{ $item->serial ?? 'N/A' }}</p>
                        <p><strong><i class="fas fa-tags mr-2 text-gray-500"></i>Categoría:</strong> {{ $item->category->nombre ?? 'Sin categoría' }}</p>
                        <p><strong><i class="fas fa-folder mr-2 text-gray-500"></i>Grupo:</strong> {{ $item->item_group }}</p>
                    </div>
                </div>
            </div>

            @if($item->currentAssignment)
            <div class="card border-left-info shadow mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Responsable Actual</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $item->currentAssignment->assignable->nombre_completo ?? 'N/D' }}
                            </div>
                            <div class="mt-2 text-muted small">
                                <i class="fas fa-map-marker-alt"></i> {{ $item->currentAssignment->location->nombre ?? 'Sin ubicación' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history mr-2"></i>Historial de Asignaciones</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Retorno</th>
                                    <th>Responsable</th>
                                    <th>Ubicación</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($item->assignments->sortByDesc('assigned_at') as $assignment)
                                <tr>
                                    <td>{{ $assignment->assigned_at->format('d/m/Y') }}</td>
                                    <td>
                                        {!! $assignment->returned_at 
                                            ? $assignment->returned_at->format('d/m/Y') 
                                            : '<span class="badge badge-pill badge-primary">Activo</span>' !!}
                                    </td>
                                    <td>
                                        <small class="font-weight-bold text-dark">
                                            {{ str_contains($assignment->assignable_type, 'Paciente') ? '👤' : '🏢' }}
                                            {{ $assignment->assignable->nombre_completo ?? 'N/D' }}
                                        </small>
                                    </td>
                                    <td>{{ $assignment->location->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <i class="fas fa-circle text-{{ $assignment->returned_at ? 'secondary' : 'success' }} fa-sm"></i>
                                    </td>
                                    <td>
                                        @if($assignment->returned_at)
                                            {{ $assignment->returned_at->format('d/m/Y') }}
                                            @if($assignment->return_notes)
                                                <i class="fas fa-info-circle text-info ml-1" 
                                                   data-toggle="tooltip"
                                                   data-placement="left" 
                                                   title="Nota: {{ $assignment->return_notes }}"></i>
                                            @endif
                                        @else
                                            <span class="badge badge-pill badge-primary">Activo</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Este activo no tiene historial de movimientos.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection