@extends('layouts.app') 
@section('title', 'Gestión de Tablones')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-seedling text-success"></i> Tablones de Siembra</h1>
        @can('crear_sectores')
        <a href="{{ route('produccion.areas.tablones.create') }}" class="btn btn-success btn-icon-split shadow-sm">
            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
            <span class="text">Registrar Tablón</span>
        </a>
        @endcan
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {!! $message !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Inventario de Áreas Cultivadas</h6>
            <div class="text-muted small">Mostrando {{ $tablones->count() }} unidades</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @can('ver_sectores')
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-gray-100 text-secondary">
                        <tr>
                            <th width="5%" class="text-center"><i class="fas fa-map text-secondary"></i></th>
                            <th>Identificación</th>
                            <th>Ubicación (S/L)</th>
                            <th>Superficie</th>
                            <th>Variedad & Edad</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tablones as $tablon)
                        <tr>
                            <td class="align-middle text-center">
                                @if($tablon->geometria)
                                    {{-- Icono verde si tiene coordenadas --}}
                                    <i class="fas fa-check-circle text-success" title="Georreferenciado"></i>
                                @else
                                    {{-- Icono de advertencia si falta el dibujo --}}
                                    <i class="fas fa-exclamation-triangle text-warning" title="Sin geometría - Pendiente por dibujar"></i>
                                @endif
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('produccion.areas.tablones.show', $tablon->id) }}" class="font-weight-bold text-primary text-decoration-none">
                                    {{ $tablon->codigo_completo }}
                                </a>
                                <br><small class="text-muted">{{ $tablon->nombre }}</small>
                            </td>

                            <td class="align-middle">
                                <i class="fas fa-map-marker-alt text-danger fa-sm"></i> {{ $tablon->lote->sector->nombre }}<br>
                                <small class="text-gray-600 font-italic">{{ $tablon->lote->nombre }}</small>
                            </td>

                            <td class="align-middle text-middle">
                                <span class="h6 font-weight-bold text-success">{{ number_format($tablon->hectareas_documento, 2) }}</span>
                                <small class="text-muted">Has</small>
                            </td>

                            <td class="align-middle">
                                <span class="badge badge-light border text-dark mb-1">
                                    <i class="fas fa-dna text-info"></i> {{ $tablon->variedad->nombre ?? 'N/A' }}
                                </span>
                                <br>
                                @if($tablon->fecha_inicio_ciclo)
                                    @php
                                        $inicio = \Carbon\Carbon::parse($tablon->fecha_inicio_ciclo);
                                        $diff = $inicio->diff(now());
                                        // Sumamos años a meses para el formato estándar de caña (ej: 14 meses)
                                        $mesesTotales = ($diff->y * 12) + $diff->m;
                                    @endphp
                                    <small class="text-muted">
                                        <span class="font-weight-bold text-dark"> {{ $mesesTotales }}m {{ $diff->d }}d </span>
                                        | <small>Desde el: {{ $tablon->fecha_inicio_ciclo->format('d/m/Y') }}</small>
                                    </small>
                                @else
                                    <small class="text-warning small italic">Fecha pendiente</small>
                                @endif
                            </td>

                            <td>
                                @php
                                    $statusConfig = [
                                        'Preparacion' => ['color' => 'primary', 'icon' => 'fa-seedling', 'label' => 'Preparación'],
                                        'Crecimiento' => ['color' => 'success', 'icon' => 'fa-leaf', 'label' => 'Crecimiento'],
                                        'Maduro'      => ['color' => 'warning', 'icon' => 'fa-certificate', 'label' => 'Maduro'],
                                        'Cosecha'     => ['color' => 'danger',  'icon' => 'fa-tractor', 'label' => 'En Cosecha'],
                                        'Inactivo'    => ['color' => 'secondary','icon' => 'fa-pause', 'label' => 'Inactivo'],
                                    ];
                                    $config = $statusConfig[$tablon->estado] ?? $statusConfig['Inactivo'];
                                @endphp
                                <span class="badge badge-{{ $config['color'] }} p-2">
                                    <i class="fas {{ $config['icon'] }}"></i> {{ $config['label'] }}
                                </span>
                            </td>

                            <td class="align-middle text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    @can('ver_sectores')
                                    <a href="{{ route('produccion.areas.tablones.show', $tablon->id) }}" class="btn btn-white btn-sm border" title="Ver Detalle">
                                        <i class="fas fa-eye text-info"></i>
                                    </a>
                                    @endcan

                                    @can('editar_sectores')
                                    <a href="{{ route('produccion.areas.tablones.edit', $tablon->id) }}" class="btn btn-white btn-sm border" title="Editar">
                                        <i class="fas fa-edit text-primary"></i>
                                    </a>
                                    @endcan

                                    @can('eliminar_sectores')
                                    <button type="button" class="btn btn-white btn-sm border delete-tablon" 
                                            data-id="{{ $tablon->id }}" 
                                            data-nombre="{{ $tablon->nombre }}" 
                                            title="Eliminar">
                                        <i class="fas fa-trash text-danger"></i>
                                    </button>
                                    <form id="delete-form-{{ $tablon->id }}" action="{{ route('produccion.areas.tablones.destroy', $tablon->id) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-lock fa-3x text-gray-200"></i>
                        <p class="text-muted mt-2">Acceso no autorizado para ver tablones.</p>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@push('scripts')
{{-- Asegúrate de incluir SweetAlert2 en tu layout principal --}}
<script>
    $(document).ready(function() {
        $('.delete-tablon').on('click', function() {
            const tablonId = $(this).data('id');
            const tablonNombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Está seguro?',
                text: `Se eliminará el tablón "${tablonNombre}". ¡Esta acción es irreversible y podría fallar si tiene cosechas registradas!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡Eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#delete-form-' + tablonId).submit();
                }
            });
        });
    });
</script>
@endpush
@endsection