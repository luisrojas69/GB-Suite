@extends('layouts.app')

@section('content')
{{-- Mostrar mensajes de sesión --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<h1 class="h3 mb-4 text-gray-800">Histórico de Aforos (Mediciones de Caudal)</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Listado de Todas las Mediciones de Caudal</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pozo</th>
                        <th>Fecha Medición</th>
                        <th>Caudal (Lts/Seg)</th>
                        <th>Nivel Estático (m)</th>
                        <th>Nivel Dinámico (m)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aforos as $aforo)
                    <tr>
                        <td>{{ $aforo->id }}</td>
                        <td><a href="{{ route('produccion.pozos.activos.show', $aforo->pozo) }}">{{ $aforo->pozo->nombre }}</a></td>
                        <td>{{ $aforo->fecha_medicion->format('d/m/Y') }}</td>
                        <td><span class="font-weight-bold text-primary">{{ $aforo->caudal_medido_lts_seg }}</span></td>
                        <td>{{ $aforo->nivel_estatico ?? 'N/A' }}</td>
                        <td>{{ $aforo->nivel_dinamico ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('produccion.pozos.aforos.show', $aforo) }}" class="btn btn-sm btn-primary"><i class="fas fa-chart-bar"></i> Analizar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection