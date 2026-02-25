<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comparativo Plan vs Real - Granja Boraure</title>
    <style>
        /* Estilos base consistentes con tu reporte de vigilancia */
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; }
        .header { border-bottom: 3px solid #1b4332; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 120px; }
        .title { text-align: right; text-transform: uppercase; color: #1b4332; }
        
        /* Tabla Premium */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #f8f9fa; border: 1px solid #ccc; padding: 8px; color: #1b4332; font-size: 9px; }
        td { border: 1px solid #eee; padding: 6px; text-align: center; }
        
        /* Semáforos */
        .cumplimiento-alto { color: #27ae60; font-weight: bold; }
        .cumplimiento-bajo { color: #e74c3c; font-weight: bold; }
        
        .kpi-box { 
            display: inline-block; width: 30%; border: 1px solid #eee; 
            padding: 10px; border-radius: 5px; text-align: center; margin-right: 2%;
        }
        .kpi-val { font-size: 18px; font-weight: bold; color: #2d6a4f; }
        .kpi-label { font-size: 8px; color: #777; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header">
        <table style="border: none;">
            <tr>
                <td style="border: none; text-align: left; width: 50%;">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" class="logo">
                </td>
                <td style="border: none; width: 50%;" class="title">
                    <h2 style="margin:0;">Comparativo de Molienda</h2>
                    <small>Plan de Zafra vs Ejecución Real</small>
                </td>
            </tr>
        </table>
    </div>

    {{-- Resumen de la Zafra --}}
    <div style="margin-bottom: 20px;">
        <div class="kpi-box">
            <div class="kpi-val">{{ number_format($data->sum('toneladas_estimadas'), 0) }}</div>
            <div class="kpi-label">Tons Planificadas</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-val">{{ number_format($data->sum('total_real'), 0) }}</div>
            <div class="kpi-label">Tons Molidas</div>
        </div>
        @php 
            $totalPlan = $data->sum('toneladas_estimadas');
            $porcentaje = $totalPlan > 0 ? ($data->sum('total_real') / $totalPlan) * 100 : 0;
        @endphp
        <div class="kpi-box" style="margin-right: 0; background: #f0fdf4;">
            <div class="kpi-val">{{ number_format($porcentaje, 1) }}%</div>
            <div class="kpi-label">Efectividad Global</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Hacienda / Tablón</th>
                <th>Variedad</th>
                <th>Fecha Plan</th>
                <th>Plan (Tons)</th>
                <th>Real (Tons)</th>
                <th>Desviación</th>
                <th>% Cumpl.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $r)
                @php 
                    $cumpl = $r->toneladas_estimadas > 0 ? ($r->total_real / $r->toneladas_estimadas) * 100 : 0;
                    $desv = $r->total_real - $r->toneladas_estimadas;
                @endphp
                <tr>
                    <td style="text-align: left;"><strong>{{ $r->hacienda_nombre }}</strong><br>{{ $r->tablon_codigo }}</td>
                    <td>{{ $r->variedad_nombre }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->fecha_corte_proyectada)->format('d/m/y') }}</td>
                    <td>{{ number_format($r->toneladas_estimadas, 1) }}</td>
                    <td>{{ number_format($r->total_real, 1) }}</td>
                    <td style="color: {{ $desv >= 0 ? '#27ae60' : '#e74c3c' }}">
                        {{ $desv > 0 ? '+' : '' }}{{ number_format($desv, 1) }}
                    </td>
                    <td class="{{ $cumpl >= 90 ? 'cumplimiento-alto' : 'cumplimiento-bajo' }}">
                        {{ number_format($cumpl, 1) }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; background: #f9f9f9; padding: 15px; border-radius: 5px; border-left: 4px solid #1b4332;">
        <strong style="color: #1b4332;">Observaciones Técnicas:</strong>
        <p style="font-size: 9px; line-height: 1.5;">
            Este reporte consolida los datos de planificación cargados en el Rol de Molienda contra los tickets de báscula/arrime recibidos. 
            Las desviaciones negativas superiores al 15% deben ser justificadas por logística de transporte o condiciones climáticas.
        </p>
    </div>

    <div style="position: fixed; bottom: 0; width: 100%; font-size: 8px; text-align: right; color: #aaa;">
        Granja Boraure, C.A. | Generado por {{ auth()->user()->name }} el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>