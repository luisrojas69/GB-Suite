@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Reportes de Consumo</h1>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros de Exportación</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('comedor.reports.generar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Desde:</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="{{ date('Y-m-01') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Hasta:</label>
                            <input type="date" name="fecha_fin" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Formato de Salida:</label>
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" name="formato" value="pdf" class="btn btn-danger btn-block">
                                        <i class="fas fa-file-pdf"></i> Generar PDF
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="submit" name="formato" value="excel" class="btn btn-success btn-block">
                                        <i class="fas fa-file-excel"></i> Generar Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer small text-muted text-center">
                    Reporte conciliador para auditoría con contratista.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection