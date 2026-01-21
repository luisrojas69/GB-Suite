@extends('layouts.app')

@section('styles')
<style>
    /* Contenedor con Scroll Horizontal y Vertical */
    .table-container { 
        height: 65vh; 
        overflow: auto; 
        border: 1px solid #e3e6f0;
    }

    /* Fix para que los bordes se vean bien con Sticky */
    table { border-collapse: separate; border-spacing: 0; }

    /* Columna de Sectores CONGELADA */
    .sticky-col {
        position: sticky;
        left: 0;
        background-color: #f8f9fc !important;
        z-index: 10;
        border-right: 2px solid #4e73df !important;
        white-space: nowrap; /* Auto-ajuste al nombre */
        min-width: 150px;
    }

    /* Headers de Días CONGELADOS (Top) */
    thead th {
        position: sticky;
        top: 0;
        background-color: #eaecf4 !important;
        z-index: 9;
        text-align: center;
        min-width: 45px; /* Columnas más pequeñas como pediste */
        padding: 5px !important;
    }

    /* Intersección (Esquina Superior Izquierda) */
    thead th.sticky-col {
        z-index: 11;
        top: 0;
    }

    /* Estilos de los Inputs */
    .cell-input {
        width: 100%;
        text-align: center;
        border: none;
        background: transparent;
        font-size: 0.85rem;
        padding: 4px 0;
    }
    
    .cell-input:focus {
        background: #fff;
        outline: 1px solid #4e73df;
        border-radius: 2px;
    }

    /* Eliminar flechas de input number */
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none; margin: 0;
    }

    /* Colores de estado */
    .bg-missing { background-color: #fff1f0 !important; }
    .bg-today { background-color: #fff9db !important; outline: 1px solid #f6c23e; }
</style>
@endsection

@section('content')

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Acumulado Mes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($acumuladoMes, 1) }} mm</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-tint fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Máximo Diario</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $maximaLluvia }} mm</div>
                        <div class="text-xs text-gray-600">{{ $nombreSectorMax }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-cloud-showers-heavy fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Días con Lluvia</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $diasConLluvia }} días</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-calendar-check fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">% Carga de Datos</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $porcentajeCarga }}%</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                         style="width: {{ $porcentajeCarga }}%" aria-valuenow="{{ $porcentajeCarga }}" 
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Control Pluviométrico</h6>
        
        <div class="d-flex align-items-center">
            <div class="btn-group btn-group-sm mr-3">
                {{-- Navegación con meses en español --}}
                <button type="button" class="btn btn-outline-primary" 
                        onclick="cambiarMes('{{ $fechaInicio->copy()->subMonth()->format('Y-m') }}')">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <button type="button" class="btn btn-primary disabled text-capitalize">
                    {{ $fechaInicio->isoFormat('MMMM YYYY') }}
                </button>
                <button type="button" class="btn btn-outline-primary" 
                        onclick="cambiarMes('{{ $fechaInicio->copy()->addMonth()->format('Y-m') }}')">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>

            <button class="btn btn-sm btn-success mr-2" onclick="exportarExcel()"><i class="fas fa-file-excel"></i></button>
            <button class="btn btn-sm btn-primary" id="btnGuardarMatriz"><i class="fas fa-save"></i> Guardar</button>
        </div>
    </div>

    <div class="card-body p-0"> <div class="table-container">
            <table class="table table-bordered table-sm" id="matrizPluvio">
                <thead>
                    <tr>
                        <th class="sticky-col">Sector</th>
                        @for ($d = 1; $d <= $diasDelMes; $d++)
                            <th class="{{ ($d == $hoy->day && $mes == $hoy->month) ? 'bg-warning text-dark' : '' }}">
                                {{ $d }}
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sectores as $sector)
                    <tr>
                        <td class="sticky-col font-weight-bold text-dark">{{ $sector->nombre }}</td>
                        @for ($d = 1; $d <= $diasDelMes; $d++)
                            @php
                                $registro = $registros[$sector->id][$d] ?? null;
                                $valor = $registro ? $registro->cantidad_mm : '';
                                $fechaCelda = \Carbon\Carbon::create($anio, $mes, $d);
                                
                                $claseFondo = '';
                                if (!$registro && $fechaCelda->isPast() && !$fechaCelda->isToday()) {
                                    $claseFondo = 'bg-missing';
                                } elseif ($fechaCelda->isToday()) {
                                    $claseFondo = 'bg-today';
                                }
                            @endphp
                            <td class="p-0 {{ $claseFondo }}">
                                <input type="number" 
                                       step="0.1" 
                                       class="cell-input" 
                                       value="{{ $valor }}"
                                       data-sector="{{ $sector->id }}"
                                       data-fecha="{{ $fechaCelda->format('Y-m-d') }}"
                                       placeholder="0.0">
                            </td>
                        @endfor
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
// Función para cambiar de mes
function cambiarMes(fechaStr) {
    let partes = fechaStr.split('-');
    window.location.href = `{{ route('produccion.pluviometria.index') }}?anio=${partes[0]}&mes=${partes[1]}`;
}

