<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pre-Liquidación de Fletes</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2d6a4f; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #2d6a4f; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .total-row { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .footer { margin-top: 50px; font-size: 10px; text-align: center; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PRE-LIQUIDACIÓN DE FLETES DE CAÑA</h2>
        <p>Periodo: {{ $filtros['desde'] }} al {{ $filtros['hasta'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Contratista</th>
                <th>Sector</th>
                <th class="text-right">Viajes</th>
                <th class="text-right">Toneladas</th>
                <th class="text-right">Tarifa ($)</th>
                <th class="text-right">Total ($)</th>
            </tr>
        </thead>
        <tbody>
            @php $granTotal = 0; @endphp
            @forelse($data as $r)
            <tr>
                <td>{{ $r->contratista_nombre }}</td>
                <td>{{ $r->sector_nombre }}</td>
                <td class="text-right">{{ $r->cantidad_viajes}}</td>
                <td class="text-right">{{ number_format($r->total_toneladas, 2) }}</td>
                <td class="text-right">{{ number_format($r->tarifa_flete, 2) }}</td>
                <td class="text-right">{{ number_format($r->monto_total, 2) }}</td>
            </tr>
            @php $granTotal += $r->monto_total; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">MONTO TOTAL A PAGAR:</td>
                <td class="text-right">${{ number_format($granTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Este documento es una pre-liquidación interna y está sujeto a revisión con los boletos físicos de romana.</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>