@extends('layouts.app')

@section('title', 'Registrar Nuevo Activo')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-primary py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="m-0 font-weight-bold text-white">
                                <i class="fas fa-plus-circle mr-2"></i> Nuevo Activo de Maquinaria
                            </h5>
                            <p class="text-white-50 small mb-0 mt-1">Complete la información técnica para dar de alta el equipo en el sistema.</p>
                        </div>
                        <i class="fas fa-tractor fa-2x text-white-50"></i>
                    </div>
                </div>

                <div class="card-body p-lg-5">
                    <form action="{{ route('activos.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation">
                        @csrf
                        
                        {{-- Partial del Formulario --}}
                        @include('taller.activos.partials._form', [
                            'activo' => new \App\Models\Logistica\Taller\Activo(), 
                            'action' => 'create'
                        ])

                        <div class="mt-5 pt-4 border-top d-flex justify-content-between align-items-center">
                            <p class="text-muted small"><span class="text-danger">*</span> Campos obligatorios</p>
                            <div>
                                <a href="{{ route('activos.index') }}" class="btn btn-light px-4 mr-2">
                                    <i class="fas fa-times mr-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Guardar y Finalizar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection