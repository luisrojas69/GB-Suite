{{-- 
    Este partial requiere las siguientes variables:
    $activo: Objeto Activo (para bindear datos)
    $action: 'create' o 'edit' (para lógica condicional)
--}}

@php
    // Definiciones de ENUMs basadas en tu migración
    $tipos_activo = ['Tractor', 'Camión', 'Camioneta', 'Moto', 'Cosechadora', 'Zorra', 'Otro'];
    $estados_operativos = ['Operativo', 'En Mantenimiento', 'Fuera de Servicio', 'Desincorporado'];
    $unidades_medida = ['KM', 'HRS'];

    // Lógica para el campo Lectura Actual (min)
    $lectura_min = ($action == 'edit') ? $activo->lectura_actual : 0;
@endphp

<div class="row">
    
    {{-- Columna 1: Identificación Básica --}}
    <div class="col-md-6">
        <h5 class="mb-3 text-info">Identificación del Vehículo/Equipo</h5>

        {{-- Campo Código --}}
        <div class="form-group">
            <label for="codigo">Código del Activo <span class="text-danger">*</span></label>
            <input type="text" 
                   name="codigo" 
                   id="codigo" 
                   class="form-control @error('codigo') is-invalid @enderror" 
                   value="{{ old('codigo', $activo->codigo ?? '') }}" 
                   placeholder="Ej: GBT01"
                   required>
            @error('codigo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Nombre --}}
        <div class="form-group">
            <label for="nombre">Nombre (Descripción corta) <span class="text-danger">*</span></label>
            <input type="text" 
                   name="nombre" 
                   id="nombre" 
                   class="form-control @error('nombre') is-invalid @enderror" 
                   value="{{ old('nombre', $activo->nombre ?? '') }}" 
                   required>
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        {{-- Campo Placa --}}
        <div class="form-group">
            <label for="placa">Placa</label>
            <input type="text" 
                   name="placa" 
                   id="placa" 
                   class="form-control @error('placa') is-invalid @enderror" 
                   value="{{ old('placa', $activo->placa ?? '') }}" 
                   placeholder="Solo para vehículos registrados">
            @error('placa')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Tipo (ENUM) --}}
        <div class="form-group">
            <label for="tipo">Tipo de Activo <span class="text-danger">*</span></label>
            <select name="tipo" 
                    id="tipo" 
                    class="form-control @error('tipo') is-invalid @enderror" 
                    required>
                <option value="">Seleccione el Tipo</option>
                @foreach ($tipos_activo as $tipo)
                    <option value="{{ $tipo }}" 
                            {{ old('tipo', $activo->tipo ?? '') == $tipo ? 'selected' : '' }}>
                        {{ $tipo }}
                    </option>
                @endforeach
            </select>
            @error('tipo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        {{-- Campo Marca --}}
        <div class="form-group">
            <label for="marca">Marca</label>
            <input type="text" 
                   name="marca" 
                   id="marca" 
                   class="form-control @error('marca') is-invalid @enderror" 
                   value="{{ old('marca', $activo->marca ?? '') }}">
            @error('marca')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        {{-- Campo Modelo --}}
        <div class="form-group">
            <label for="modelo">Modelo</label>
            <input type="text" 
                   name="modelo" 
                   id="modelo" 
                   class="form-control @error('modelo') is-invalid @enderror" 
                   value="{{ old('modelo', $activo->modelo ?? '') }}">
            @error('modelo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
    </div>

    {{-- Columna 2: Uso, Estado y Adquisición --}}
    <div class="col-md-6">
        <h5 class="mb-3 text-info">Uso y Metadatos</h5>
        
        {{-- Campo Departamento Asignado --}}
        <div class="form-group">
            <label for="departamento_asignado">Departamento Asignado <span class="text-danger">*</span></label>
            <input type="text" 
                   name="departamento_asignado" 
                   id="departamento_asignado" 
                   class="form-control @error('departamento_asignado') is-invalid @enderror" 
                   value="{{ old('departamento_asignado', $activo->departamento_asignado ?? '') }}" 
                   placeholder="Ej: Cosecha, Administración"
                   required>
            @error('departamento_asignado')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Unidad de Medida (ENUM) --}}
        <div class="form-group">
            <label for="unidad_medida">Unidad de Medida <span class="text-danger">*</span></label>
            <select name="unidad_medida" 
                    id="unidad_medida" 
                    class="form-control @error('unidad_medida') is-invalid @enderror" 
                    required>
                <option value="">Seleccione</option>
                @foreach ($unidades_medida as $unidad)
                    <option value="{{ $unidad }}" 
                            {{ old('unidad_medida', $activo->unidad_medida ?? '') == $unidad ? 'selected' : '' }}>
                        {{ $unidad }}
                    </option>
                @endforeach
            </select>
            @error('unidad_medida')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Lectura Actual --}}
        <div class="form-group">
            <label for="lectura_actual">Lectura Inicial/Actual (<span id="unidad_label">{{ old('unidad_medida', $activo->unidad_medida ?? 'KM/HRS') }}</span>) <span class="text-danger">*</span></label>
            <input type="number" 
                   name="lectura_actual" 
                   id="lectura_actual" 
                   class="form-control @error('lectura_actual') is-invalid @enderror" 
                   value="{{ old('lectura_actual', $activo->lectura_actual ?? 0) }}" 
                   min="{{ $lectura_min }}"
                   required>
            @if ($action == 'edit')
                <small class="form-text text-muted">En edición, el valor no puede ser menor a la lectura actual ({{ number_format($lectura_min) }}) para proteger la secuencia de lecturas.</small>
            @else
                <small class="form-text text-muted">Ingrese la lectura inicial (kilometraje u horas).</small>
            @endif
            @error('lectura_actual')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <script>
            // Script básico para actualizar la etiqueta de la unidad de medida
            document.getElementById('unidad_medida').addEventListener('change', function() {
                document.getElementById('unidad_label').innerText = this.value || 'KM/HRS';
            });
        </script>
        
        {{-- Campo Fecha Adquisición --}}
        <div class="form-group">
            <label for="fecha_adquisicion">Fecha de Adquisición</label>
            <input type="date" 
                   name="fecha_adquisicion" 
                   id="fecha_adquisicion" 
                   class="form-control @error('fecha_adquisicion') is-invalid @enderror" 
                   value="{{ old('fecha_adquisicion', ($activo->fecha_adquisicion ?? null) ? \Carbon\Carbon::parse($activo->fecha_adquisicion)->format('Y-m-d') : '') }}">
            @error('fecha_adquisicion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        {{-- Campo Estado Operativo (ENUM) --}}
        <div class="form-group">
            <label for="estado_operativo">Estado Operativo <span class="text-danger">*</span></label>
            <select name="estado_operativo" 
                    id="estado_operativo" 
                    class="form-control @error('estado_operativo') is-invalid @enderror" 
                    required>
                <option value="">Seleccione el Estado</option>
                @foreach ($estados_operativos as $estado)
                    <option value="{{ $estado }}" 
                            {{ old('estado_operativo', $activo->estado_operativo ?? 'Operativo') == $estado ? 'selected' : '' }}
                            {{-- Lógica: Solo en la creación (create), el usuario solo puede registrar como 'Operativo'. --}}
                            @if ($action == 'create' && $estado != 'Operativo') disabled @endif>
                        {{ $estado }}
                    </option>
                @endforeach
            </select>
            @error('estado_operativo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    </div>
</div>