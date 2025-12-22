@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="text-center">
                        <img class="img-profile rounded-circle img-thumbnail mb-3" 
                         src="{{ asset($paciente->foto) }}" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                </div>
                <div class="col">
                    <h3 class="font-weight-bold text-primary mb-0">{{ $paciente->nombre_completo }}</h3>
                    <p class="text-muted mb-0">Cédula: {{ $paciente->ci }} | Depto: {{ $paciente->des_depart }}</p>
                </div>
                <div class="col-auto">
                    <!--a href="{{ route('medicina.consultas.create', ['paciente_id' => $paciente->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nueva Consulta
                    </a-->
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
            <div class="col-md-12">
                <h3 class="font-weight-bold text-success mb-0 float-rigth">Historial M&eacute;dico</h3>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="consultas-tab" data-toggle="tab" href="#consultas" role="tab">
                <i class="fas fa-notes-medical"></i> Consultas Médicas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="accidentes-tab" data-toggle="tab" href="#accidentes" role="tab">
                <i class="fas fa-user-injured"></i> Accidentes/Incidentes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="dotaciones-tab" data-toggle="tab" href="#dotaciones" role="tab">
                <i class="fas fa-tshirt"></i> Historial EPP
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="archivos-tab" data-toggle="tab" href="#archivos" role="tab">
                <i class="fas fa-file-medical"></i> Expediente Digital
            </a>
        </li>
    </ul>

    <div class="tab-content border-left border-right border-bottom bg-white p-4 shadow-sm" id="myTabContent">
        
        <div class="tab-pane fade show active" id="consultas" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Diagnóstico</th>
                            <th>Tratamiento</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paciente->consultas as $c)
                        <tr>
                            <td>{{ $c->created_at->format('d/m/Y') }}</td>
                            <td>{{ $c->motivo_consulta }}</td>
                            <td><span class="badge badge-info">{{ $c->diagnostico_cie10 }}</span></td>
                            <td>{{ Str::limit($c->plan_tratamiento, 50) }}</td>
                            <td><a class="btn btn-sm btn-outline-primary" href="{{ route('medicina.consultas.show', $c->id) }}"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="accidentes" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-sm text-danger">
                    <thead>
                        <tr>
                            <th>Fecha Evento</th>
                            <th>Tipo</th>
                            <th>Lugar</th>
                            <th>Acciones Correctivas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paciente->accidentes as $acc)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($acc->fecha_hora_accidente)->format('d/m/Y') }}</td>
                            <td>{{ $acc->tipo_evento }}</td>
                            <td>{{ $acc->lugar_exacto }}</td>
                            <td>{{ Str::limit($acc->acciones_correctivas, 60) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Sin registros de accidentes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="dotaciones" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Implementos</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paciente->dotaciones as $d)
                        <tr>
                            <td>{{ $d->created_at->format('d/m/Y') }}</td>
                            <td>
                                {{ $d->calzado_entregado ? 'Calzado (T:'.$d->calzado_talla.') ' : '' }}
                                {{ $d->pantalon_entregado ? 'Pantalón (T:'.$d->pantalon_talla.') ' : '' }}
                            </td>
                            <td>
                                {!! $d->entregado_en_almacen 
                                    ? '<span class="text-success"><i class="fas fa-check"></i> Entregado</span>' 
                                    : '<span class="text-warning"><i class="fas fa-clock"></i> Pendiente</span>' !!}
                            </td>
                            <td><a class="btn btn-sm btn-outline-danger" href="{{ route('medicina.imprimir.ticket', $d->id) }}"><i class="fas fa-print"></i></a></td>
                             <td><a class="btn btn-sm btn-outline-success" href="{{ route('medicina.dotaciones.validar', $d->qr_token) }}"><i class="fas fa-check"></i></a></td>
                             </tr>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="archivos" role="tabpanel">
            <div class="row">
                <div class="col-md-4 border-right">
                    <h6 class="font-weight-bold">Subir Nuevo Examen</h6>
                    <form action="{{ route('medicina.pacientes.subirArchivo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                        <div class="form-group">
                            <label class="small">Descripción del documento</label>
                            <input type="text" name="nombre_archivo" class="form-control form-control-sm" placeholder="Ej: Laboratorio Pre-empleo" required>
                        </div>
                        <div class="form-group">
                            <label class="small">Archivo (PDF o Imagen)</label>
                            <input type="file" name="archivo" class="form-control-file small" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm btn-block">Subir Archivo</button>
                    </form>
                </div>

                <div class="col-md-8">
                    <h6 class="font-weight-bold">Documentos Escaneados</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Descripción</th>
                                    <th>Tipo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $archivos = DB::table('med_paciente_archivos')->where('paciente_id', $paciente->id)->orderBy('created_at', 'desc')->get();
                                @endphp
                                @forelse($archivos as $archivo)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($archivo->created_at)) }}</td>
                                    <td>{{ $archivo->nombre_archivo }}</td>
                                    <td><span class="badge badge-secondary">{{ strtoupper($archivo->tipo_archivo) }}</span></td>
                                    <td>
                                        <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" target="_blank" class="btn btn-info btn-xs">
                                            <i class="fas fa-download"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted">No hay documentos digitalizados.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
