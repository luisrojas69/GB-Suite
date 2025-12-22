@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-xl-12">
            <div class="card shadow border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto mr-3">
                            <div class="text-center">
                                <img class="img-profile rounded-circle img-thumbnail mb-3" 
                                 src="{{ asset($paciente->foto) }}" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="h3 mb-0 text-gray-800 font-weight-bold">{{ $paciente->nombre_completo }}</h2>
                            <p class="text-muted mb-0">
                                <i class="fas fa-id-card"></i> {{ $paciente->ci }} | 
                                <i class="fas fa-briefcase"></i> {{ $paciente->des_cargo }} | 
                                <i class="fas fa-map-marker-alt"></i> {{ $paciente->des_depart }}
                            </p>
                        </div>
                        <div class="col-auto">
                            <div class="dropdown">
                                <button class="btn btn-dark dropdown-toggle shadow-sm" type="button" data-toggle="dropdown">
                                    <i class="fas fa-plus"></i> Acción Rápida
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('medicina.consultas.create', ['paciente_id' => $paciente->id]) }}"><i class="fas fa-notes-medical text-primary mr-2"></i> Nueva Consulta</a>
                                    <a class="dropdown-item" href="{{ route('medicina.accidentes.create', $paciente->id) }}"><i class="fas fa-ambulance text-danger mr-2"></i> Registrar Accidente</a>
                                    <a class="dropdown-item" href="{{ route('medicina.dotaciones.create', $paciente->id) }}"><i class="fas fa-tshirt text-success mr-2"></i> Nueva Dotación</a>

                                     <a class="dropdown-item" href="{{ route('medicina.consultas.historial', $paciente->id) }}"><i class="fas fa-notes-medical text-info mr-2"></i> Ver Historial M&eacute;dico</a>

                                    </a>
                                    <button class="dropdown-item btnEdit" data-id="{{ $paciente->id }}" title="Editar Datos Médicos"><i class="fas fa-user-edit text-warning mr-2"></i>Editar datos médicos</button>
                                    <a class="dropdown-item" href="{{ route('medicina.pacientes.index', $paciente->id) }}"><i class="fas fa-list text-secondary mr-2"></i> Ir a Lista de Pacientes</a>



                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('medicina.consultas.historial', $paciente->id) }}"><i class="fas fa-history mr-2"></i> Ver Historial Completo</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Consultas Realizadas</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_consultas'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Días desde último Accidente</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['dias_desde_accidente'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Última Dotación</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $stats['ultima_dotacion'] ? $stats['ultima_dotacion']->created_at->format('d/m/Y') : 'Sin registros' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-gradient-primary">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-stethoscope"></i> Recientes: Consultas</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th>Fecha</th>
                                <th>Diagnóstico</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paciente->consultas as $con)
                            <tr>
                                <td class="small">{{ $con->created_at->format('d/m/Y') }}</td>
                                <td class="small">{{ Str::limit($con->diagnostico_cie10, 40) }}</td>
                                <td class="small"> <a class="dropdown-item" href="{{ route('medicina.consultas.show', $con->id) }}"><i class="fas fa-eye text-success mr-2"></i> Ver detalle</a> </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-gradient-danger">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-exclamation-circle"></i> Alertas: Accidentes</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-gray-100 text-danger">
                            <tr>
                                <th>Fecha</th>
                                <th>Evento</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paciente->accidentes as $acc)
                            <tr>
                                <td class="small">{{ \Carbon\Carbon::parse($acc->fecha_hora_accidente)->format('d/m/Y') }}</td>
                                <td class="small font-weight-bold">{{ $acc->tipo_evento }}</td>
                                 <td class="small"> <a class="dropdown-item" href="{{ route('medicina.accidentes.show', $acc->id) }}"><i class="fas fa-eye text-success mr-2"></i> Ver detalle</a> </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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

</div>


@endsection


@section('scripts')
<script>
$(document).ready(function() {
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
                $('#tblPacientes').DataTable().ajax.reload(null, false); // Recarga sin resetear paginación
            }
        });
    });

});
</script>
@endsection
