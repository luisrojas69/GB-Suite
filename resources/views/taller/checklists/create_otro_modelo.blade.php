@extends('layouts.app') 

@section('title', 'Crear Nuevo Plan de Mantenimiento (Checklist)')

@section('content')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Registro de Plan de Mantenimiento Preventivo</h6>
        </div>
        <div class="card-body">
            
            <form action="{{ route('checklists.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    {{-- Columna 1: Identificación --}}
                    <div class="col-md-6">
                        <h5 class="text-info mb-3">Datos del Plan</h5>

                        {{-- Campo Nombre --}}
                        <div class="form-group">
                            <label for="nombre">Nombre del Plan <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre') }}" 
                                   placeholder="Ej: MP 500 Horas Tractor John Deere"
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo Tipo de Activo --}}
                        <div class="form-group">
                            <label for="tipo_activo">Aplica al Tipo de Activo <span class="text-danger">*</span></label>
                            <select name="tipo_activo" 
                                    id="tipo_activo" 
                                    class="form-control @error('tipo_activo') is-invalid @enderror" 
                                    required>
                                <option value="">Seleccione el tipo</option>
                                @foreach ($tipos_activo as $tipo)
                                    <option value="{{ $tipo }}" 
                                            {{ old('tipo_activo') == $tipo ? 'selected' : '' }}>
                                        {{ $tipo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_activo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo Intervalo de Referencia --}}
                        <div class="form-group">
                            <label for="intervalo_referencia">Intervalo de Referencia <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="intervalo_referencia" 
                                   id="intervalo_referencia" 
                                   class="form-control @error('intervalo_referencia') is-invalid @enderror" 
                                   value="{{ old('intervalo_referencia') }}" 
                                   placeholder="Ej: 250 HRS, 12 Meses, 10000 KM"
                                   required>
                            @error('intervalo_referencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    
                    {{-- Columna 2: Tareas --}}
                    <div class="col-md-6">
                         <h5 class="text-info mb-3">Tareas a Realizar</h5>

                         {{-- Campo Descripción de Tareas --}}
                        <div class="form-group">
                            <label for="descripcion_tareas">Lista de Tareas <span class="text-danger">*</span></label>
                            <textarea name="descripcion_tareas" 
                                      id="descripcion_tareas" 
                                      class="form-control @error('descripcion_tareas') is-invalid @enderror" 
                                      rows="10" 
                                      placeholder="Introduce cada tarea en una línea nueva.&#10;Ej:&#10;1. Revisar nivel de aceite de motor&#10;2. Engrasar puntos de articulación&#10;3. Verificar presión de neumáticos"
                                      required>{{ old('descripcion_tareas') }}</textarea>
                            @error('descripcion_tareas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('checklists.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Checklist
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection