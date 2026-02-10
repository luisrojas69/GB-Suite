<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Informe de Vigilancia Epidemiológica {{ $anioActual }}</title>
    <style>
        @page { 
            margin: 2cm 1.5cm 3cm 1.5cm;
            @bottom-center {
                content: element(footer);
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10.5px;
            color: #2c3e50;
            line-height: 1.6;
        }
        
        /* HEADER */
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 3px solid #1a592e;
            padding-bottom: 15px;
        }
        
        .header-left {
            display: table-cell;
            width: 50%;
            vertical-align: middle;
        }
        
        .header-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: middle;
        }
        
        .logo {
            max-width: 160px;
            height: auto;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a592e;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        
        .company-details {
            font-size: 9px;
            color: #555;
            line-height: 1.4;
        }
        
        .report-title {
            font-size: 13px;
            font-weight: 700;
            color: #1a592e;
            margin-top: 8px;
            padding: 6px 12px;
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            border-left: 4px solid #1a592e;
            display: inline-block;
        }
        
        /* METADATA */
        .metadata-box {
            background: #f8f9fc;
            border: 1px solid #e1e4e8;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 25px;
            display: table;
            width: 100%;
        }
        
        .metadata-item {
            display: table-cell;
            padding: 0 15px;
            border-right: 1px solid #d1d5db;
        }
        
        .metadata-item:last-child {
            border-right: none;
        }
        
        .metadata-label {
            font-size: 8.5px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        
        .metadata-value {
            font-size: 11px;
            font-weight: 600;
            color: #1f2937;
        }
        
        /* SECTIONS */
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section-header {
            background: linear-gradient(135deg, #1a592e 0%, #2d7a4a 100%);
            color: white;
            padding: 10px 15px;
            font-weight: 700;
            font-size: 11.5px;
            margin-bottom: 15px;
            border-radius: 4px;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 4px rgba(26, 89, 46, 0.15);
        }
        
        .section-number {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 3px;
            margin-right: 8px;
            font-weight: 700;
        }
        
        .section-description {
            font-size: 9.5px;
            color: #64748b;
            margin-bottom: 12px;
            padding-left: 5px;
            font-style: italic;
            line-height: 1.5;
        }
        
        /* TABLES */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        
        thead {
            background: linear-gradient(to bottom, #f8f9fc, #f1f3f9);
        }
        
        th {
            border: 1px solid #d1d5db;
            padding: 10px 12px;
            text-align: left;
            font-weight: 700;
            color: #374151;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        td {
            border: 1px solid #e5e7eb;
            padding: 9px 12px;
            color: #1f2937;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        tbody tr:hover {
            background-color: #f0fdf4;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        /* VISUAL BAR */
        .visual-bar {
            display: inline-block;
            height: 18px;
            background: linear-gradient(90deg, #10b981, #34d399);
            border-radius: 3px;
            margin-left: 8px;
            vertical-align: middle;
            box-shadow: 0 1px 2px rgba(16, 185, 129, 0.3);
        }
        
        .percentage-cell {
            font-weight: 600;
            color: #059669;
        }
        
        /* INSIGHTS BOX */
        .insights-box {
            margin-top: 25px;
            border: 2px solid #fbbf24;
            border-radius: 6px;
            padding: 15px 18px;
            background: linear-gradient(to bottom, #fffbeb, #ffffff);
            page-break-inside: avoid;
        }
        
        .insights-title {
            font-size: 11px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .insights-title::before {
            content: "💡";
            margin-right: 8px;
            font-size: 14px;
        }
        
        .insights-content {
            font-size: 9.5px;
            color: #451a03;
            line-height: 1.7;
        }
        
        .insights-content strong {
            color: #1a592e;
            font-weight: 700;
        }
        
        .recommendation {
            margin-top: 10px;
            padding-left: 15px;
            border-left: 3px solid #fbbf24;
        }
        
        /* STATISTICS CARDS */
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 10px;
        }
        
        .stat-card {
            display: table-cell;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #1a592e;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* FOOTER */
        .footer-content {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding: 8px 0;
            background: #ffffff;
        }
        
        .confidential {
            color: #dc2626;
            font-weight: 700;
            margin-bottom: 3px;
            font-size: 8px;
        }
        
        .footer-info {
            color: #6b7280;
            line-height: 1.4;
        }
        
        /* PRIORITY INDICATORS */
        .priority-high {
            color: #dc2626;
            font-weight: 700;
        }
        
        .priority-medium {
            color: #f59e0b;
            font-weight: 600;
        }
        
        .priority-low {
            color: #10b981;
        }
        
        /* PAGE NUMBER */
        .page-number::after {
            content: "Página " counter(page) " de " counter(pages);
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header-container">
        <div class="header-left">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" class="logo" alt="Logo Granja Boraure">
        </div>
        <div class="header-right">
            <div class="company-name">GRANJA BORAURE, C.A.</div>
            <div class="company-details">
                RIF: J-08500570-6<br>
                Departamento de Medicina Ocupacional<br>
                Servicio de Seguridad y Salud en el Trabajo
            </div>
            <div class="report-title">
                INFORME DE VIGILANCIA EPIDEMIOLÓGICA {{ $anioActual }}
            </div>
        </div>
    </div>

    <!-- METADATA -->
    <div class="metadata-box">
        <div class="metadata-item">
            <div class="metadata-label">Período de Análisis</div>
            <div class="metadata-value">Enero - Diciembre {{ $anioActual }}</div>
        </div>
        <div class="metadata-item">
            <div class="metadata-label">Fecha de Generación</div>
            <div class="metadata-value">{{ date('d/m/Y') }}</div>
        </div>
        <div class="metadata-item">
            <div class="metadata-label">Clasificación</div>
            <div class="metadata-value" style="color: #dc2626;">CONFIDENCIAL</div>
        </div>
    </div>

    <!-- SECTION 1: DISTRIBUCIÓN POR GÉNERO -->
    <div class="section">
        <div class="section-header">
            <span class="section-number">01</span>
            DISTRIBUCIÓN DE POBLACIÓN POR GÉNERO
        </div>
        
        @php 
            $totalPoblacion = $porGenero->sum('total');
            $masculino = $porGenero->where('sexo', 'M')->first();
            $femenino = $porGenero->where('sexo', 'F')->first();
        @endphp
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $totalPoblacion }}</div>
                <div class="stat-label">Total Trabajadores</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $masculino->total ?? 0 }}</div>
                <div class="stat-label">Masculino</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $femenino->total ?? 0 }}</div>
                <div class="stat-label">Femenino</div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Género</th>
                    <th class="text-center">Total Trabajadores</th>
                    <th class="text-center">Porcentaje</th>
                    <th class="text-center">Distribución Visual</th>
                </tr>
            </thead>
            <tbody>
                @foreach($porGenero as $g)
                @php 
                    $porcentaje = ($g->total / $totalPoblacion) * 100;
                    $barWidth = ($porcentaje * 2) . 'px';
                @endphp
                <tr>
                    <td><strong>{{ $g->sexo == 'M' ? 'Masculino' : 'Femenino' }}</strong></td>
                    <td class="text-center">{{ number_format($g->total, 0, ',', '.') }}</td>
                    <td class="text-center percentage-cell">{{ number_format($porcentaje, 1) }}%</td>
                    <td class="text-center">
                        <span class="visual-bar" style="width: {{ $barWidth }};"></span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- SECTION 2: MORBILIDAD POR SISTEMAS -->
    <div class="section">
        <div class="section-header">
            <span class="section-number">02</span>
            MORBILIDAD POR SISTEMAS ORGÁNICOS
        </div>
        
        <div class="section-description">
            Análisis detallado de la incidencia de patologías según sistemas orgánicos afectados durante el período {{ $anioActual }}.
            Este desglose permite identificar las áreas del cuerpo más comprometidas y orientar acciones preventivas específicas.
        </div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">N°</th>
                    <th style="width: 50%;">Sistema / Categoría Patológica</th>
                    <th style="width: 15%;" class="text-center">Casos</th>
                    <th style="width: 15%;" class="text-center">Frecuencia</th>
                    <th style="width: 15%;" class="text-center">Prioridad</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalSistemas = $porSistemas->sum('total');
                    $contador = 1;
                @endphp
                @foreach($porSistemas as $s)
                @php 
                    $frecuencia = ($s->total / ($totalSistemas ?: 1)) * 100;
                    
                    // Determinar prioridad según frecuencia
                    if ($frecuencia >= 20) {
                        $prioridad = 'ALTA';
                        $prioridadClass = 'priority-high';
                    } elseif ($frecuencia >= 10) {
                        $prioridad = 'MEDIA';
                        $prioridadClass = 'priority-medium';
                    } else {
                        $prioridad = 'BAJA';
                        $prioridadClass = 'priority-low';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $contador++ }}</td>
                    <td><strong>{{ $s->sistema }}</strong></td>
                    <td class="text-center">{{ number_format($s->total, 0, ',', '.') }}</td>
                    <td class="text-center percentage-cell">{{ number_format($frecuencia, 1) }}%</td>
                    <td class="text-center {{ $prioridadClass }}">{{ $prioridad }}</td>
                </tr>
                @endforeach
                <tr style="background: #f3f4f6; font-weight: 700;">
                    <td colspan="2" class="text-right">TOTAL</td>
                    <td class="text-center">{{ number_format($totalSistemas, 0, ',', '.') }}</td>
                    <td class="text-center">100%</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        
        <!-- CONCLUSIONES Y RECOMENDACIONES -->
        <div class="insights-box">
            <div class="insights-title">Análisis y Recomendaciones</div>
            <div class="insights-content">
                @php
                    $sistemaPrincipal = $porSistemas->first();
                    $porcentajePrincipal = $sistemaPrincipal ? number_format(($sistemaPrincipal->total / ($totalSistemas ?: 1)) * 100, 1) : 0;
                    $top3Sistemas = $porSistemas->take(3);
                @endphp
                
                <p>
                    <strong>Hallazgos Principales:</strong> El sistema con mayor incidencia es 
                    <strong>{{ $sistemaPrincipal->sistema ?? 'N/A' }}</strong>, 
                    representando el <strong>{{ $porcentajePrincipal }}%</strong> de los casos totales registrados 
                    durante el período {{ $anioActual }}.
                </p>
                
                @if($top3Sistemas->count() >= 3)
                <p style="margin-top: 8px;">
                    Los tres sistemas más afectados concentran el 
                    <strong>{{ number_format(($top3Sistemas->sum('total') / ($totalSistemas ?: 1)) * 100, 1) }}%</strong> 
                    de la morbilidad total:
                </p>
                <ul style="margin: 8px 0 0 20px; line-height: 1.8;">
                    @foreach($top3Sistemas as $index => $sistema)
                    <li>{{ $sistema->sistema }} ({{ number_format(($sistema->total / ($totalSistemas ?: 1)) * 100, 1) }}%)</li>
                    @endforeach
                </ul>
                @endif
                
                <div class="recommendation">
                    <strong>Recomendaciones:</strong>
                    <ul style="margin: 5px 0 0 20px; line-height: 1.8;">
                        <li>Implementar programas de pausas activas dirigidas a los sistemas más afectados</li>
                        <li>Realizar evaluaciones ergonómicas en las estaciones de trabajo de mayor riesgo</li>
                        <li>Fortalecer las campañas preventivas y de educación en salud ocupacional</li>
                        <li>Establecer seguimiento trimestral de indicadores para detectar tendencias</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer-content">
        <div class="confidential">⚠ DOCUMENTO CONFIDENCIAL - USO EXCLUSIVO INTERNO</div>
        <div class="footer-info">
            Este documento contiene información confidencial y de uso exclusivo del Servicio de Seguridad y Salud en el Trabajo de Granja Boraure, C.A.<br>
            Queda prohibida su reproducción, distribución o divulgación sin autorización expresa del Departamento de Medicina Ocupacional.<br>
            Generado el {{ date('d/m/Y H:i') }} | <span class="page-number"></span>
        </div>
    </div>
</body>
</html>