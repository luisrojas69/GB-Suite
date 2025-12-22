@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-dark d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">Resumen Diario para Ajuste en Profit</h6>
            <form action="{{ route('medicina.reportes.profit') }}" method="GET" class="form-inline">
                <input type="date" name="fecha" class="form-control form-control-sm mr-2" value="{{ $fecha }}">
                <button type="submit" class="btn btn-primary btn-sm">Consultar</button>
            </form>
        </div>
        <div class="card-body">
            <div id="exportButtons" class="mb-3"></div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tblReporteProfit">
                    <thead class="bg-gray-200">
                        <tr>
                            <th>Código Artículo (Profit)</th>
                            <th class="text-center">Cantidad Total</th>
                            <th>Tipo de Operación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resumen as $r)
                        <tr>
                            <td class="font-weight-bold">{{ $r->co_art }}</td>
                            <td class="text-center">{{ $r->total }}</td>
                            <td>AJUSTE DE SALIDA (EPP)</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No hay despachos confirmados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#tblReporteProfit').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        },
        dom: 'Bfrtip', // Habilita los botones
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
                className: 'btn btn-success btn-sm',
                title: 'Reporte_Profit_{{ $fecha }}',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimir',
                className: 'btn btn-secondary btn-sm',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            }
        ]
    });

    // Mover los botones a nuestro contenedor personalizado si lo deseas
    table.buttons().container().appendTo('#exportButtons');
});
</script>
@endsection