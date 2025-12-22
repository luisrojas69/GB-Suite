@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-dark text-white">
            <h6 class="m-0 font-weight-bold">Entrega de Equipos de Protección (EPP) - {{ $paciente->nombre_completo }}</h6>
        </div>
        <div class="card-body">
                <div class="alert alert-dark small">
                    <i class="fas fa-warehouse"></i> <strong>Stock Real en Profit (Línea 308):</strong> 
                    @foreach($stockProfit as $item)
                        <span class="badge badge-light border ml-2">{{ $item->art_des }}: {{ number_format($item->stock_act, 0) }}</span>
                    @endforeach
                </div>
                 @php
                    // Lógica para determinar si ya le toca (6 meses = 180 días)
                    $ultimaBota = $paciente->dotaciones()->where('calzado_entregado', 1)->latest()->first();
                    $diasBota = $ultimaBota ? now()->diffInDays($ultimaBota->created_at) : 999;
                    $necesitaJustificacion = $diasBota < 180;
                @endphp

            <form id="formDotacion" action="{{ route('medicina.dotaciones.store') }}" method="POST">
                @csrf
                <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle"></i> <strong>Tallas Sugeridas (según Ficha Médica):</strong> 
                            Camisa: {{ $paciente->talla_camisa ?? 'N/P' }} | 
                            Pantalón: {{ $paciente->talla_pantalon ?? 'N/P' }} | 
                            Calzado: {{ $paciente->talla_calzado ?? 'N/P' }}
                             <small class="text-danger font-weight-bold float-right">Última entrega hace {{ $diasBota }} días.</small>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="card p-3 border-left-success">
                            <label class="font-weight-bold">Calzado / Botas</label>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="checkCalzado" name="calzado_entregado" value="1">
                                <label class="custom-control-label" for="checkCalzado">Entregar</label>
                            </div>
                            <input type="text" class="form-control form-control-sm" name="calzado_talla" value="{{ $paciente->talla_calzado }}" placeholder="Talla">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card p-3 border-left-primary">
                            <label class="font-weight-bold">Pantalón / Jean</label>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="checkPantalon" name="pantalon_entregado" value="1">
                                <label class="custom-control-label" for="checkPantalon">Entregar</label>
                            </div>
                            <input type="text" class="form-control form-control-sm" name="pantalon_talla" value="{{ $paciente->talla_pantalon }}" placeholder="Talla">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card p-3 border-left-warning">
                            <label class="font-weight-bold">Camisa / Franela</label>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="checkCamisa" name="camisa_entregado" value="1">
                                <label class="custom-control-label" for="checkCamisa">Entregar</label>
                            </div>
                            <input type="text" class="form-control form-control-sm" name="camisa_talla" value="{{ $paciente->talla_camisa }}" placeholder="Talla">
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <label class="small font-weight-bold">Otros EPP (Casco, Lentes, etc.)</label>
                        <textarea class="form-control" name="otros_epp" rows="2"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="small font-weight-bold">Observaciones / Motivo</label>
                        <select class="form-control" name="motivo">
                            <option>Dotación Semestral</option>
                            <option>Reposición por Deterioro</option>
                            <option>Ingreso de Personal</option>
                        </select>
                    </div>
                </div>

                 <hr class="sidebar-divider d-none d-md-block">

                <div class="col-md-12 text-center">
                    <label class="font-weight-bold">Firma del Trabajador (Aceptación de Dotación)</label>
                    <div class="signature-pad-container shadow-sm border bg-white" style="width: 100%; max-width: 500px; margin: 0 auto;">
                        <canvas id="signature-pad" width="500" height="200" style="touch-action: none;"></canvas>
                    </div>
                    <div class="mt-2">
                        <button type="button" id="clear" class="btn btn-sm btn-outline-secondary">Limpiar Firma</button>
                    </div>
                    <input type="hidden" name="firma_digital" id="firma_digital">
                    <input type="hidden" name="qr_token" value="{{ Str::random(40) }}">
                </div>

                <div class="mt-4 text-right">
                    <button type="submit" class="btn btn-dark btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-truck-loading"></i></span>
                        <span class="text">Procesar Entrega</span>
                    </button>
                </div>

                
                </div>
            </form>
        </div>
    </div>
    <div class="row mt-4">




</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
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