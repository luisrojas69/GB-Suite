@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Detalle de Consulta Médica #{{ $consulta->id }}</h6>
                    <a href="{{ route('medicina.consultas.imprimir', $consulta->id) }}" class="btn btn-sm btn-light shadow-sm">
                        <i class="fas fa-print"></i> Imprimir
                    </a>

                   

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