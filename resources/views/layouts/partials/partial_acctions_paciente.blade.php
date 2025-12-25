<div class="d-flex justify-content-around"> 
    <div class="col-auto">
        <div class="dropdown no-arrow">
            <a class="btn btn-dark dropdown-toggle shadow-sm" href="#" role="button" data-toggle="dropdown">
                <i class="fas fa-file-pdf text-danger"></i>
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

                <a class="dropdown-item" href="medicina/accidentes/registrar/${data}">
                    <i class="fas fa-ambulance fa-sm fa-fw mr-2 text-danger"></i> Registrar Accidente
                </a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="medicina/pacientes"><i class="fas fa-hospital-user mr-2"></i> Lista de Pacientes</a>
               
            </div>
        </div>
    </div>

    <div class="col-auto">
        <div class="dropdown no-arrow">
            <a class="btn btn-dark dropdown-toggle shadow-sm" href="#" role="button" data-toggle="dropdown">
                <i class="fas fa-file-pdf text-danger"></i>
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
                <a class="dropdown-item" href="medicina/pacientes"><i class="fas fa-hospital-user mr-2"></i> Lista de Pacientes</a>
               
            </div>
        </div>
    </div>
</div>