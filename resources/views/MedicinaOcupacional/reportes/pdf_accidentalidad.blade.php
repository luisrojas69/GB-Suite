<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Informe de Accidentabilidad {{ date('Y') }}</title>
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
            border-bottom: 3px solid #e74a3b;
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
            color: #e74a3b;
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
            color: #e74a3b;
            margin-top: 8px;
            padding: 6px 12px;
            background: linear-gradient(to right, #fff5f5, #ffffff);
            border-left: 4px solid #e74a3b;
            display: inline-block;
        }
        
        /* METADATA */
        .metadata-box {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 25px;
            display: table;
            width: 100%;
        }
        
        .metadata-item {
            display: table-cell;
            padding: 0 15px;
            border-right: 1px solid #fca5a5;
        }
        
        .metadata-item:last-child {
            border-right: none;
        }
        
        .metadata-label {
            font-size: 8.5px;
            color: #991b1b;
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
            background: linear-gradient(135deg, #e74a3b 0%, #dc2626 100%);
            color: white;
            padding: 10px 15px;
            font-weight: 700;
            font-size: 11.5px;
            margin-bottom: 15px;
            border-radius: 4px;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 4px rgba(231, 74, 59, 0.15);
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
            background: linear-gradient(to bottom, #fff5f5, #fee2e2);
        }
        
        th {
            border: 1px solid #fca5a5;
            padding: 10px 12px;
            text-align: left;
            font-weight: 700;
            color: #991b1b;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        td {
            border: 1px solid #fecaca;
            padding: 9px 12px;
            color: #1f2937;
        }
        
        tbody tr:nth-child(even) {
            background-color: #fef2f2;
        }
        
        tbody tr:hover {
            background-color: #fee2e2;
        }
        
        tbody tr.critical-zone {
            background-color: #fef3c7;
            font-weight: 600;
        }
        
        tfoot tr {
            background: #fee2e2;
            font-weight: 700;
        }
        
        tfoot th {
            border: 1px solid #fca5a5;
            padding: 10px 12px;
            color: #991b1b;
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
            background: linear-gradient(90deg, #ef4444, #f87171);
            border-radius: 3px;
            margin-left: 8px;
            vertical-align: middle;
            box-shadow: 0 1px 2px rgba(239, 68, 68, 0.3);
        }
        
        .percentage-cell {
            font-weight: 600;
            color: #dc2626;
        }
        
        /* INSIGHTS BOX */
        .insights-box {
            margin-top: 25px;
            border: 2px solid #f59e0b;
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
        }
        
        .insights-content {
            font-size: 9.5px;
            color: #451a03;
            line-height: 1.7;
        }
        
        .insights-content strong {
            color: #dc2626;
            font-weight: 700;
        }
        
        .recommendation {
            margin-top: 10px;
            padding-left: 15px;
            border-left: 3px solid #f59e0b;
        }
        
        /* ALERT BOX */
        .alert-box {
            margin-top: 25px;
            border: 2px solid #dc2626;
            border-radius: 6px;
            padding: 15px 18px;
            background: linear-gradient(to bottom, #fee2e2, #ffffff);
            page-break-inside: avoid;
        }
        
        .alert-title {
            font-size: 11px;
            font-weight: 700;
            color: #991b1b;
            margin-bottom: 10px;
        }
        
        .alert-content {
            font-size: 9.5px;
            color: #7f1d1d;
            line-height: 1.7;
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
            background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%);
            border: 1px solid #fecaca;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #dc2626;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 9px;
            color: #991b1b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* PRIORITY INDICATORS */
        .priority-critical {
            color: #dc2626;
            font-weight: 700;
        }
        
        .priority-high {
            color: #f59e0b;
            font-weight: 600;
        }
        
        .priority-normal {
            color: #10b981;
        }
        
        /* RANKING BADGE */
        .ranking-badge {
            display: inline-block;
            background: #dc2626;
            color: white;
            font-weight: 700;
            font-size: 8px;
            padding: 3px 8px;
            border-radius: 10px;
            margin-left: 8px;
            vertical-align: middle;
        }
        
        /* NO DATA BOX */
        .no-data-box {
            background: linear-gradient(to bottom, #d1fae5, #a7f3d0);
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .no-data-title {
            font-size: 16px;
            font-weight: 700;
            color: #065f46;
            margin-bottom: 10px;
        }
        
        .no-data-text {
            font-size: 11px;
            color: #047857;
            line-height: 1.6;
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
                INFORME DE ACCIDENTABILIDAD Y PUNTOS CRÍTICOS {{ date('Y') }}
            </div>
        </div>
    </div>

    @php 
        $totalAccidentes = $data->sum('total');
        $lugaresUnicos = $data->count();
        $lugarMasPeligroso = $data->first();
        $porcentajePrincipal = ($totalAccidentes > 0 && $lugarMasPeligroso) ? number_format(($lugarMasPeligroso->total / $totalAccidentes) * 100, 1) : 0;
        $lugaresAltoRiesgo = $data->where('total', '>', 5)->count();
    @endphp

    <!-- METADATA -->
    <div class="metadata-box">
        <div class="metadata-item">
            <div class="metadata-label">Período de Análisis</div>
            <div class="metadata-value">Año Fiscal {{ date('Y') }}</div>
        </div>
        <div class="metadata-item">
            <div class="metadata-label">Fecha de Generación</div>
            <div class="metadata-value">{{ date('d/m/Y') }}</div>
        </div>
        <div class="metadata-item">
            <div class="metadata-label">Total de Incidentes</div>
            <div class="metadata-value">{{ number_format($totalAccidentes, 0, ',', '.') }}</div>
        </div>
        <div class="metadata-item">
            <div class="metadata-label">Clasificación</div>
            <div class="metadata-value" style="color: #dc2626;">CONFIDENCIAL</div>
        </div>
    </div>

    @if($totalAccidentes > 0)
        <!-- SECTION 1: RESUMEN ESTADÍSTICO -->
        <div class="section">
            <div class="section-header">
                <span class="section-number">01</span>
                RESUMEN ESTADÍSTICO DE ACCIDENTABILIDAD
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ number_format($totalAccidentes, 0, ',', '.') }}</div>
                    <div class="stat-label">Total Incidentes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $lugaresUnicos }}</div>
                    <div class="stat-label">Ubicaciones Afectadas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ number_format($totalAccidentes / 12, 1) }}</div>
                    <div class="stat-label">Promedio Mensual</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $lugaresAltoRiesgo }}</div>
                    <div class="stat-label">Zonas Alto Riesgo</div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: PUNTOS CRÍTICOS POR UBICACIÓN -->
        <div class="section">
            <div class="section-header">
                <span class="section-number">02</span>
                DISTRIBUCIÓN DE INCIDENTES POR UBICACIÓN
            </div>
            
            <div class="section-description">
                Identificación de los focos de riesgo según el número de incidentes registrados por ubicación específica dentro de las instalaciones.
                Este análisis permite priorizar áreas para la aplicación de medidas preventivas y correctivas inmediatas.
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">N°</th>
                        <th style="width: 45%;">Lugar / Ubicación de la Granja</th>
                        <th style="width: 15%;" class="text-center">Incidentes</th>
                        <th style="width: 15%;" class="text-center">Frecuencia</th>
                        <th style="width: 10%;" class="text-center">Nivel Riesgo</th>
                        <th style="width: 10%;" class="text-center">Visual</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 1; @endphp
                    @foreach($data as $lugar)
                    @php 
                        $porcentaje = ($lugar->total / $totalAccidentes) * 100;
                        $barWidth = ($porcentaje * 1.5) . 'px';
                        
                        // Determinar nivel de riesgo
                        if ($lugar->total > 5) {
                            $nivel = 'ALTO';
                            $nivelClass = 'priority-critical';
                        } elseif ($lugar->total > 2) {
                            $nivel = 'MEDIO';
                            $nivelClass = 'priority-high';
                        } else {
                            $nivel = 'BAJO';
                            $nivelClass = 'priority-normal';
                        }
                        
                        // Marcar top 3
                        $isTop = $contador <= 3;
                    @endphp
                    <tr class="{{ $isTop ? 'critical-zone' : '' }}">
                        <td class="text-center">
                            {{ $contador }}
                            @if($isTop)
                            <span class="ranking-badge">CRÍTICO</span>
                            @endif
                        </td>
                        <td><strong>{{ $lugar->lugar_exacto }}</strong></td>
                        <td class="text-center">{{ number_format($lugar->total, 0, ',', '.') }}</td>
                        <td class="text-center percentage-cell">{{ number_format($porcentaje, 1) }}%</td>
                        <td class="text-center {{ $nivelClass }}">{{ $nivel }}</td>
                        <td class="text-center">
                            @if($porcentaje >= 5)
                            <span class="visual-bar" style="width: {{ $barWidth }};"></span>
                            @endif
                        </td>
                    </tr>
                    @php $contador++; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">TOTAL DE INCIDENTES</th>
                        <th class="text-center">{{ number_format($totalAccidentes, 0, ',', '.') }}</th>
                        <th class="text-center">100.0%</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
            
            <!-- ZONAS CRÍTICAS -->
            @php
                $zonasCriticas = $data->where('total', '>', 5);
            @endphp
            @if($zonasCriticas->count() > 0)
            <div class="alert-box">
                <div class="alert-title">ZONAS CRÍTICAS IDENTIFICADAS</div>
                <div class="alert-content">
                    <p>
                        <strong>ATENCIÓN:</strong> Se han identificado <strong>{{ $zonasCriticas->count() }} zonas de alto riesgo</strong> 
                        con más de 5 incidentes registrados durante el año {{ date('Y') }}:
                    </p>
                    <ul style="margin: 8px 0 0 20px; line-height: 1.8;">
                        @foreach($zonasCriticas as $zona)
                        <li>
                            <strong>{{ $zona->lugar_exacto }}</strong> - 
                            {{ $zona->total }} incidentes 
                            ({{ number_format(($zona->total / $totalAccidentes) * 100, 1) }}%)
                        </li>
                        @endforeach
                    </ul>
                    <p style="margin-top: 10px;">
                        <strong>Estas áreas requieren intervención inmediata y seguimiento continuo.</strong>
                    </p>
                </div>
            </div>
            @endif
            
            <!-- ANÁLISIS Y RECOMENDACIONES -->
            <div class="insights-box">
                <div class="insights-title">Análisis de Accidentabilidad y Recomendaciones</div>
                <div class="insights-content">
                    @php
                        $top3 = $data->take(3);
                        $concentracionTop3 = number_format(($top3->sum('total') / $totalAccidentes) * 100, 1);
                    @endphp
                    
                    <p>
                        <strong>Hallazgos Principales:</strong> Durante el año {{ date('Y') }} se registraron 
                        <strong>{{ number_format($totalAccidentes, 0, ',', '.') }} incidentes</strong> distribuidos en 
                        <strong>{{ $lugaresUnicos }} ubicaciones diferentes</strong> dentro de las instalaciones.
                    </p>
                    
                    <p style="margin-top: 8px;">
                        La ubicación con mayor incidencia es <strong>{{ $lugarMasPeligroso->lugar_exacto ?? 'N/A' }}</strong> 
                        con <strong>{{ number_format($lugarMasPeligroso->total ?? 0, 0, ',', '.') }} incidentes</strong> 
                        ({{ $porcentajePrincipal }}% del total).
                    </p>
                    
                    @if($top3->count() >= 3)
                    <p style="margin-top: 8px;">
                        Las tres ubicaciones más peligrosas concentran el <strong>{{ $concentracionTop3 }}%</strong> 
                        de los incidentes totales:
                    </p>
                    <ul style="margin: 8px 0 0 20px; line-height: 1.8;">
                        @foreach($top3 as $index => $ubicacion)
                        <li>{{ $ubicacion->lugar_exacto }} ({{ number_format(($ubicacion->total / $totalAccidentes) * 100, 1) }}%)</li>
                        @endforeach
                    </ul>
                    @endif
                    
                    <div class="recommendation">
                        <strong>Recomendaciones Prioritarias:</strong>
                        <ul style="margin: 5px 0 0 20px; line-height: 1.8;">
                            <li>Realizar inspecciones de seguridad exhaustivas en las zonas de alto riesgo identificadas</li>
                            <li>Implementar señalización de seguridad reforzada en los puntos críticos</li>
                            <li>Desarrollar procedimientos de trabajo seguros específicos para cada área de riesgo</li>
                            <li>Intensificar la capacitación del personal que labora en las zonas críticas</li>
                            <li>Establecer monitoreo continuo y evaluación mensual de las medidas implementadas</li>
                            <li>Evaluar la necesidad de modificaciones ergonómicas o estructurales en las áreas afectadas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- NO HAY DATOS -->
        <div class="no-data-box">
            <div class="no-data-title">No se registraron incidentes durante el período</div>
            <div class="no-data-text">
                Excelente noticia: Durante el año <strong>{{ date('Y') }}</strong> no se han registrado incidentes o accidentes laborales en el sistema.<br>
                Este reporte refleja un período libre de accidentes, lo que indica la efectividad de las medidas preventivas implementadas.
            </div>
        </div>
    @endif

    <!-- FOOTER -->
    <div class="footer-content">
        <div class="confidential">DOCUMENTO CONFIDENCIAL - USO EXCLUSIVO INTERNO</div>
        <div class="footer-info">
            Este documento contiene información confidencial y de uso exclusivo del Servicio de Seguridad y Salud en el Trabajo de Granja Boraure, C.A.<br>
            Queda prohibida su reproducción, distribución o divulgación sin autorización expresa del Departamento de Medicina Ocupacional.<br>
            Generado el {{ date('d/m/Y H:i') }} | <span class="page-number"></span>
        </div>
    </div>
</body>
</html>