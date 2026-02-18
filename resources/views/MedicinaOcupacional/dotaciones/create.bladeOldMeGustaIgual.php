@extends('layouts.app')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .kpi-card { transition: transform 0.2s; border: none; border-radius: 15px; }
    .kpi-card:hover { transform: translateY(-5px); }
    .icon-shape {
        width: 48px; height: 48px; background-position: center;
        border-radius: 12px; display: flex; align-items: center; justify-content: center;
    }
    .bg-gradient-medical { background: linear-gradient(45deg, #2c3e50, #4e73df); }
    .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple {
        border: 1px solid #d1d3e2 !important;
        border-radius: 0.35rem !important;
        padding: 0.375rem 0.75rem !important;
        height: auto !important;
    }
    .signature-pad-container {
        border: 2px dashed #d1d3e2;
        border-radius: 10px;
        background-color: #f8f9fc;
    }
    .step-indicator {
        width: 30px; height: 30px; border-radius: 50%;
        background: #4e73df; color: white;
        display: inline-flex; align-items: center; justify-content: center;
        margin-right: 10px; font-weight: bold;
    }
    .section-title { font-size: 1.1rem; font-weight: 700; color: #4e73df; margin-bottom: 20px; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Emisión de Dotación Técnica</h1>
        <div>
            <span class="badge badge-outline-secondary">ID Paciente: #{{ $paciente->id }}</span>
            <span class="badge badge-primary">Estatus: Activo</span>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card shadow h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Última Dotación</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $paciente->dotaciones->last() ? $paciente->dotaciones->last()->fecha_entrega : 'Sin registro' }}
                            </div>
                            <small class="text-muted">Hace: {{ $paciente->dotaciones->last() ? \Carbon\Carbon::parse($paciente->dotaciones->last()->fecha_entrega)->diffForHumans(null, true) : 'N/A' }}</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-history fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card shadow h-100 py-2 border-left-danger">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Historial Accidentes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $paciente->accidentes->count() }} Registrados</div>
                            <small class="text-{{ $paciente->accidentes->where('fecha', '>', now()->subMonths(6))->count() > 0 ? 'danger' : 'success' }} font-weight-bold">
                                {{ $paciente->accidentes->where('fecha', '>', now()->subMonths(6))->count() }} en últimos 6 meses
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ambulance fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card shadow h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Antigüedad Cane</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $paciente->fecha_ing ? \Carbon\Carbon::parse($paciente->fecha_ing)->diffForHumans(null, true) : 'N/D' }}</div>
                            <small class="text-muted">Área: {{ $paciente->des_depart }}</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card bg-gradient-medical shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Días sin Accidentes (Sede)</div>
                            <div class="h5 mb-0 font-weight-bold text-white">124 Días</div>
                            <div class="progress progress-sm mr-2 mt-2">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 80%"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-virus fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4 border-top-primary">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-clipboard-check mr-2"></i> Configuración de Kit para: {{ $paciente->nombre_completo }}
            </h6>
            <span class="text-muted small">Cédula: {{ $paciente->ci }}</span>
        </div>
        
        <div class="card-body bg-light-gray">
            <form id="formDotacion" action="{{ route('medicina.dotaciones.store') }}" method="POST">
                @csrf
                <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

                <div class="section-title"><span class="step-indicator">1</span> Selección de Artículos (Stock de Profit)</div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-shape bg-light-success text-success mr-3">
                                        <i class="fas fa-shoe-prints"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold">Calzado</h6>
                                        <span class="badge badge-pill badge-success">Talla: {{ $paciente->talla_calzado }}</span>
                                    </div>
                                </div>
                                <select name="co_art_calzado" class="form-control select2-single">
                                    <option value="">-- No entregar --</option>
                                    @foreach($stock['botas'] as $item)
                                        <option value="{{ $item->co_art }}" {{ (Str::contains($item->art_des, $paciente->talla_calzado)) ? 'selected' : '' }}>
                                            {{ $item->art_des }} (Stock: {{ number_format($item->stock_act, 0) }})
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="calzado_talla" value="{{ $paciente->talla_calzado }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-shape bg-light-primary text-primary mr-3">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold">Pantalón</h6>
                                        <span class="badge badge-pill badge-primary">Talla: {{ $paciente->talla_pantalon }}</span>
                                    </div>
                                </div>
                                <select name="co_art_pantalon" class="form-control select2-single">
                                    <option value="">-- No entregar --</option>
                                    @foreach($stock['pantalones'] as $item)
                                        <option value="{{ $item->co_art }}" {{ (Str::contains($item->art_des, $paciente->talla_pantalon)) ? 'selected' : '' }}>
                                            {{ $item->art_des }} (Stock: {{ number_format($item->stock_act, 0) }})
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="pantalon_talla" value="{{ $paciente->talla_pantalon }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-shape bg-light-warning text-warning mr-3">
                                        <i class="fas fa-tshirt"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold">Camisa</h6>
                                        <span class="badge badge-pill badge-warning">Talla: {{ $paciente->talla_camisa }}</span>
                                    </div>
                                </div>
                                <select name="co_art_camisa" class="form-control select2-single">
                                    <option value="">-- No entregar --</option>
                                    @foreach($stock['camisas'] as $item)
                                        <option value="{{ $item->co_art }}" {{ (Str::contains($item->art_des, $paciente->talla_camisa)) ? 'selected' : '' }}>
                                            {{ $item->art_des }} (Stock: {{ number_format($item->stock_act, 0) }})
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="camisa_talla" value="{{ $paciente->talla_camisa }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-left-warning">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-warning mb-3"><i class="fas fa-mask mr-2"></i> Equipos Especiales y Consumibles</h6>
                                <select name="otros_epp_codigos[]" class="form-control select2-multiple" multiple="multiple">
                                    @foreach($stock['otros'] as $item)
                                        <option value="{{ $item->co_art }}">
                                            {{ $item->art_des }} | Stock: {{ number_format($item->stock_act, 0) }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 mb-0 small text-muted">Añada guantes, lentes, protectores auditivos, etc.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-lg-6">
                        <div class="section-title"><span class="step-indicator">2</span> Detalles Médicos</div>
                        <div class="form-group">
                            <label class="font-weight-bold small">Motivo Legal/Administrativo</label>
                            <select class="form-control custom-select" name="motivo">
                                <option>Dotación Semestral</option>
                                <option>Reposición por Deterioro</option>
                                <option>Ingreso de Personal</option>
                                <option>Reposición por Accidente</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold small">Observaciones Médicas / SSL</label>
                            <textarea name="observaciones" class="form-control" rows="6" placeholder="Detalle cualquier condición especial aquí..."></textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="section-title"><span class="step-indicator">3</span> Validación del Trabajador</div>
                        <div class="text-center p-3 signature-pad-container">
                            <p class="small text-muted mb-2">Firma digital en pantalla</p>
                            <canvas id="signature-pad" class="bg-white rounded shadow-sm" style="width: 100%; height: 200px; cursor: crosshair;"></canvas>
                            <div class="mt-3">
                                <button type="button" id="clear" class="btn btn-sm btn-outline-danger btn-pill">
                                    <i class="fas fa-eraser mr-1"></i> Borrar Firma
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="firma_digital" id="firma_digital">
                    </div>
                </div>

                <div class="mt-5 border-top pt-4 text-right">
                    <button type="button" onclick="history.back()" class="btn btn-light btn-lg px-5 mr-2">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                        <i class="fas fa-paper-plane mr-2"></i> Generar Ticket y Notificar Almacén
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-single').select2({ placeholder: "Seleccione artículo o deje vacío" });
        $('.select2-multiple').select2({ placeholder: "Busque artículos..." });
    });
</script>

<script>
    // Usamos el ID específico del canvas para evitar conflictos
    const canvas = document.getElementById("signature-pad");
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)' // Asegura fondo blanco
    });

    // Ajustar el tamaño del canvas (importante para pantallas táctiles)
    function resizeCanvas() {
        const ratio =  Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }
    window.onresize = resizeCanvas;
    resizeCanvas();

    $('#formDotacion').on('submit', function(e) {
        // Detenemos el envío para validar
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            Swal.fire({
                title: 'Firma Requerida',
                text: 'El trabajador debe firmar la recepción del equipo antes de continuar.',
                icon: 'warning',
                confirmButtonColor: '#4e73df'
            });
            return false;
        } else {
            // Si hay firma, la pasamos al input hidden
            const dataURL = signaturePad.toDataURL("image/png");
            $('#firma_digital').val(dataURL);
            
            // Log para debuggear en consola antes de que se vaya la página
            console.log("Firma capturada: " + dataURL.substring(0, 50) + "...");
            return true; // Permite el envío
        }
    });

    document.getElementById('clear').addEventListener('click', () => {
        signaturePad.clear();
        $('#firma_digital').val('');
    });
</script>
@endsection