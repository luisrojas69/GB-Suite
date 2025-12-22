@extends('layouts.app') 

@section('title', 'Programar Mantenimiento para ' . $activo->codigo)

@section('content')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Programar MP para Activo: {{ $activo->codigo }} ({{ $activo->nombre }})</h6>
        </div>
        <div class="card-body">
            
            <form action="{{ route('programacionesMP.store', $activo->id) }}" method="POST">
                @csrf
                
                {{-- Campo oculto para pasar el ID del activo --}}
                <input type="hidden" name="activo_id" value="{{ $activo->id }}">

                <div class="row">
                    <div class="col-md-6">
                        {{-- Campo Checklist (Tipo de MP) --}}
                        <div class="form-group">
                            <label for="checklist_id">Seleccionar Plan de Mantenimiento <span class="text-danger">*</span></label>
                            <select name="checklist_id" 
                                    id="checklist_id" 
                                    class="form-control @error('checklist_id') is-invalid @enderror" 
                                    required>
                                <option value="">Seleccione un MP disponible...</option>
                                @foreach ($checklists as $checklist)
                                    <option value="{{ $checklist->id }}" 
                                            {{ old('checklist_id') == $checklist->id ? 'selected' : '' }}>
                                        {{ $checklist->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('checklist_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="text-info mt-3 mt-md-0">Detalles de la Meta ({{ $activo->unidad_medida }})</h5>
                        
                        <p class="text-muted small">El MP se activará por la meta alcanzada primero (lectura o fecha).</p>

                        {{-- Campo Próximo Valor Lectura (Basado en Uso: KM/HRS) --}}
                        <div class="form-group">
                            <label for="proximo_valor_lectura">Próximo Valor Meta ({{ $activo->unidad_medida }})</label>
                            <input type="number" 
                                name="proximo_valor_lectura" 
                                id="proximo_valor_lectura" 
                                class="form-control @error('proximo_valor_lectura') is-invalid @enderror" 
                                value="{{ old('proximo_valor_lectura') }}" 
                                min="{{ $activo->lectura_actual + 1 }}">
                            <small class="form-text text-muted">Lectura actual: {{ number_format($activo->lectura_actual, 0) }} {{ $activo->unidad_medida }}. La meta debe ser mayor.</small>
                            @error('proximo_valor_lectura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo Próxima Fecha Mantenimiento (Basado en Tiempo) --}}
                        <div class="form-group">
                            <label for="proxima_fecha_mantenimiento">Próxima Fecha de Mantenimiento (Opcional)</label>
                            <input type="date" 
                                name="proxima_fecha_mantenimiento" 
                                id="proxima_fecha_mantenimiento" 
                                class="form-control @error('proxima_fecha_mantenimiento') is-invalid @enderror" 
                                value="{{ old('proxima_fecha_mantenimiento') }}">
                            @error('proxima_fecha_mantenimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('activos.show', $activo->id) }}" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-calendar-check"></i> Programar MP
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection