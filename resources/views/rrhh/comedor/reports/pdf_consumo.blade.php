<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Consumo</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; }
        .badge-invitado { color: #856404; background-color: #fff3cd; padding: 2px 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REPORTE DE CONSUMO DE COMEDOR</h2>
        <p>Periodo: {{ date('d/m/Y', strtotime($inicio)) }} al {{ date('d/m/Y', strtotime($fin)) }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Servicio</th>
                <th>Fecha y Hora</th>
                <th>Costo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $record->employee_id }}</td>
                <td>
                    @if($record->employee_id > 99000)
                        <span class="badge-invitado">INVITADO</span>
                    @endif
                    {{ $record->employee_name ?? 'Usuario Biomérico' }}
                </td>
                <td>{{ $record->mealType->name }}</td>
                <td>{{ $record->punch_time->format('d/m/Y h:i A') }}</td>
                <td class="text-right">${{ number_format($record->cost, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">TOTAL A FACTURAR:</th>
                <th class="text-right">${{ number_format($total, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generado el {{ date('d/m/Y H:i:s') }} - Sistema de Comedor ZK
    </div>
</body>
</html>