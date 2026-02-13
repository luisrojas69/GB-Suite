@extends('layouts.app')

@section('styles')
<style>
    .gravedad-selector .btn {
        min-width: 120px;
        transition: all 0.3s ease;
    }
    .gravedad-selector input[type="radio"]:checked + label {
        transform: scale(1.05);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.2);
    }
    .body-part-selector {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .body-part-selector:hover {
        background-color: #f8f9fc;
        transform: scale(1.02);
    }
    .body-part-selector.selected {
        background-color: #4e73df;
        color: white;
        border-color: #4e73df;
    }
    .severity-badge {
        font-size: 1.2rem;
        padding: 0.8rem 1.5rem;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .severity-badge:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Notificaciones mejoradas --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg border-left-success" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x mr-3"></i>
                <div>
                    <strong>¡Éxito!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
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
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    {{-- Header Principal --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body bg-gradient-danger text-white py-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="icon-circle bg-white text-danger" style="width: 80px; height: 80px;">
                        <i class="fas fa-exclamation-triangle fa-3x"></i>
                    </div>
                </div>
                <div class="col">
                    <h1 class="h2 mb-1 font-weight-bold text-white">
                        <i class="fas fa-clipboard-list"></i> Registro de Accidente Laboral
                    </h1>
                    <p class="mb-0 text-white-50">
                        <i class="fas fa-shield-alt"></i> Notificación INPSASEL | Sistema de Gestión de Seguridad
                    </p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('medicina.pacientes.show', $paciente->id) }}" class="btn btn-light btn-lg shadow">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Info del Trabajador Afectado --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-left-danger">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img class="rounded-circle border border-danger border-3" 
                                 src="{{ asset($paciente->foto) }}" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="col">
                            <h4 class="font-weight-bold text-danger mb-1">
                                <i class="fas fa-user-injured"></i> {{ $paciente->nombre_completo }}
                            </h4>
                            <div class="row small">
                                <div class="col-md-3">
                                    <i class="fas fa-id-card text-primary"></i> 
                                    <strong>CI:</strong> {{ $paciente->ci }}
                                </div>
                                <div class="col-md-4">
                                    <i class="fas fa-briefcase text-info"></i> 
                                    <strong>Cargo:</strong> {{ $paciente->des_cargo }}
                                </div>
                                <div class="col-md-3">
                                    <i class="fas fa-building text-success"></i> 
                                    <strong>Depto:</strong> {{ $paciente->des_depart }}
                                </div>
                                <div class="col-md-2">
                                    <i class="fas fa-tint text-danger"></i> 
                                    <strong>Sangre:</strong> {{ $paciente->tipo_sangre ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="text-center">
                                <div class="badge badge-danger badge-lg px-3 py-2">
                                    <i class="fas fa-ambulance"></i> ACCIDENTE LABORAL
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alertas de Consulta Vinculada --}}
    @if(request('consulta_id'))
        @php $consultaPrevia = App\Models\MedicinaOcupacional\Consulta::find(request('consulta_id')); @endphp
        @if($consultaPrevia)
            <div class="alert alert-info shadow-lg border-left-info mb-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fas fa-link fa-2x"></i>
                    </div>
                    <div class="col">
                        <h6 class="font-weight-bold mb-1">
                            <i class="fas fa-notes-medical"></i> Referencia Médica Detectada
                        </h6>
                        <p class="mb-1">
                            Este reporte se vinculará automáticamente a la consulta del <strong>{{ $consultaPrevia->created_at->format('d/m/Y') }}</strong>
                        </p>
                        <div class="small">
                            <span class="badge badge-primary mr-2">
                                Diagnóstico: {{ $consultaPrevia->diagnostico_cie10 }}
                            </span>
                            <span class="badge badge-info">
                                Médico: {{ $consultaPrevia->medico->name." ".$consultaPrevia->medico->last_name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    @if($consultaHoy)
        <div class="alert alert-success shadow-lg border-left-success mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fas fa-check-double fa-2x"></i>
                </div>
                <div class="col">
                    <h6 class="font-weight-bold mb-1">
                        <i class="fas fa-stethoscope"></i> Evaluación Médica Detectada
                    </h6>
                    <p class="mb-1">
                        Accidente vinculado automáticamente a consulta de hoy a las {{ \Carbon\Carbon::parse($consultaHoy->created_at)->format('h:i A') }}
                    </p>
                    <span class="badge badge-success">
                        Diagnóstico: {{ $consultaHoy->diagnostico_cie10 }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    {{-- Formulario Principal --}}
    <form action="{{ route('medicina.accidentes.store') }}" method="POST" id="formAccidente">
        @csrf
        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
        <input type="hidden" name="consulta_id" value="{{ $consultaHoy ? $consultaHoy->id : request('consulta_id') }}">

        {{-- Sección 1: Datos del Suceso y Gravedad --}}
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-calendar-alt"></i> 1. Datos del Suceso
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fas fa-clock text-danger"></i> Fecha y Hora Exacta del Accidente
                            </label>
                            <input type="datetime-local" 
                                   name="fecha_hora_accidente" 
                                   class="form-control form-control-lg border-left-danger" 
                                   max="{{ date('Y-m-d\TH:i') }}"
                                   required>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Indique la fecha y hora exacta en que ocurrió el accidente
                            </small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fas fa-map-marker-alt text-info"></i> Lugar Exacto del Accidente
                            </label>
                            <input type="text" 
                                   name="lugar_exacto" 
                                   class="form-control form-control-lg" 
                                   placeholder="Ej: Taller Central - Fosa 2, Almacén Principal, Área de Producción..."
                                   required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fas fa-exclamation-circle text-warning"></i> Clasificación del Evento
                            </label>
                            <select name="tipo_evento" class="form-control form-control-lg border-left-warning" required>
                                <option value="">Seleccione la clasificación...</option>
                                <option>Accidente con Tiempo Perdido (Reposo)</option>
                                <option>Accidente sin Tiempo Perdido</option>
                                <option>Incidente (Casi-Accidente)</option>
                                <option>Accidente de Trayecto (In-Itinere)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fas fa-business-time text-primary"></i> Horas Trabajadas al Momento del Accidente
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="number" 
                                       name="horas_trabajadas" 
                                       class="form-control" 
                                       min="0" 
                                       max="24" 
                                       step="0.5"
                                       placeholder="Ej: 4.5"
                                       required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-clock"></i> horas
                                    </span>
                                </div>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Número de horas trabajadas desde el inicio del turno
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gravedad del Accidente --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-header bg-gradient-danger text-white py-3">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-chart-line"></i> Gravedad y Lesiones
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold d-block mb-3">
                                <i class="fas fa-thermometer-half"></i> Nivel de Gravedad del Accidente
                            </label>
                            <div class="btn-group-toggle gravedad-selector d-flex flex-column" data-toggle="buttons">
                                <label class="btn btn-outline-success btn-lg mb-2 text-left">
                                    <input type="radio" name="gravedad" value="Leve" required>
                                    <i class="fas fa-check-circle"></i> <strong>LEVE</strong>
                                    <small class="d-block">Lesiones menores, sin reposo o reposo ≤ 3 días</small>
                                </label>
                                <label class="btn btn-outline-warning btn-lg mb-2 text-left">
                                    <input type="radio" name="gravedad" value="Grave" required>
                                    <i class="fas fa-exclamation-triangle"></i> <strong>GRAVE</strong>
                                    <small class="d-block">Lesiones significativas, reposo > 3 días, posible hospitalización</small>
                                </label>
                                <label class="btn btn-outline-danger btn-lg text-left">
                                    <input type="radio" name="gravedad" value="Mortal" required>
                                    <i class="fas fa-skull-crossbones"></i> <strong>MORTAL</strong>
                                    <small class="d-block">Accidente fatal o con riesgo inminente de muerte</small>
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-lightbulb"></i> <strong>Nota:</strong> La gravedad determina el protocolo de notificación y seguimiento requerido por INPSASEL.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección 2: Parte del Cuerpo y Análisis --}}
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-warning text-white py-3">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-user-injured"></i> 2. Parte del Cuerpo Lesionada
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold mb-3">
                                <i class="fas fa-hand-point-right"></i> Seleccione las partes del cuerpo afectadas
                            </label>
                            <input type="hidden" name="parte_lesionada" id="partes_lesionadas_input">
                            <div class="row" id="body-parts-selector">
                                @php
                                $partesCuerpo = [
                                    ['nombre' => 'Cabeza', 'icono' => 'fa-head-side-virus', 'color' => 'danger'],
                                    ['nombre' => 'Ojos', 'icono' => 'fa-eye', 'color' => 'info'],
                                    ['nombre' => 'Oídos', 'icono' => 'fa-deaf', 'color' => 'warning'],
                                    ['nombre' => 'Cuello', 'icono' => 'fa-head-side-cough', 'color' => 'primary'],
                                    ['nombre' => 'Hombro Derecho', 'icono' => 'fa-hand-point-right', 'color' => 'success'],
                                    ['nombre' => 'Hombro Izquierdo', 'icono' => 'fa-hand-point-left', 'color' => 'success'],
                                    ['nombre' => 'Brazo Derecho', 'icono' => 'fa-hand-paper', 'color' => 'info'],
                                    ['nombre' => 'Brazo Izquierdo', 'icono' => 'fa-hand-paper', 'color' => 'info'],
                                    ['nombre' => 'Mano Derecha', 'icono' => 'fa-hand-rock', 'color' => 'warning'],
                                    ['nombre' => 'Mano Izquierda', 'icono' => 'fa-hand-rock', 'color' => 'warning'],
                                    ['nombre' => 'Dedos Mano', 'icono' => 'fa-hand-scissors', 'color' => 'danger'],
                                    ['nombre' => 'Tórax/Pecho', 'icono' => 'fa-user', 'color' => 'primary'],
                                    ['nombre' => 'Espalda', 'icono' => 'fa-user-injured', 'color' => 'danger'],
                                    ['nombre' => 'Abdomen', 'icono' => 'fa-user', 'color' => 'warning'],
                                    ['nombre' => 'Pierna Derecha', 'icono' => 'fa-running', 'color' => 'success'],
                                    ['nombre' => 'Pierna Izquierda', 'icono' => 'fa-running', 'color' => 'success'],
                                    ['nombre' => 'Rodilla', 'icono' => 'fa-walking', 'color' => 'info'],
                                    ['nombre' => 'Labios / Boca', 'icono' => 'fa-face-surprise', 'color' => 'warning'],
                                    ['nombre' => 'Dientes', 'icono' => 'fa-tooth', 'color' => 'danger'],
                                    ['nombre' => 'Pie Derecho', 'icono' => 'fa-shoe-prints', 'color' => 'primary'],
                                    ['nombre' => 'Pie Izquierdo', 'icono' => 'fa-shoe-prints', 'color' => 'primary'],
                                    ['nombre' => 'Dedos Pie', 'icono' => 'fa-socks', 'color' => 'warning'],
                                    ['nombre' => 'Múltiples Partes', 'icono' => 'fa-notes-medical', 'color' => 'danger'],
                                    ['nombre' => 'Otra', 'icono' => 'fa-question-circle', 'color' => 'secondary']
                                ];
                                @endphp
                                @foreach($partesCuerpo as $parte)
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <div class="card body-part-selector border border-{{ $parte['color'] }}" 
                                         data-parte="{{ $parte['nombre'] }}">
                                        <div class="card-body text-center py-2">
                                            <i class="fas {{ $parte['icono'] }} text-{{ $parte['color'] }} fa-lg"></i>
                                            <div class="small font-weight-bold mt-1">{{ $parte['nombre'] }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div id="selected-parts" class="mt-3">
                                <strong class="d-block mb-2">Partes seleccionadas:</strong>
                                <div id="selected-parts-badges"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Análisis de Causas --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-info text-white py-3">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-microscope"></i> 3. Análisis de Causas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold text-danger">
                                <i class="fas fa-bolt"></i> Causas Inmediatas (Actos/Condiciones Inseguras)
                            </label>
                            <textarea name="causas_inmediatas" 
                                      class="form-control border-left-danger" 
                                      rows="4" 
                                      placeholder="Ej: Piso resbaladizo por derrame de aceite, falta de uso de botas antideslizantes, iluminación deficiente..."
                                      required></textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Condiciones o actos inseguros que causaron directamente el accidente
                            </small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-warning">
                                <i class="fas fa-search"></i> Causas Raíz (Fallas Sistémicas)
                            </label>
                            <textarea name="causas_raiz" 
                                      class="form-control border-left-warning" 
                                      rows="4" 
                                      placeholder="Ej: Programa de orden y limpieza deficiente, falta de supervisión constante, ausencia de señalización..."
                                      required></textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Fallas en los sistemas de gestión que permitieron el accidente
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección 3: Testigos y Relatos --}}
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-gradient-secondary text-white py-3">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-users"></i> 4. Testigos y Descripción del Evento
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fas fa-user-friends text-info"></i> Testigos Presenciales
                            </label>
                            <textarea name="testigos" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Nombre completo y cédula de identidad de los testigos (si aplica)&#10;Ej:&#10;- Juan Pérez (V-12.345.678)&#10;- María González (V-23.456.789)"></textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Liste los nombres y cédulas de personas que presenciaron el accidente
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold text-primary">
                                <i class="fas fa-comment-dots"></i> Relato del Acontecido
                            </label>
                            <textarea name="descripcion_relato" 
                                      class="form-control border-left-primary" 
                                      rows="4" 
                                      placeholder="Describa cronológicamente cómo ocurrió el accidente, qué estaba haciendo el trabajador, qué sucedió..."
                                      required></textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Relato cronológico del evento
                            </small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold text-danger">
                        <i class="fas fa-band-aid"></i> Descripción Detallada de Lesiones
                    </label>
                    <textarea name="lesion_detallada" 
                              class="form-control border-left-danger" 
                              rows="3" 
                              placeholder="Ej: Traumatismo en falange distal del dedo índice derecho con herida cortante de 3 cm, fractura expuesta de tibia..."
                              required></textarea>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Describa detalladamente las lesiones, su ubicación y severidad
                    </small>
                </div>
            </div>
        </div>

        {{-- Sección 4: Plan de Acción --}}
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-gradient-success text-white py-3">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-check-double"></i> 5. Plan de Acción Correctiva y Preventiva
                </h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="font-weight-bold text-success">
                        <i class="fas fa-tasks"></i> Medidas Correctivas Implementadas
                    </label>
                    <textarea name="acciones_correctivas" 
                              class="form-control border-left-success" 
                              rows="4" 
                              placeholder="¿Qué medidas inmediatas se tomaron? ¿Qué acciones se implementarán para evitar que esto vuelva a ocurrir?&#10;&#10;Ej:&#10;- Limpieza inmediata del área&#10;- Señalización de zona peligrosa&#10;- Capacitación adicional al personal&#10;- Revisión del procedimiento de trabajo&#10;- Implementación de barandas de seguridad"
                              required></textarea>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Detalle las acciones correctivas y preventivas para evitar recurrencia
                    </small>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Importante:</strong> Las medidas correctivas deben ser específicas, medibles y con responsables asignados.
                </div>
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-body bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('medicina.pacientes.show', $paciente->id) }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Cancelar Registro
                    </a>
                    <button type="submit" class="btn btn-danger btn-lg shadow" id="btnFinalizar">
                        <i class="fas fa-save"></i> Finalizar y Guardar Investigación
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let partesSeleccionadas = [];

    // Selector de partes del cuerpo
    $('.body-part-selector').click(function() {
        const parte = $(this).data('parte');
        
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            partesSeleccionadas = partesSeleccionadas.filter(p => p !== parte);
        } else {
            $(this).addClass('selected');
            if(!partesSeleccionadas.includes(parte)) {
                partesSeleccionadas.push(parte);
            }
        }
        
        actualizarPartesSeleccionadas();
    });

    function actualizarPartesSeleccionadas() {
        const badgesContainer = $('#selected-parts-badges');
        badgesContainer.empty();
        
        if(partesSeleccionadas.length === 0) {
            badgesContainer.html('<span class="text-muted"><i>Ninguna parte seleccionada</i></span>');
            $('#partes_lesionadas_input').val('');
        } else {
            partesSeleccionadas.forEach(parte => {
                badgesContainer.append(`
                    <span class="badge badge-primary badge-lg mr-2 mb-2">
                        <i class="fas fa-check"></i> ${parte}
                        <i class="fas fa-times ml-2" style="cursor: pointer;" onclick="eliminarParte('${parte}')"></i>
                    </span>
                `);
            });
            $('#partes_lesionadas_input').val(partesSeleccionadas.join(', '));
        }
    }

    window.eliminarParte = function(parte) {
        $(`.body-part-selector[data-parte="${parte}"]`).removeClass('selected');
        partesSeleccionadas = partesSeleccionadas.filter(p => p !== parte);
        actualizarPartesSeleccionadas();
    };

    // Validación del formulario
    $('#formAccidente').submit(function(e) {
        // Validar que se haya seleccionado al menos una parte del cuerpo
        if(partesSeleccionadas.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Parte del Cuerpo Requerida',
                text: 'Por favor seleccione al menos una parte del cuerpo lesionada.',
            });
            return false;
        }

        // Validar que se haya seleccionado gravedad
        if(!$('input[name="gravedad"]:checked').val()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Gravedad Requerida',
                text: 'Por favor seleccione el nivel de gravedad del accidente.',
            });
            return false;
        }

        e.preventDefault();
        
        const gravedad = $('input[name="gravedad"]:checked').val();
        let iconoGravedad = 'warning';
        let colorGravedad = '#f6c23e';
        
        if(gravedad === 'Leve') {
            iconoGravedad = 'info';
            colorGravedad = '#36b9cc';
        } else if(gravedad === 'Mortal') {
            iconoGravedad = 'error';
            colorGravedad = '#e74a3b';
        }
        
        Swal.fire({
            title: '¿Finalizar Investigación de Accidente?',
            html: `
                <p>Se guardará el reporte de accidente <strong class="text-${gravedad === 'Leve' ? 'success' : gravedad === 'Grave' ? 'warning' : 'danger'}">${gravedad.toUpperCase()}</strong></p>
                <p class="text-muted small">Verifique que toda la información esté completa y correcta.</p>
                <div class="mt-3">
                    <strong>Partes lesionadas:</strong><br>
                    <span class="badge badge-primary">${partesSeleccionadas.join('</span> <span class="badge badge-primary">')}</span>
                </div>
            `,
            icon: iconoGravedad,
            showCancelButton: true,
            confirmButtonColor: colorGravedad,
            cancelButtonColor: '#858796',
            confirmButtonText: '<i class="fas fa-check"></i> Sí, Finalizar',
            cancelButtonText: '<i class="fas fa-times"></i> Revisar',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // Efecto visual en selección de gravedad
    $('.gravedad-selector label').click(function() {
        $('.gravedad-selector label').removeClass('active');
        $(this).addClass('active');
    });
});
</script>
@endsection