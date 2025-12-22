@extends('layouts.app')

@section('title', 'Registrar Nuevo Activo')

@section('content')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulario de Registro de Activo</h6>
        </div>
        <div class="card-body">
            
            <form action="{{ route('activos.store') }}" method="POST">
                @csrf
                
                {{-- Incluye el formulario parcial para la estructura de campos --}}
                @include('taller.activos.partials._form', [
                    // El modelo $activo no existe en la creación
                    'activo' => new \App\Models\Logistica\Taller\Activo(), 
                    'action' => 'create' // Indicador para el partial si se necesita diferenciar la lógica
                ])

                <hr>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('activos.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Activo
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection