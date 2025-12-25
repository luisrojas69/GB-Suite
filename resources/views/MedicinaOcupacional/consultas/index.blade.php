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
        <h1 class="h3 mb-0 text-gray-800">Control de Consultas Médicas</h1>
        <a href="{{ route('medicina.pacientes.index') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-search fa-sm text-white-50"></i> Buscar Paciente para Consulta
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm" id="tblConsultas" width="100%">
                    <thead class="bg-gray-100">
                        <tr>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Cédula</th>
                            <th>Motivo</th>
                            <th>Diagnóstico</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consultas as $con)
                        @php
                            // Verificación de los 3 días para mostrar o no el botón editar
                            $esEditable = $con->created_at->gt(now()->subDays(3));
                        @endphp
                        <tr>
                            <td>{{ $con->created_at->format('d/m/Y h:i A') }}</td>
                            <td class="font-weight-bold">{{ $con->paciente->nombre_completo }}</td>
                            <td>{{ $con->paciente->ci }}</td>
                            <td>{{ Str::limit($con->motivo_consulta, 30) }}</td>
                            <td><span class="badge badge-info">{{ $con->diagnostico_cie10}}</span></td>
                            <td class="text-center">
                                <div class="dropdown no-arrow">
                                    <a class="btn btn-sm btn-outline-success dropdown-toggle text-warning" href="#" role="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-warning"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                        <div class="dropdown-header ">Acciones de Consulta:</div>
                                        <a class="dropdown-item" href="{{ route('medicina.consultas.show', $con->id) }}">
                                            <i class="fas fa-eye fa-sm fa-fw mr-2 text-gray-400"></i> Ver Detalle
                                        </a>
                                        <a class="dropdown-item" href="{{ route('medicina.consultas.imprimir', $con->id) }}" target="_blank">
                                            <i class="fas fa-print fa-sm fa-fw mr-2 text-warning"></i> Imprimir Reporte
                                        </a>
                                        
                                        @if($esEditable)
                                        <a class="dropdown-item" href="{{ route('medicina.consultas.edit', $con->id) }}">
                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-primary"></i> Editar Consulta
                                        </a>
                                        @endif

                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-header">Acceso al Paciente:</div>
                                        <a class="dropdown-item" href="{{ route('medicina.pacientes.show', $con->paciente_id) }}">
                                            <i class="fas fa-user fa-sm fa-fw mr-2 text-info"></i> Ver Perfil Completo
                                        </a>
                                        <a class="dropdown-item" href="{{ route('medicina.accidentes.create', $con->paciente_id) }}">
                                            <i class="fas fa-ambulance fa-sm fa-fw mr-2 text-danger"></i> Reportar Accidente
                                        </a>
                                    </div>
                                </div>
                            </td>
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
        $('#tblConsultas').DataTable({
            language: { url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json" },
            order: [[0, "desc"]]
        });
    });
</script>

@if(session('print_id'))
<script>
    Swal.fire({
        title: '¡Consulta Guardada!',
        text: "¿Desea imprimir el récipe y la constancia médica ahora?",
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#858796',
        confirmButtonText: '<i class="fas fa-print"></i> Imprimir Reporte',
        cancelButtonText: 'Cerrar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Abrimos el PDF en una nueva pestaña
            window.open("{{ route('medicina.consultas.imprimir', session('print_id')) }}", '_blank');
        }
    });
</script>
@endif

@endsection