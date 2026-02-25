<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Labores Post-Cosecha - {{ date('Y') }}</title>
    <style>
        /* Reutilizamos y adaptamos tu base de estilos de vigilancia */
        @page { 
            margin: 1.5cm 1cm 2.5cm 1cm;
            @bottom-center { content: element(footer); }
        }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #2d3436;
            line-height: 1.4;
        }

        /* COLORES AGRO PREMIUM */
        .text-agro { color: #1b4332; }
        .bg-agro { background: #1b4332; color: white; }
        .border-agro { border-bottom: 3px solid #1b4332; }

        /* HEADER */
        .header-container { display: table; width: 100%; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 3px solid #1b4332; }
        .header-left { display: table-cell; width: 40%; vertical-align: middle; }
        .header-right { display: table-cell; width: 60%; text-align: right; vertical-align: middle; }
        .logo { max-width: 140px; }
        .report-title { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 5px; color: #1b4332; }

        /* METADATA BOX */
        .metadata-box { background: #f4f7f6; border-radius: 5px; padding: 10px; margin-bottom: 20px; width: 100%; display: table; }
        .meta-item { display: table-cell; padding: 0 10px; border-right: 1px solid #d1d8d6; }
        .meta-label { font-size: 8px; color: #636e72; text-transform: uppercase; }
        .meta-value { font-size: 10px; font-weight: bold; }

        /* TABLA DE DATOS */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f1f3f2; border: 1px solid #d1d8d6; padding: 7px 5px; text-align: left; font-size: 9px; text-transform: uppercase; color: #1b4332; }
        td { border: 1px solid #e2e8e6; padding: 6px 5px; vertical-align: middle; }
        .tr-even { background-color: #fafafa; }

        /* INDICADORES DE VENTANA CRÍTICA */
        .alerta-roja { color: #d63031; font-weight: bold; } /* Retraso en labor */
        .alerta-verde { color: #27ae60; font-weight: bold; } /* A tiempo */

        /* BADGES */
        .badge { padding: 2px 6px; border-radius: 10px; font-size: 8px; font-weight: bold; }
        .badge-propia { background: #e3f2fd; color: #1976d2; }
        .badge-outsourcing { background: #fff3e0; color: #ef6c00; }

        /* SECCIÓN DE RESUMEN (STATS) */
        .stats-container { display: table; width: 100%; border-spacing: 10px; margin-bottom: 10px; }
        .stat-card { display: table-cell; background: white; border: 1px solid #e2e8e6; padding: 10px; text-align: center; border-radius: 4px; }
        .stat-val { font-size: 16px; font-weight: bold; color: #1b4332; }
        .stat-lab { font-size: 8px; color: #636e72; }

        /* FOOTER */
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #95a5a6; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header-container">
        <div class="header-left">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" class="logo">
        </div>
        <div class="header-right">
            <div style="font-size: 16px; font-weight: 800; color: #1b4332;">GRANJA BORAURE, C.A.</div>
            <div style="font-size: 9px; color: #636e72;">RIF: J-08500570-6 | Gerencia de Producción Agrícola</div>
            <div class="report-title">Control de Labores Post-Cosecha</div>
        </div>
    </div>

    <div class="metadata-box">
        <div class="meta-item">
            <div class="meta-label">Periodo</div>
            <div class="meta-value">{{ \Carbon\Carbon::parse($filtros['desde'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($filtros['hasta'])->format('d/m/Y') }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Sector Seleccionado</div>
            <div class="meta-value">{{ $filtros['sector_id'] == 'todos' ? 'TODOS LOS SECTORES' : 'SECTOR ESPECÍFICO' }}</div>
        </div>
        <div class="meta-item" style="border-right: none;">
            <div class="meta-label">Fecha Emisión</div>
            <div class="meta-value">{{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    {{-- Resumen Ejecutivo rápido --}}
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-val">{{ $data->count() }}</div>
            <div class="stat-lab">LABORES REGISTRADAS</div>
        </div>
        <div class="stat-card">
            <div class="stat-val">{{ number_format($data->whereNull('contratista_id')->count()) }}</div>
            <div class="stat-lab">EJECUCIÓN IN-HOUSE</div>
        </div>
        <div class="stat-card">
            <div class="stat-val">{{ number_format($data->sum('horas_totales'), 1) }}</div>
            <div class="stat-lab">HORAS MÁQUINA TOTALES</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%">Fecha</th>
                <th width="7%">Ventana*</th>
                <th width="15%">Ubicación</th>
                <th width="15%">Labor</th>
                <th width="15%">Ejecución</th>
                <th width="12%">Equipo</th>
                <th width="18%">Horómetros (I-F)</th>
                <th width="10%">Horas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $labor)
            <tr class="{{ $index % 2 == 0 ? '' : 'tr-even' }}">
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($labor->fecha_ejecucion)->format('d/m/y') }}</td>
                
                {{-- Cálculo de días ventana --}}
                <td style="text-align: center;">
                    @php 
                        $dias = \Carbon\Carbon::parse($labor->tablon->fecha_arrime)->diffInDays($labor->fecha_ejecucion);
                    @endphp
                    <span class="{{ $dias > 5 ? 'alerta-roja' : 'alerta-verde' }}">
                        {{ $dias }} d.
                    </span>
                </td>

                <td>
                    <strong>{{ $labor->tablon->lote->sector->nombre }}</strong><br>
                    <small>{{ $labor->tablon->codigo_completo }}</small>
                </td>

                <td>{{ $labor->tipo_labor }}</td>

                <td>
                    @if($labor->contratista_id)
                        <span class="badge badge-outsourcing">CONTRATISTA</span><br>
                        <small>{{ Str::limit($labor->contratista->nombre, 15) }}</small>
                    @else
                        <span class="badge badge-propia">IN-HOUSE</span>
                    @endif
                </td>

                <td>{{ $labor->maquinaria ? $labor->maquinaria->codigo : 'Manual' }}</td>

                <td style="text-align: center; color: #636e72;">
                    @if($labor->horometro_inicial)
                        {{ number_format($labor->horometro_inicial, 1) }} - {{ number_format($labor->horometro_final, 1) }}
                    @else
                        ---
                    @endif
                </td>

                <td style="text-align: right; font-weight: bold;">
                    {{ $labor->horometro_inicial ? number_format($labor->horometro_final - $labor->horometro_inicial, 1) : '0.0' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Notas al pie de la tabla --}}
    <p style="font-size: 8px; color: #636e72; font-style: italic;">
        * Ventana: Días transcurridos desde el último arrime/quema hasta la ejecución de la labor. 
        Un valor > 5 días impacta directamente en la estimación de la edad de la caña.
    </p>

    {{-- Cuadro de Insights similar a tu PDF de Vigilancia --}}
    <div style="margin-top: 20px; border: 1px solid #1b4332; border-radius: 4px; padding: 10px; background: #fdfdfd;">
        <div style="font-weight: bold; color: #1b4332; margin-bottom: 5px;">💡 Análisis de Eficiencia Operativa:</div>
        <div style="font-size: 9px;">
            En el periodo consultado, se observa que el <strong>{{ number_format(($data->whereNull('contratista_id')->count() / ($data->count() ?: 1)) * 100) }}%</strong> 
            de las labores fueron realizadas con maquinaria propia. 
            @php $retrasados = $data->filter(fn($l) => \Carbon\Carbon::parse($l->tablon->fecha_arrime)->diffInDays($l->fecha_ejecucion) > 5)->count(); @endphp
            Se detectaron <strong>{{ $retrasados }} tablones</strong> con ejecución fuera de la ventana óptima (> 5 días), lo cual requiere revisión de la disponibilidad de flota.
        </div>
    </div>

    <div class="footer">
        GRANJA BORAURE, C.A. - Sistema GB-SUITE | Documento de Control Agronómico | Página <span class="page-number"></span>
    </div>

</body>
</html>