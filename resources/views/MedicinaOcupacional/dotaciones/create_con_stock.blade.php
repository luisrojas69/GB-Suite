@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('medicina.dotaciones.store') }}" method="POST" id="formDotacion">
        @csrf
        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold">Nueva Solicitud de EPP: {{ $paciente->nombre_completo }}</h6>
                <span>Departamento: {{ $paciente->des_depart }}</span>
            </div>
            
            <div class="card-body">
                <div class="alert alert-dark small">
                    <i class="fas fa-warehouse"></i> <strong>Stock Real en Profit (Línea 308):</strong> 
                    @foreach($stockProfit as $item)
                        <span class="badge badge-light border ml-2">{{ $item->art_des }}: {{ number_format($item->stock_act, 0) }}</span>
                    @endforeach
                </div>

                <div class="row">
                    @php
                        // Lógica para determinar si ya le toca (6 meses = 180 días)
                        $ultimaBota = $paciente->dotaciones()->where('calzado_entregado', 1)->latest()->first();
                        $diasBota = $ultimaBota ? now()->diffInDays($ultimaBota->created_at) : 999;
                        $necesitaJustificacion = $diasBota < 180;
                    @endphp

                    <div class="col-md-4">
                        <div class="card p-3 border-left-{{ $necesitaJustificacion ? 'warning' : 'success' }}">
                            <label class="font-weight-bold">Calzado / Botas</label>
                            <div class="custom-control custom-switch mb-2">
                                <input type="checkbox" class="custom-control-input" id="checkCalzado" name="calzado_entregado" value="1">
                                <label class="custom-control-label" for="checkCalzado">Solicitar Talla: {{ $paciente->talla_calzado }}</label>
                            </div>
                            @if($necesitaJustificacion)
                                <small class="text-danger font-weight-bold">Última entrega hace {{ $diasBota }} días.</small>
                            @endif
                        </div>
                    </div>
                    </div>

                @if($necesitaJustificacion)
                <div class="mt-3 p-3 bg-light border-left-danger" id="boxJustificacion" style="display:none;">
                    <label class="small font-weight-bold text-danger">JUSTIFICACIÓN DE REPOSICIÓN PREMATURA (Obligatorio)</label>
                    <textarea name="justificacion_excepcional" class="form-control form-control-sm" placeholder="Ej: Deterioro prematuro por químicos, pérdida accidental..."></textarea>
                </div>
                @endif

                <div class="row mt-4">
                    <div class="col-md-12 text-center">
                        <canvas id="signature-pad" class="border" width="500" height="200" style="background:#fff;"></canvas>
                        <input type="hidden" name="firma_digital" id="firma_digital">
                        <br>
                        <button type="button" id="clear" class="btn btn-sm btn-link text-danger">Borrar Firma</button>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Generar Solicitud y Ticket QR</button>
            </div>
        </div>
    </form>
</div>
@endsection