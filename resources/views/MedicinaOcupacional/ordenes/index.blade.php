@extends('layouts.app')

@section('styles')
<style>
    /* Estilos personalizados para las Cards */
    .card-indicator {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card-indicator:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15)!important;
    }
    .icon-bg {
        opacity: 0.15;
        font-size: 3rem;
        position: absolute;
        right: 15px;
        top: 15px;
        transform: rotate(-10deg);
    }
    
    /* Estilo para la lista de exámenes dentro de la tabla */
    .exam-pill {
        font-size: 0.75rem;
        padding: 0.2em 0.5em;
        margin-right: 2px;
        margin-bottom: 2px;
        border: 1px solid #e3e6f0;
        border-radius: 4px;
        display: inline-block;
        background: #f8f9fc;
        color: #5a5c69;
    }

    /* Columnas fijas para acciones */
    .table-actions {
        width: 140px;
        text-align: center;
    }
</style>
@endsection

@section('content')

{{-- 1. HEADER DASHBOARD (4 CARDS) --}}
<div class="row mb-4">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 card-indicator">
            <div class="card-body position-relative overflow-hidden">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Órdenes Hoy</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['hoy'] }}</div>
                    </div>
                </div>
                <i class="fas fa-calendar-day icon-bg text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 card-indicator">
            <div class="card-body position-relative overflow-hidden">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendientes / En Proceso</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['pendientes'] }}</div>
                    </div>
                </div>
                <i class="fas fa-hourglass-half icon-bg text-warning"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 card-indicator">
            <div class="card-body position-relative overflow-hidden">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Resultados Listos</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['completadas'] }}</div>
                    </div>
                </div>
                <i class="fas fa-check-circle icon-bg text-success"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 card-indicator">
            <div class="card-body position-relative overflow-hidden">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Histórico</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                    </div>
                </div>
                <i class="fas fa-database icon-bg text-info"></i>
            </div>
        </div>
    </div>
</div>

{{-- 2. TABLA DE GESTIÓN --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list-alt mr-2"></i>Listado de Órdenes Generadas</h6>
        <a href="{{ route('medicina.pacientes.index') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Nueva Consulta/Orden
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTableOrdenes" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">Fecha</th>
                        <th width="20%">Paciente</th>
                        <th width="35%">Exámenes Solicitados</th>
                        <th width="10%">Estado</th>
                        <th class="table-actions">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordenes as $orden)
                    <tr>
                        <td class="align-middle font-weight-bold text-center">{{ $orden->id }}</td>
                        <td class="align-middle">
                            <span class="d-block font-weight-bold text-gray-800">{{ $orden->created_at->format('d/m/Y') }}</span>
                            <small class="text-muted">{{ $orden->created_at->format('h:i A') }}</small>
                        </td>
                        <td class="align-middle">
                            <div class="font-weight-bold text-primary">
                                {{ Str::limit($orden->paciente->nombre_completo, 25) }}
                            </div>
                            <small class="text-muted">CI: {{ number_format($orden->paciente->ci, 0, ',', '.') }}</small>
                        </td>
                        <td class="align-middle">
                            @if(is_array($orden->examenes) || is_object($orden->examenes))
                                @foreach($orden->examenes as $examen)
                                    @if($loop->iteration <= 3)
                                        <span class="exam-pill">{{ $examen }}</span>
                                    @endif
                                @endforeach
                                @if(count($orden->examenes) > 3)
                                    <span class="exam-pill bg-gray-200 text-gray-600 font-weight-bold" 
                                          data-toggle="tooltip" 
                                          title="{{ implode(', ', array_slice($orden->examenes, 3)) }}">
                                        +{{ count($orden->examenes) - 3 }} más...
                                    </span>
                                @endif
                            @else
                                <span class="text-danger small">Error de formato</span>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            @if($orden->status_orden == 'Pendiente')
                                <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Pendiente</span>
                            @elseif($orden->status_orden == 'Completada')
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Lista</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1">{{ $orden->status_orden }}</span>
                            @endif
                        </td>
                        <td class="align-middle table-actions">
                            <div class="btn-group" role="group">
                                {{-- Botón Ver/Imprimir PDF --}}
                                <a href="{{ route('medicina.ordenes.pdf', $orden->id) }}" target="_blank" class="btn btn-info btn-sm" title="Imprimir Orden">
                                    <i class="fas fa-print"></i>
                                </a>

                                 <a href="{{ route('medicina.ordenes.edit', $orden->id) }}" class="btn btn-info btn-sm" title="Cargar Resultados">
                                    <i class="fas fa-file-medical-alt"></i>
                                </a>
                                
                                {{-- Botón Completar (Solo si está pendiente) --}}
                                @if($orden->status_orden == 'Pendiente')
                                <form action="{{ route('medicina.ordenes.completar', $orden->id) }}" method="POST" class="d-inline form-completar">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm" title="Marcar como Completada">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Botón Borrar (Solo Admins o el creador) --}}
                                <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $orden->id }}" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTableOrdenes').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            order: [[ 0, "desc" ]] // Ordenar por ID descendente
        });

        // Tooltips de Bootstrap para ver los exámenes ocultos
        $('[data-toggle="tooltip"]').tooltip();
        
        // SweetAlert para confirmaciones (opcional)
        $('.form-completar').on('submit', function(e){
            e.preventDefault();
            Swal.fire({
                title: '¿Marcar como completada?',
                text: "Esto indicará que los resultados ya están listos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1cc88a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, completar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    });
</script>
@endsection