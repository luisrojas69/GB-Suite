@extends('layouts.app')

@section('content')
{{-- Mostrar mensajes de sesión --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-info">
                <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-edit"></i> Editar Aforo del Pozo: {{ $aforo->pozo->nombre }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('produccion.pozos.aforos.update', $aforo) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="id_pozo">Pozo</label>
                        <select id="id_pozo" name="id_pozo" class="form-control select2" disabled>
                            <option value="{{ $aforo->pozo->id }}">{{ $aforo->pozo->nombre }}</option>
                        </select>
                        <input type="hidden" name="id_pozo" value="{{ $aforo->id_pozo }}">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fecha_medicion">Fecha de la Medición</label>
                            <input type="date" class="form-control" id="fecha_medicion" name="fecha_medicion" value="{{ old('fecha_medicion', $aforo->fecha_medicion->format('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="caudal_medido_lts_seg">Caudal Medido (Lts/Seg)</label>
                            <input type="number" step="0.01" class="form-control" id="caudal_medido_lts_seg" name="caudal_medido_lts_seg" value="{{ old('caudal_medido_lts_seg', $aforo->caudal_medido_lts_seg) }}" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nivel_estatico">Nivel Estático (metros)</label>
                            <input type="number" step="0.01" class="form-control" id="nivel_estatico" name="nivel_estatico" value="{{ old('nivel_estatico', $aforo->nivel_estatico) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nivel_dinamico">Nivel Dinámico (metros)</label>
                            <input type="number" step="0.01" class="form-control" id="nivel_dinamico" name="nivel_dinamico" value="{{ old('nivel_dinamico', $aforo->nivel_dinamico) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3">{{ old('observaciones', $aforo->observaciones) }}</textarea>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-success btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-sync"></i></span>
                        <span class="text">Actualizar Aforo</span>
                    </button>
                    <a href="{{ route('produccion.pozos.aforos.show', $aforo) }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection