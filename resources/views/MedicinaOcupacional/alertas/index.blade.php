@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800 font-weight-bold">
        <i class="fas fa-bell text-warning"></i> Panel de Retornos Diarios: {{ date('d/m/Y') }}
    </h1>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger">
                    <h6 class="m-0 font-weight-bold text-white">Fin de Reposo (Chequeo de Reincorporación)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light small font-weight-bold">
                                <tr>
                                    <th>Paciente</th>
                                    <th>Días Cumplidos</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($retornoReposo as $r)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $r->paciente->nombre_completo }}</div>
                                        <div class="small text-muted">{{ $r->diagnostico_cie10 }}</div>
                                    </td>
                                    <td>{{ $r->dias_reposo }} días</td>
                                    <td>
                                        <a href="{{ route('medicina.consultas.create', ['paciente_id' => $r->paciente_id, 'reincorporacion' => 1]) }}" 
                                           class="btn btn-sm btn-outline-danger">
                                            Iniciar Chequeo
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-4 text-muted">No hay reincorporaciones por reposo para hoy.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info">
                    <h6 class="m-0 font-weight-bold text-white">Retorno de Vacaciones (Post-Vacacional)</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 text-sm">
                        <thead class="bg-light small font-weight-bold">
                            <tr>
                                <th>Paciente</th>
                                <th>Departamento</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($retornoVacaciones as $v)
                            <tr>
                                <td>{{ $v->nombre_completo }}</td>
                                <td>{{ $v->des_depart }}</td>
                                <td>
                                    <a href="{{ route('medicina.consultas.create', ['paciente_id' => $v->id, 'tipo' => 'Post-Vacacional']) }}" 
                                       class="btn btn-sm btn-outline-info">
                                        Evaluar
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted">No hay retornos de vacaciones programados para hoy.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection