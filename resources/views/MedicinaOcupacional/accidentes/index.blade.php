@extends('layouts.app')

@section('content')
 {{-- Mostrar mensajes de sesión --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Investigación de Accidentes e Incidentes</h1>
        <a href="{{ route('medicina.pacientes.index') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Registrar Nuevo Suceso
        </a>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Accidentes (Total)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $accidentes->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ambulance fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Incidentes (Leves)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $accidentes->where('tipo_evento', 'Incidente (Casi-Accidentes)')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Acciones Correctivas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Activas</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">D&iacute;as sin Accidentes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0 (Por Programar)</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-house-medical-circle-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Investigaciones Registradas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm" id="tblAccidentes" width="100%" cellspacing="0">
                    <thead class="bg-gray-200">
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Trabajador</th>
                            <th>Lugar</th>
                            <th>Tipo de Evento</th>
                            <th>Acciones Correctivas</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accidentes as $acc)
                        <tr>
                            <td class="small">{{ \Carbon\Carbon::parse($acc->fecha_hora_accidente)->format('d/m/Y h:i A') }}</td>
                            <td class="font-weight-bold">{{ $acc->paciente->nombre_completo }}</td>
                            <td>{{ $acc->lugar_exacto }}</td>
                            <td>
                                @php
                                    $badge = 'badge-secondary';
                                    if(str_contains($acc->tipo_evento, 'Tiempo Perdido')) $badge = 'badge-danger';
                                    if(str_contains($acc->tipo_evento, 'Incidente')) $badge = 'badge-warning text-dark';
                                @endphp
                                <span class="badge {{ $badge }} px-2">{{ $acc->tipo_evento }}</span>
                            </td>
                            <td class="small">{{ Str::limit($acc->acciones_correctivas, 60) }}</td>
                            <td class="text-center">
                                <a href="{{ route('medicina.accidentes.show', $acc->id) }}" class="btn btn-info btn-circle btn-sm" title="Ver Investigación Completa">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('medicina.accidentes.inpsasel', $acc->id) }}" class="btn btn-secondary btn-circle btn-sm" title="Imprimir Reporte INPSASEL">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#tblAccidentes').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
            },
            order: [[0, "desc"]], // Mostrar primero los más recientes
            pageLength: 10,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endsection