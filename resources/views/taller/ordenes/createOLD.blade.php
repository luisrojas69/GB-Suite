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
                                {{-- El controlador solo pasa activos operativos --}}
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
                            <label for="lectura_ingreso">Lectura de Ingreso (<span id="unidad_label">KM/HRS</span>) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="lectura_ingreso" 
                                   id="lectura_ingreso" 
                                   class="form-control @error('lectura_ingreso') is-invalid @enderror" 
                                   value="{{ old('lectura_ingreso') }}" 
                                   min="0" 
                                   required>
                            <small class="form-text text-muted" id="lectura_info">Lectura actual: N/A</small>
                            @error('lectura_ingreso')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Columna 2: Tipo de Servicio y Fechas --}}
                    <div class="col-md-6">
                        
                        {{-- Campo Tipo de Orden --}}
                        <div class="form-group">
                            <label for="tipo_orden">Tipo de Servicio <span class="text-danger">*</span></label>
                            <select name="tipo_orden" 
                                    id="tipo_orden" 
                                    class="form-control @error('tipo_orden') is-invalid @enderror" 
                                    required>
                                <option value="">Seleccione...</option>
                                <option value="Reparacion" {{ old('tipo_orden') == 'Reparacion' ? 'selected' : '' }}>Reparación/Falla</option>
                                <option value="Mantenimiento" {{ old('tipo_orden') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento Preventivo (MP)</option>
                                <option value="Inspeccion" {{ old('tipo_orden') == 'Inspeccion' ? 'selected' : '' }}>Inspección/Diagnóstico</option>
                            </select>
                            @error('tipo_orden')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo Fecha Solicitud --}}
                        <div class="form-group">
                            <label for="fecha_solicitud">Fecha de Solicitud <span class="text-danger">*</span></label>
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

                        {{-- Campo ID Solicitante (Asumimos que el usuario autenticado lo proveerá el controlador en el Store) --}}
                        <div class="form-group">
                            <label>Solicitante</label>
                            {{-- Se asume que el ID del solicitante será el usuario autenticado (Auth::id()) o se obtendrá de otra variable si se pasa --}}
                            <p class="form-control-static text-muted">Usuario actual (será asignado en el servidor)</p>
                        </div>
                    </div>
                </div>

                <h5 class="text-danger mt-4 mb-3">Descripción del Problema</h5>
                
                {{-- Campo Descripción de la Falla/Trabajo Solicitado --}}
                <div class="form-group">
                    <label for="descripcion_falla">Descripción de la Falla/Trabajo Solicitado <span class="text-danger">*</span></label>
                    <textarea name="descripcion_falla" 
                              id="descripcion_falla" 
                              class="form-control @error('descripcion_falla') is-invalid @enderror" 
                              rows="4" 
                              placeholder="Describa claramente la falla o el trabajo solicitado por el usuario/departamento."
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
    const lecturaIngreso = document.getElementById('lectura_ingreso');
    const lecturaInfo = document.getElementById('lectura_info');

    function updateLecturaFields() {
        const selectedOption = activoSelect.options[activoSelect.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            const unidad = selectedOption.getAttribute('data-unidad-medida') || 'KM/HRS';
            const lecturaActual = selectedOption.getAttribute('data-lectura-actual');
            
            unidadLabel.textContent = unidad;
            lecturaInfo.textContent = `Lectura actual del sistema: ${lecturaActual} ${unidad}`;
            // Establecer el mínimo para la validación
            lecturaIngreso.setAttribute('min', lecturaActual); 
            
            // Si el campo de lectura de ingreso está vacío, precargar con la lectura actual del sistema
            if (lecturaIngreso.value === '') {
                lecturaIngreso.value = lecturaActual;
            }
        } else {
            // Valores por defecto si no se selecciona un activo
            unidadLabel.textContent = 'KM/HRS';
            lecturaInfo.textContent = 'Lectura actual: N/A';
            lecturaIngreso.removeAttribute('min');
            lecturaIngreso.value = '';
        }
    }

    // Ejecutar al cargar y al cambiar
    activoSelect.addEventListener('change', updateLecturaFields);
    updateLecturaFields(); // Ejecutar al cargar la página para precargar valores si hay old()

});
</script>
@endpush