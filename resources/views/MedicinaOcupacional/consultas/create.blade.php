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
                        <div class="mt-2 text-center p-2 bg-light rounded">
                               <button class="btn btn-primary btn-sm btnEdit" data-id="{{ $paciente->id }}" title="Editar Datos Médicos">
                                <i class="fas fa-user-edit"></i> Editar Datos Medicos
                            </button>
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
                            <div class="text-muted mt-1">{{ Str::limit($h->diagnostico_cie10, 50) }} <a href="{{ route('medicina.consultas.show', $h->id) }}">Ver detalles</a></div>

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
                            <div class="col-md-4">
                                <label class="font-weight-bold small text-uppercase">Motivo de Atención</label>
                                <select class="form-control border-left-primary" name="motivo_consulta" id="motivo_consulta" required>
                                    <option value="">Seleccione...</option>
                                    <option>Enfermedad Común</option>
                                    <option>Accidente Laboral</option>
                                    <option>Control Médico Interno</option>
                                    <option value="Evaluación Ocupacional">Evaluación Ocupacional (Pre-empleo/Egreso)</option>
                                    <option value="Pre-vacacional">Evaluación Pre-vacacional</option>
                                </select>
                            </div>

                            <div class="col-md-4" id="div_retorno_vacaciones" style="display:none;">
                                <label class="font-weight-bold small text-uppercase text-primary">
                                    <i class="fas fa-calendar-alt"></i> Fecha Estimada de Retorno
                                </label>
                                <input type="date" class="form-control border-left-primary" name="fecha_retorno_vacaciones">
                                <small class="text-muted">Se usará para la alerta post-vacacional.</small>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Diagnóstico (CIE-10)</label>
                                    <select name="diagnostico_cie10" id="diagnostico_cie10" class="form-control" required>
                                        <option value="">Escriba para buscar diagnóstico...</option>
                                    </select>
                                    <small class="text-muted">Use códigos Z00-Z10 para chequeos de rutina o vacaciones.</small>
                                </div>
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

                        <div class="row border-top pt-3" id="div_aptitud">
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

<div class="modal fade" id="modalPaciente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Ficha Médica: <span id="nombrePacienteTitle"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPaciente">
                @csrf
                <input type="hidden" id="paciente_id" name="id">
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-bio-tab" data-toggle="pill" href="#tab-bio"><i class="fas fa-heartbeat"></i> Biometría</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-med-tab" data-toggle="pill" href="#tab-med"><i class="fas fa-pills"></i> Médicos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-talla-tab" data-toggle="pill" href="#tab-talla"><i class="fas fa-cut"></i> Tallas y EPP</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-bio">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Tipo de Sangre</label>
                                    <select class="form-control" name="tipo_sangre" id="tipo_sangre">
                                        <option value="">Seleccione...</option>
                                        <option value="O+">O+</option><option value="O-">O-</option>
                                        <option value="A+">A+</option><option value="A-">A-</option>
                                        <option value="B+">B+</option><option value="B-">B-</option>
                                        <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Peso (Kg)</label>
                                    <input type="number" step="0.1" class="form-control" name="peso_inicial" id="peso_inicial">
                                </div>
                                <div class="col-md-4">
                                    <label>Estatura (Cm)</label>
                                    <input type="number" class="form-control" name="estatura" id="estatura">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-med">
                            <div class="form-group">
                                <label>Alergias Conocidas</label>
                                <textarea class="form-control" name="alergias" id="alergias" rows="2" placeholder="Ej: Penicilina, polen..."></textarea>
                            </div>
                            <div class="form-group">
                                <label>Enfermedades de Base / Patologías</label>
                                <textarea class="form-control" name="enfermedades_base" id="enfermedades_base" rows="2"></textarea>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="es_zurdo" name="es_zurdo">
                                <label class="custom-control-label" for="es_zurdo">¿Es Zurdo?</label>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-talla">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Talla Camisa</label>
                                    <input type="text" class="form-control" name="talla_camisa" id="talla_camisa">
                                </div>
                                <div class="col-md-4">
                                    <label>Talla Pantalón</label>
                                    <input type="text" class="form-control" name="talla_pantalon" id="talla_pantalon">
                                </div>
                                <div class="col-md-4">
                                    <label>Calzado</label>
                                    <input type="text" class="form-control" name="talla_calzado" id="talla_calzado">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

    //Si el motivo es PreVacacional se habilita el campo de retorno
    $('#motivo_consulta').change(function() {
        if($(this).val() === 'Pre-vacacional') {
            $('#div_retorno_vacaciones').fadeIn();
            $('input[name="fecha_retorno_vacaciones"]').attr('required', true);
            $('#div_aptitud').fadeOut();
        } else {
            $('#div_retorno_vacaciones').fadeOut();
            $('input[name="fecha_retorno_vacaciones"]').attr('required', false).val('');
            $('#div_aptitud').fadeIn();
            
        }
    });



    // Abrir Modal y Cargar Datos
    $(document).on('click', '.btnEdit', function() {
        let id = $(this).data('id');
        $.get('/medicina/pacientes/'+id+'/edit', function(data) {
            $('#paciente_id').val(data.id);
            $('#nombrePacienteTitle').text(data.nombre_completo);
            $('#tipo_sangre').val(data.tipo_sangre);
            $('#peso_inicial').val(data.peso_inicial);
            $('#estatura').val(data.estatura);
            $('#alergias').val(data.alergias);
            $('#enfermedades_base').val(data.enfermedades_base);
            $('#talla_camisa').val(data.talla_camisa);
            $('#talla_pantalon').val(data.talla_pantalon);
            $('#talla_calzado').val(data.talla_calzado);
            $('#es_zurdo').prop('checked', data.es_zurdo == 1);
            
            $('#modalPaciente').modal('show');
        });
    });

        // Guardar por AJAX
    $('#formPaciente').on('submit', function(e) {
        e.preventDefault();
        let id = $('#paciente_id').val();
        let formData = $(this).serialize();

        $.ajax({
            url: `/medicina/pacientes/${id}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                $('#modalPaciente').modal('hide');
                Swal.fire('¡Guardado!', 'La ficha médica ha sido actualizada.', 'success');
                location.reload()
            }
        });
    });


    $(document).ready(function() {
        $('#diagnostico_cie10').select2({
            theme: 'bootstrap4',
            ajax: {
                url: "{{ route('medicina.buscarCie10') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            },
            minimumInputLength: 3
        });
    });


});
</script>
@endsection