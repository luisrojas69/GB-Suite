@extends('layouts.app') 

@section('title', 'Editar Lectura No. ' . $lectura->id)

@section('content')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">Editar Lectura de Activo: **{{ $lectura->activo->codigo ?? 'N/A' }}**</h6>
        </div>
        <div class="card-body">
            
            <form action="{{ route('lecturas.update', $lectura->id) }}" method="POST">
                @csrf
                @method('PUT') 
                
                <h5 class="text-info mb-4">Modificar Registro</h5>

                <div class="row">
                    {{-- Columna 1: Información del Activo --}}
                    <div class="col-md-6">
                        
                        {{-- Activo Asociado (No editable) --}}
                        <div class="form-group">
                            <label>Activo Asociado</label>
                            <input type="text" class="form-control" value="{{ $lectura->activo->codigo ?? 'N/A' }} - {{ $lectura->activo->nombre ?? 'Activo Eliminado' }}" disabled>
                            <input type="hidden" name="activo_id" value="{{ $lectura->activo_id }}">
                            <small class="form-text text-muted">La unidad de medida es **{{ $lectura->unidad_medida }}**.</small>
                        </div>
                        
                        {{-- Campo Fecha de la Lectura --}}
                        <div class="form-group">
                            <label for="fecha_lectura">Fecha de la Lectura <span class="text-danger">*</span></label>
                            <input type="date" 
                                   name="fecha_lectura" 
                                   id="fecha_lectura" 
                                   class="form-control @error('fecha_lectura') is-invalid @enderror" 
                                   value="{{ old('fecha_lectura', \Carbon\Carbon::parse($lectura->fecha_lectura)->toDateString()) }}" 
                                   max="{{ now()->toDateString() }}"
                                   required>
                            @error('fecha_lectura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Columna 2: Valor y Observaciones --}}
                    <div class="col-md-6">
                        
                        {{-- Campo Valor de la Lectura --}}
                        <div class="form-group">
                            <label for="valor_lectura">Valor de la Lectura ({{ $lectura->unidad_medida }}) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="valor_lectura" 
                                   id="valor_lectura" 
                                   class="form-control @error('valor_lectura') is-invalid @enderror" 
                                   value="{{ old('valor_lectura', $lectura->valor_lectura) }}" 
                                   min="{{ \App\Models\Logistica\Taller\LecturaActivo::where('activo_id', $lectura->activo_id)->where('id', '!=', $lectura->id)->latest('valor_lectura')->value('valor_lectura') ?? 0 }}" 
                                   required>
                            @php
                                $penultimaLecturaValor = \App\Models\Logistica\Taller\LecturaActivo::where('activo_id', $lectura->activo_id)
                                                                ->where('id', '!=', $lectura->id)
                                                                ->latest('valor_lectura')
                                                                ->value('valor_lectura');
                                $minDisplay = $penultimaLecturaValor ? number_format($penultimaLecturaValor) : 0;
                            @endphp
                            <small class="form-text text-muted">El nuevo valor debe ser mayor o igual a la lectura anterior registrada ({{ $minDisplay }} {{ $lectura->unidad_medida }}).</small>
                            @error('valor_lectura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo Observaciones --}}
                        <div class="form-group">
                            <label for="observaciones">Observaciones (Opcional)</label>
                            <textarea name="observaciones" 
                                      id="observaciones" 
                                      class="form-control @error('observaciones') is-invalid @enderror" 
                                      rows="2">{{ old('observaciones', $lectura->observaciones) }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('lecturas.show', $lectura->id) }}" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sync"></i> Actualizar Lectura
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection