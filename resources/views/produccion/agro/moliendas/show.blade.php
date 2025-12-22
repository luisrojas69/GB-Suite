@extends('layouts.app')
@section('title', 'Arrime de Molienda: ' . $molienda->boleto)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📊 Arrime Detallado: Boleto **{{ $molienda->boleto }}**</h1>
        <a href="{{ route('produccion.agro.moliendas.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Histórico
        </a>
    </div>

    @can('ver_moliendas')
        {{-- Fila de Indicadores Clave --}}
        <div class="row">
            
            {{-- Indicador 1: Peso Neto Total --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Peso Neto Arrimado</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($molienda->peso_neto, 2, ',', '.') }} kg</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-truck-moving fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Indicador 2: Rendimiento (%) --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Rendimiento Estimado (TTP)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $molienda->rendimiento ? number_format($molienda->rendimiento, 2, ',', '.') . '%' : 'N/A' }}
                                </div>
                            </div>
                            <div class="col-auto"><i class="fas fa-percent fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Indicador 3: Fecha de Arrime --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Registro de Zafra</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \Carbon\Carbon::parse($molienda->fecha_arrime)->format('d/m/Y H:i A') }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-calendar-alt fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            
            {{-- Columna 1: Información de Origen y Relaciones --}}
            <div class="col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-link"></i> Datos de Origen y Contrato</h6>
                    </div>
                    <div class="card-body">
                        
                        <div class="row">
                            {{-- Bloque 1: Ubicación --}}
                            <div class="col-md-6 border-right">
                                <h5 class="text-primary mb-3"><i class="fas fa-map-marked-alt"></i> Ubicación de Origen</h5>
                                <p class="mb-1"><strong>Zafra:</strong> <span class="badge badge-secondary">{{ $molienda->zafra->nombre }}</span></p>
                                <p class="mb-1"><strong>Tablón:</strong> <span class="badge badge-dark">{{ $molienda->tablon->codigo_completo }}</span></p>
                                <p class="ml-3 small">
                                    {{ $molienda->tablon->nombre }} <br>
                                    <i class="fas fa-layer-group"></i> Lote: {{ $molienda->tablon->lote->nombre }} <br>
                                    <i class="fas fa-globe-americas"></i> Sector: {{ $molienda->tablon->lote->sector->nombre }}
                                </p>
                            </div>

                            {{-- Bloque 2: Logística --}}
                            <div class="col-md-6">
                                <h5 class="text-success mb-3"><i class="fas fa-cogs"></i> Logística</h5>
                                <p class="mb-1"><strong>Contratista:</strong> 
                                    <span class="badge badge-primary p-2">{{ $molienda->contratista->nombre }}</span>
                                </p>
                                <p class="mb-1"><strong>Destino (Central):</strong> 
                                    <span class="badge badge-info p-2">{{ $molienda->destino->nombre }}</span>
                                </p>
                                <p class="mb-1"><strong>Variedad:</strong> 
                                    <span class="badge badge-warning p-2">{{ $molienda->variedad->nombre }}</span>
                                </p>
                            </div>
                        </div>
                        
                        <hr class="sidebar-divider my-4">

                        <h5 class="text-primary mb-3"><i class="fas fa-tools"></i> Acciones</h5>
                        <div class="mt-2">
                            @can('editar_moliendas')
                                <a href="{{ route('produccion.agro.moliendas.edit', $molienda->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar Arrime</a>
                            @endcan
                            @can('eliminar_moliendas')
                                <button type="button" class="btn btn-danger delete-molienda-show" data-id="{{ $molienda->id }}" data-boleto="{{ $molienda->boleto }}"><i class="fas fa-trash"></i> Eliminar Arrime</button>
                            @endcan
                        </div>
                        
                    </div>
                </div>
            </div>
            
            {{-- Columna 2: Datos de Peso y Calidad --}}
            <div class="col-lg-5">
                
                {{-- Card de Pesaje --}}
                <div class="card shadow mb-4 border-left-danger">
                    <div class="card-header py-3 bg-danger text-white">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-balance-scale"></i> Detalle de Pesaje</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Peso Bruto:</strong> <span class="float-right h5 text-dark">{{ number_format($molienda->peso_bruto, 2, ',', '.') }} kg</span></p>
                        <p class="mb-1"><strong>Peso Tara:</strong> <span class="float-right h5 text-dark">{{ number_format($molienda->peso_tara, 2, ',', '.') }} kg</span></p>
                        <hr>
                        <p class="mb-1"><strong>Peso Neto:</strong> <span class="float-right h4 text-info font-weight-bold">{{ number_format($molienda->peso_neto, 2, ',', '.') }} kg</span></p>
                    </div>
                </div>

                {{-- Card de Calidad --}}
                <div class="card shadow mb-4 border-left-warning">
                    <div class="card-header py-3 bg-warning text-white">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-flask"></i> Resultados de Calidad</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 text-center">
                                <h6 class="mb-0 text-dark">{{ $molienda->brix ? number_format($molienda->brix, 2) . '%' : 'N/A' }}</h6>
                                <small class="text-muted">Brix</small>
                            </div>
                            <div class="col-4 text-center border-left border-right">
                                <h6 class="mb-0 text-dark">{{ $molienda->pol ? number_format($molienda->pol, 2) . '%' : 'N/A' }}</h6>
                                <small class="text-muted">Pol</small>
                            </div>
                            <div class="col-4 text-center">
                                <h6 class="mb-0 text-dark">{{ $molienda->rendimiento ? number_format($molienda->rendimiento, 2) . '%' : 'N/A' }}</h6>
                                <small class="text-muted">Rendimiento</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card de Auditoría (Fila Inferior) --}}
        <div class="card shadow mb-4 border-left-secondary">
            <div class="card-body">
                <div class="row small text-muted">
                    <div class="col-md-4">
                        <i class="fas fa-barcode"></i> <strong>ID de Registro:</strong> {{ $molienda->id }}
                    </div>
                    <div class="col-md-4">
                        <i class="fas fa-plus-circle"></i> <strong>Creado el:</strong> {{ $molienda->created_at->format('d/m/Y h:i A') }}
                    </div>
                    <div class="col-md-4">
                        <i class="fas fa-sync-alt"></i> <strong>Última Actualización:</strong> {{ $molienda->updated_at->format('d/m/Y h:i A') }}
                    </div>
                </div>
            </div>
        </div>
        
    @else
        <p class="alert alert-danger">Usted no tiene permisos para ver el detalle de este arrime.</p>
    @endcan
</div>

@push('scripts')
{{-- Script de SweetAlert2 para la eliminación desde el detalle --}}
<script>
    $(document).ready(function() {
        $('.delete-molienda-show').on('click', function() {
            const moliendaId = $(this).data('id');
            const boleto = $(this).data('boleto');
            
            Swal.fire({
                title: '¿Eliminar Arrime?',
                text: `¿Está seguro de eliminar el registro de molienda con Boleto **${boleto}**? Esta acción es irreversible.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡Eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/produccion/agro/moliendas/${moliendaId}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    // Redirigir al index después de eliminar
                                    window.location.href = "{{ route('produccion.agro.moliendas.index') }}"; 
                                });
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Ocurrió un error al intentar eliminar el registro.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
@endsection