@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📋 Detalle del Gasto #{{ $gasto->id }}</h1>
        <div>
            <a href="{{ route('produccion.animales.costos.expenses.index') }}" class="btn btn-sm btn-secondary shadow-sm mr-2">
                <i class="fas fa-arrow-left"></i> Volver al Historial
            </a>
            @if (!$gasto->accountingExport)
                <a href="{{ route('produccion.animales.costos.expenses.edit', $gasto->id) }}" class="btn btn-sm btn-info shadow-sm">
                    <i class="fas fa-edit"></i> Editar Gasto
                </a>
            @endif
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Información General</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Fecha del Gasto:</strong> {{ $gasto->expense_date->format('d/m/Y') }}</p>
                    <p><strong>Monto:</strong> <span class="text-success font-weight-bold">${{ number_format($gasto->amount, 2, ',', '.') }}</span></p>
                    <p><strong>Tipo de Gasto:</strong> {{ $gasto->costType->name }}</p>
                    <p><strong>Descripción:</strong> {{ $gasto->description ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>UID Trazabilidad (Profit):</strong> <code>{{ $gasto->uid }}</code></p>
                    <p><strong>Estado Contable:</strong> 
                        @if ($gasto->accountingExport)
                            <span class="badge badge-success">EXPORTADO</span> (Lote #{{ $gasto->export_id }})
                        @else
                            <span class="badge badge-warning">PENDIENTE</span>
                        @endif
                    </p>
                    <p><strong>Proveedor / Documento:</strong> {{ $gasto->supplier_name ?? 'N/A' }} / {{ $gasto->document_number ?? 'N/A' }}</p>
                </div>
            </div>
            
            <hr>
            
            <h6 class="m-0 font-weight-bold text-primary">Referencia y Centro de Costo</h6>
            <div class="row mt-3">
                <div class="col-md-6">
                    @php
                        $reference = $gasto->reference;
                        $refName = $reference ? $reference->name ?? $reference->code : 'N/A';
                    @endphp
                    <p><strong>Tipo de Referencia:</strong> {{ strtoupper($gasto->reference_type) }}</p>
                    <p><strong>Referencia ID:</strong> {{ $gasto->reference_id }}</p>
                    <p><strong>Nombre/Código:</strong> {{ $refName }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Centro de Costo (CeCo) Sugerido:</strong> 
                        <span class="badge badge-danger">{{ $gasto->cost_center_id ?? 'No Asignado' }}</span>
                    </p>
                    <p><strong>Cuenta Débito (Gasto):</strong> <code>{{ $gasto->costType->debit_account }}</code></p>
                    <p><strong>Cuenta Crédito (Pasivo):</strong> <code>{{ $gasto->costType->credit_account }}</code></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection