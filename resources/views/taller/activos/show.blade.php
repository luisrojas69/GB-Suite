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
                    <div class="card-body text-center">
                        @if($activo->imagen)
                            <img src="{{ asset('storage/' . $activo->imagen) }}" class="img-fluid rounded mb-3" style="max-height: 200px;" alt="Foto del activo">
                        @else
                            <img src="{{ asset('img/default-tractor.jpg') }}" class="img-fluid rounded mb-3" style="max-height: 200px;" alt="Sin foto">
                        @endif
                        
                        <p><strong>Activo:</strong> {{ $activo->codigo }} - {{ $activo->nombre }}</p>
                    </div>
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
                    @can('crear_lecturas') {{-- Asumimos este permiso del seeder --}}
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
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- SOLUCIÓN AL ERROR: Usamos @forelse en lugar de @foreach --}}
                                @forelse ($activo->lecturas as $lectura)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($lectura->fecha_lectura)->format('d/m/Y') }}</td>
                                        <td>{{ number_format($lectura->valor_lectura, 0, ',', '.') }} {{ $lectura->unidad_medida }}</td>
                                        {{-- Asumimos que la relación 'registrador' está cargada y apunta al modelo User --}}
                                        <td>{{ $lectura->registrador->name." ".$lectura->registrador->last_name ?? 'Sistema' }}</td>
                                        <td>{{ $lectura->observaciones ?? 'Sistema' }}</td
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay lecturas registradas.</td>
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
@extends('layouts.app')

@section('title', 'Expediente de Activo: ' . $activo->codigo)

@push('styles')
<style>
    .activo-img-container { width: 150px; height: 150px; cursor: pointer; transition: transform .3s; border: 4px solid #fff; }
    .activo-img-container:hover { transform: scale(1.05); }
    .nav-pills .nav-link.active { background-color: #4e73df; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .card-header-icon { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px; }
    .timeline-small { border-left: 2px solid #e3e6f0; margin-left: 10px; padding-left: 20px; position: relative; }
    .timeline-item-point { position: absolute; left: -7px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background: #4e73df; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('activos.index') }}">Activos</a></li>
                    <li class="breadcrumb-item active">{{ $activo->codigo }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">{{ $activo->nombre }}</h1>
        </div>
        <div class="btn-group shadow-sm">
            <button onclick="window.print()" class="btn btn-light border"><i class="fas fa-print"></i> Imprimir</button>
            <a href="{{ route('activos.edit', $activo->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card shadow mb-4 border-bottom-primary">
                <div class="card-body text-center">
                    <div class="activo-img-container rounded-circle shadow mx-auto mb-3 overflow-hidden" data-toggle="modal" data-target="#imageModal">
                        <img src="{{ $activo->imagen ? asset('storage/'.$activo->imagen) : asset('img/default-tractor.jpg') }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                    </div>
                    <h5 class="font-weight-bold mb-0">{{ $activo->placa ?? 'SIN PLACA' }}</h5>
                    <p class="text-muted small">{{ $activo->marca }} {{ $activo->modelo }}</p>
                    
                    @php
                        $statusColors = [
                            'Operativo' => 'success',
                            'Taller' => 'warning',
                            'Baja' => 'danger',
                            'Reparacion' => 'info'
                        ];
                        $color = $statusColors[$activo->estado_operativo] ?? 'secondary';
                    @endphp
                    <span class="badge badge-{{ $color }} px-3 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-circle fa-xs mr-1"></i> {{ $activo->estado_operativo }}
                    </span>
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between"><span>Depto:</span> <strong>{{ $activo->departamento_asignado }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Tipo:</span> <strong>{{ $activo->tipo }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Adquisición:</span> <strong>{{ $activo->fecha_adquisicion ? $activo->fecha_adquisicion->format('d/m/Y') : 'N/A' }}</strong></li>
                </ul>
            </div>

            <div class="card border-left-info shadow py-2 mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Lectura Actual</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ number_format($activo->lectura_actual, 1) }} <small>{{ $activo->unidad_medida }}</small></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-historial-tab" data-toggle="pill" href="#pills-historial" role="tab"><i class="fas fa-history mr-1"></i> Historial Uso</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-labores-tab" data-toggle="pill" href="#pills-labores" role="tab"><i class="fas fa-tractor mr-1"></i> Log Labores Campo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-mantenimiento-tab" data-toggle="pill" href="#pills-mantenimiento" role="tab"><i class="fas fa-tools mr-1"></i> Mantenimiento</a>
                </li>
            </ul>

            <div class="tab-content bg-white p-4 shadow rounded border" id="pills-tabContent">
                
                <div class="tab-pane fade show active" id="pills-historial" role="tabpanel">
                    <div class="d-flex align-items-center mb-3">
                        <div class="card-header-icon bg-light-primary text-primary mr-3"><i class="fas fa-stopwatch"></i></div>
                        <h5 class="mb-0 font-weight-bold text-dark">Trazabilidad de Horómetros</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Lectura</th>
                                    <th>Registrador</th>
                                    <th>Origen / Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activo->lecturas->take(10) as $lec)
                                <tr>
                                    <td>{{ $lec->fecha_lectura->format('d/m/Y') }}</td>
                                    <td><span class="badge badge-light border font-weight-bold">{{ number_format($lec->valor_lectura, 1) }} {{ $lec->unidad_medida }}</span></td>
                                    <td><small>{{ $lec->registrador->name." ".$lec->registrador->last_name }}</small></td>
                                    <td><span class="text-muted small">{{ $lec->observaciones ?? 'Actualización de rutina' }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-labores" role="tabpanel">
                    <div class="d-flex align-items-center mb-3">
                        <div class="card-header-icon bg-light-success text-success mr-3"><i class="fas fa-leaf"></i></div>
                        <h5 class="mb-0 font-weight-bold text-dark">Labores de Campo Realizadas</h5>
                    </div>
                    @forelse($labores ?? [] as $item)
                    <div class="timeline-small">
                        <div class="timeline-item-point"></div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-0 font-weight-bold">{{ $item->registro->labor->nombre }}</h6>
                                    <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i> {{ $item->registro->fecha_ejecucion->format('d/m/Y') }}</small>
                                </div>
                                <a href="{{ route('produccion.labores.show', $item->registro_labor_id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    Ver Jornada #{{ $item->registro_labor_id }}
                                </a>
                            </div>
                            <div class="mt-2 bg-light p-2 rounded">
                                <small><strong>Horas trabajadas:</strong> {{ $item->horometro_final - $item->horometro_inicial }} hrs | <strong>Operador:</strong> {{ $item->operador->nombre_completo ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted my-5">No se registran labores de campo para este activo.</p>
                    @endforelse
                </div>

                <div class="tab-pane fade" id="pills-mantenimiento" role="tabpanel">
                    <div class="d-flex align-items-center mb-3">
                        <div class="card-header-icon bg-light-warning text-warning mr-3"><i class="fas fa-wrench"></i></div>
                        <h5 class="mb-0 font-weight-bold text-dark">Programaciones y Servicios</h5>
                    </div>
                    <div class="row">
                        <div class="card-body">
                             <div class="table-responsive">
                                <table class="table table-sm table-hover" width="100%" cellspacing="0">
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

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="{{ $activo->imagen ? asset('storage/'.$activo->imagen) : asset('img/default-machinery.png') }}" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection
</div>
@endsection