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
            <div class="card-header py-3 bg-danger">
                <h6 class="m-0 font-weight-bold text-white">🚨 Reportar Falla - Activo: {{ $activo->nombre }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('produccion.pozos.activos.mantenimientos.store', $activo) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="fecha_falla_reportada">Fecha y Hora de Detección de Falla</label>
                        <input type="datetime-local" class="form-control" id="fecha_falla_reportada" name="fecha_falla_reportada" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        <small class="form-text text-muted">Este es el momento en que el activo pasa a estatus "En Mantenimiento".</small>
                    </div>

                    <div class="form-group">
                        <label for="sintoma_falla">Síntoma / Descripción Inicial de la Falla</label>
                        <textarea class="form-control" id="sintoma_falla" name="sintoma_falla" rows="3" required>{{ old('sintoma_falla') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="responsable">Reportado Por / Responsable Inicial</label>
                        <input type="text" class="form-control" id="responsable" name="responsable" value="{{ old('responsable') }}">
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-danger btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span>
                        <span class="text">Registrar Falla e Iniciar Mantenimiento</span>
                    </button>
                    <a href="{{ route('produccion.pozos.activos.show', $activo) }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection