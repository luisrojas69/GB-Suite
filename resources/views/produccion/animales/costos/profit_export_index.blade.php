@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-file-export"></i> Exportación Contable a Profit</h1>
        <a href="{{ route('produccion.animales.costos.expenses.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nuevo Gasto
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-success text-white">
            <h6 class="m-0 font-weight-bold">Generar Archivo XML para Profit</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('produccion.animales.costos.profit.generate_export') }}" method="POST">
                @csrf
                <p>Seleccione el rango de fechas para exportar todos los gastos **pendientes** registrados en ese período.</p>
                
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="start_date">Fecha de Inicio <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                        @error('start_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="end_date">Fecha de Fin <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required>
                        @error('end_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        @if ($pendingExpenses->isEmpty())
                            <button type="submit" class="btn btn-success btn-icon-split" disabled>
                                <span class="icon text-white-50"><i class="fas fa-file-excel"></i></span>
                                <span class="text">No hay gastos pendientes</span>
                            </button>
                        @else
                            <button type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50"><i class="fas fa-file-excel"></i></span>
                                <span class="text">Generar y Descargar XML ({{ $pendingExpenses->count() }} Gastos)</span>
                            </button>
                            <small class="form-text text-muted mt-2">Al exportar, los gastos seleccionados serán marcados como PROCESADOS.</small>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Gastos Pendientes de Exportar ({{ $pendingExpenses->count() }})
            </h6>
        </div>
        <div class="card-body">
            @if ($pendingExpenses->isEmpty())
                <div class="alert alert-info mb-0">No hay gastos registrados que estén pendientes de exportación contable.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo de Gasto</th>
                                <th>Monto</th>
                                <th>Referencia</th>
                                <th>CeCo (Esperado)</th>
                                <th>UID Trazabilidad</th>
                                <th>Proveedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalPendingAmount = 0;
                            @endphp
                            @foreach ($pendingExpenses as $expense)
                                @php
                                    $totalPendingAmount += $expense->amount;
                                    $refName = $expense->reference ? $expense->reference->name ?? $expense->reference->code : 'N/A';
                                    $ceco = $expense->cost_center_id; // Usa el Accessor definido en el modelo Expense
                                @endphp
                                <tr>
                                    <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                                    <td>{{ $expense->costType->name }}</td>
                                    <td class="text-right">${{ number_format($expense->amount, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ strtoupper($expense->reference_type) }}</span>
                                        {{ $refName }} (ID: {{ $expense->reference_id }})
                                    </td>
                                    <td>{{ $ceco ?? 'SIN CECO' }}</td>
                                    <td><code>{{ $expense->uid }}</code></td>
                                    <td>{{ $expense->supplier_name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total Pendiente:</th>
                                <th class="text-right">${{ number_format($totalPendingAmount, 2, ',', '.') }}</th>
                                <th colspan="4"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection