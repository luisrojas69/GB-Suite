@extends('layouts.app')
@section('title-page', 'Historial de Labores Agrícolas')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
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

        :root {
        --agro-dark: #1b4332;
        --agro-primary: #2d6a4f;
        --agro-accent: #52b788;
    }

    .page-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; 
        padding: 20px 25px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(27, 67, 50, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .badge-propio { background-color: #e3f2fd; color: #0d47a1; border: 1px solid #bbdefb; }
    .badge-outsourcing { background-color: #fff8e1; color: #f57f17; border: 1px solid #ffecb3; }
    
    .table-agro th {
        background-color: #f8f9fc;
        color: var(--agro-dark);
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--agro-primary) !important;
    }
    
    .table-agro td { vertical-align: middle; font-size: 0.85rem; color: #4a5568; }
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
    <div class="page-header-agro">
        <div>
            <h3 class="font-weight-bold mb-1"><i class="fas fa-tractor mr-2"></i> Registro de Labores</h3>
            <p class="mb-0 opacity-75 small">Consulta y exportación del historial de trabajos en campo.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-light text-success font-weight-bold shadow-sm mr-2" data-toggle="modal" data-target="#modalFiltrosExport">
                <i class="fas fa-file-excel mr-1"></i> Exportar
            </button>
            <a href="{{ route('produccion.labores.create') }}" class="btn btn-success shadow-sm border-white">
                <i class="fas fa-plus-circle mr-1"></i> Nueva Jornada
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
                        <th class="pl-4">ID</th>
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
                        <td class="pl-4 font-weight-bold">#{{ str_pad($reg->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4">
                            <span class="font-weight-bold text-dark">{{ \Carbon\Carbon::parse($reg->fecha_ejecucion)->format('d M, Y') }}</span>
                            <div class="x-small text-muted">{{ \Carbon\Carbon::parse($reg->fecha_ejecucion)->diffForHumans() }}</div>
                        </td>
                        <td>
                            <div class="font-weight-bold text-gray-800">{{ $reg->labor->nombre }}</div>
                            @if($reg->maquinarias->count() > 0)
                                <span class="badge badge-soft-primary x-small"><i class="fas fa-tractor mr-1"></i> MECANIZADA</span>
                                <span class="badge badge-info x-small"><i class="fas fa-tractor mr-1"></i> {{ $reg->maquinarias->count() }} Equipo(s)</span>
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
                                 <span class="badge badge-outsourcing px-2 py-1"><i class="fas fa-handshake mr-1"></i> Contratista</span><br>
                                <small class="text-muted">{{ Str::limit($reg->contratista->nombre ?? $reg->contratista_nombre, 15) }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- Sumamos hectáreas desde el pivote --}}
                            <strong>{{ $reg->tablones->count() }}</strong> <small>Tablon(es)</small><br>
                            <strong>{{ number_format($reg->tablones->sum('pivot.hectareas_logradas'), 2) }}</strong>
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
                        <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25"></i><br>
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

<div class="modal fade" id="modalFiltrosExport" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-filter mr-2"></i> Filtros de Exportación</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('produccion.labores.export.excel') }}" method="GET">
                <div class="modal-body p-4 bg-light">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold text-dark">Desde</label>
                            <input type="date" name="fecha_desde" class="form-control" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold text-dark">Hasta</label>
                            <input type="date" name="fecha_hasta" class="form-control" value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold text-dark">Tipo de Labor</label>
                        <select name="labor_id" class="form-control select2-modal w-100">
                            <option value="">-- Todas las Labores --</option>
                            @foreach($catLabores as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="small font-weight-bold text-dark">Sector / Hacienda</label>
                        <select name="sector_id" class="form-control select2-modal w-100">
                            <option value="">-- Todos los Sectores --</option>
                            @foreach($sectores as $sector)
                                <option value="{{ $sector->id }}">{{ $sector->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success shadow-sm"><i class="fas fa-download mr-1"></i> Generar Excel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
<script>
    $(document).ready(function() {
        // Inicializar DataTables para la vista Index
        $('#tablaLabores').DataTable({
            "language": { "url": "/js/lang/Spanish.json" },
            "pageLength": 25,
            "ordering": false, // Desactivamos el orden nativo si ya lo traemos ordenado de DB
            "dom": '<"p-3 d-flex justify-content-between align-items-center"f>rtip' // Buscador bonito
        });

        // Inicializar Select2 DENTRO del Modal (El truco es el dropdownParent)
        $('.select2-modal').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modalFiltrosExport')
        });
    });
</script>
@endpush