@php
    $tipos_activo = ['Tractor', 'Camión', 'Camioneta', 'Moto', 'Cosechadora', 'Zorra', 'Otro'];
    $estados_operativos = ['Operativo', 'En Mantenimiento', 'Fuera de Servicio', 'Desincorporado'];
    $unidades_medida = ['KM', 'HRS'];
    $lectura_min = ($action == 'edit') ? $activo->lectura_actual : 0;
@endphp

<style>
    .form-section-title { border-left: 4px solid #4e73df; padding-left: 15px; margin-bottom: 25px; }
    .input-group-text { background-color: #f8f9fc; border-right: none; color: #4e73df; }
    .form-control { border-left: none; }
    .form-control:focus { border-left: none; box-shadow: none; border-color: #d1d3e2; }
    .custom-file-label::after { content: "Buscar"; }
</style>

<div class="row">
    {{-- Sección 1: Datos Técnicos --}}
    <div class="col-lg-6 pr-lg-4 border-right">
        <div class="form-section-title">
            <h6 class="text-primary font-weight-bold text-uppercase small mb-1">Paso 1</h6>
            <h5 class="text-dark font-weight-bold">Identificación Técnica</h5>
        </div>

        <div class="form-group mb-4">
            <label class="font-weight-bold small">Código Interno <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-hashtag"></i></span></div>
                <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" 
                       value="{{ old('codigo', $activo->codigo) }}" placeholder="Ej: GBT-01" required>
            </div>
            @error('codigo') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="font-weight-bold small">Nombre del Equipo <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-signature"></i></span></div>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                       value="{{ old('nombre', $activo->nombre) }}" placeholder="Ej: Tractor Massey Ferguson" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold small">Placa / Matrícula</label>
                    <input type="text" name="placa" class="form-control" value="{{ old('placa', $activo->placa) }}" placeholder="A12BC3D">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold small">Tipo <span class="text-danger">*</span></label>
                    <select name="tipo" class="form-control select2" required>
                        <option value="">Seleccione...</option>
                        @foreach ($tipos_activo as $t)
                            <option value="{{ $t }}" {{ old('tipo', $activo->tipo) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Marca</label>
                    <input type="text" name="marca" class="form-control bg-light" value="{{ old('marca', $activo->marca) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Modelo</label>
                    <input type="text" name="modelo" class="form-control bg-light" value="{{ old('modelo', $activo->modelo) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- Sección 2: Operación e Imagen --}}
    <div class="col-lg-6 pl-lg-4">
        <div class="form-section-title">
            <h6 class="text-primary font-weight-bold text-uppercase small mb-1">Paso 2</h6>
            <h5 class="text-dark font-weight-bold">Uso y Asignación</h5>
        </div>

        <div class="form-group mb-4">
            <label class="font-weight-bold small">Departamento / Frente <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-users-cog"></i></span></div>
                <input type="text" name="departamento_asignado" class="form-control" 
                       value="{{ old('departamento_asignado', $activo->departamento_asignado) }}" placeholder="Ej: Cosecha III">
            </div>
        </div>

        <div class="card bg-light border-0 mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <label class="font-weight-bold small">Unidad Medida</label>
                        <select name="unidad_medida" id="u_medida" class="form-control font-weight-bold text-primary">
                            @foreach ($unidades_medida as $u)
                                <option value="{{ $u }}" {{ old('unidad_medida', $activo->unidad_medida) == $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-7">
                        <label class="font-weight-bold small">Lectura <span id="u_label">{{ old('unidad_medida', $activo->unidad_medida ?? 'HRS') }}</span> <span class="text-danger">*</span></label>
                        <input type="number" name="lectura_actual" class="form-control form-control-lg font-weight-bold" 
                               value="{{ old('lectura_actual', $activo->lectura_actual ?? 0) }}" min="{{ $lectura_min }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold small">Fecha Adquisición</label>
                    <input type="date" name="fecha_adquisicion" class="form-control" value="{{ old('fecha_adquisicion', $activo->fecha_adquisicion ? $activo->fecha_adquisicion->format('Y-m-d') : '') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold small">Estado Inicial</label>
                    <select name="estado_operativo" class="form-control border-left-success">
                        @foreach ($estados_operativos as $e)
                            <option value="{{ $e }}" {{ old('estado_operativo', $activo->estado_operativo) == $e ? 'selected' : '' }}
                                @if ($action == 'create' && $e != 'Operativo') disabled @endif>
                                {{ $e }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mt-3">
            <label class="font-weight-bold small">Fotografía del Activo</label>
            <div class="custom-file">
                <input type="file" name="imagen" class="custom-file-input" id="customFile" accept="image/*">
                <label class="custom-file-label" for="customFile">Elegir archivo...</label>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('u_medida').addEventListener('change', function() {
        document.getElementById('u_label').innerText = this.value;
    });

    // Mostrar nombre del archivo en el input
    document.querySelector('.custom-file-input').addEventListener('change', function(e){
        var fileName = document.getElementById("customFile").files[0].name;
        var nextSibling = e.target.nextElementSibling
        nextSibling.innerText = fileName
    })
</script>