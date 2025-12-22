@extends('layouts.app') 

@section('title', 'Editar Checklist: ' . $checklist->nombre)

@section('content')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">Editar Plan de Mantenimiento: {{ $checklist->nombre }}</h6>
        </div>
        <div class="card-body">
            
            {{-- Formulario apunta a la ruta UPDATE con el método PUT --}}
            <form action="{{ route('checklists.update', $checklist->id) }}" method="POST">
                @csrf
                @method('PUT') 
                
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
                                   value="{{ old('nombre', $checklist->nombre) }}" 
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
                                            {{ old('tipo_activo', $checklist->tipo_activo) == $tipo ? 'selected' : '' }}>
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
                                   value="{{ old('intervalo_referencia', $checklist->intervalo_referencia) }}" 
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
                                      required>{{ old('descripcion_tareas', $checklist->descripcion_tareas) }}</textarea>
                            @error('descripcion_tareas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('checklists.show', $checklist->id) }}" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sync"></i> Actualizar Checklist
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection