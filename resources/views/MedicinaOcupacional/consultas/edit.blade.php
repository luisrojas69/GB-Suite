@extends('layouts.app')

@section('content')
        {{-- Mostrar mensajes de sesión --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modificar Atención Médica</h1>
        <a href="{{ route('medicina.consultas.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Cancelar Edición
        </a>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4 border-left-info">
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-profile rounded-circle img-thumbnail mb-3" 
                             src="{{ asset($consulta->paciente->foto) }}" style="width: 100px; height: 100px; object-fit: cover;">
                        <h5 class="font-weight-bold text-info mb-0">{{ $consulta->paciente->nombre_completo }}</h5>
                        <p class="text-muted small">Editando Consulta del: {{ $consulta->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="mt-3 small">
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span class="font-weight-bold">Ficha:</span> <span>{{ $consulta->paciente->cod_emp }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span class="font-weight-bold">Cédula:</span> <span>{{ $consulta->paciente->cedula }}</span>
                        </div>
                        <div class="mt-2 text-center p-2 bg-light rounded">
                            <strong class="text-xs text-danger">ALERGIAS REGISTRADAS:</strong><br>
                            <span class="text-danger font-weight-bold">{{ $consulta->paciente->alergias ?? 'NINGUNA CONOCIDA' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-warning shadow">
                <i class="fas fa-exclamation-triangle"></i> 
                <small>Usted está editando un registro clínico. Todos los cambios quedarán auditados bajo su usuario.</small>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <form action="{{ route('medicina.consultas.update', $consulta->id) }}" method="POST" id="formEditConsulta">
                @csrf
                @method('PUT')

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-info">
                        <h6 class="m-0 font-weight-bold text-white">Actualizar Datos de la Consulta</h6>
                        <span class="badge badge-light">ID Registro: #{{ $consulta->id }}</span>
                    </div>
                    
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="font-weight-bold small text-uppercase">Motivo de Atención</label>
                                <select class="form-control" name="motivo_consulta" required>
                                    <option value="Enfermedad Común" {{ $consulta->motivo_consulta == 'Enfermedad Común' ? 'selected' : '' }}>Enfermedad Común</option>
                                    <option value="Accidente Laboral" {{ $consulta->motivo_consulta == 'Accidente Laboral' ? 'selected' : '' }}>Accidente Laboral</option>
                                    <option value="Control Médico Interno" {{ $consulta->motivo_consulta == 'Control Médico Interno' ? 'selected' : '' }}>Control Médico Interno</option>
                                    <option value="Evaluación Ocupacional" {{ $consulta->motivo_consulta == 'Evaluación Ocupacional' ? 'selected' : '' }}>Evaluación Ocupacional</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold small text-uppercase">Diagnóstico (CIE-10)</label>
                                <input type="text" class="form-control" name="diagnostico_cie10" value="{{ $consulta->diagnostico_cie10 }}" required>
                            </div>
                        </div>

                        <div class="row bg-light p-3 rounded mb-4">
                            <div class="col-md-3">
                                <label class="small font-weight-bold">Tensión Art.</label>
                                <input type="text" class="form-control form-control-sm" name="tension_arterial" value="{{ $consulta->tension_arterial }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold">Frec. Card.</label>
                                <input type="number" class="form-control form-control-sm" name="frecuencia_cardiaca" value="{{ $consulta->frecuencia_cardiaca }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold">Temp. (°C)</label>
                                <input type="number" step="0.1" class="form-control form-control-sm" name="temperatura" value="{{ $consulta->temperatura }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold">Sat. O2 (%)</label>
                                <input type="number" class="form-control form-control-sm" name="saturacion_oxigeno" value="{{ $consulta->saturacion_oxigeno }}">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-uppercase">Anamnesis</label>
                            <textarea class="form-control border-left-info" name="anamnesis" rows="3" required>{{ $consulta->anamnesis }}</textarea>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-uppercase">Examen Físico / Hallazgos</label>
                            <textarea class="form-control" name="examen_fisico" rows="2">{{ $consulta->examen_fisico }}</textarea>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-uppercase text-success">Plan de Tratamiento</label>
                            <textarea class="form-control border-left-success" name="plan_tratamiento" rows="3" required>{{ $consulta->plan_tratamiento }}</textarea>
                        </div>

                        <div class="row border-top pt-3">
                            <div class="col-md-6">
                                <label class="font-weight-bold small">Aptitud Laboral</label>
                                <select class="form-control" name="aptitud">
                                    <option value="Apto" {{ $consulta->aptitud == 'Apto' ? 'selected' : '' }}>Apto</option>
                                    <option value="Apto con Restricción" {{ $consulta->aptitud == 'Apto con Restricción' ? 'selected' : '' }}>Apto con Restricción</option>
                                    <option value="No Apto" {{ $consulta->aptitud == 'No Apto' ? 'selected' : '' }}>No Apto</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="font-weight-bold small">¿Genera Reposo?</label>
                                <select class="form-control" name="genera_reposo" id="genera_reposo_edit">
                                    <option value="0" {{ $consulta->genera_reposo == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $consulta->genera_reposo == 1 ? 'selected' : '' }}>Sí</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="div_dias_edit" style="{{ $consulta->genera_reposo == 1 ? '' : 'display:none;' }}">
                                <label class="font-weight-bold small">Días</label>
                                <input type="number" class="form-control" name="dias_reposo" value="{{ $consulta->dias_reposo }}">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-gray-100 text-right">
                        <button type="submit" class="btn btn-info shadow-sm btn-icon-split">
                            <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                            <span class="text">Guardar Cambios</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#genera_reposo_edit').change(function() {
        if($(this).val() == '1') {
            $('#div_dias_edit').fadeIn();
        } else {
            $('#div_dias_edit').fadeOut();
            $('input[name="dias_reposo"]').val(0);
        }
    });
});
</script>
@endsection