@extends('layouts.app')
@section('title', 'Detalle de Contratista: ' . $contratista->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🔍 Detalle de Contratista: **{{ $contratista->nombre }}**</h1>
        <a href="{{ route('produccion.agro.contratistas.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('ver_contratistas')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Información Detallada</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Columna de Datos --}}
                    <div class="col-md-6 border-right">
                        <p><strong>Nombre / Razón Social:</strong> {{ $contratista->nombre }}</p>
                        <p><strong>RIF / Identificación:</strong> <span class="badge badge-info">{{ $contratista->rif ?? 'N/A' }}</span></p>
                        <p><strong>Trabajos Asignados:</strong> 
                            <span class="badge badge-primary">{{ $contratista->moliendas()->count() }}</span>
                        </p>
                    </div>
                    
                    {{-- Columna de Contacto --}}
                    <div class="col-md-6">
                        <h6>Contacto</h6>
                        <hr class="mt-0">
                        <p><strong>Persona de Contacto:</strong> 
                            {{ $contratista->persona_contacto ?? 'No Definido' }}
                        </p>
                        <p><strong>Teléfono:</strong> 
                            {{ $contratista->telefono ?? 'No Definido' }}
                        </p>
                    </div>
                </div>
                
                <hr>
                
                <p><strong>ID:</strong> {{ $contratista->id }}</p>
                <p><strong>Fecha de Creación:</strong> {{ $contratista->created_at->format('d/m/Y h:i A') }}</p>
                <p><strong>Última Actualización:</strong> {{ $contratista->updated_at->format('d/m/Y h:i A') }}</p>


                <div class="mt-4">
                    @can('editar_contratistas')
                        <a href="{{ route('produccion.agro.contratistas.edit', $contratista->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar Contratista</a>
                    @endcan
                </div>
                
            </div>
        </div>
    @else
        <p class="alert alert-danger">Usted no tiene permisos para ver el detalle de este contratista.</p>
    @endcan
</div>
@endsection