@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow border-left-warning">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Próximas Re-dotaciones Sugeridas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Trabajador</th>
                            <th>Departamento</th>
                            <th>Última Entrega</th>
                            <th>Días Transcurridos</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alertas as $a)
                        <tr>
                            <td>{{ $a->nombre_completo }}</td>
                            <td>{{ $a->des_depart }}</td>
                            <td>{{ \Carbon\Carbon::parse($a->fecha_entrega)->format('d/m/Y') }}</td>
                            <td><span class="text-danger font-weight-bold">{{ \Carbon\Carbon::parse($a->fecha_entrega)->diffInDays(now()) }} días</span></td>
                            <td>
                                <a href="{{ route('medicina.dotaciones.create', $a->paciente_id) }}" class="btn btn-xs btn-success">Programar Entrega</a>
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
