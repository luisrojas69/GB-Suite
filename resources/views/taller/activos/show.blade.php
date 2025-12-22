@extends('layouts.app') 

@section('title', 'Detalles del Activo: ' . $activo->codigo)

@section('content')

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Activo: {{ $activo->codigo }} - {{ $activo->nombre }}</h1>
        <a href="{{ route('activos.index') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver al Listado
        </a>
    </div>

    <div class="row">
        
        {{-- COLUMNA PRINCIPAL (DATOS GENERALES) --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Información Básica</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Código:</strong> {{ $activo->codigo }}</li>
                        <li class="list-group-item"><strong>Placa:</strong> {{ $activo->placa ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Tipo:</strong> {{ $activo->tipo }}</li>
                        <li class="list-group-item"><strong>Marca/Modelo:</strong> {{ $activo->marca ?? 'N/A' }} / {{ $activo->modelo ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Departamento Asignado:</strong> {{ $activo->departamento_asignado }}</li>
                        <li class="list-group-item">
                            <strong>Fecha Adquisición:</strong> 
                            {{ $activo->fecha_adquisicion ? \Carbon\Carbon::parse($activo->fecha_adquisicion)->format('d/m/Y') : 'N/A' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Estado Operativo:</strong> 
                            <span class="badge badge-{{ $activo->estado_operativo == 'Operativo' ? 'success' : ($activo->estado_operativo == 'En Mantenimiento' ? 'warning' : 'danger') }}">
                                {{ $activo->estado_operativo }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- COLUMNA LATERAL (LECTURAS Y USO) --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">

                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">Uso y Lecturas</h6>
                    
                    {{-- 🟢 BOTÓN PARA AGREGAR NUEVA PROGRAMACIÓN 🟢 --}}
                    @can('registrar_lecturas') {{-- Asumimos este permiso del seeder --}}
                        <a href="{{ route('lecturas.create', ['activo' => $activo->id]) }}" 
                           class="btn btn-success btn-sm shadow-sm"
                           title="Crear Nueva Programación de MP">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Agregar Lectura
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">
                            <strong>Unidad de Medida:</strong> {{ $activo->unidad_medida }}
                        </li>
                        <li class="list-group-item">
                            <strong>Lectura Actual:</strong> 
                            <span class="h4 text-primary">{{ number_format($activo->lectura_actual, 0, ',', '.') }}</span> {{ $activo->unidad_medida }}
                        </li>
                    </ul>

                    <h6 class="mt-4 mb-2">Historial Reciente de Lecturas:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Valor</th>
                                    <th>Registrador</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- SOLUCIÓN AL ERROR: Usamos @forelse en lugar de @foreach --}}
                                @forelse ($activo->lecturas as $lectura)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($lectura->fecha_lectura)->format('d/m/Y') }}</td>
                                        <td>{{ number_format($lectura->valor_lectura, 0, ',', '.') }} {{ $lectura->unidad_medida }}</td>
                                        {{-- Asumimos que la relación 'registrador' está cargada y apunta al modelo User --}}
                                        <td>{{ $lectura->registrador->name ?? 'Sistema' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No hay lecturas registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- SECCIÓN MANTENIMIENTO PREVENTIVO --}}
    {{-- SECCIÓN MANTENIMIENTO PREVENTIVO --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">Programación de Mantenimiento Preventivo (MP)</h6>
            
            {{-- 🟢 BOTÓN PARA AGREGAR NUEVA PROGRAMACIÓN 🟢 --}}
            @can('programar_mp') {{-- Asumimos este permiso del seeder --}}
                <a href="{{ route('programacionesMP.create', ['activo' => $activo->id]) }}" 
                   class="btn btn-success btn-sm shadow-sm"
                   title="Crear Nueva Programación de MP">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Agregar MP
                </a>
            @endcan
        </div>
        <div class="card-body">
             <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>MP Asignado</th>
                            <th>Último Valor</th>
                            <th>Próximo Valor Meta</th>
                            <th>Próxima Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th> {{-- Agregamos la columna de acciones para MPs --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activo->programacionesMP as $mp)
                            @php
                                $mp_status_class = match($mp->status) {
                                    'Vigente' => 'success',
                                    'Proximo a Vencer' => 'warning',
                                    'Vencido' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <tr>
                                <td>{{ $mp->checklist->nombre ?? 'N/A' }}</td>
                                <td>{{ number_format($mp->ultimo_valor_ejecutado, 0) }} {{ $activo->unidad_medida }} ({{ \Carbon\Carbon::parse($mp->ultima_ejecucion_fecha)->format('d/m/Y') }})</td>
                                <td>{{ number_format($mp->proximo_valor_lectura, 0) }} {{ $activo->unidad_medida }}</td>
                                <td>{{ $mp->proxima_fecha_mantenimiento ? \Carbon\Carbon::parse($mp->proxima_fecha_mantenimiento)->format('d/m/Y') : 'N/A' }}</td>
                                <td><span class="badge badge-{{ $mp_status_class }}">{{ $mp->status }}</span></td>
                                <td>
                                    {{-- Botón de Acciones para la Programación Específica (Ej: Ver/Editar) --}}
                                    @can('programar_mp')
                                        <a href="{{ route('programacionesMP.edit', $mp->id) }}" class="btn btn-sm btn-info" title="Editar MP"><i class="fas fa-edit"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay programaciones de MP activas para este activo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection