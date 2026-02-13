@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Mensajes de sesión mejorados --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg border-left-success" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x mr-3"></i>
                <div>
                    <strong>¡Éxito!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-lg border-left-danger" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                <div>
                    <strong>¡Error!</strong> {{ session('error') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Header mejorado con gradiente --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body bg-gradient-primary text-white py-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="icon-circle bg-white text-primary">
                        <i class="fas fa-user-md fa-3x"></i>
                    </div>
                </div>
                <div class="col">
                    <h1 class="h2 mb-1 font-weight-bold text-white">
                        <i class="fas fa-hospital-user"></i> Control de Personal - Medicina Ocupacional
                    </h1>
                    <p class="mb-0 text-white-50">
                        <i class="fas fa-info-circle"></i> Gestión integral del personal y fichas médicas
                    </p>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-light btn-lg dropdown-toggle shadow" type="button" data-toggle="dropdown">
                            <i class="fas fa-cog"></i> Acciones
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow-lg animated--fade-in" style="min-width: 280px;">
                            <div class="dropdown-header bg-gradient-primary text-white">
                                <i class="fas fa-tools"></i> Herramientas del Sistema
                            </div>
                            <a class="dropdown-item" href="#" id="btnSync">
                                <i class="fas fa-sync-alt text-primary mr-2"></i>
                                <strong>Sincronizar con Profit</strong>
                                <small class="d-block text-muted">Actualizar datos de nómina</small>
                            </a>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-header bg-gradient-success text-white">
                                <i class="fas fa-file-export"></i> Exportaciones
                            </div>
                            <a class="dropdown-item" href="#" id="btnExportExcel">
                                <i class="fas fa-file-excel text-success mr-2"></i>
                                <strong>Listado Completo</strong>
                                <small class="d-block text-muted">Excel con todos los datos</small>
                            </a>
                            <a class="dropdown-item" href="#" id="btnExportTallas">
                                <i class="fas fa-tshirt text-info mr-2"></i>
                                <strong>Reporte de Tallas</strong>
                                <small class="d-block text-muted">Para compra de EPP</small>
                            </a>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-header bg-gradient-warning text-white">
                                <i class="fas fa-chart-line"></i> Reportes Especializados
                            </div>
                            <a class="dropdown-item" href="{{ route('medicina.reportes.morbilidad') }}" target="_blank">
                                <i class="fas fa-chart-bar text-primary mr-2"></i>
                                <strong>Morbilidad Mensual</strong>
                            </a>
                            <a class="dropdown-item" href="{{ route('medicina.reportes.accidentalidad') }}" target="_blank">
                                <i class="fas fa-ambulance text-danger mr-2"></i>
                                <strong>Accidentalidad</strong>
                            </a>
                            <a class="dropdown-item" href="{{ route('medicina.reportes.vigilancia') }}" target="_blank">
                                <i class="fas fa-eye text-warning mr-2"></i>
                                <strong>Vigilancia Epidemiológica</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cards informativos mejorados con animaciones --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body bg-gradient-primary text-white">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-2">
                                <i class="fas fa-users"></i> Total Personal
                            </div>
                            <div class="h2 mb-0 font-weight-bold" id="card-total-pacientes">
                                <span class="counter">0</span>
                            </div>
                            <small class="text-white-50 mt-1 d-block">
                                <i class="fas fa-chart-line"></i> Trabajadores activos
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white-20">
                                <i class="fas fa-users fa-3x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <small class="text-primary">
                        <i class="fas fa-info-circle"></i> Base de datos actualizada
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body bg-gradient-danger text-white">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-2">
                                <i class="fas fa-exclamation-triangle"></i> Casos Críticos
                            </div>
                            <div class="h2 mb-0 font-weight-bold" id="card-criticos">
                                <span class="counter">0</span>
                            </div>
                            <small class="text-white-50 mt-1 d-block">
                                <i class="fas fa-pills"></i> Con patologías base
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white-20">
                                <i class="fas fa-heartbeat fa-3x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <small class="text-danger">
                        <i class="fas fa-stethoscope"></i> Requieren seguimiento
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body bg-gradient-warning text-white">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-2">
                                <i class="fas fa-wheelchair"></i> Discapacidad
                            </div>
                            <div class="h2 mb-0 font-weight-bold" id="card-discapacidad">
                                <span class="counter">0</span>
                            </div>
                            <small class="text-white-50 mt-1 d-block">
                                <i class="fas fa-universal-access"></i> Con limitaciones
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white-20">
                                <i class="fas fa-wheelchair fa-3x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <small class="text-warning">
                        <i class="fas fa-hand-holding-heart"></i> Atención especial
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body bg-gradient-info text-white">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-2">
                                <i class="fas fa-birthday-cake"></i> Edad Promedio
                            </div>
                            <div class="h2 mb-0 font-weight-bold" id="card-promedio-edad">
                                <span class="counter">0</span> años
                            </div>
                            <small class="text-white-50 mt-1 d-block">
                                <i class="fas fa-chart-area"></i> Media poblacional
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white-20">
                                <i class="fas fa-user-clock fa-3x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <small class="text-info">
                        <i class="fas fa-calculator"></i> Cálculo automático
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros rápidos --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-gradient-secondary text-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-filter"></i> Filtros Rápidos
                    </h6>
                </div>
                <div class="col-auto">
                    <button class="btn btn-light btn-sm" id="btnResetFilters">
                        <i class="fas fa-redo"></i> Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body bg-light">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <button class="btn btn-outline-primary btn-block filter-btn" data-filter="all">
                        <i class="fas fa-users"></i> Todos
                    </button>
                </div>
                <div class="col-md-3 mb-2">
                    <button class="btn btn-outline-success btn-block filter-btn" data-filter="active">
                        <i class="fas fa-check-circle"></i> Solo Activos
                    </button>
                </div>
                <div class="col-md-3 mb-2">
                    <button class="btn btn-outline-danger btn-block filter-btn" data-filter="critical">
                        <i class="fas fa-heartbeat"></i> Casos Críticos
                    </button>
                </div>
                <div class="col-md-3 mb-2">
                    <button class="btn btn-outline-warning btn-block filter-btn" data-filter="disability">
                        <i class="fas fa-wheelchair"></i> Con Discapacidad
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de pacientes mejorada --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-table"></i> Listado de Personal Médico
                    </h6>
                </div>
                <div class="col-auto">
                    <span class="badge badge-primary badge-lg px-3 py-2">
                        <i class="fas fa-database"></i> <span id="total-registros">0</span> registros
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm" id="tblPacientes" width="100%" cellspacing="0">
                    <thead class="bg-gradient-primary text-white">
                        <tr>
                            <th><i class="fas fa-user"></i> Personal</th> 
                            <th><i class="fas fa-id-card"></i> Cédula</th>
                            <th><i class="fas fa-birthday-cake"></i> Edad</th>    
                            <th><i class="fas fa-shield-virus"></i> Riesgos</th>  
                            <th><i class="fas fa-building"></i> Departamento</th>
                            <th><i class="fas fa-info-circle"></i> Estatus</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>       
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal mejorado --}}
<div class="modal fade" id="modalPaciente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-md"></i> Ficha Médica: <span id="nombrePacienteTitle" class="font-weight-bold"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPaciente">
                @csrf
                <input type="hidden" id="paciente_id" name="id">
                <div class="modal-body">
                    <ul class="nav nav-pills nav-fill mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-bio-tab" data-toggle="pill" href="#tab-bio">
                                <i class="fas fa-heartbeat fa-lg"></i><br>
                                <strong>Biometría</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-med-tab" data-toggle="pill" href="#tab-med">
                                <i class="fas fa-pills fa-lg"></i><br>
                                <strong>Médicos</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-talla-tab" data-toggle="pill" href="#tab-talla">
                                <i class="fas fa-tshirt fa-lg"></i><br>
                                <strong>Tallas y EPP</strong>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-bio">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-tint text-danger"></i> Tipo de Sangre
                                        </label>
                                        <select class="form-control form-control-lg" old="tipo_sangre" name="tipo_sangre" id="tipo_sangre">
                                            <option value="">Seleccione...</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-weight text-primary"></i> Peso (Kg)
                                        </label>
                                        <input type="number" step="0.1" class="form-control form-control-lg" name="peso_inicial" id="peso_inicial">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-ruler-vertical text-info"></i> Estatura (Cm)
                                        </label>
                                        <input type="number" class="form-control form-control-lg" name="estatura" id="estatura">
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <div class="custom-control custom-switch custom-control-lg">
                                    <input type="checkbox" class="custom-control-input" id="es_zurdo" name="es_zurdo">
                                    <label class="custom-control-label font-weight-bold" for="es_zurdo">
                                        <i class="fas fa-hand-paper text-warning"></i> ¿Es Zurdo/a?
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-med">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="fas fa-allergies text-warning"></i> Alergias Conocidas
                                </label>
                                <textarea class="form-control" name="alergias" id="alergias" rows="3" 
                                          placeholder="Ej: Penicilina, polen, mariscos..."></textarea>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Especifique cualquier alergia conocida (medicamentos, alimentos, etc.)
                                </small>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="fas fa-file-medical text-danger"></i> Enfermedades de Base / Patologías
                                </label>
                                <textarea class="form-control" name="enfermedades_base" id="enfermedades_base" rows="3"
                                          placeholder="Ej: Diabetes, hipertensión, asma..."></textarea>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Indique condiciones médicas crónicas o relevantes
                                </small>
                            </div>
                            
                            <div class="alert alert-info">
                                <div class="custom-control custom-switch custom-control-lg mb-3">
                                    <input type="checkbox" class="custom-control-input" id="discapacitado" name="discapacitado">
                                    <label class="custom-control-label font-weight-bold" for="discapacitado">
                                        <i class="fas fa-wheelchair text-info"></i> ¿Tiene alguna discapacidad o limitación funcional?
                                    </label>
                                </div>
                                
                                <div id="campo_tipo_discapacidad" style="display: none;">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-clipboard-list text-warning"></i> Tipo de Discapacidad
                                    </label>
                                    <input type="text" class="form-control" name="tipo_discapac" id="tipo_discapac" 
                                           placeholder="Ej: Visual, Auditiva, Motora, Intelectual...">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Especifique el tipo de discapacidad para ajustar el puesto de trabajo
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-talla">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-shirt text-primary"></i> Talla Camisa
                                        </label>
                                        <input type="text" class="form-control form-control-lg" name="talla_camisa" id="talla_camisa" placeholder="Ej: M, L, XL">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-user-tie text-info"></i> Talla Pantalón
                                        </label>
                                        <input type="text" class="form-control form-control-lg" name="talla_pantalon" id="talla_pantalon" placeholder="Ej: 32, 34">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-socks text-success"></i> Calzado
                                        </label>
                                        <input type="text" class="form-control form-control-lg" name="talla_calzado" id="talla_calzado" placeholder="Ej: 42, 43">
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle"></i> <strong>Nota:</strong> Estas tallas son necesarias para la entrega de EPP (Equipo de Protección Personal)
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Estilos adicionales --}}
<style>
.icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-white-20 {
    background-color: rgba(255, 255, 255, 0.2);
}

.card-hover {
    transition: all 0.3s ease;
    cursor: pointer;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.3) !important;
}

.filter-btn {
    transition: all 0.2s ease;
    font-weight: 600;
}

.filter-btn:hover {
    transform: scale(1.05);
}

.filter-btn.active {
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.badge-lg {
    font-size: 0.95rem;
    padding: 0.5rem 1rem;
}

/* Animación de contador */
@keyframes countUp {
    from { opacity: 0; transform: scale(0.5); }
    to { opacity: 1; transform: scale(1); }
}

.counter {
    animation: countUp 0.5s ease-out;
}

/* Mejoras para la tabla */
#tblPacientes {
    border-collapse: separate !important; /* Ayuda con el manejo de capas */
}

#tblPacientes tbody tr:hover {
    background-color: #f8f9fc !important;
    transform: scale(1.005);
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

#tblPacientes tbody td {
    vertical-align: middle;
    padding: 0.5rem;
}

/* Optimización de altura de filas */
#tblPacientes .badge-sm {
    font-size: 0.65rem;
    padding: 0.2rem 0.4rem;
}

/* Z-index para dropdowns */
.table .dropdown-menu {
    z-index: 9999 !important;
    margin: 0; 
    /* Si usas Bootstrap 4, esto ayuda a evitar saltos */
    position: absolute;
}

/* Ajuste para que el grupo de botones no bloquee el flujo */
.btn-group {
    position: static !important;
}

#tblPacientes td:last-child {
    position: static !important;
}

.table .dropdown {
    position: static;
}

.dropdown-menu {
    position: absolute !important;
}

/* Text truncate para columnas largas */
.text-truncate {
    max-width: 250px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Mejoras para tabs del modal */
.nav-pills .nav-link {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.nav-pills .nav-link:hover {
    background-color: #f8f9fc;
    transform: translateY(-2px);
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    box-shadow: 0 0.5rem 1rem rgba(78, 115, 223, 0.3);
}

/* Animaciones de entrada */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: slideInUp 0.5s ease-out;
}

/* Custom switch más grande */
.custom-control-lg .custom-control-label {
    padding-left: 2rem;
}

.custom-control-lg .custom-control-label::before {
    width: 3rem;
    height: 1.5rem;
    border-radius: 2rem;
}

.custom-control-lg .custom-control-label::after {
    width: calc(1.5rem - 4px);
    height: calc(1.5rem - 4px);
}

.custom-control-lg .custom-control-input:checked ~ .custom-control-label::after {
    transform: translateX(1.5rem);
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    
    // Botones de Exportación
    $('#btnExportExcel').click(function() {
        Swal.fire({
            title: 'Generando Excel...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        window.location.href = "{{ route('medicina.pacientes.export.excel') }}";
        setTimeout(() => Swal.close(), 2000);
    });

    $('#btnExportTallas').click(function() {
        Swal.fire({
            title: 'Generando Reporte...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        window.location.href = "{{ route('medicina.pacientes.export.tallas') }}";
        setTimeout(() => Swal.close(), 2000);
    });

    // Inicializar DataTable
    let table = $('#tblPacientes').DataTable({
        ajax: "{{ route('medicina.pacientes.listado') }}",
        columns: [
            { 
                data: 'nombre_completo',
                width: '35%',
                render: function(data, type, row) {
                    let avatar = row.sexo === 'F' ? 'avatar_female.png' : 'avatar_male.png';
                    let sexoIcon = row.sexo === 'F' ? '<i class="fas fa-venus text-danger"></i>' : '<i class="fas fa-mars text-primary"></i>';
                    return `
                    <div class="d-flex align-items-center py-1">
                        <div class="position-relative mr-2">
                            <img src="/assets/img/${avatar}" class="rounded-circle border border-primary" width="32" height="32">
                            <span class="position-absolute" style="bottom: -2px; right: -2px; font-size: 0.7rem;">${sexoIcon}</span>
                        </div>
                        <div class="flex-fill" style="min-width: 0;">
                            <div class="d-flex align-items-center">
                                <span class="badge badge-primary badge-sm mr-1" style="font-size: 0.65rem;">${row.cod_emp}</span>
                                <span class="text-dark font-weight-bold text-truncate" style="font-size: 0.85rem;" title="${data}">${data}</span>
                            </div>
                            <div class="small text-muted text-truncate" style="font-size: 0.7rem;" title="${row.des_cargo || 'Sin cargo'}">
                                <i class="fas fa-briefcase"></i> ${row.des_cargo || 'Sin cargo'}
                            </div>
                        </div>
                    </div>`;
                }
            },
            { 
                data: 'ci',
                width: '10%',
                render: function(data) {
                    if(!data) return '<span class="badge badge-secondary badge-sm">N/A</span>';
                    return `<div class="text-center py-1">
                                <strong style="font-size: 0.8rem;">${data}</strong>
                            </div>`;
                }
            },
            { 
                data: 'fecha_nac',
                width: '8%',
                render: function(data) {
                    if(!data) return '<span class="badge badge-secondary badge-sm">N/A</span>';
                    let hoy = new Date();
                    let cumple = new Date(data);
                    let edad = hoy.getFullYear() - cumple.getFullYear();
                    let badgeColor = edad < 30 ? 'success' : (edad < 50 ? 'info' : 'warning');
                    return `<div class="text-center py-1">
                                <span class="badge badge-${badgeColor}" style="font-size: 0.75rem;">
                                    ${edad} años
                                </span>
                            </div>`;
                }
            },
            {
                data: null,
                width: '15%',
                render: function(data, type, row) {
                    let badges = '<div class="d-flex flex-wrap justify-content-center py-1">';
                    
                    // Tipo de sangre
                    if(row.tipo_sangre) {
                        badges += `<span class="badge badge-danger m-1" style="font-size: 0.7rem;" title="Tipo de Sangre: ${row.tipo_sangre}">
                                    <i class="fas fa-tint"></i> ${row.tipo_sangre}
                                   </span>`;
                    }
                    
                    // Alergias
                    if(row.alergias && row.alergias.trim() !== '') {
                        badges += `<span class="badge badge-warning m-1" style="font-size: 0.7rem;" title="Alergias: ${row.alergias}">
                                    <i class="fas fa-exclamation-triangle"></i>
                                   </span>`;
                    }
                    
                    // Enfermedad de base
                    if(row.enfermedades_base && row.enfermedades_base.trim() !== '') {
                        badges += `<span class="badge badge-dark m-1" style="font-size: 0.7rem;" title="Patologías: ${row.enfermedades_base}">
                                    <i class="fas fa-heartbeat"></i>
                                   </span>`;
                    }
                    
                    // Discapacidad
                    if(row.discapacitado == 1) {
                        badges += `<span class="badge badge-info m-1" style="font-size: 0.7rem;" title="Discapacidad">
                                    <i class="fas fa-wheelchair"></i>
                                   </span>`;
                    }
                    
                    badges += '</div>';
                    return badges || '<small class="text-muted" style="font-size: 0.75rem;">Sin riesgos</small>';
                }
            },
            { 
                data: 'des_depart',
                width: '12%',
                render: function(data) {
                    if(!data) return '<span class="badge badge-secondary badge-sm">N/A</span>';
                    return `<div class="small text-center py-1 text-truncate" title="${data}" style="font-size: 0.75rem;">
                                <strong>${data}</strong>
                            </div>`;
                }
            },
            { 
                data: 'status',
                width: '8%',
                render: function(data) {
                    let status = data ? data.trim().toUpperCase() : '';
                    
                    let badge = '';
                    let texto = '';
                    let icon = '';

                    if (status === 'A') {
                        badge = 'badge-success';
                        texto = 'Activo';
                        icon = 'fas fa-check-circle';
                    } else if (status === 'V') {
                        badge = 'badge-info';
                        texto = 'Vacaciones';
                        icon = 'fas fa-umbrella-beach';
                    } else {
                        badge = 'badge-danger';
                        texto = 'Egreso';
                        icon = 'fas fa-times-circle';
                    }

                    return `<div class="text-center py-1">
                                <span class="badge ${badge}" style="font-size: 0.75rem;">
                                    <i class="${icon}"></i> ${texto}
                                </span>
                            </div>`;
                }
            },
            {
                data: 'id',
                width: '12%',
                render: function(data) {
                    return `
                    <div class="btn-group" role="group">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fas fa-user-md"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow-lg animated--fade-in">
                                <div class="dropdown-header bg-gradient-primary text-white">
                                    <i class="fas fa-stethoscope"></i> Accesos Médicos
                                </div>
                                <a class="dropdown-item" href="/medicina/paciente/${data}">
                                    <i class="fas fa-eye text-info mr-2"></i> Ver Detalles
                                </a>
                                <a class="dropdown-item" href="/medicina/consultas/crear/${data}">
                                    <i class="fas fa-plus-circle text-success mr-2"></i> Nueva Consulta
                                </a>
                                <a class="dropdown-item" href="/medicina/consultas/historial/${data}">
                                    <i class="fas fa-history text-primary mr-2"></i> Historia Médica
                                </a>
                                <button class="dropdown-item btnEdit" data-id="${data}">
                                    <i class="fas fa-user-edit text-warning mr-2"></i> Editar Ficha
                                </button>
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-header bg-gradient-warning text-white">
                                    <i class="fas fa-hard-hat"></i> SSL
                                </div>
                                <a class="dropdown-item" href="dotaciones/entregar/${data}">
                                    <i class="fas fa-tshirt text-info mr-2"></i> Nueva Dotación
                                </a>
                                <a class="dropdown-item" href="accidentes/registrar/${data}">
                                    <i class="fas fa-ambulance text-danger mr-2"></i> Registrar Accidente
                                </a>
                            </div>
                        </div>
                        
                        <div class="dropdown">
                            <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow-lg animated--fade-in" style="z-index: 1050;">
                                <div class="dropdown-header bg-gradient-danger text-white">
                                    <i class="fas fa-certificate"></i> Certificados
                                </div>
                                <a class="dropdown-item" href="/medicina/aptitud/${data}" target="_blank">
                                    <i class="fas fa-check-circle text-success mr-2"></i> Aptitud
                                </a>
                                <a class="dropdown-item" href="/medicina/constancia/${data}" target="_blank">
                                    <i class="fas fa-file-medical text-primary mr-2"></i> Constancia
                                </a>
                                <a class="dropdown-item" href="/medicina/historial/${data}" target="_blank">
                                    <i class="fas fa-virus text-danger mr-2"></i> Epidemiológico
                                </a>
                                <a class="dropdown-item" href="/medicina/epp/${data}" target="_blank">
                                    <i class="fas fa-hard-hat text-warning mr-2"></i> EPP
                                </a>
                            </div>
                        </div>
                    </div>`;
                }
            }
        ],
        language: { url: "/js/lang/Spanish.json" },
        order: [[0, 'asc']],
        pageLength: 25,
        drawCallback: function(settings) {
            if(settings.json) {
                // Actualizar cards con animación
                $('#card-total-pacientes').html(`<span class="counter">${settings.json.total_pacientes}</span>`);
                $('#card-criticos').html(`<span class="counter">${settings.json.total_criticos}</span>`);
                $('#card-discapacidad').html(`<span class="counter">${settings.json.total_discapacidad}</span>`);
                $('#card-promedio-edad').html(`<span class="counter">${settings.json.promedio_edad}</span> años`);
                $('#total-registros').text(settings.json.total_pacientes);
            }
        }
    });

    // Filtros rápidos
    $('.filter-btn').click(function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        let filter = $(this).data('filter');
        
        if(filter === 'all') {
            table.search('').columns().search('').draw();
        } else if(filter === 'active') {
            table.column(5).search('Activo').draw();
        } else if(filter === 'critical') {
            table.column(3).search('Patología').draw();
        } else if(filter === 'disability') {
            table.column(3).search('Discapacidad').draw();
        }
    });

    $('#btnResetFilters').click(function() {
        $('.filter-btn').removeClass('active');
        $('.filter-btn[data-filter="all"]').addClass('active');
        table.search('').columns().search('').draw();
    });

    // Evento Sincronizar
    $('#btnSync').click(function() {
        Swal.fire({
            title: '¿Sincronizar con Profit?',
            html: '<p>Se actualizarán los datos de nómina desde el sistema principal.</p><p class="text-muted small">Este proceso puede tardar unos segundos...</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4e73df',
            cancelButtonColor: '#858796',
            confirmButtonText: '<i class="fas fa-sync-alt"></i> Sí, sincronizar',
            cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: "{{ route('medicina.pacientes.sync') }}",
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}" }
                }).catch(error => {
                    Swal.showValidationMessage(`Error: ${error.responseJSON ? error.responseJSON.text : 'Error desconocido'}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: result.value.icon,
                    title: result.value.title,
                    text: result.value.text,
                    showConfirmButton: false,
                    timer: 2000
                });
                table.ajax.reload();
            }
        });
    });

    // Modal - Abrir y Cargar Datos
    $(document).on('click', '.btnEdit', function() {
        let id = $(this).data('id');
        
        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.get('/medicina/pacientes/'+id+'/edit', function(data) {
            Swal.close();
            
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
            $('#discapacitado').prop('checked', data.discapacitado == 1);
            $('#tipo_discapac').val(data.tipo_discapac);
            
            // Mostrar/ocultar campo de tipo de discapacidad
            if(data.discapacitado == 1) {
                $('#campo_tipo_discapacidad').slideDown();
            } else {
                $('#campo_tipo_discapacidad').slideUp();
            }
            
            $('#modalPaciente').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar la información del paciente'
            });
        });
    });

    // Toggle campo de tipo de discapacidad
    $('#discapacitado').change(function() {
        if($(this).is(':checked')) {
            $('#campo_tipo_discapacidad').slideDown();
        } else {
            $('#campo_tipo_discapacidad').slideUp();
            $('#tipo_discapac').val('');
        }
    });

    // Guardar por AJAX
    $('#formPaciente').on('submit', function(e) {
        e.preventDefault();
        let id = $('#paciente_id').val();
        let formData = $(this).serialize();

        Swal.fire({
            title: 'Guardando...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `/medicina/pacientes/${id}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                $('#modalPaciente').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Guardado!',
                    text: 'La ficha médica ha sido actualizada correctamente',
                    showConfirmButton: false,
                    timer: 2000
                });
                table.ajax.reload(null, false);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al guardar los datos'
                });
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
        confirmButtonText: '<i class="fas fa-print"></i> Imprimir Ahora',
        cancelButtonText: '<i class="fas fa-times"></i> Más Tarde'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("{{ route('medicina.consultas.imprimir', session('print_id')) }}", '_blank');
        }
    });
</script>
@endif
@endsection
