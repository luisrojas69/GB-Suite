@extends('layouts.app')
@section('title-page', 'Validación de Importación')

@section('styles')
<style>
    :root {
        --agro-dark: #1b4332;
        --agro-primary: #2d6a4f;
        --agro-accent: #52b788;
    }
    
    /* Estilo para las filas segun estado */
    .row-rojo { border-left: 5px solid #e74a3b !important; background-color: rgba(231, 74, 59, 0.03); }
    .row-amarillo { border-left: 5px solid #f6c23e !important; background-color: rgba(246, 194, 62, 0.03); }
    .row-verde { border-left: 5px solid #1cc88a !important; }

    .table thead th {
        background-color: var(--agro-dark);
        color: white;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        border: none;
    }

    .badge-status {
        width: 12px; height: 12px; border-radius: 50%; display: inline-block;
        box-shadow: 0 0 5px rgba(0,0,0,0.2);
    }

    /* Animación para el botón de procesar */
    .btn-loader { position: relative; display: flex; align-items: center; gap: 8px; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-body bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="font-weight-bold text-primary mb-1">
                        <i class="fas fa-clipboard-check mr-2"></i>Validación de Boletos
                    </h4>
                    <p class="text-muted mb-0">Revisa y corrige los datos antes de la integración definitiva.</p>
                </div>
                <div class="col-auto">
                    <button type="button" onclick="confirmarProcesamiento()" class="btn btn-success btn-icon-split shadow">
                        <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                        <span class="text font-weight-bold">Finalizar y Guardar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        @php
            $counts = collect($purgatorio)->countBy('status_color');
        @endphp
        <div class="col-md-4">
            <div class="card border-left-success shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Listos (Verde)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['verde'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Actualizaciones (Amarillo)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['amarillo'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-danger shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Por Corregir (Rojo)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="count-errores">{{ $counts['rojo'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-5">
        <div class="card-body p-0">
            <form id="form-purgatorio" action="{{ route('produccion.arrimes.process') }}" method="POST">
                @csrf
                <input type="hidden" name="zafra_id" value="{{ $zafraActiva->id }}">
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="dataTablePurgatorio">
                        <thead>
                            <tr>
                                <th class="text-center">Estado</th>
                                <th>Boleto / Remesa</th>
                                <th>Sector / Tablón (CSV)</th>
                                <th width="300">Asignación GB-SUITE</th>
                                <th>Data</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purgatorio as $index => $item)
                            <tr class="row-{{ $item['status_color'] }}">
                                <td class="text-center">
                                    <span class="badge-status bg-{{ $item['status_color'] == 'verde' ? 'success' : ($item['status_color'] == 'amarillo' ? 'warning' : 'danger') }}"></span>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-dark">{{ $item['boleto'] }}</span><br>
                                    <small class="text-muted">Remesa: {{ $item['remesa'] }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ $item['codigo_sector_limpio'] }}</span>
                                    <i class="fas fa-arrow-right mx-1 text-muted small"></i>
                                    <span class="badge badge-light border">{{ $item['tablon_csv'] }}</span>
                                </td>
                                <td>
                                    @if($item['status_color'] == 'rojo')
                                        <select name="correccion_tablon[{{ $index }}]" class="form-control form-control-sm select2-agro" required>
                                            <option value="">⚠️ Seleccionar Tablón Correcto</option>
                                            @foreach($todosLosTablones as $t)
                                                <option value="{{ $t->id }}">{{ $t->codigo_completo }} - {{ $t->nombre }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-link text-success mr-2"></i>
                                            <span class="text-dark small font-weight-bold">{{ $item['tablon_nombre_completo'] }}</span>
                                            <input type="hidden" name="tablon_id[{{ $index }}]" value="{{ $item['tablon_id'] }}">
                                        </div>
                                    @endif

                                    <input type="hidden" name="data[{{ $index }}][boleto]" value="{{ $item['boleto'] }}">
                                    <input type="hidden" name="data[{{ $index }}][remesa]" value="{{ $item['remesa'] }}">
                                    <input type="hidden" name="data[{{ $index }}][toneladas_netas]" value="{{ $item['toneladas_netas'] }}">
                                    <input type="hidden" name="data[{{ $index }}][rendimiento_real]" value="{{ $item['rendimiento_real'] }}">
                                    <input type="hidden" name="data[{{ $index }}][status_color]" value="{{ $item['status_color'] }}">
                                    <input type="hidden" name="data[{{ $index }}][cod_hacienda_original]" value="{{ $item['cod_hacienda_original'] }}">
                                    <input type="hidden" name="data[{{ $index }}][tablon_csv]" value="{{ $item['tablon_csv'] }}">
                                    <input type="hidden" name="data[{{ $index }}][dia_zafra]" value="{{ $item['dia_zafra'] }}">
                                    <input type="hidden" name="data[{{ $index }}][tablon_id]" value="{{ $item['tablon_id'] }}">
                                    <input type="hidden" name="data[{{ $index }}][tablon_nombre_completo]" value="{{ $item['tablon_nombre_completo'] }}">
                                    <input type="hidden" name="data[{{ $index }}][activo_jaiba_id]" value="{{ $item['activo_jaiba_id'] }}">
                                    <input type="hidden" name="data[{{ $index }}][fecha_quema]" value="{{ $item['fecha_quema'] }}">
                                    <input type="hidden" name="data[{{ $index }}][fecha_arrime]" value="{{ $item['fecha_arrime'] }}">
                                    <input type="hidden" name="data[{{ $index }}][trash_porcentaje]" value="{{ $item['trash_porcentaje'] }}">
                                    <input type="hidden" name="data[{{ $index }}][id_chofer]" value="{{ $item['id_chofer'] }}">
                                    <input type="hidden" name="data[{{ $index }}][mensajes_error]" value="{{ $item['mensajes_error'] }}">
                                </td>
                                <td>
                                    <small class="d-block text-primary"><strong>{{ number_format($item['toneladas_netas'], 2) }} Tns</strong></small>
                                    <small class="text-muted">Rend: {{ $item['rendimiento_real'] }}%</small>
                                </td>
                                <td>
                                    <small class="{{ $item['status_color'] == 'rojo' ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                        {{ $item['mensajes_error'] }}
                                    </small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-agro').select2({ theme: 'bootstrap4' });

        $('#dataTablePurgatorio').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json" },
            "pageLength": 50,
            "dom": '<"p-3 d-flex justify-content-between"fB>rtip',
        });
    });

    function confirmarProcesamiento() {
        Swal.fire({
            title: '¿Confirmar Carga?',
            text: "Los boletos válidos se integrarán al inventario de zafra.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2d6a4f',
            confirmButtonText: '<i class="fas fa-save mr-1"></i> Sí, Procesar',
            cancelButtonText: 'Revisar más',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                // Bloqueamos el botón y enviamos
                $('#form-purgatorio').submit();
            }
        });
    }
</script>
@endpush