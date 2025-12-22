@extends('layouts.app')

@section('content')
 {{-- Mostrar mensajes de sesión --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
<div class="card shadow">
    <div class="card-header {{ $dotacion->entregado_en_almacen ? 'bg-danger' : 'bg-success' }} text-white text-center">
        <h3>{{ $dotacion->entregado_en_almacen ? 'EQUIPO YA ENTREGADO' : 'TICKET VÁLIDO' }}</h3>
    </div>
    <div class="card-body">
        <p><strong>Trabajador:</strong> {{ $dotacion->paciente->nombre_completo }}</p>
        <p><strong>Departamento:</strong> {{ $dotacion->paciente->des_depart }}</p>
        <hr>
        <h5>Implementos a Entregar:</h5>
        <ul>
            @if($dotacion->calzado_entregado) <li>Botas Talla: {{ $dotacion->calzado_talla }}</li> @endif
            @if($dotacion->pantalon_entregado) <li>Pantalón Talla: {{ $dotacion->pantalon_talla }}</li> @endif
        </ul>

        @if(!$dotacion->entregado_en_almacen)
            <form action="{{ route('medicina.dotaciones.confirmar', $dotacion->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-block btn-primary btn-lg">
                    <i class="fas fa-check-circle"></i> CONFIRMAR ENTREGA FÍSICA
                </button>
            </form>
        @else
            <div class="alert alert-warning">
                Entregado físicamente el: <strong>{{ $dotacion->fecha_despacho_almacen }}</strong>
            </div>
        @endif
    </div>
</div>
@endsection