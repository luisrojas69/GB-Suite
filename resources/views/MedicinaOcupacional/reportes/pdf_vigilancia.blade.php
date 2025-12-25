<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .header-table { width: 100%; border-bottom: 2px solid #1a592e; margin-bottom: 20px; }
        .logo { width: 70px; }
        .company-info { text-align: right; }
        .company-name { font-size: 16px; font-weight: bold; color: #1a592e; }
        .section-title { background: #f2f2f2; padding: 8px; font-weight: bold; margin-top: 20px; border-left: 5px solid #1a592e; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f8f9fc; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #777; border-top: 1px solid #ddd; padding-top: 5px; }
        .chart-sim { width: 15px; height: 15px; display: inline-block; margin-right: 5px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="border:none;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logoB.png'))) }}" class="logo">
            </td>
            <td style="border:none;" class="company-info">
                <div class="company-name">GRANJA BORAURE, C.A.</div>
                <div>RIF: J-00000000-0</div>
                <div>Departamento de Medicina Ocupacional</div>
                <strong>Informe de Vigilancia Epidemiológica {{ $anioActual }}</strong>
            </td>
        </tr>
    </table>

    <div class="section-title">1. DISTRIBUCIÓN DE POBLACIÓN POR GÉNERO</div>
    <table>
        <thead>
            <tr>
                <th>Género</th>
                <th style="text-align: center;">Total Trabajadores</th>
                <th style="text-align: center;">Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            @php $totalP = $porGenero->sum('total'); @endphp
            @foreach($porGenero as $g)
            <tr>
                <td>{{ $g->sexo == 'M' ? 'Masculino' : 'Femenino' }}</td>
                <td style="text-align: center;">{{ $g->total }}</td>
                <td style="text-align: center;">{{ number_format(($g->total / $totalP) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">2. MORBILIDAD POR SISTEMAS ORGÁNICOS (VIGILANCIA)</div>
    <p>Este desglose permite identificar las áreas del cuerpo más afectadas según los motivos de consulta médica del año actual.</p>
    <table>
        <thead>
            <tr>
                <th>Sistema / Categoría Patológica</th>
                <th style="text-align: center;">Nro. de Casos</th>
                <th style="text-align: center;">Frecuencia Relativa</th>
            </tr>
        </thead>
        <tbody>
            @php $totalS = $porSistemas->sum('total'); @endphp
            @foreach($porSistemas as $s)
            <tr>
                <td><strong>{{ $s->sistema }}</strong></td>
                <td style="text-align: center;">{{ $s->total }}</td>
                <td style="text-align: center;">{{ number_format(($s->total / ($totalS ?: 1)) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; border: 1px solid #ddd; padding: 10px; background-color: #fffdf5;">
        <strong>Conclusiones Preliminares:</strong>
        <p>Basado en los datos obtenidos, el sistema con mayor incidencia es <strong>{{ $porSistemas->first()->sistema ?? 'N/A' }}</strong>. 
        Se recomienda fortalecer los programas de pausas activas y revisión de puestos de trabajo en las áreas correspondientes.</p>
    </div>

    <div class="footer">
        Documento Estricto para Uso Interno del SSSL - Granja Boraure, C.A. - Página 1 de 1
    </div>
</body>
</html>