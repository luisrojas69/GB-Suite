@extends('layouts.app')

@section('title', 'Editar Activo: ' . $activo->codigo)

@section('content')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">Edición de Activo: **{{ $activo->codigo }}**</h6>
        </div>
        <div class="card-body">
            
            {{-- Formulario que apunta al método 'update' usando PUT/PATCH --}}
            <form action="{{ route('activos.update', $activo->id) }}" method="POST">
                @csrf
                @method('PUT') 
                
                {{-- Incluye el formulario parcial para la estructura de campos --}}
                @include('taller.activos.partials._form', [
                    // Pasamos el modelo $activo existente para rellenar los campos
                    'activo' => $activo, 
                    'action' => 'edit' // Indicador de que estamos en modo edición
                ])

                <hr>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('activos.show', $activo->id) }}" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sync-alt"></i> Actualizar Activo
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection