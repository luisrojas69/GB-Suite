@extends('layouts.app')

@push('styles')
<style>
    /* Efectos de Elevación y Glassmorphism */
    .card { border: none; border-radius: 12px; transition: transform 0.2s; }
    .table-hover tbody tr:hover { background-color: rgba(78, 115, 223, 0.03); }
    
    /* Estilo de la tabla */
    .table thead th { 
        background-color: #f8f9fc; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        border-bottom: 2px solid #e3e6f0;
    }

    /* Badges Modernos */
    .badge-soft-primary { background-color: #e0e7ff; color: #4e73df; }
    .badge-soft-success { background-color: #dcfce7; color: #1cc88a; }
    
    /* Grupos de Tablones (Avatar style) */
    .tablon-pill {
        display: inline-block;
        padding: 2px 8px;
        background: #f1f5f9;
        border-radius: 4px;
        font-size: 10px;
        font-weight: bold;
        color: #475569;
        margin: 1px;
        border: 1px solid #e2e8f0;
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(78, 115, 223, 0.1);
        color: #4e73df;
    }
</style>
@endpush

@section('content')
  {{-- Mostrar mensajes de sesión --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Bitácora de Labores</h1>
            <p class="text-muted small mb-0">Seguimiento de tareas críticas y rendimientos.</p>
        </div>
        <div>
            <button class="btn btn-white border shadow-sm btn-sm mr-2">
                <i class="fas fa-download mr-1"></i> Reporte
            </button>
            <a href="{{ route('produccion.labores.create') }}" class="btn btn-primary btn-sm shadow-sm px-3">
                <i class="fas fa-plus-circle mr-1"></i> Nueva Labor
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Rendimiento (Mes)</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ number_format($kpiHectareas, 1) }}</div>
                            <span class="text-muted small">Hectáreas Totales</span>
                        </div>
                        <div class="kpi-icon"><i class="fas fa-chart-area fa-lg"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body p-3">
                    <canvas id="chartSectores" height="65"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-0">
            <form class="row align-items-end">
                <div class="col-md-3">
                    <label class="x-small font-weight-bold text-muted">SECTOR</label>
                    <select name="sector_id" class="form-control form-control-sm border-0 bg-light">
                        <option value="">Todos los Sectores</option>
                        @foreach($sectores as $s)
                            <option value="{{ $s->id }}" {{ request('sector_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="x-small font-weight-bold text-muted">FECHA INICIO</label>
                    <input type="date" name="fecha_inicio" class="form-control form-control-sm border-0 bg-light" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark btn-sm px-4 btn-block shadow-sm">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="text-muted">
                        <th class="px-4 py-3" style="width: 15%">Fecha</th>
                        <th style="width: 25%">Labor / Tipo</th>
                        <th style="width: 25%">Ubicación</th>
                        <th style="width: 15%">Ejecutor</th>
                        <th class="text-center" style="width: 10%">Área</th>
                        <th class="text-right px-4" style="width: 10%">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($registros as $reg)
                    <tr>
                        <td class="px-4">
                            <span class="font-weight-bold text-dark">{{ \Carbon\Carbon::parse($reg->fecha_ejecucion)->format('d M, Y') }}</span>
                            <div class="x-small text-muted">{{ \Carbon\Carbon::parse($reg->fecha_ejecucion)->diffForHumans() }}</div>
                        </td>
                        <td>
                            <div class="font-weight-bold text-gray-800">{{ $reg->labor->nombre }}</div>
                            @if($reg->labor->requiere_maquinaria)
                                <span class="badge badge-soft-primary x-small"><i class="fas fa-tractor mr-1"></i> MECANIZADA</span>
                            @else
                                <span class="badge badge-soft-success x-small"><i class="fas fa-walking mr-1"></i> MANUAL</span>
                            @endif
                        </td>
                        <td>
                            @php
                                // Extraemos los nombres de sectores únicos para este registro
                                // Como 'tablones' es una relación Many-to-Many, cada elemento es un objeto Tablon
                                $sectoresNombres = $reg->tablones->map(function($t) {
                                    return $t->lote->sector->nombre; 
                                })->unique();
                            @endphp
                            
                            <div class="mb-1">
                                <strong class="text-dark">{{ $sectoresNombres->implode(', ') }}</strong>
                            </div>
                            
                            <div class="d-flex flex-wrap">
                                @foreach($reg->tablones->take(5) as $tablon)
                                    {{-- Aquí $tablon ya es el modelo Tablon, accedemos directo a su código --}}
                                    <span class="tablon-pill">{{ $tablon->codigo_completo }}</span>
                                @endforeach
                                
                                @if($reg->tablones->count() > 5)
                                    <span class="tablon-pill text-primary">+{{ $reg->tablones->count() - 5 }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($reg->tipo_ejecutor == 'Propio')
                                <div class="d-flex align-items-center">
                                    <div class="bg-success rounded-circle mr-2" style="width: 8px; height: 8px;"></div>
                                    <span>Personal Propio</span>
                                </div>
                            @else
                                <div class="d-flex align-items-center">
                                    <div class="bg-info rounded-circle mr-2" style="width: 8px; height: 8px;"></div>
                                    <span class="text-truncate" style="max-width: 120px;">{{ $reg->contratista_nombre }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- Sumamos hectáreas desde el pivote --}}
                            <h6 class="mb-0 font-weight-bold">{{ number_format($reg->tablones->sum('pivot.hectareas_logradas'), 2) }}</h6>
                            <small class="text-muted">Has</small>
                        </td>
                        <td class="text-right px-4">
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                    <a class="dropdown-item" href="{{ route('produccion.labores.show', $reg->id) }}"><i class="fas fa-eye fa-sm mr-2 text-muted"></i> Ver detalle</a>
                                    <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash fa-sm mr-2"></i> Eliminar</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No se encontraron registros de labores.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $registros->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartSectores').getContext('2d');
    
    // Gradiente para el gráfico
    const gradient = ctx.createLinearGradient(0, 0, 400, 0);
    gradient.addColorStop(0, '#4e73df');
    gradient.addColorStop(1, '#224abe');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($datosGrafico->pluck('sector')) !!},
            datasets: [{
                label: 'Hectáreas',
                data: {!! json_encode($datosGrafico->pluck('total')) !!},
                backgroundColor: gradient,
                borderRadius: 8,
                barThickness: 20
            }]
        },
        options: {
            indexAxis: 'y',
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { 
                    backgroundColor: '#1e293b',
                    padding: 12,
                    displayColors: false 
                }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false } },
                y: { grid: { display: false }, border: { display: false } }
            }
        }
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        title: '¡Labor Registrada Exitosamente!',
        text: "¿Desea agregar mas labores.?",
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#858796',
        confirmButtonText: '<i class="fas fa-print"></i> Crear otra Labor',
        cancelButtonText: 'Cerrar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("{{ route('produccion.labores.create') }}");
        }

    });
</script>
@endif

@endpush