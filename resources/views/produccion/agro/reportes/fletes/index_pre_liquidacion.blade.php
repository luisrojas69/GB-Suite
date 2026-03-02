@extends('layouts.app')
@section('title-page', 'Pre-Visualización de Fletes')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-search-dollar mr-2"></i>Resultados de Pre-Liquidación
            </h5>
            <div>
                <span class="badge badge-light border p-2">Periodo: {{ $filtros['desde'] }} al {{ $filtros['hasta']}}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light text-uppercase small font-weight-bold">
                        <tr>
                            <th>Contratista</th>
                            <th>Sector</th>
                            <th class="text-center">Viajes</th>
                            <th class="text-right">Toneladas Totales</th>
                            <th class="text-right">Tarifa ($)</th>
                            <th class="text-right">Total a Pagar ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $r)
                        <tr>
                            <td class="font-weight-bold">{{ $r->contratista_nombre }}</td>
                            <td>{{ $r->sector_nombre }}</td>
                            <td class="text-center">{{ $r->cantidad_viajes }}</td>
                            <td class="text-right">{{ number_format($r->total_toneladas, 2) }}</td>
                            <td class="text-right">{{ number_format($r->tarifa_flete, 2) }}</td>
                            <td class="text-right font-weight-bold text-success">
                                {{ number_format($r->monto_total, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No se encontraron fletes en este rango.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($data->count() > 0)
                    <tfoot class="bg-dark text-white">
                        <tr>
                            <td colspan="5" class="text-right font-weight-bold text-uppercase">Gran Total Proyectado:</td>
                            <td class="text-right font-weight-bold h5">
                                ${{ number_format($data->sum('monto_total'), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-right">
            <a href="{{ url('produccion/agro/rol_molienda/reportes') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Volver al Centro de Reportes
            </a>

            <a href="{{ route('rol_molienda.exportar', [
                'nombreReporte' => 'preliquidacion_fletes',
                'tipo_exportacion' => 'pdf',
                'fecha_desde' => $filtros['desde'],
                'fecha_hasta' => $filtros['hasta'],
                'zafra_id' => $filtros['zafra_id'],
                'sector_id' => $filtros['sector_id'],
                'contratista_id' => $filtros['contratista_id']
            ]) }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf mr-1"></i> Descargar PDF
            </a>

            <a href="{{ route('rol_molienda.exportar', [
                'nombreReporte' => 'preliquidacion_fletes',
                'tipo_exportacion' => 'excel',
                'fecha_desde' => $filtros['desde'],
                'fecha_hasta' => $filtros['hasta'],
                'zafra_id' => $filtros['zafra_id'],
                'sector_id' => $filtros['sector_id'],
                'contratista_id' => $filtros['contratista_id']
            ]) }}" class="btn btn-success">
                <i class="fas fa-file-excel mr-1"></i> Descargar Excel
            </a>
        </div>
    </div>
</div>
@endsection