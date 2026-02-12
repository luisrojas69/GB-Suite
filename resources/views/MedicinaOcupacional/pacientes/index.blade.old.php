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
        <h1 class="h3 mb-0 text-gray-800">Control de Pacientes (Personal)</h1>
        <div class="btn-group shadow-sm">
            <button id="btnSync" class="btn btn-sm btn-primary mr-2">
                <span class="icon text-white-50"><i class="fas fa-sync"></i></span>
                <span class="text">Sincronizar con Profit</span>
            </button>
            <button type="button" class="btn btn-sm btn-success mr-2" id="btnExportExcel">
                <i class="fas fa-file-excel"></i> Exportar Listado
            </button>
            <button type="button" class="btn btn-sm btn-info mr-2" id="btnExportTallas">
                <i class="fas fa-tshirt"></i> Reporte de Tallas
            </button>
            <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                    <i class="fas fa-file-medical"></i> Reportes Especializados
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                    <a class="dropdown-item" href="{{ route('medicina.reportes.morbilidad') }}" target="_blank"><i class="fas fa-chart-line mr-2 text-primary"></i> Morbilidad Mensual</a>
                    <a class="dropdown-item" href="{{ route('medicina.reportes.accidentalidad') }}" target="_blank"><i class="fas fa-ambulance mr-2 text-danger"></i> Accidentalidad</a>
                    <a class="dropdown-item" href="{{ route('medicina.reportes.vigilancia') }}" target="_blank"><i class="fas fa-eye mr-2 text-warning"></i> Vigilancia Epidemiológica</a>
                </div>
            </div>
        </div>
    </div>

            {{-- CARDS INFORMATIVOS --}}
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Personal</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="card-total-pacientes">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Estatus Críticos (Patologías)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="card-criticos">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-pills fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Discapacidad / Limitación</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="card-discapacidad">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wheelchair fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Promedio de Edad</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="card-promedio-edad">0 años</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Personal Médico</h6>
        </div>

        {{-- TABLA DE PACIENTES --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm hover stripe" id="tblPacientes" width="100%" cellspacing="0">
                    <thead class="bg-gray-100">
    {{--                     <tr>
                            <th>Ficha</th>
                            <th>Cédula</th>
                            <th>Nombre Completo</th>
                            <th>Departamento</th>
                            <th>Cargo</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr> --}}

                        <tr>
                            <th>Personal</th> 
                            <th>Cédula</th>
                            <th>Edad</th>    
                            <th>Riesgos</th>  
                            <th>Departamento</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>       
                    </thead>
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    
    //Botones de Exportacion
    $('#btnExportExcel').click(function() {
        window.location.href = "{{ route('medicina.pacientes.export.excel') }}";
    });

    $('#btnExportTallas').click(function() {
        window.location.href = "{{ route('medicina.pacientes.export.tallas') }}";
    });

    // Inicializar DataTable
    let table = $('#tblPacientes').DataTable({
        ajax: "{{ route('medicina.pacientes.listado') }}",
        columns: [
            { 
                data: 'nombre_completo',
                render: function(data, type, row) {
                    let avatar = row.sexo === 'F' ? 'avatar_female.png' : 'avatar_male.png';
                return `
                    <div class="d-flex align-items-center">
                        <img src="/assets/img/${avatar}" class="rounded-circle mr-2" width="30" height="30">
                        <div>
                            <div class="small font-weight-bold text-primary">${row.cod_emp}</div>
                            <div class="text-dark" style="font-size: 0.85rem;">${data}</div>
                        </div>
                    </div>`;
            }
        },

        { 
            data: 'ci',
            render: function(data) {
                if(!data) return '-';
                return `<small class="text-muted">${data}</small>`;
            }
        },
        { 
            data: 'fecha_nac',
            render: function(data) {
                if(!data) return '-';
                let hoy = new Date();
                let cumple = new Date(data);
                let edad = hoy.getFullYear() - cumple.getFullYear();
                return `<span class="badge badge-light text-dark border">${edad} años</span>`;
            }
        },
        {
            data: null,
            render: function(data, type, row) {
                let badges = '';
                // Riesgo Sangre
                if(row.tipo_sangre) 
                    badges += `<span class="badge badge-warning text-dark mr-1" title="Tipo: ${row.tipo_sangre}">🩸 ${row.tipo_sangre}</span>`;
                
                // Riesgo Alergias
                if(row.alergias && row.alergias.trim() !== '') 
                    badges += `<span class="badge badge-danger mr-1" title="${row.alergias}">⚠️</span>`;
                
                // Riesgo Enfermedad de Base
                if(row.enfermedades_base && row.enfermedades_base.trim() !== '') 
                    badges += `<span class="badge badge-dark mr-1" title="${row.enfermedades_base}">🧬</span>`;
                
                return badges || '<small class="text-muted">N/A</small>';
            }
        },
        { 
            data: 'des_depart',
            render: function(data) {
                if(!data) return '-';
                return `<small class="text-muted">${data}</small>`;
            }
        },
         { 
                data: 'status',
                render: function(data) {
                    // Limpiamos espacios y aseguramos mayúsculas
                    let status = data ? data.trim().toUpperCase() : '';
                    
                    let badge = '';
                    let texto = '';

                    if (status = 'A') {
                        badge = 'badge-success';
                        texto = 'Activo';
                    } else if (status === 'V') {
                        badge = 'badge-info';
                        texto = 'Vacaciones';
                    } else {
                        badge = 'badge-danger';
                        texto = 'Egreso';
                    }

                    return `<span class="badge ${badge}">${texto}</span>`;
                }
            },
            {
                data: 'id',
                render: function(data) {
                    return `
                <div class="d-flex justify-content-around"> 
                    <div class="col-auto">
                        <div class="dropdown no-arrow">
                            <a class="btn btn-sm btn-light btn-primary dropdown-toggle shadow-sm" href="#" role="button" data-toggle="dropdown">
                                <i class="fa-solid fa-user"></i>
                                <i class="fa-solid fa-angle-down"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                <div class="dropdown-header ">Accesos M&eacute;dicos:</div>

                                <a class="dropdown-item" href="/medicina/paciente/${data}">
                                    <i class="fas fa-person-circle-check fa-sm fa-fw mr-2 text-body"></i> Ver Detalles
                                </a>

                                <a class="dropdown-item" href="/medicina/consultas/crear/${data}">
                                    <i class="fas fa-file-medical fa-sm fa-fw mr-2 text-success"></i> Nueva Consulta
                                </a>

                                 <a class="dropdown-item" href="/medicina/consultas/historial/${data}">
                                    <i class="fas fa-notes-medical fa-sm fa-fw mr-2 text-info"></i> Ver Historia M&eacute;dica
                                </a> 

                                <button class="dropdown-item btnEdit" data-id="${data}" title="Editar Datos Médicos">
                                    <i class="fas fa-user-edit fa-sm fa-fw mr-2 text-primary"></i> Editar datos M&eacute;dicos
                                </button>
                                

                                <div class="dropdown-divider"></div>
                                <div class="dropdown-header">Accessos SSL:</div>
                                <a class="dropdown-item" href="dotaciones/entregar/${data}">
                                    <i class="fas fa-tshirt fa-sm fa-fw mr-2 text-warning"></i> Nueva Dotaci&oacute;n
                                </a>

                                <a class="dropdown-item" href="accidentes/registrar/${data}">
                                    <i class="fas fa-ambulance fa-sm fa-fw mr-2 text-danger"></i> Registrar Accidente
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/home"><i class="fas fa-home mr-2"></i> Ir al Home</a>
                               
                            </div>
                        </div>
                    </div>

                    <div class="col-auto">
                        <div class="dropdown no-arrow">
                            <a class="btn btn-sm btn-info dropdown-toggle shadow-sm" href="#" role="button" data-toggle="dropdown">
                                <i class="fa-solid fa-file-pdf"></i>
                                <i class="fa-solid fa-angle-down"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                <div class="dropdown-header ">Certificados M&eacute;dicos:</div>

                                <a class="dropdown-item" href="/medicina/aptitud/${data}" target="_blank">
                                    <i class="fas fa-person-circle-check fa-sm fa-fw mr-2 text-warning"></i> Certificado de Aptitud
                                </a>

                                <a class="dropdown-item" href="/medicina/constancia/${data}" target="_blank">
                                    <i class="fas fa-person-walking-arrow-right fa-sm fa-fw mr-2 text-info"></i> Constancia de Asistencia
                                </a>

                                 <a class="dropdown-item" href="/medicina/historial/${data}" target="_blank">
                                    <i class="fas fa-virus fa-sm fa-fw mr-2 text-danger"></i> Historial Epidemiol&oacute;gico
                                </a>
                                

                                <div class="dropdown-divider"></div>
                                <div class="dropdown-header">Certificados SSL:</div>
                                <a class="dropdown-item" target="_blank" href="/medicina/epp/${data}">
                                    <i class="fas fa-user-tag fa-sm fa-fw mr-2 text-info"></i> Generador de Entrega de EPP
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/home"><i class="fas fa-home mr-2"></i> Ir al Home</a>
                               
                            </div>
                        </div>
                    </div>
                </div>

`;
                }
            }
        ],
        language: { url: "/js/lang/Spanish.json" },
        drawCallback: function(settings) {
        if(settings.json) {
            // Estos IDs deben coincidir con los de tus HTML cards
            $('#card-total-pacientes').text(settings.json.total_pacientes);
            $('#card-criticos').text(settings.json.total_criticos);
            $('#card-discapacidad').text(settings.json.total_discapacidad);
            $('#card-promedio-edad').text(settings.json.promedio_edad + ' años');
        }
    }
       
    });

    // Evento Sincronizar con SweetAlert2
    $('#btnSync').click(function() {
        Swal.fire({
            title: '¿Sincronizar con Profit?',
            text: "Se actualizarán los datos de nómina. Esto puede tardar unos segundos.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4e73df',
            confirmButtonText: 'Sí, sincronizar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: "{{ route('medicina.pacientes.sync') }}",
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}" }
                }).catch(error => {
                    Swal.showValidationMessage(`Error: ${error.responseJSON.text}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(result.value.title, result.value.text, result.value.icon);
                table.ajax.reload();
            }
        });
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
                $('#tblPacientes').DataTable().ajax.reload(null, false); // Recarga sin resetear paginación
            }
        });
    });

});
</script>

@if(session('print_id'))
<script>
    Swal.fire({
        title: '¡Consulta Guardada!',
        text: "¿Desea imprimir el récipe y la constancia médica ahora?",
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#858796',
        confirmButtonText: '<i class="fas fa-print"></i> Imprimir Reporte',
        cancelButtonText: 'Cerrar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Abrimos el PDF en una nueva pestaña
            window.open("{{ route('medicina.consultas.imprimir', session('print_id')) }}", '_blank');
        }
    });
</script>
@endif
@endsection