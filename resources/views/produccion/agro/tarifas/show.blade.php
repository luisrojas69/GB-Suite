@extends('layouts.app')
@section('title', 'Detalle de Liquidación #' . $liquidacion->id)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🔍 Detalle de Liquidación: **#{{ $liquidacion->id }}**</h1>
        <a href="{{ route('liquidacion.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Histórico
        </a>
    </div>

    @can('ver_liquidaciones')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Información General de Liquidación</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Arrime de Referencia:</strong> <span class="badge badge-info">#{{ $liquidacion->molienda_id }}</span></p>
                        <p><strong>Fecha de Cierre:</strong> {{ $liquidacion->fecha_cierre ? $liquidacion->fecha_cierre->format('d/m/Y') : 'N/A' }}</p>
                        <p><strong>Fecha de Registro:</strong> {{ $liquidacion->created_at->format('d/m/Y h:i A') }}</p>
                        <p><strong>Peso Arrimado (Tn):</strong> {{ number_format($liquidacion->molienda->toneladas_arrimadas ?? 0, 2, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Polarización (%) en Caña:</strong> {{ number_format($liquidacion->pol_cana, 2, ',', '.') }} %</p>
                        <p><strong>Fibra (%) en Caña:</strong> {{ number_format($liquidacion->fibra_cana, 2, ',', '.') }} %</p>
                        <p><strong>Precio Base (T.T.P. / Azúcar):</strong> {{ '$ ' . number_format($liquidacion->precio_base, 4, ',', '.') }}</p>
                    </div>
                </div>
                
                <hr>

                <div class="row bg-light py-3 rounded">
                    <div class="col-md-6">
                        <h5 class="text-danger">Deducibles: <span class="float-right">{{ '$ ' . number_format($liquidacion->deducibles, 2, ',', '.') }}</span></h5>
                    </div>
                    <div class="col-md-6">
                        <h4 class="text-success">Liquidación Neta: <span class="float-right">{{ '$ ' . $liquidacion->liquidacion_neta }}</span></h4>
                    </div>
                </div>

                <div class="mt-4">
                    @can('generar_liquidaciones')
                        <a href="{{ route('liquidacion.edit', $liquidacion->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar Liquidación</a>
                    @endcan
                    {{-- Puedes añadir aquí un botón para la descarga de PDF --}}
                    {{-- <a href="{{ route('liquidacion.download.pdf', $liquidacion->id) }}" class="btn btn-secondary ml-2"><i class="fas fa-file-pdf"></i> Descargar PDF</a> --}}
                </div>
                
            </div>
        </div>
        
    @else
        <p class="alert alert-danger">Usted no tiene permisos para ver el detalle de esta Liquidación.</p>
    @endcan

</div>
@endsection