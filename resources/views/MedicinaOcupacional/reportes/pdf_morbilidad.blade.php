<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Morbilidad</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #333; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { bg-color: #f2f2f2; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>GRANJA BORAURE, C.A.</h2>
        <h3>SERVICIO DE SEGURIDAD Y SALUD LABORAL</h3>
        <h4>Reporte Estadístico de Morbilidad - Mes: {{ $mes }} / {{ $anio }}</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th>Diagnóstico (CIE-10 / Descripción)</th>
                <th style="width: 100px; text-align: center;">Casos</th>
                <th style="width: 100px; text-align: center;">Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            @php $totalCasos = $data->sum('total'); @endphp
            @foreach($data as $item)
            <tr>
                <td>{{ $item->diagnostico_cie10 }}</td>
                <td style="text-align: center;">{{ $item->total }}</td>
                <td style="text-align: center;">{{ number_format(($item->total / $totalCasos) * 100, 2) }}%</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>TOTAL CONSULTAS EN EL MES</th>
                <th style="text-align: center;">{{ $totalCasos }}</th>
                <th>100%</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generado el: {{ date('d/m/Y h:i A') }} | Firma del Médico Ocupacional: ______________________
    </div>
</body>
</html>