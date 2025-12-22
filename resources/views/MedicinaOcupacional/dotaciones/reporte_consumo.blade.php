@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Consumo de EPP por Departamento</h1>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="form-inline" method="GET">
                <label class="mr-2">Desde:</label>
                <input type="date" name="desde" class="form-control mr-3" value="{{ $desde }}">
                <label class="mr-2">Hasta:</label>
                <input type="date" name="hasta" class="form-control mr-3" value="{{ $hasta }}">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Resumen de Entregas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>Departamento</th>
                            <th class="text-center">Botas (Calzado)</th>
                            <th class="text-center">Jeans (Pantalones)</th>
                            <th class="text-center">Camisas / Franelas</th>
                            <th class="text-center">Total Implementos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consumo as $c)
                        <tr>
                            <td class="font-weight-bold">{{ $c->des_depart }}</td>
                            <td class="text-center">{{ $c->total_botas }}</td>
                            <td class="text-center">{{ $c->total_pantalones }}</td>
                            <td class="text-center">{{ $c->total_camisas }}</td>
                            <td class="text-center bg-gray-100 font-weight-bold">
                                {{ $c->total_botas + $c->total_pantalones + $c->total_camisas }}
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