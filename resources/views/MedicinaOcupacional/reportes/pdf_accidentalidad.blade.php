<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .header-table { width: 100%; border: none; margin-bottom: 30px; }
        .logo { width: 80px; }
        .company-name { font-size: 18px; font-weight: bold; color: #1a592e; } /* Verde caña */
        .title { text-align: center; background: #f8f9fc; padding: 10px; border: 1px solid #e3e6f0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #e74a3b; color: white; padding: 10px; font-size: 11px; text-transform: uppercase; }
        td { padding: 10px; border: 1px solid #e3e6f0; font-size: 11px; }
        .puntos-criticos { color: #e74a3b; font-weight: bold; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="border:none; width: 20%;">
                {{-- Aquí va el logo cargado desde public --}}
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logoB.png'))) }}" class="logo">
            </td>
            <td style="border:none; width: 80%; text-align: right;">
                <div class="company-name">GRANJA BORAURE, C.A.</div>
                <div>RIF: J-00000000-0</div>
                <div>Servicio de Seguridad y Salud Laboral (SSSL)</div>
            </td>
        </tr>
    </table>

    <div class="title">
        <h3>INFORME DE ACCIDENTALIDAD Y PUNTOS CRÍTICOS</h3>
        <p>Periodo: Año Fiscal {{ date('Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Lugar / Ubicación de la Granja</th>
                <th style="text-align: center;">Nro. Incidentes</th>
                <th style="text-align: center;">Nivel de Riesgo</th>
                <th style="text-align: center;">Frecuencia %</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAcc = $data->sum('total'); @endphp
            @foreach($data as $lugar)
            <tr>
                <td>{{ $lugar->lugar_exacto }}</td>
                <td style="text-align: center;">{{ $lugar->total }}</td>
                <td style="text-align: center;">
                    @if($lugar->total > 5) <span class="puntos-criticos">ALTO</span>
                    @elseif($lugar->total > 2) <span style="color:orange">MEDIO</span>
                    @else <span style="color:green">BAJO</span> @endif
                </td>
                <td style="text-align: center;">{{ number_format(($lugar->total / $totalAcc) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <p class="small text-muted"><strong>Nota:</strong> Este reporte identifica los focos de riesgo para la aplicación de medidas preventivas inmediatas.</p>
    </div>
</body>
</html>