$(document).ready(function() {
    $('#btnGuardarMatriz').click(function() {
        let datos = [];

        // Recolectamos solo las celdas que tienen algún valor
        $('.cell-input').each(function() {
            let val = $(this).val();
            if (val !== "") {
                datos.push({
                    id_sector: $(this).data('sector'),
                    fecha: $(this).data('fecha'),
                    cantidad_mm: val,
                    intensidad: calcularIntensidad(val) // Lógica automática inicial
                });
            }
        });

        if (datos.length === 0) return Swal.fire('Atención', 'No hay datos nuevos para guardar', 'info');

        $.ajax({
            url: "{{ route('produccion.pluviometria.guardar_masivo') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                registros: datos
            },
            beforeSend: function() {
                Swal.showLoading();
            },
            success: function(response) {
                Swal.fire('¡Éxito!', 'Registros actualizados correctamente', 'success').then(() => {
                    location.reload();
                });
            }
        });
    });
});

function calcularIntensidad(mm) {
    if (mm == 0) return 'NULA';
    if (mm < 10) return 'LIGERA';
    if (mm < 30) return 'MODERADA';
    if (mm < 60) return 'FUERTE';
    return 'TORRENCIAL';
}


function exportarExcel() {
    Swal.fire({
        title: 'Exportar Reporte de Lluvia',
        html: `
            <div class="text-left">
                <label><b>Rango de Fechas:</b></label>
                <div class="row mb-3">
                    <div class="col">
                        <small>Desde:</small>
                        <input type="date" id="fecha_desde" class="form-control" value="{{ $fechaInicio->startOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="col">
                        <small>Hasta:</small>
                        <input type="date" id="fecha_hasta" class="form-control" value="{{ $fechaInicio->endOfMonth()->format('Y-m-d') }}">
                    </div>
                </div>
                <label><b>Formato de archivo:</b></label>
                <div class="custom-control custom-radio mb-2">
                    <input type="radio" id="formato1" name="formato_excel" class="custom-control-input" value="matriz" checked>
                    <label class="custom-control-label" for="formato1">Formato Matriz (Igual a la pantalla)</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="formato2" name="formato_excel" class="custom-control-input" value="plano">
                    <label class="custom-control-label" for="formato2">Formato Listado (Para Tablas Dinámicas)</label>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-download"></i> Descargar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            return {
                desde: document.getElementById('fecha_desde').value,
                hasta: document.getElementById('fecha_hasta').value,
                formato: document.querySelector('input[name="formato_excel"]:checked').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { desde, hasta, formato } = result.value;
            // Construimos la URL con los parámetros
            let url = `{{ route('produccion.pluviometria.exportar') }}?desde=${desde}&hasta=${hasta}&formato=${formato}`;
            window.location.href = url;
        }
    });
}
</script>
@endsection
