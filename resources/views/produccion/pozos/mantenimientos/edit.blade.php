@extends('layouts.app')

@section('content')
{{-- Mostrar mensajes de sesión --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3 @if($mantenimiento->fecha_reinicio_operacion) bg-success @else bg-warning @endif">
                <h6 class="m-0 font-weight-bold text-white">
                    @if($mantenimiento->fecha_reinicio_operacion) ✅ Detalle del Mantenimiento Cerrado @else 🛠️ Cerrar Mantenimiento @endif
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('produccion.pozos.mantenimientos.update', $mantenimiento) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="alert alert-info">Activo: <strong>{{ $mantenimiento->activo->nombre }}</strong> (Estatus actual: {{ $mantenimiento->activo->estatus_actual }})</div>

                    <h6>Datos de la Falla</h6>
                    <hr class="mt-1">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fecha_falla_reportada">Fecha de Reporte</label>
                            <input type="datetime-local" class="form-control" name="fecha_falla_reportada" value="{{ $mantenimiento->fecha_falla_reportada->format('Y-m-d\TH:i:s') }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="responsable">Responsable Inicial</label>
                            <input type="text" class="form-control" name="responsable" value="{{ old('responsable', $mantenimiento->responsable) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sintoma_falla">Síntoma de la Falla</label>
                        <textarea class="form-control" name="sintoma_falla" rows="2" required>{{ old('sintoma_falla', $mantenimiento->sintoma_falla) }}</textarea>
                    </div>

                    <h6 class="mt-4">Datos del Cierre y Reparación</h6>
                    <hr class="mt-1">

                    <div class="form-group">
                        <label for="trabajo_realizado">Trabajo Realizado / Solución</label>
                        <textarea class="form-control" name="trabajo_realizado" rows="4">{{ old('trabajo_realizado', $mantenimiento->trabajo_realizado) }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="costo_asociado">Costo Total Asociado ($)</label>
                            <input type="number" step="0.01" class="form-control" name="costo_asociado" value="{{ old('costo_asociado', $mantenimiento->costo_asociado) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha_reinicio_operacion">Fecha y Hora de Reinicio Operativo</label>
                            @if(!$mantenimiento->fecha_reinicio_operacion)
                                <input type="datetime-local" class="form-control" id="fecha_reinicio_operacion" name="fecha_reinicio_operacion" value="{{ now()->format('Y-m-d\TH:i') }}">
                                <small class="form-text text-danger">⚠️ **Al llenar esta fecha, el activo volverá a estatus 'OPERATIVO'.**</small>
                            @else
                                <input type="datetime-local" class="form-control" value="{{ $mantenimiento->fecha_reinicio_operacion->format('Y-m-d\TH:i:s') }}" readonly>
                                <small class="form-text text-success">El activo estuvo **Parado por {{ $mantenimiento->tiempo_parada_horas }} horas**.</small>
                            @endif
                        </div>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-success btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                        <span class="text">@if($mantenimiento->fecha_reinicio_operacion) Actualizar @else Cerrar y Guardar @endif Mantenimiento</span>
                    </button>
                    <a href="{{ route('produccion.pozos.mantenimientos.show', $mantenimiento) }}" class="btn btn-secondary">Volver al Detalle</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection