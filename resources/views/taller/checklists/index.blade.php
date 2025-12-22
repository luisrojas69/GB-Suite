@extends('layouts.app') 

@section('title', 'Gestión de Planes de Mantenimiento (Checklists)')

@section('content')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">Listado de Checklists / Planes de MP</h6>
            
            @can('gestionar_checklists')
                <a href="{{ route('checklists.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Crear Nuevo Plan
                </a>
            @endcan
        </div>
        <div class="card-body">
            
            {{-- Mensajes de Sesión --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nombre del Plan</th>
                            <th>Aplica a Tipo de Activo</th>
                            <th>Intervalo Ref.</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($checklists as $checklist)
                            <tr>
                                <td>{{ $checklist->nombre }}</td>
                                <td><span class="badge badge-info">{{ $checklist->tipo_activo }}</span></td>
                                <td>{{ $checklist->intervalo_referencia }}</td>
                                <td>
                                    @can('gestionar_checklists')
                                        {{-- Botón VER --}}
                                        <a href="{{ route('checklists.show', $checklist->id) }}" class="btn btn-info btn-circle btn-sm" title="Ver Detalles"><i class="fas fa-eye"></i></a>
                                        
                                        {{-- Botón EDITAR --}}
                                        <a href="{{ route('checklists.edit', $checklist->id) }}" class="btn btn-warning btn-circle btn-sm" title="Editar Plan"><i class="fas fa-edit"></i></a>
                                        
                                        {{-- Botón ELIMINAR (Modal) --}}
                                        {{-- <button type="button" class="btn btn-danger btn-circle btn-sm" data-toggle="modal" data-target="#deleteModal{{ $checklist->id }}" title="Eliminar"><i class="fas fa-trash"></i></button> --}}
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay planes de mantenimiento (Checklists) registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $checklists->links() }}
            </div>
            
        </div>
    </div>
</div>
@endsection