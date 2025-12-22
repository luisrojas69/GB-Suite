@extends('layouts.app') 

@section('title', 'Crear Nueva Orden de Servicio')

@section('content')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Registro de Nueva Orden de Servicio</h6>
        </div>
        <div class="card-body">
            
            <form action="{{ route('ordenes.store') }}" method="POST">
                @csrf
                
                <h5 class="text-danger mb-4">Detalles del Activo y Solicitud</h5>

                <div class="row">
                    {{-- Columna 1: Selección del Activo y Lectura --}}
                    <div class="col-md-6">
                        
                        {{-- Campo Activo --}}
                        <div class="form-group">
                            <label for="activo_id">Activo a Intervenir <span class="text-danger">*</span></label>
                            <select name="activo_id" 
                                    id="activo_id" 
                                    class="form-control @error('activo_id') is-invalid @enderror" 
                                    required>
                                <option value="">Seleccione un Activo (Solo Operativos)</option>
                                @forelse ($activos as $activo)
                                    <option value="{{ $activo->id }}" 
                                            data-unidad-medida="{{ $activo->unidad_medida }}"
                                            data-lectura-actual="{{ $activo->lectura_actual }}"
                                            {{ old('activo_id') == $activo->id ? 'selected' : '' }}>
                                        {{ $activo->codigo }} - {{ $activo->nombre }}
                                    </option>
                                @empty
                                    <option value="" disabled>No hay activos operativos disponibles.</option>
                                @endforelse
                            </select>
                            @error('activo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo Lectura del Activo --}}
                        <div class="form-group">
                            <label for="lectura_inicial">Lectura de Ingreso (<span id="unidad_label">KM/HRS</span>) <span class="text-danger">*</span></label>
                            {{-- 🛑 CORREGIDO: El nombre del campo debe ser 'lectura_inicial' --}}
                            <input type="number" 
                                   name="lectura_inicial" 
                                   id="lectura_inicial" 
                                   class="form-control @error('lectura_inicial') is-invalid @enderror" 
                                   value="{{ old('lectura_inicial') }}" 
                                   min="0" 
                                   required>
                            <small class="form-text text-muted" id="lectura_info">Lectura actual: N/A</small>
                            @error('lectura_inicial')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Columna 2: Tipo de Servicio y Fechas --}}
                    <div class="col-md-6">
                        
                        {{-- Campo Tipo de Orden (tipo_servicio) --}}
                        <div class="form-group">
                            <label for="tipo_servicio">Tipo de Servicio <span class="text-danger">*</span></label>
                            {{-- 🛑 CORREGIDO: El nombre del campo debe ser 'tipo_servicio' --}}
                            <select name="tipo_servicio" 
                                    id="tipo_servicio" 
                                    class="form-control @error('tipo_servicio') is-invalid @enderror" 
                                    required>
                                <option value="">Seleccione...</option>
                                {{-- 🛑 CORREGIDO: Solo se permiten Preventivo y Correctivo según la migración --}}
                                <option value="Correctivo" {{ old('tipo_servicio') == 'Correctivo' ? 'selected' : '' }}>Correctivo (Por Falla)</option>
                                <option value="Preventivo" {{ old('tipo_servicio') == 'Preventivo' ? 'selected' : '' }}>Preventivo (Programado)</option>
                            </select>
                            @error('tipo_servicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo ID Solicitante --}}
                        <div class="form-group">
                            <label>Solicitante</label>
                            <p class="form-control-static text-muted">Será asignado a **{{ auth()->user()->name ?? 'Usuario Autenticado' }}**</p>
                            <small class="form-text text-muted">Esto usa el usuario que está abriendo la orden.</small>
                        </div>
                        
                        {{-- Campo Fecha Solicitud --}}
                        <div class="form-group">
                            <label for="fecha_solicitud">Fecha de Solicitud/Apertura <span class="text-danger">*</span></label>
                            <input type="date" 
                                   name="fecha_solicitud" 
                                   id="fecha_solicitud" 
                                   class="form-control @error('fecha_solicitud') is-invalid @enderror" 
                                   value="{{ old('fecha_solicitud', now()->toDateString()) }}" 
                                   required>
                            @error('fecha_solicitud')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <h5 class="text-danger mt-4 mb-3">Descripción de la Falla/Trabajo Solicitado</h5>
                
                {{-- Campo Descripción de la Falla/Trabajo Solicitado --}}
                <div class="form-group">
                    <label for="descripcion_falla">Descripción (Falla o Trabajo de MP) <span class="text-danger">*</span></label>
                    <textarea name="descripcion_falla" 
                              id="descripcion_falla" 
                              class="form-control @error('descripcion_falla') is-invalid @enderror" 
                              rows="4" 
                              placeholder="Describa claramente la falla del activo o especifique el Plan de Mantenimiento Preventivo (MP)."
                              required>{{ old('descripcion_falla') }}</textarea>
                    @error('descripcion_falla')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('ordenes.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-wrench"></i> Abrir Orden de Servicio
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const activoSelect = document.getElementById('activo_id');
    const unidadLabel = document.getElementById('unidad_label');
    // 🛑 CORREGIDO: Usamos el ID correcto 'lectura_inicial'
    const lecturaInicial = document.getElementById('lectura_inicial'); 
    const lecturaInfo = document.getElementById('lectura_info');

    function updateLecturaFields() {
        const selectedOption = activoSelect.options[activoSelect.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            const unidad = selectedOption.getAttribute('data-unidad-medida') || 'KM/HRS';
            const lecturaActual = selectedOption.getAttribute('data-lectura-actual');
            
            unidadLabel.textContent = unidad;
            lecturaInfo.textContent = `Lectura actual del sistema: ${lecturaActual} ${unidad}`;
            
            lecturaInicial.setAttribute('min', lecturaActual); 
            
            if (lecturaInicial.value === '') {
                lecturaInicial.value = lecturaActual;
            }
        } else {
            unidadLabel.textContent = 'KM/HRS';
            lecturaInfo.textContent = 'Lectura actual: N/A';
            lecturaInicial.removeAttribute('min');
            lecturaInicial.value = '';
        }
    }

    activoSelect.addEventListener('change', updateLecturaFields);
    updateLecturaFields(); 
});
</script>
@endpush