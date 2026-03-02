@extends('layouts.app')
@section('title-page', 'Tabulador Maestro de Fletes')
@section('styles')
<style>
    /* ========================================
       VARIABLES GLOBALES - TEMA AGRO/ECO
    ======================================== */
    :root {
        --agro-dark: #1b4332;      /* Verde Bosque Profundo */
        --agro-primary: #2d6a4f;   /* Verde Esmeralda */
        --agro-light: #d8f3dc;     /* Verde Suave / Pastel */
        --agro-accent: #52b788;    /* Verde Vibrante */
        --agro-earth: #bc6c25;     /* Tono Tierra para contrastes */
    }

    /* ========================================
       HEADER PRINCIPAL
    ======================================== */
    .page-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; 
        padding: 25px 30px; 
        border-radius: 15px;
        margin-bottom: 25px; 
        box-shadow: 0 8px 25px rgba(45, 106, 79, 0.25);
        position: relative; 
        overflow: hidden;
    }
    .page-header-agro::before {
        content: '\f5a0'; /* fa-map-marked-alt */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute; 
        top: -15px; 
        right: 15px;
        font-size: 8rem; 
        color: rgba(255,255,255,0.06);
        transform: rotate(-10deg);
    }

    /* ========================================
       TARJETAS DE ESTADÍSTICAS (KPIs)
    ======================================== */
    .card-stat-agro {
        border: none;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        background: #fff;
    }
    .card-stat-agro:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .border-agro-1 { border-bottom: 4px solid var(--agro-dark); }
    .border-agro-2 { border-bottom: 4px solid var(--agro-accent); }
    .border-agro-3 { border-bottom: 4px solid #f6c23e; } 
    .border-agro-4 { border-bottom: 4px solid var(--agro-earth); } 
    
    .icon-circle-agro {
        width: 50px; height: 50px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }

    /* ========================================
       TABLA Y ESTRUCTURAS
    ======================================== */
    .table-agro thead th {
        background-color: #f8f9fc;
        color: var(--agro-dark);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--agro-light);
    }
    .table-agro tbody tr {
        transition: background-color 0.2s;
    }
    .table-agro tbody tr:hover {
        background-color: rgba(82, 183, 136, 0.05);
    }
    .badge-agro-soft {
        background-color: var(--agro-light);
        color: var(--agro-dark);
        font-weight: 600;
    }
    .btn-agro {
        background-color: var(--agro-primary);
        color: white;
    }
    .btn-agro:hover {
        background-color: var(--agro-dark);
        color: white;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">


    <div class="page-header-agro d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div>
            <h2 class="font-weight-bold mb-1"><i class="fas fa-layer-group mr-2"></i> Tabulador de Fletes por Sector</h2>
            <p class="mb-0 text-white-50" style="font-size: 1.1rem;">
                Administración de Tarifas de Transporte por Sector (TABULADOR).
            </p>
        </div>
        @can('produccion.areas.crear')
        <div class="mt-3 mt-md-0 btn-group shadow-sm">
            <a href="{{ route('produccion.areas.sectores.create') }}" class="btn btn-light text-success font-weight-bold shadow-sm rounded-pill px-4">
                <i class="fas fa-plus-circle mr-1"></i> Crear Sector
            </a>
        </div>
        @endcan
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show border-left-success shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-2"></i> <strong>¡Éxito!</strong> {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-money-check-alt mr-2"></i> Tarifas de Transporte por Sector</h5>
            <span class="badge badge-info">Moneda: USD / Tonelada</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 pl-4">Sector</th>
                            <th class="border-0">Estructura</th>
                            <th class="border-0">Superficie (HAS)</th>
                            <th class="border-0 text-center">Tarifa Actual ($)</th>
                            <th class="border-0 text-right pr-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sectores as $sector)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="badge badge-success p-2 rounded">{{ $sector->codigo_sector }}</div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold text-gray-800">{{ $sector->nombre }}</h6>
                                        <small class="text-muted d-block text-truncate" style="max-width: 200px;">{{ $sector->descripcion ?? 'Sin descripción' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="badge badge-light border text-left mb-1 py-1 px-2"><i class="fas fa-layer-group text-info mr-1"></i> {{ $sector->lotes_count }} Lotes</span>
                                    <span class="badge badge-light border text-left py-1 px-2"><i class="fas fa-th text-secondary mr-1"></i> {{ $sector->tablones_count }} Tablones</span>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark" title="Hectáreas según Geometría Satelital">
                                    <i class="fas fa-satellite text-success mr-1"></i> {{ number_format($sector->hectareas_geometria, 2) }}
                                </div>
                                <div class="small text-muted font-weight-bold mt-1" title="Hectáreas Documentales Totales">
                                    <i class="fas fa-file-alt text-secondary mr-1"></i> {{ number_format($sector->tablones->sum('hectareas_documento'), 2) }} Doc.
                                </div>
                            </td>
                            <td class="text-center font-weight-bold text-success" style="font-size: 1.1rem;">${{ number_format($sector->tarifa_flete, 2) }}
                            </td>
                            <td class="text-right pr-4">
                                <button onclick="editTarifa({{ $sector->id }}, '{{ $sector->nombre }}', {{ $sector->tarifa_flete }})" 
                                        class="btn btn-sm btn-outline-success rounded-pill">
                                    <i class="fas fa-edit mr-1"></i> Ajustar Precio
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTarifa" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title font-weight-bold">Ajustar Tarifa</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formUpdateTarifa">
                @csrf
                <input type="hidden" id="sector_id">
                <div class="modal-body">
                    <p class="small text-muted mb-3">Sector: <strong id="sector_name" class="text-dark"></strong></p>
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Nueva Tarifa ($/Ton)</label>
                        <input type="number" step="0.01" id="input_tarifa" class="form-control form-control-lg text-center font-weight-bold text-success" required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="submit" class="btn btn-primary btn-block shadow-sm">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editTarifa(id, nombre, tarifa) {
        $('#sector_id').val(id);
        $('#sector_name').text(nombre);
        $('#input_tarifa').val(tarifa);
        $('#modalTarifa').modal('show');
    }

    $('#formUpdateTarifa').on('submit', function(e) {
        e.preventDefault();
        const id = $('#sector_id').val();
        const tarifa = $('#input_tarifa').val();

        $.ajax({
            url: `/produccion/arrimes/tabulador/fletes/${id}`,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                tarifa_flete: tarifa
            },
            success: function(res) {
                $('#modalTarifa').modal('hide');
                Swal.fire('¡Actualizado!', res.message, 'success').then(() => location.reload());
            }
        });
    });
</script>
@endpush