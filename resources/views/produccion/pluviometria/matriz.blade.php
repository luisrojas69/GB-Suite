@extends('layouts.app')

@section('title-page', 'Control Pluviométrico')

@section('styles')
<style>
    /* ========================================
       VARIABLES GLOBALES - TEMA WATER/RAIN
    ======================================== */
    :root {
        --water-dark: #005f73;      /* Azul Profundo */
        --water-primary: #0a9396;   /* Teal / Cyan oscuro */
        --water-light: #94d2bd;     /* Celeste suave */
        --water-accent: #00b4d8;    /* Azul brillante */
        --water-bg: #e9ecef;        /* Fondo neutro */
    }

    /* ========================================
       HEADER PRINCIPAL
    ======================================== */
    .page-header-water {
        background: linear-gradient(135deg, var(--water-dark) 0%, var(--water-primary) 100%);
        color: white; 
        padding: 25px 30px; 
        border-radius: 15px;
        margin-bottom: 25px; 
        box-shadow: 0 8px 25px rgba(10, 147, 150, 0.25);
        position: relative; 
        overflow: hidden;
    }
    .page-header-water::before {
        content: '\f740'; /* fa-cloud-rain icon code */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute; 
        top: -20px; 
        right: 10px;
        font-size: 8rem; 
        color: rgba(255,255,255,0.05);
        transform: rotate(-15deg);
    }

    /* ========================================
       TARJETAS DE ESTADÍSTICAS (PREMIUM)
    ======================================== */
    .card-stat-water {
        border: none;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        background: #fff;
    }
    .card-stat-water:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .card-stat-water .card-body {
        position: relative;
        z-index: 2;
    }
    /* Colores Específicos para cada Card */
    .border-water-1 { border-bottom: 4px solid var(--water-dark); }
    .border-water-2 { border-bottom: 4px solid var(--water-primary); }
    .border-water-3 { border-bottom: 4px solid var(--water-accent); }
    .border-water-4 { border-bottom: 4px solid #f6c23e; } /* Mantenemos amarillo para advertencia/carga */
    
    .icon-circle-water {
        width: 50px; height: 50px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(10, 147, 150, 0.1);
        color: var(--water-primary);
        font-size: 1.5rem;
    }

    /* ========================================
       MATRIZ (TABLA) DE DATOS
    ======================================== */
    .table-wrapper {
        border-radius: 12px;
        background: #fff;
        padding: 1px;
    }
    .table-container { 
        height: 60vh; 
        overflow: auto; 
        border-radius: 10px;
        border: 1px solid #e3e6f0;
    }
    
    /* Scrollbar Personalizado para la Matriz */
    .table-container::-webkit-scrollbar { width: 8px; height: 8px; }
    .table-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .table-container::-webkit-scrollbar-thumb { background: var(--water-light); border-radius: 4px; }
    .table-container::-webkit-scrollbar-thumb:hover { background: var(--water-primary); }

    table { border-collapse: separate; border-spacing: 0; margin-bottom: 0; }
    
    /* Headers de Días CONGELADOS (Top) */
    thead th {
        position: sticky; top: 0;
        background-color: var(--water-dark) !important;
        color: white;
        z-index: 9; text-align: center;
        min-width: 48px; padding: 10px 5px !important;
        font-size: 0.85rem; font-weight: 600;
        border-bottom: none !important;
    }

    /* Columna de Sectores CONGELADA */
    .sticky-col {
        position: sticky; left: 0;
        background-color: #f8f9fc !important;
        z-index: 10;
        border-right: 2px solid var(--water-light) !important;
        white-space: nowrap; 
        min-width: 160px;
        box-shadow: 2px 0 5px rgba(0,0,0,0.02);
    }
    
    /* Intersección Esquina Superior Izquierda */
    thead th.sticky-col {
        z-index: 11; top: 0; left: 0;
        background-color: var(--water-dark) !important;
        border-right: 2px solid rgba(255,255,255,0.2) !important;
        box-shadow: none;
    }

    /* Celdas e Inputs */
    tbody td {
        vertical-align: middle !important;
        padding: 0 !important;
        border-color: #eaecf4;
        transition: background-color 0.2s;
    }
    .cell-input {
        width: 100%; height: 100%;
        min-height: 38px;
        text-align: center;
        border: 2px solid transparent;
        background: transparent;
        font-size: 0.9rem; font-weight: 600; color: #5a5c69;
        transition: all 0.2s ease-in-out;
    }
    .cell-input:focus {
        background: #fff;
        border: 2px solid var(--water-accent);
        outline: none;
        box-shadow: inset 0 0 5px rgba(0, 180, 216, 0.2);
        color: var(--water-dark);
        border-radius: 4px;
        transform: scale(1.02);
    }
    .cell-input::placeholder { color: #d1d3e2; font-weight: 400; }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    /* Colores de Estado de Celda */
    .bg-missing { background-color: #fff1f0 !important; }
    .bg-today { background-color: #fff9db !important; box-shadow: inset 0 0 0 1px #f6c23e; }
    .th-today { background-color: #f6c23e !important; color: #3a3b45 !important; }

    /* Botones y Controles */
    .btn-water {
        background-color: var(--water-primary);
        color: white;
        border: none;
    }
    .btn-water:hover { background-color: var(--water-dark); color: white; }
    .btn-outline-water {
        border-color: var(--water-primary);
        color: var(--water-primary);
    }
    .btn-outline-water:hover {
        background-color: var(--water-primary);
        color: white;
    }
</style>
@endsection

@section('content')

<div class="page-header-water">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="font-weight-bold mb-1"><i class="fas fa-cloud-showers-heavy mr-2"></i> Pluviometría General</h2>
            <p class="mb-0 text-white-50" style="font-size: 1.1rem;">
                Registro masivo y control de precipitaciones por sector agrícola.
            </p>
        </div>
        <div class="col-md-4 text-md-right mt-3 mt-md-0">
            <span class="badge badge-light text-dark px-3 py-2 shadow-sm" style="font-size: 0.9rem;">
                <i class="far fa-calendar-alt mr-1 text-primary"></i> 
                {{ $fechaInicio->isoFormat('MMMM YYYY') }}
            </span>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card card-stat-water border-water-1 shadow-sm h-100">
            <div class="card-body py-3 px-4">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--water-dark);">Acumulado del Mes</div>
                        <div class="h4 mb-0 font-weight-black text-gray-800">{{ number_format($acumuladoMes, 1) }} <small class="text-muted text-xs">mm</small></div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle-water" style="background: rgba(0, 95, 115, 0.1); color: var(--water-dark);">
                            <i class="fas fa-tint"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card card-stat-water border-water-2 shadow-sm h-100">
            <div class="card-body py-3 px-4">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--water-primary);">Máximo Diario</div>
                        <div class="h4 mb-0 font-weight-black text-gray-800">{{ $maximaLluvia }} <small class="text-muted text-xs">mm</small></div>
                        <div class="text-xs text-gray-500 font-weight-bold text-truncate" title="{{ $nombreSectorMax }}"><i class="fas fa-map-marker-alt mr-1"></i>{{ $nombreSectorMax }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle-water">
                            <i class="fas fa-cloud-showers-heavy"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card card-stat-water border-water-3 shadow-sm h-100">
            <div class="card-body py-3 px-4">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--water-accent);">Días con Lluvia</div>
                        <div class="h4 mb-0 font-weight-black text-gray-800">{{ $diasConLluvia }} <small class="text-muted text-xs">días</small></div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle-water" style="background: rgba(0, 180, 216, 0.1); color: var(--water-accent);">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card card-stat-water border-water-4 shadow-sm h-100">
            <div class="card-body py-3 px-4">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Carga de Datos</div>
                        <div class="d-flex align-items-center mb-1">
                            <div class="h4 mb-0 font-weight-black text-gray-800 mr-2">{{ $porcentajeCarga }}%</div>
                        </div>
                        <div class="progress progress-sm" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $porcentajeCarga }}%" aria-valuenow="{{ $porcentajeCarga }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle-water" style="background: rgba(246, 194, 62, 0.1); color: #f6c23e;">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4 border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-center justify-content-between border-bottom">
        <h6 class="m-0 font-weight-bold mb-3 mb-md-0" style="color: var(--water-dark);">
            <i class="fas fa-table mr-2"></i> Matriz de Registros
        </h6>
        
        <div class="d-flex align-items-center flex-wrap justify-content-center">
            <div class="btn-group btn-group-sm mr-3 shadow-sm rounded-pill overflow-hidden mb-2 mb-md-0">
                <button type="button" class="btn btn-outline-water px-3" onclick="cambiarMes('{{ $fechaInicio->copy()->subMonth()->format('Y-m') }}')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="btn btn-water text-capitalize font-weight-bold px-4 disabled" style="opacity: 1;">
                    {{ $fechaInicio->isoFormat('MMMM YYYY') }}
                </button>
                <button type="button" class="btn btn-outline-water px-3" onclick="cambiarMes('{{ $fechaInicio->copy()->addMonth()->format('Y-m') }}')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            @can('produccion.pluviometria.reportes')
                <button class="btn btn-sm btn-light text-success border shadow-sm mr-2 mb-2 mb-md-0 font-weight-bold" onclick="exportarExcel()">
                    <i class="fas fa-file-excel mr-1"></i> Exportar
                </button>
            @endcan
            
            @can('produccion.pluviometria.crear')
                <button class="btn btn-sm btn-water shadow-sm px-3 mb-2 mb-md-0 font-weight-bold" id="btnGuardarMatriz">
                    <i class="fas fa-save mr-1"></i> Guardar Cambios
                </button>
            @endcan
        </div>
    </div>

    <div class="card-body p-3 bg-light"> 
        <div class="table-wrapper shadow-sm">
            <div class="table-container bg-white">
                @can('produccion.pluviometria.ver')
                    <table class="table table-hover table-sm" id="matrizPluvio">
                        <thead>
                            <tr>
                                <th class="sticky-col align-middle text-left pl-3">Sector</th>
                                @for ($d = 1; $d <= $diasDelMes; $d++)
                                    <th class="{{ ($d == $hoy->day && $mes == $hoy->month) ? 'th-today' : '' }}">
                                        {{ str_pad($d, 2, '0', STR_PAD_LEFT) }}
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sectores as $sector)
                            <tr>
                                <td class="sticky-col font-weight-bold text-gray-700 pl-3">
                                    {{ $sector->nombre }}
                                </td>
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
                                    <td class="{{ $claseFondo }}">
                                        <input type="number" 
                                               step="0.1" 
                                               class="cell-input" 
                                               value="{{ $valor }}"
                                               data-sector="{{ $sector->id }}"
                                               data-fecha="{{ $fechaCelda->format('Y-m-d') }}"
                                               placeholder=" - ">
                                    </td>
                                @endfor
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-5">
                        <div class="icon-circle-water mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2.5rem; background: #eaecf4; color: #b7b9cc;">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h5 class="font-weight-bold text-gray-700">Acceso Restringido</h5>
                        <p class="text-gray-500">No tienes los permisos necesarios para visualizar o gestionar las pluviometrías.</p>
                    </div>
                @endcan
            </div>
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
                    intensidad: calcularIntensidad(val)
                });
            }
        });

        if (datos.length === 0) {
            return Swal.fire({
                icon: 'info',
                title: 'Sin datos nuevos',
                text: 'No has ingresado ningún valor en la matriz para guardar.',
                confirmButtonColor: '#0a9396'
            });
        }

        $.ajax({
            url: "{{ route('produccion.pluviometria.guardar_masivo') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                registros: datos
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Guardando Registros...',
                    text: 'Por favor, espera un momento.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Los registros de lluvia han sido actualizados.',
                    confirmButtonColor: '#0a9396'
                }).then(() => {
                    location.reload();
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un problema al guardar los datos.',
                    confirmButtonColor: '#e74a3b'
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
        title: '<i class="fas fa-file-excel text-success mr-2"></i> Exportar Reporte',
        html: `
            <div class="text-left mt-3">
                <label class="font-weight-bold text-gray-800"><i class="far fa-calendar-alt mr-1"></i> Rango de Fechas:</label>
                <div class="row mb-4">
                    <div class="col">
                        <small class="text-muted font-weight-bold">Desde:</small>
                        <input type="date" id="fecha_desde" class="form-control form-control-sm" value="{{ $fechaInicio->startOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="col">
                        <small class="text-muted font-weight-bold">Hasta:</small>
                        <input type="date" id="fecha_hasta" class="form-control form-control-sm" value="{{ $fechaInicio->endOfMonth()->format('Y-m-d') }}">
                    </div>
                </div>
                <label class="font-weight-bold text-gray-800"><i class="fas fa-list-ul mr-1"></i> Formato de Archivo:</label>
                <div class="custom-control custom-radio mb-2">
                    <input type="radio" id="formato1" name="formato_excel" class="custom-control-input" value="matriz" checked>
                    <label class="custom-control-label" for="formato1">Formato Matriz (Visual como en pantalla)</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="formato2" name="formato_excel" class="custom-control-input" value="plano">
                    <label class="custom-control-label" for="formato2">Formato Listado (Ideal para Tablas Dinámicas)</label>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#1cc88a',
        cancelButtonColor: '#858796',
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
            let url = `{{ route('produccion.pluviometria.exportar') }}?desde=${desde}&hasta=${hasta}&formato=${formato}`;
            window.location.href = url;
        }
    });
}
</script>
@endsection