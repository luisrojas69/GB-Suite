@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-water"></i> Registrar Nuevo Aforo</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('produccion.pozos.aforos.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="id_pozo">Seleccionar Pozo</label>
                        <select id="id_pozo" name="id_pozo" class="form-control select2" required>
                            <option value="" disabled selected>Seleccione el Pozo</option>
                            @foreach($pozos as $pozo)
                                <option value="{{ $pozo->id }}" {{ old('id_pozo', $pozoId) == $pozo->id ? 'selected' : '' }}>{{ $pozo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fecha_medicion">Fecha de la Medición</label>
                            <input type="date" class="form-control" id="fecha_medicion" name="fecha_medicion" value="{{ old('fecha_medicion', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="caudal_medido_lts_seg">Caudal Medido (Lts/Seg)</label>
                            <input type="number" step="0.01" class="form-control" id="caudal_medido_lts_seg" name="caudal_medido_lts_seg" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nivel_estatico">Nivel Estático (metros)</label>
                            <input type="number" step="0.01" class="form-control" id="nivel_estatico" name="nivel_estatico">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nivel_dinamico">Nivel Dinámico (metros)</label>
                            <input type="number" step="0.01" class="form-control" id="nivel_dinamico" name="nivel_dinamico">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                        <span class="text">Guardar Aforo</span>
                    </button>
                    <a href="{{ route('produccion.pozos.aforos.index') }}" class="btn btn-secondary">Cancelar</a>
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