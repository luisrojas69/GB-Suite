@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-hand-holding-usd"></i> Historial de Gastos Registrados</h1>
        
        <div>
            <a href="{{ route('produccion.animales.costos.profit.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mr-2">
                <i class="fas fa-file-export fa-sm text-white-50"></i> Módulo de Exportación a Profit
            </a>
            <a href="{{ route('produccion.animales.costos.expenses.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Registrar Nuevo Gasto
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detalle de Gastos por Período</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="gastosTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo de Gasto</th>
                            <th>Monto</th>
                            <th>Referencia (Animal/Lote)</th>
                            <th>Proveedor</th>
                            <th>UID Trazabilidad</th>
                            <th>Estado Contable</th>
                            {{-- AJUSTE DE ANCHO DE COLUMNA --}}
                            <th style="min-width: 150px;">Acciones</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gastos as $gasto)
                            @php
                                // Lógica para obtener el nombre de la referencia (Animal o Lote)
                                $refName = 'N/A';
                                $refDisplay = '';
                                if ($gasto->reference) {
                                    $refName = $gasto->reference->code ?? $gasto->reference->name ?? 'ID: ' . $gasto->reference_id;
                                    $refDisplay = strtoupper($gasto->reference_type) . ': ' . $refName;
                                }

                                // Determinar el CeCo (para referencia rápida)
                                $ceco = $gasto->cost_center_id ?? 'SIN ASIGNAR';
                            @endphp
                            <tr>
                                <td>{{ $gasto->expense_date->format('Y-m-d') }}</td>
                                <td>{{ $gasto->costType->name }}</td>
                                <td class="text-right">${{ number_format($gasto->amount, 2, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $refDisplay }}</span>
                                    <small class="d-block text-muted">CeCo: {{ $ceco }}</small>
                                </td>
                                <td>{{ $gasto->supplier_name ?? 'Propio' }}</td>
                                <td><code>{{ $gasto->uid }}</code></td>
                                <td>
                                    @if ($gasto->accountingExport)
                                        <span class="badge badge-success">Exportado</span>
                                        <small class="d-block text-muted">Lote #{{ $gasto->export_id }}</small>
                                    @else
                                        <span class="badge badge-warning">Pendiente</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- NUEVO BOTÓN: Ver Detalle --}}
                                    <a href="{{ route('produccion.animales.costos.expenses.show', $gasto->id) }}" class="btn btn-sm btn-primary" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    {{-- Botón de Editar --}}
                                    <a href="{{ route('produccion.animales.costos.expenses.edit', $gasto->id) }}" class="btn btn-sm btn-info" title="Editar Gasto">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- Botón de Eliminar (solo si NO ha sido exportado) --}}
                                    @if (!$gasto->accountingExport)
                                        <form action="{{ route('produccion.animales.costos.expenses.destroy', $gasto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este gasto? Esta acción no se puede deshacer y debe corregirse manualmente en Profit si ya fue exportado.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Gasto">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay gastos registrados. Comience registrando uno.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $gastos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection