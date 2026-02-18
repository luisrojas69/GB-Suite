@extends('layouts.app')

@section('titulo', 'Cerrar Orden y Cargar Resultados')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('medicina.ordenes.update', $orden->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-file-medical-alt mr-2"></i> Procesando Orden #{{ $orden->id }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-secondary py-2">
                            <strong>Paciente:</strong> {{ $orden->paciente->nombre_completo }}
                            <span class="mx-2">|</span> 
                            <strong>C.I:</strong> {{ $orden->paciente->ci }}
                        </div>

                        <div class="mb-4">
                            <label class="text-xs font-weight-bold text-uppercase text-muted">Exámenes Solicitados en esta Orden:</label>
                            <div class="d-flex flex-wrap">
                                @foreach($orden->examenes as $examen)
                                    <span class="badge badge-light border mr-2 mb-2 p-2">
                                        <i class="fas fa-microscope text-primary mr-1"></i> {{ $examen }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-gray-800">1. Juicio Clínico Global:</label>
                            <div class="d-flex justify-content-start mt-2">
                                <div class="custom-control custom-radio custom-control-inline mr-4">
                                    <input type="radio" id="normal" name="interpretacion" value="Normal" class="custom-control-input" required>
                                    <label class="custom-control-label text-success font-weight-bold" for="normal">
                                        <i class="fas fa-check-circle mr-1"></i> RESULTADOS NORMALES
                                    </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="alterado" name="interpretacion" value="Alterado" class="custom-control-input">
                                    <label class="custom-control-label text-danger font-weight-bold" for="alterado">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> RESULTADOS ALTERADOS
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-gray-800" for="hallazgos">2. Hallazgos / Observaciones:</label>
                            <textarea class="form-control" name="hallazgos" id="hallazgos" rows="3" 
                                placeholder="Describa brevemente lo relevante (ej. Leve aumento de Triglicéridos, resto dentro de límites normales...)"></textarea>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-gray-800">3. Adjuntar Informe Escaneado (PDF/Imagen):</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="archivo_adjunto" id="archivo_adjunto" accept=".pdf,.jpg,.png">
                                <label class="custom-file-label" for="archivo_adjunto">Elegir archivo...</label>
                            </div>
                            <small class="text-muted">Formatos permitidos: PDF, JPG, PNG. Máximo 5MB.</small>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-between">
                        <a href="{{ route('medicina.ordenes.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success shadow">
                            <i class="fas fa-save mr-1"></i> Guardar Resultados y Cerrar Orden
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Mostrar nombre del archivo seleccionado en el input de Bootstrap
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@endsection