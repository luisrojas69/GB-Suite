@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar Activo: {{ $activo->nombre }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('produccion.pozos.activos.update', $activo) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nombre">Nombre / Identificador</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $activo->nombre) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ubicacion">Ubicación (Coordenadas o Finca)</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="{{ old('ubicacion', $activo->ubicacion) }}" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Tipo de Activo</label>
                            <input type="text" class="form-control" value="{{ $activo->tipo_activo }}" readonly>
                            <input type="hidden" name="tipo_activo" value="{{ $activo->tipo_activo }}">
                        </div>
                        @if ($activo->tipo_activo == 'POZO')
                        <div class="form-group col-md-6">
                            <label for="subtipo_pozo">Subtipo de Pozo</label>
                            <select id="subtipo_pozo" name="subtipo_pozo" class="form-control select2">
                                <option value="TURBINA" {{ old('subtipo_pozo', $activo->subtipo_pozo) == 'TURBINA' ? 'selected' : '' }}>Turbina</option>
                                <option value="SUMERGIBLE" {{ old('subtipo_pozo', $activo->subtipo_pozo) == 'SUMERGIBLE' ? 'selected' : '' }}>Sumergible</option>
                            </select>
                        </div>
                        @endif
                    </div>
                    
                    @if ($activo->tipo_activo == 'ESTACION_REBOMBEO')
                    <div class="form-group">
                        <label for="id_pozo_asociado">Pozo Asociado (para Estaciones de Rebombeo)</label>
                        <select id="id_pozo_asociado" name="id_pozo_asociado" class="form-control select2">
                            <option value="">N/A (Pozo Principal)</option>
                            @foreach($pozos as $pozo)
                                <option value="{{ $pozo->id }}" {{ old('id_pozo_asociado', $activo->id_pozo_asociado) == $pozo->id ? 'selected' : '' }}>{{ $pozo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="estatus_actual">Estatus Actual</label>
                        <input type="text" class="form-control" value="{{ $activo->estatus_actual }} (Cambiar en el Detalle)" readonly>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-success btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-sync"></i></span>
                        <span class="text">Actualizar Activo</span>
                    </button>
                    <a href="{{ route('produccion.pozos.activos.show', $activo) }}" class="btn btn-secondary">Cancelar</a>
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
    });
</script>
@endsection