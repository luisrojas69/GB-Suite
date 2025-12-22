@extends('layouts.app')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detalle de Mantenimiento Correctivo</h1>
    <div class="d-flex">
        <a href="{{ route('produccion.pozos.mantenimientos.edit', $mantenimiento) }}" class="btn btn-sm btn-info shadow-sm mr-2">
            <i class="fas fa-edit fa-sm text-white-50"></i> Editar / Cerrar Mantenimiento
        </a>
        <a href="{{ route('produccion.pozos.activos.show', $mantenimiento->activo) }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver al Activo
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">Activo y Resumen</h6>
            </div>
            <div class="card-body">
                <p><strong>Activo Afectado:</strong> <a href="{{ route('produccion.pozos.activos.show', $mantenimiento->activo) }}">{{ $mantenimiento->activo->nombre }}</a></p>
                <p><strong>Tipo de Activo:</strong> <span class="badge badge-secondary">{{ $mantenimiento->activo->tipo_activo }}</span></p>
                <hr>
                <p><strong>Fecha de Reporte (Falla):</strong> {{ $mantenimiento->fecha_falla_reportada->format('d/m/Y H:i') }}</p>
                <p><strong>Síntoma de Falla:</strong> {{ $mantenimiento->sintoma_falla }}</p>
                <p><strong>Responsable Inicial:</strong> {{ $mantenimiento->responsable ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 @if($mantenimiento->fecha_reinicio_operacion) bg-success @else bg-warning @endif">
                <h6 class="m-0 font-weight-bold text-white">Resultado y Costo</h6>
            </div>
            <div class="card-body">
                @if($mantenimiento->fecha_reinicio_operacion)
                    <p><strong>Fecha de Reinicio Operativo:</strong> <span class="text-success font-weight-bold">{{ $mantenimiento->fecha_reinicio_operacion->format('d/m/Y H:i') }}</span></p>
                    <p class="h4 text-success"><strong>Tiempo de Parada (MTTR):</strong> {{ $mantenimiento->tiempo_parada_horas }} horas</p>
                    <hr>
                    <p><strong>Trabajo Realizado:</strong> {{ $mantenimiento->trabajo_realizado }}</p>
                    <p><strong>Costo Asociado:</strong> <span class="text-danger font-weight-bold">${{ number_format($mantenimiento->costo_asociado, 2) }}</span></p>
                @else
                    <div class="alert alert-warning">
                        Este mantenimiento aún está <strong>**ABIERTO**.</strong> El activo se encuentra en estatus <strong>"{{ $mantenimiento->activo->estatus_actual }}".</strong>
                        <br>
                        <hr class="sidebar-divider d-none d-md-block">

                    <a href="{{ route('produccion.pozos.mantenimientos.edit', $mantenimiento) }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-close fa-sm text-white-50"></i> 
                        Cerrar Mantenimiento para calcular tiempo y costo.
                    </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 

