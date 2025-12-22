@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-boxes"></i> Inventario General de Semovientes</h1>
        @can('crear_animal')
            <a href="{{ route('animals.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Registrar Nuevo Animal
            </a>
        @endcan
    </div>

    {{-- Mensaje de Éxito --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detalle del Inventario Activo</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID/Tatuaje</th>
                            <th>Especie</th>
                            <th>Categoría (CeCo)</th>
                            <th>Sexo</th>
                            <th>Propietario</th>
                            <th>Ubicación Actual</th>
                            <th>Fecha Nac.</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($animals as $animal)
                        <tr>
                            <td>
                                <strong>{{ $animal->iron_id }}</strong>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ optional($animal->specie)->name }}</span>
                            </td>
                            <td>
                                {{-- Categoría y CeCo --}}
                                <span class="badge badge-info">{{ optional($animal->category)->name }}</span>
                                <small class="text-muted d-block">{{ optional($animal->category)->cost_center_id }}</small>
                            </td>
                            <td>{{ $animal->sex }}</td>
                            <td>{{ optional($animal->owner)->name }}</td>
                            <td>{{ optional($animal->location)->name }}</td>
                            <td>
                                @if ($animal->birth_date)
                                    {{ \Carbon\Carbon::parse($animal->birth_date)->format('d/m/Y') }}
                                @else
                                    N/D
                                @endif
                            </td>
                            <td>
                                @if ($animal->is_active)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    {{-- Este código es más difícil de alcanzar con el filtro where('is_active', true) --}}
                                    <span class="badge badge-danger">Inactivo/Baja</span> 
                                @endif
                            </td>
                            <td class="text-nowrap">
                                
                                @can('ver_animal')
                                {{-- Botón para ver el detalle completo (ruta show) --}}
                                <a href="{{ route('animals.show', $animal->id) }}" class="btn btn-primary btn-circle btn-sm" title="Ver Detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan

                                {{-- Botón para editar (ruta edit) --}}
                                @can('editar_animal')
                                    <a href="{{ route('animals.edit', $animal->id) }}" class="btn btn-info btn-circle btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                {{-- Botón de Baja/Mortalidad (Sugerencia) --}}
                                @can('crear_baja')
                                    <a href="{{ route('bajas.create') }}?iron_id={{ $animal->iron_id }}" class="btn btn-warning btn-circle btn-sm" title="Dar de Baja / Venta">
                                        <i class="fas fa-arrow-alt-circle-down"></i>
                                    </a>
                                @endcan

                                {{-- Botón y Formulario de ELIMINAR --}}
                                @can('elimnar_animal')
                                    <form action="{{ route('animals.destroy', $animal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de que desea eliminar PERMANENTEMENTE a este animal? Se eliminarán todos sus pesajes y eventos asociados.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-circle btn-sm" title="Eliminar Permanentemente">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <i class="fas fa-exclamation-triangle text-warning"></i> No hay animales registrados en el inventario activo.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Paginación --}}
            <div class="d-flex justify-content-center">
                {{ $animals->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection