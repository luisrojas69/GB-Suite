@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Detalle de Consulta Médica #{{ $consulta->id }}</h6>
                    <
                    <div class="dropdown no-arrow">
                        <a class="btn btn-light btn-sm dropdown-toggle shadow-sm" href="#" role="button" data-toggle="dropdown">
                            <i class="fa-solid fa-file-pdf"></i>
                            <i class="fa-solid fa-angle-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <div class="dropdown-header ">Acciones Posibles</div>

                            <a class="dropdown-item" href="{{ route('medicina.consultas.imprimir', $consulta->id) }}" target="_blank">
                                <i class="fas fa-print fa-sm fa-fw mr-2 text-body"></i> Imprimir Recipe
                            </a>

                            

                            <div class="dropdown-divider"></div>
                            <div class="dropdown-header ">Certificados M&eacute;dicos:</div>

                            <a class="dropdown-item" href="{{ route('medicina.pdf.aptitud', $consulta->paciente->id) }}" target="_blank">
                                <i class="fas fa-person-circle-check fa-sm fa-fw mr-2 text-warning"></i> Certificado de Aptitud
                            </a>

                            <a class="dropdown-item" href="{{ route('medicina.pdf.constancia', $consulta->id) }}" target="_blank">
                                <i class="fas fa-person-walking-arrow-right fa-sm fa-fw mr-2 text-info"></i> Constancia de Asistencia
                            </a>

                             <a class="dropdown-item" href="{{ route('medicina.pdf.historial', $consulta->id) }}" target="_blank">
                                <i class="fas fa-virus fa-sm fa-fw mr-2 text-danger"></i> Historial Epidemiol&oacute;gico
                            </a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('medicina.pacientes.show', $consulta->paciente->id) }}">
                                <i class="fas fa-person-circle-check fa-sm fa-fw mr-2 text-body"></i> Detalles del Paciente
                            </a>

                            <a class="dropdown-item" href="{{ route('medicina.pacientes.index') }}"><i class="fas fa-users mr-2"></i> Ir a Pacientes</a>
                           
                        </div>
                    </div>
                   

                </div>
                <div class="card-body">
                    @if($consulta->motivo_consulta == 'Accidente Laboral')
                        @if($consulta->accidente)
                        <div class="alert alert-info border-left-info">
                            <i class="fas fa-exclamation-triangle fa-2x text-red"></i>
                                <strong>  Este evaluación médica esta asociada a un accidente ocurrido el  {{ $consulta->accidente->fecha_hora_accidente }}  </strong>
                            <i class="fas fa-ambulance fa-2x text-black-300"></i>
                            <a href="{{ route('medicina.accidentes.show', $consulta->accidente->id) }}" class="btn btn-danger btn-sm shadow-sm">
                                <i class="fas fa-file-signature"></i> Ver Reporte de Investigación
                            </a>
                        </div>

                        @else

                        <div class="alert alert-info border-left-info">
                            <i class="fas fa-exclamation-triangle fa-2x text-red"></i>
                                <strong>  Este evaluación médica esta asociada a un accidente que aún no ha sido reportado en el sistema.  </strong>
                            <i class="fas fa-ambulance fa-2x text-black-300"></i>
                            <a href="{{ route('medicina.accidentes.create', ['consulta_id' => $consulta->id, 'paciente_id' => $consulta->id])  }}" class="btn btn-outline-danger btn-sm shadow-sm">
                                <i class="fas fa-plus"></i> Crear Reporte de Accidente
                            </a>
                        </div>
                        @endif
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-uppercase">Paciente</label>
                            <p class="h5">{{ $consulta->paciente->nombre_completo }}</p>      
                        </div>
                        <div class="col-md-6 text-md-right">
                            <label class="small font-weight-bold text-uppercase">Fecha y Hora</label>
                            <p>{{ $consulta->created_at->format('d/m/Y h:i A') }}</p>

                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-uppercase text-primary">Motivo de Consulta</label>
                        <p class="bg-light p-2 border rounded">{{ $consulta->motivo_consulta }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-uppercase text-primary">Examen Físico / Hallazgos</label>
                        <p>{{ $consulta->examen_fisico ?? 'No registrado' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-uppercase text-primary">Anamnesis (Relato y Antecedentes)</label>
                        <p>{{ $consulta->anamnesis?? 'No registrado' }}</p>
                    </div>
                    @if($consulta->genera_reposo == 1)
                    <div class="mb-3">
                        <span class="badge badge-danger"><i class="fa-solid fa-bed-pulse"></i>  Amaritó {{ $consulta->dias_reposo}} dias de reposo</span>
                    </div>
                    @else
                    <div class="mb-3">
                        <span class="badge badge-info"> <i class="fa-solid fa-bed-pulse"></i>  No amaritó dias de reposo</span>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-uppercase text-danger">Diagnóstico</label>
                            <div class="alert alert-danger p-2">{{ $consulta->diagnostico_cie10 }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-uppercase text-success">Tratamiento / Plan</label>
                            <div class="alert alert-success p-2">{{ $consulta->plan_tratamiento }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="small text-muted">
                        Atendido por: {{ $consulta->medico->name." ".$consulta->medico->last_name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection