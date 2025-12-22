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
        <h1 class="h3 mb-0 text-gray-800">Atención Médica Digital</h1>
        <a href="{{ route('medicina.pacientes.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Volver al Listado
        </a>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-profile rounded-circle img-thumbnail mb-3" 
                             src="{{ asset($paciente->foto) }}" style="width: 100px; height: 100px; object-fit: cover;">
                        <h5 class="font-weight-bold text-primary mb-0">{{ $paciente->nombre_completo }}</h5>
                        <p class="text-muted small">{{ $paciente->des_cargo }}</p>
                    </div>
                    <div class="mt-3 small">
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span class="font-weight-bold">Ficha:</span> <span>{{ $paciente->cod_emp }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span class="font-weight-bold">Edad:</span> <span>{{ \Carbon\Carbon::parse($paciente->fecha_nac)->age }} años</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span class="font-weight-bold">Tipo Sangre:</span> <span class="badge badge-danger">{{ $paciente->tipo_sangre ?? 'N/P' }}</span>
                        </div>
                        <div class="mt-2 text-center p-2 bg-light rounded">
                            <strong class="text-xs">ALERGIAS:</strong><br>
                            <span class="text-danger font-weight-bold">{{ $paciente->alergias ?? 'NINGUNA CONOCIDA' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history"></i> Historial Reciente</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($historial as $h)
                        <li class="list-group-item small">
                            <div class="d-flex justify-content-between">
                                <span class="text-primary font-weight-bold">{{ $h->created_at->format('d/m/Y') }}</span>
                                <span class="badge badge-secondary">{{ $h->motivo_consulta }}</span>
                            </div>
                            <div class="text-muted mt-1">{{ Str::limit($h->diagnostico_cie10, 50) }}</div>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted italic">Sin consultas previas en el sistema</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <form action="{{ route('medicina.consultas.store') }}" method="POST" id="formConsulta">
                @csrf
                <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
                        <h6 class="m-0 font-weight-bold text-white">Registro de Consulta / Emergencia</h6>
                        <span class="badge badge-light">Fecha: {{ date('d/m/Y') }}</span>
                    </div>
                    
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="font-weight-bold small text-uppercase">Motivo de Atención</label>
                                <select class="form-control" name="motivo_consulta" required>
                                    <option value="">Seleccione...</option>
                                    <option>Enfermedad Común</option>
                                    <option>Accidente Laboral</option>
                                    <option>Control Médico Interno</option>
                                    <option>Evaluación Ocupacional</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold small text-uppercase">Diagnóstico (CIE-10)</label>
                                <input type="text" class="form-control" name="diagnostico_cie10" placeholder="Ej: J00 - Rinofaringitis" required>
                            </div>
                        </div>

                        <div class="row bg-light p-3 rounded mb-4">
                            <div class="col-md-3">
                                <label class="small font-weight-bold">Tensión Art. (mmHg)</label>
                                <input type="text" class="form-control form-control-sm" name="tension_arterial" placeholder="120/80">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold">Frec. Card. (bpm)</label>
                                <input type="number" class="form-control form-control-sm" name="frecuencia_cardiaca">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold">Temp. (°C)</label>
                                <input type="number" step="0.1" class="form-control form-control-sm" name="temperatura">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold">Sat. O2 (%)</label>
                                <input type="number" class="form-control form-control-sm" name="saturacion_oxigeno">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-uppercase">Anamnesis (Relato y Antecedentes)</label>
                            <textarea class="form-control border-left-info" name="anamnesis" rows="3" required placeholder="¿Qué refiere el paciente?"></textarea>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-uppercase">Examen Físico / Hallazgos</label>
                            <textarea class="form-control" name="examen_fisico" rows="2"></textarea>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-uppercase text-success">Plan de Tratamiento / Indicaciones</label>
                            <textarea class="form-control border-left-success" name="plan_tratamiento" rows="3" required placeholder="Medicamentos, dosis, recomendaciones..."></textarea>
                        </div>

                        <div class="row border-top pt-3">
                            <div class="col-md-6">
                                <label class="font-weight-bold small">Aptitud Laboral Post-Consulta</label>
                                <select class="form-control border-left-warning" name="aptitud">
                                    <option value="Apto">Apto - Reincorporación inmediata</option>
                                    <option value="Apto con Restricción">Apto con Restricciones Temporales</option>
                                    <option value="No Apto">No Apto - Requiere Reposo / Traslado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="font-weight-bold small">¿Genera Reposo?</label>
                                <select class="form-control" name="genera_reposo" id="genera_reposo">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="div_dias" style="display:none;">
                                <label class="font-weight-bold small">Días</label>
                                <input type="number" class="form-control" name="dias_reposo" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-gray-100 text-right">
                        <button type="submit" class="btn btn-success shadow-sm btn-icon-split" id="btnFinalizar">
                            <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                            <span class="text">Finalizar y Guardar Atención</span>
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
    // Mostrar/Ocultar campo de días de reposo
    $('#genera_reposo').change(function() {
        if($(this).val() == '1') {
            $('#div_dias').fadeIn();
        } else {
            $('#div_dias').fadeOut();
            $('input[name="dias_reposo"]').val(0);
        }
    });

    // Confirmación antes de guardar
    $('#formConsulta').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Finalizar consulta?',
            text: "Se guardará el registro y se cerrará la atención del paciente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Sí, finalizar',
            cancelButtonText: 'Revisar'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});
</script>
@endsection