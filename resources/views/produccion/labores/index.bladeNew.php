
@extends('layouts.app')
@section('title-page', 'Historial de Labores Agrícolas')

@section('styles')
<style>
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
@endsection

@section('content')
<div class="container-fluid pb-4">

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

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-agro mb-0" id="tablaLabores">
                    <thead>
                        <tr>
                            <th class="pl-4">ID</th>
                            <th>Fecha</th>
                            <th>Labor</th>
                            <th>Ejecución</th>
                            <th>Recurso</th>
                            <th>Tablones / Has</th>
                            <th class="text-center pr-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($labores as $labor)
                        <tr>
                            <td class="pl-4 font-weight-bold">#{{ str_pad($labor->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $labor->fecha_ejecucion->format('d/m/Y') }}</td>
                            <td>
                                <span class="font-weight-bold text-dark">{{ $labor->labor_catalogo->nombre ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if($labor->tipo_ejecutor == 'Propio')
                                    <span class="badge badge-propio px-2 py-1"><i class="fas fa-home mr-1"></i> In-House</span>
                                @else
                                    <span class="badge badge-outsourcing px-2 py-1"><i class="fas fa-handshake mr-1"></i> Contratista</span><br>
                                    <small class="text-muted">{{ Str::limit($labor->contratista->nombre ?? $labor->contratista_nombre, 15) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($labor->maquinarias->count() > 0)
                                    <i class="fas fa-tractor text-primary"></i> {{ $labor->maquinaria_detalles->count() }} Equipo(s)
                                @else
                                    <i class="fas fa-hands-helping text-success"></i> Manual
                                @endif
                            </td>
                            <td>
                                <strong>{{ $labor->tablones->count() }}</strong> Tablon(es)<br>
                                <small class="text-muted">{{ number_format($labor->tablones->sum('pivot.hectareas_logradas'), 2) }} Ha Totales</small>
                            </td>
                            <td class="text-center pr-4">
                                <a href="{{ route('produccion.labores.show', $labor->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Ver Detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25"></i><br>
                                No hay jornadas de labores registradas aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-3 d-flex justify-content-center">
        Linkss
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
            <form action="#" method="GET">
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
<script>
    $(document).ready(function() {
        // Inicializar DataTables para la vista Index
        $('#tablaLabores').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json" },
            "pageLength": 25,
            "ordering": false, // Desactivamos el orden nativo si ya lo traemos ordenado de DB
            "dom": '<"p-3 d-flex justify-content-between align-items-center"f>rtip' // Buscador bonito
        });

        // Inicializar Select2 DENTRO del Modal (El truco es el dropdownParent)
        $('.select2-modal').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#modalFiltrosExport')
        });
    });
</script>
@endpush