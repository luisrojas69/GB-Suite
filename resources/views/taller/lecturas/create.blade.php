@extends('layouts.app') 

@section('title', 'Registro de Lectura de Uso de Activo')

@section('content')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Registro de Nueva Lectura de Uso (KM / HRS)</h6>
        </div>
        <div class="card-body">
            
            <form action="{{ route('lecturas.store') }}" method="POST">
                @csrf
                
                <h5 class="text-info mb-4">Detalles del Registro</h5>

                <div class="row">
                    {{-- Columna 1: Selección del Activo y Fecha --}}
                    <div class="col-md-6">
                        
                        {{-- Campo Activo --}}
                        <div class="form-group">
                            <label for="activo_id">Seleccionar Activo <span class="text-danger">*</span></label>
                            <select name="activo_id" 
                                    id="activo_id" 
                                    class="form-control @error('activo_id') is-invalid @enderror" 
                                    required>
                                <option value="">Seleccione un Activo (Solo Operativos)</option>
                                @forelse ($activos as $activo)
                                    {{-- Lógica de Selección Automática --}}
                                    @php
                                        // Preselección si viene por URL (activo_id_url) O si falló la validación (old('activo_id'))
                                        $isSelected = (isset($activo_id_url) && $activo_id_url == $activo->id) || old('activo_id') == $activo->id;
                                    @endphp
                                    
                                    <option value="{{ $activo->id }}" 
                                            data-unidad-medida="{{ $activo->unidad_medida }}"
                                            data-lectura-actual="{{ $activo->lectura_actual }}"
                                            {{ $isSelected ? 'selected' : '' }}>
                                        {{ $activo->codigo }} - {{ $activo->nombre }} ({{ $activo->unidad_medida }})
                                    </option>
                                @empty
                                    <option value="" disabled>No hay activos operativos disponibles.</option>
                                @endforelse
                            </select>
                            @error('activo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Campo Fecha de la Lectura --}}
                        <div class="form-group">
                            <label for="fecha_lectura">Fecha de la Lectura <span class="text-danger">*</span></label>
                            <input type="date" 
                                   name="fecha_lectura" 
                                   id="fecha_lectura" 
                                   class="form-control @error('fecha_lectura') is-invalid @enderror" 
                                   value="{{ old('fecha_lectura', now()->toDateString()) }}" 
                                   max="{{ now()->toDateString() }}"
                                   required>
                            @error('fecha_lectura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Columna 2: Valor y Observaciones --}}
                    <div class="col-md-6">
                        
                        {{-- Campo Valor de la Lectura --}}
                        <div class="form-group">
                            <label for="valor_lectura">Valor de la Lectura (<span id="unidad_label">KM/HRS</span>) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="valor_lectura" 
                                   id="valor_lectura" 
                                   class="form-control @error('valor_lectura') is-invalid @enderror" 
                                   value="{{ old('valor_lectura') }}" 
                                   min="0" 
                                   required>
                            <small class="form-text text-muted" id="lectura_info">Lectura actual: N/A</small>
                            @error('valor_lectura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo Observaciones --}}
                        <div class="form-group">
                            <label for="observaciones">Observaciones (Opcional)</label>
                            <textarea name="observaciones" 
                                      id="observaciones" 
                                      class="form-control @error('observaciones') is-invalid @enderror" 
                                      rows="2">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Usuario Registrador (Informativo) --}}
                        <div class="form-group">
                            <label>Registrador</label>
                            <p class="form-control-static text-muted">Será asignado a **{{ auth()->user()->name." ".auth()->user()->last_name  ?? 'Usuario Autenticado' }}**</p>
                            <small class="form-text text-muted">Esto usa el usuario que está cargando la lectura.</small>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('activos.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Registrar Lectura
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
    const valorLectura = document.getElementById('valor_lectura'); 
    const lecturaInfo = document.getElementById('lectura_info');

    function updateLecturaFields() {
        const selectedOption = activoSelect.options[activoSelect.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            const unidad = selectedOption.getAttribute('data-unidad-medida') || 'KM/HRS';
            // Formatear el valor actual para mostrarlo de forma legible
            const lecturaActualRaw = selectedOption.getAttribute('data-lectura-actual');
            const lecturaActualFormatted = Number(lecturaActualRaw).toLocaleString('es-ES');
            
            unidadLabel.textContent = unidad;
            lecturaInfo.textContent = `Lectura actual del sistema: ${lecturaActualFormatted} ${unidad}. La nueva lectura debe ser mayor o igual.`;
            
            // Establecer el mínimo para la validación (se usa el valor RAW sin formato)
            valorLectura.setAttribute('min', lecturaActualRaw); 
            
            if (valorLectura.value === '') {
                // Opcional: precargar con la lectura actual si está vacío
                valorLectura.value = lecturaActualRaw;
            }
        } else {
            unidadLabel.textContent = 'KM/HRS';
            lecturaInfo.textContent = 'Lectura actual: N/A';
            valorLectura.removeAttribute('min');
            valorLectura.value = '';
        }
    }

            activoSelect.addEventListener('change', updateLecturaFields);
            updateLecturaFields(); 
        // Si hay un valor previo seleccionado (por old() en caso de error), 
            // asegura que los campos de lectura se inicialicen correctamente.
            if (activoSelect.value) {
                updateLecturaFields();
            }
            
            activoSelect.addEventListener('change', updateLecturaFields);
            
            // Si no había un valor seleccionado por old(), updateLecturaFields() se llamará al final.
            // updateLecturaFields(); // <-- Si no usas la condición de arriba, puedes dejar esta como estaba

});
</script>
@endpush