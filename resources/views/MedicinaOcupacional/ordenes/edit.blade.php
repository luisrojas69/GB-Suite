@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Mensaje de Éxito --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    {{-- HEADER: DETALLES DEL PACIENTE Y CONSULTA --}}
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-md-7 border-right">
                    <h5 class="font-weight-bold text-primary"><i class="fas fa-user-circle"></i> Datos del Trabajador</h5>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted d-block">Nombre Completo:</small>
                            <span class="h6 font-weight-bold">{{ $orden->paciente->nombre_completo }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Cédula / Ficha:</small>
                            <span class="h6">{{ number_format($orden->paciente->ci, 0, ',', '.') }} | {{ $orden->paciente->cod_emp ?? 'N/A' }}</span>
                        </div>
                        <div class="col-6 mt-2">
                            <small class="text-muted d-block">Cargo y Depto:</small>
                            <span class="small font-weight-bold">{{ $orden->paciente->des_cargo }}</span> / <span class="small">{{ $orden->paciente->des_depart }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <h5 class="font-weight-bold text-info"><i class="fas fa-stethoscope"></i> Origen: Consulta #{{ $orden->consulta->id }}</h5>
                    <small class="text-muted d-block">Motivo de Consulta:</small>
                    <p class="small mb-1"><strong>{{ $orden->consulta->motivo_consulta }}</strong></p>
                    <small class="text-muted d-block">Sintomatología/Diagnóstico:</small>
                    <p class="small italic text-truncate">"{{ $orden->consulta->diagnostico_cie10 ?? 'Sin descripción' }}"</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('medicina.ordenes.update', $orden->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- BLOQUE IZQUIERDO: INTERPRETACIÓN --}}
            <div class="col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-gray-100">
                        <h6 class="m-0 font-weight-bold text-primary">Juicio Clínico y Hallazgos</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light border mb-4">
                            <label class="font-weight-bold text-xs text-uppercase">Exámenes que se solicitaron:</label>
                            <div class="mt-1">
                                @foreach($orden->examenes as $examen)
                                    <span class="badge badge-info shadow-sm mr-1 mb-1 p-2"><i class="fas fa-microscope"></i> {{ $examen }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Interpretación Global:</label>
                            <div class="d-flex mt-2">
                                <div class="custom-control custom-radio custom-control-inline mr-4">
                                    <input type="radio" id="normal" name="interpretacion" value="Normal" class="custom-control-input" {{ $orden->interpretacion == 'Normal' ? 'checked' : '' }}>
                                    <label class="custom-control-label text-success font-weight-bold" for="normal">NORMAL</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="alterado" name="interpretacion" value="Alterado" class="custom-control-input" {{ $orden->interpretacion == 'Alterado' ? 'checked' : '' }}>
                                    <label class="custom-control-label text-danger font-weight-bold" for="alterado">ALTERADO</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Hallazgos / Notas del Médico:</label>
                            <textarea name="hallazgos" class="form-control" rows="5" placeholder="Escriba aquí los resultados relevantes...">{{ old('hallazgos', $orden->hallazgos) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BLOQUE DERECHO: ARCHIVOS MÚLTIPLES --}}
            <div class="col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-gray-100 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Archivos de la Orden</h6>
                        <span class="badge badge-primary">{{ count($archivos_orden) }} adjuntos</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-4 p-3 border-dashed" style="border: 2px dashed #d1d3e2; border-radius: 10px; background: #f8f9fc;">
                            <label class="font-weight-bold text-primary"><i class="fas fa-upload"></i> Subir nuevos resultados</label>
                            <input type="file" name="archivos[]" class="form-control-file" multiple accept=".pdf,.png,.jpg,.jpeg">
                            <small class="text-muted d-block mt-1">Puedes seleccionar varios archivos a la vez.</small>
                        </div>

                        <label class="font-weight-bold text-xs text-uppercase text-muted">Documentos cargados:</label>
                        <div class="list-group list-group-flush border rounded" style="max-height: 300px; overflow-y: auto;">
                            @forelse($archivos_orden as $file)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <div class="text-truncate" style="max-width: 70%;">
                                    <i class="fas fa-file-pdf text-danger mr-2"></i>
                                    <span class="small font-weight-bold text-gray-800">{{ $file->nombre_archivo }}</span>
                                    <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($file->created_at)->format('d/m/y') }}</small>
                                </div>
                                <a href="{{ asset('storage/' . $file->ruta_archivo) }}" target="_blank" class="btn btn-sm btn-circle btn-info shadow-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                            @empty
                            <div class="p-4 text-center">
                                <i class="fas fa-folder-open text-gray-300 fa-3x mb-2"></i>
                                <p class="small text-muted mb-0">No hay archivos en esta orden.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-body p-2">
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow">
                            <i class="fas fa-save mr-1"></i> Guardar Avances
                        </button>
                        <a href="{{ route('medicina.ordenes.index') }}" class="btn btn-link btn-sm btn-block text-muted">Volver al listado</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection