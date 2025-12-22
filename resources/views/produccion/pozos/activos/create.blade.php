@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Crear Nuevo Activo (Pozo o Estación)</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('produccion.pozos.activos.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nombre">Nombre / Identificador</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ubicacion">Ubicación (Coordenadas o Finca)</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tipo_activo">Tipo de Activo</label>
                            <select id="tipo_activo" name="tipo_activo" class="form-control select2" required>
                                <option value="" disabled selected>Seleccione</option>
                                <option value="POZO">Pozo de Extracción</option>
                                <option value="ESTACION_REBOMBEO">Estación de Rebombeo</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="div_subtipo_pozo" style="display:none;">
                            <label for="subtipo_pozo">Subtipo de Pozo</label>
                            <select id="subtipo_pozo" name="subtipo_pozo" class="form-control select2">
                                <option value="TURBINA">Turbina</option>
                                <option value="SUMERGIBLE">Sumergible</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row" id="div_asociacion_pozo" style="display:none;">
                        <div class="form-group col-md-6">
                            <label for="id_pozo_asociado">Pozo Asociado (para Estaciones de Rebombeo)</label>
                            <select id="id_pozo_asociado" name="id_pozo_asociado" class="form-control select2">
                                <option value="">N/A (Pozo Principal)</option>
                                @foreach($pozos as $pozo)
                                    <option value="{{ $pozo->id }}">{{ $pozo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="estatus_actual">Estatus Inicial</label>
                        <select id="estatus_actual" name="estatus_actual" class="form-control select2" required>
                            <option value="OPERATIVO">Operativo</option>
                            <option value="PARADO">Parado</option>
                            <option value="EN_MANTENIMIENTO">En Mantenimiento</option>
                        </select>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                        <span class="text">Guardar Activo</span>
                    </button>
                    <a href="{{ route('produccion.pozos.activos.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#tipo_activo').on('change', function() {
            var tipo = $(this).val();
            
            // Mostrar/Ocultar Subtipo de Pozo
            if (tipo === 'POZO') {
                $('#div_subtipo_pozo').show();
                $('#div_asociacion_pozo').hide();
            } else if (tipo === 'ESTACION_REBOMBEO') {
                $('#div_subtipo_pozo').hide();
                $('#div_asociacion_pozo').show();
            } else {
                $('#div_subtipo_pozo').hide();
                $('#div_asociacion_pozo').hide();
            }
             // Lógica adicional para traducir Select2 a español (opcional, pero mejora la UX)
            $('.select2').select2({
                language: "es" // Asumiendo que tienes un archivo de localización para 'es'
            });
        }).trigger('change'); // Ejecutar al cargar para inicializar el estado
    });
</script>

@endsection