@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Notificaciones --}}
    @if (session('success'))
        <div class="alert alert-success border-left-success shadow">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger border-left-danger shadow">{{ session('error') }}</div>
    @endif

    {{-- Encabezado de la Investigación --}}
    <div class="card border-left-danger shadow h-100 py-2 mb-4">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Registro de Notificación INPSASEL</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $paciente->nombre_completo }}</div>
                    <div class="small text-muted">Cédula: {{ $paciente->cedula }} | Cargo: {{ $paciente->des_cargo }}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-ambulance fa-3x text-gray-200"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Info de la Consulta Vinculada (Si existe) --}}
    @if(request('consulta_id'))
        @php $consultaPrevia = App\Models\MedicinaOcupacional\Consulta::find(request('consulta_id')); @endphp
        @if($consultaPrevia)
            <div class="alert alert-info shadow mb-4">
                <i class="fas fa-info-circle"></i> <strong>Referencia Médica Detectada:</strong> 
                Este reporte se está vinculando a la consulta del {{ $consultaPrevia->created_at->format('d/m/Y') }}. 
                <br><small>Diagnóstico Inicial: <strong>{{ $consultaPrevia->diagnostico_cie10 }}</strong></small>
                <br><small>Atendido por: <strong>{{ $consultaPrevia->medico->name." ".$consultaPrevia->medico->last_name }}</strong></small>
            </div>
        @endif
    @endif

    @if($consultaHoy)
        <div class="alert alert-success border-left-success shadow mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fas fa- check-double fa-2x"></i>
                </div>
                <div class="col">
                    <h6 class="font-weight-bold mb-0">Evaluación Médica Detectada</h6>
                    <p class="mb-0 small">Hemos vinculado automáticamente este accidente a la consulta realizada hoy a las {{ \Carbon\Carbon::parse($consultaHoy->created_at)->format('h:i A') }}.</p>
                    <br><small>Diagnóstico Inicial: <strong>{{ $consultaHoy->diagnostico_cie10 }}</strong></small>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('medicina.accidentes.store') }}" method="POST">
        @csrf
        {{-- Campos Ocultos de Relación --}}
        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
        <input type="hidden" name="consulta_id" value="{{ $consultaHoy ? $consultaHoy->id : request('consulta_id') }}">

        <div class="row">
            {{-- SECCIÓN 1: DATOS DEL SUCESO --}}
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-day"></i> 1. Datos del Suceso</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold small text-uppercase">Fecha y Hora Exacta</label>
                                    <input type="datetime-local" name="fecha_hora_accidente" class="form-control border-left-danger" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold small text-uppercase">Lugar Exacto (Sector/Lote/Taller)</label>
                            <input type="text" name="lugar_exacto" class="form-control" placeholder="Ej: Taller Central - Fosa 2" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold small text-uppercase">Clasificación del Evento</label>
                            <select name="tipo_evento" class="form-control" required>
                                <option value="">Seleccione...</option>
                                <option>Accidente con Tiempo Perdido (Reposo)</option>
                                <option>Accidente sin Tiempo Perdido</option>
                                <option>Incidente (Casi-Accidente)</option>
                                <option>Accidente de Trayecto (In-Itinere)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: ANÁLISIS TÉCNICO --}}
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-microscope"></i> 2. Análisis de Causas</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold small text-uppercase text-danger">Causas Inmediatas (Acto/Condición)</label>
                            <textarea name="causas_inmediatas" class="form-control" rows="2" placeholder="Ej: Piso resbaladizo, falta de uso de botas de seguridad..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold small text-uppercase text-warning">Causas Raíz (Falla Sistémica)</label>
                            <textarea name="causas_raiz" class="form-control" rows="2" placeholder="Ej: Programa de orden y limpieza deficiente, falta de supervisión..." required></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN 3: TESTIGOS Y RELATOS --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bullhorn"></i> 3. Testigos y Relatos</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold small text-uppercase">Testigos Presenciales</label>
                            <textarea name="testigos" class="form-control" rows="2" placeholder="Nombres y Cédulas (Si aplica)"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold small text-uppercase text-primary">Relato del Acontecido</label>
                            <textarea name="descripcion_relato" class="form-control" rows="2" placeholder="Describa brevemente cómo ocurrió el evento..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold small text-uppercase">Descripción Detallada de Lesiones</label>
                    <textarea name="lesion_detallada" class="form-control" rows="2" placeholder="Ej: Traumatismo en falange distal derecha..." required></textarea>
                </div>
            </div>
        </div>

        {{-- SECCIÓN 4: CIERRE Y ACCIONES --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-gray-100">
                <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-check-double"></i> 4. Plan de Acción Correctiva</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <textarea name="acciones_correctivas" class="form-control border-left-success" rows="3" placeholder="¿Qué medidas se tomaron para evitar que esto vuelva a ocurrir en Granja Boraure?" required></textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('medicina.pacientes.show', $paciente->id) }}" class="btn btn-secondary shadow-sm">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-danger btn-icon-split shadow-sm">
                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                        <span class="text">Finalizar Investigación</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection