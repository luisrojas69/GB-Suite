<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reporte de Morbilidad {{ $mes }}/{{ $anio }}</title>
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
        
        tbody tr.top-diagnosis {
            background-color: #fef3c7;
            font-weight: 600;
        }
        
        tfoot tr {
            background: #f3f4f6;
            font-weight: 700;
        }
        
        tfoot th {
            border: 1px solid #d1d5db;
            padding: 10px 12px;
            color: #1a592e;
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
            background: #fbbf24;
            color: #78350f;
            font-weight: 700;
            font-size: 8px;
            padding: 3px 8px;
            border-radius: 10px;
            margin-left: 8px;
            vertical-align: middle;
        }
        
        /* NO DATA BOX */
        .no-data-box {
            background: linear-gradient(to bottom, #fef3c7, #fde68a);
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .no-data-title {
            font-size: 16px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 10px;
        }
        
        .no-data-text {
            font-size: 11px;
            color: #78350f;
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
                REPORTE DE MORBILIDAD - {{ strtoupper($mes) }} {{ $anio }}
            </div>
        </div>
    </div>

    @php 
        $totalCasos = $data->sum('total');
        $diagnosticosUnicos = $data->count();
        $diagnosticoPrincipal = $data->first();
        $porcentajePrincipal = ($totalCasos > 0 && $diagnosticoPrincipal) ? number_format(($diagnosticoPrincipal->total / $totalCasos) * 100, 1) : 0;
        
        // Obtener el mes actual para cálculos
        $meses = [
            'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4,
            'mayo' => 5, 'junio' => 6, 'julio' => 7, 'agosto' => 8,
            'septiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12
        ];
        $mesNumero = $meses[strtolower($mes)] ?? date('n');
        $diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mesNumero, $anio);
        $promedioDiario = $totalCasos > 0 ? number_format($totalCasos / $diasDelMes, 1) : 0;
        $diagnosticosFrecuentes = $data->where('total', '>=', 5)->count();
    @endphp

    <!-- METADATA -->
    <div class="metadata-box">
        <div class="metadata-item">
            <div class="metadata-label">Período de Análisis</div>
            <div class="metadata-value">{{ ucfirst($mes) }} / {{ $anio }}</div>
        </div>
        <div class="metadata-item">
            <div class="metadata-label">Fecha de Generación</div>
            <div class="metadata-value">{{ date('d/m/Y') }}</div>
        </div>
        <div class="metadata-item">
            <div class="metadata-label">Total de Consultas</div>
            <div class="metadata-value">{{ number_format($totalCasos, 0, ',', '.') }}</div>
        </div>
        <div class="metadata-item">
            <div class="metadata-label">Clasificación</div>
            <div class="metadata-value" style="color: #dc2626;">CONFIDENCIAL</div>
        </div>
    </div>

    @if($totalCasos > 0)
        <!-- SECTION 1: RESUMEN ESTADÍSTICO -->
        <div class="section">
            <div class="section-header">
                <span class="section-number">01</span>
                RESUMEN ESTADÍSTICO DEL PERÍODO
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ number_format($totalCasos, 0, ',', '.') }}</div>
                    <div class="stat-label">Consultas Registradas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $diagnosticosUnicos }}</div>
                    <div class="stat-label">Diagnósticos Únicos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $promedioDiario }}</div>
                    <div class="stat-label">Promedio Diario</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $diagnosticosFrecuentes }}</div>
                    <div class="stat-label">Diagnósticos Frecuentes</div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: MORBILIDAD POR DIAGNÓSTICO -->
        <div class="section">
            <div class="section-header">
                <span class="section-number">02</span>
                DISTRIBUCIÓN DE MORBILIDAD POR DIAGNÓSTICO CIE-10
            </div>
            
            <div class="section-description">
                Clasificación de las consultas médicas según diagnósticos CIE-10 registrados durante {{ $mes }}/{{ $anio }}.
                Se incluye el número de casos, porcentaje de incidencia y nivel de prioridad para cada diagnóstico.
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">N°</th>
                        <th style="width: 55%;">Diagnóstico CIE-10 / Descripción</th>
                        <th style="width: 15%;" class="text-center">Casos</th>
                        <th style="width: 15%;" class="text-center">Porcentaje</th>
                        <th style="width: 10%;" class="text-center">Prioridad</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 1; @endphp
                    @foreach($data as $item)
                    @php 
                        $porcentaje = ($item->total / $totalCasos) * 100;
                        
                        // Determinar prioridad según porcentaje
                        if ($porcentaje >= 50) {
                            $prioridad = 'CRÍTICA';
                            $prioridadClass = 'priority-critical';
                        } elseif ($porcentaje >= 25) {
                            $prioridad = 'ALTA';
                            $prioridadClass = 'priority-high';
                        } else {
                            $prioridad = 'NORMAL';
                            $prioridadClass = 'priority-normal';
                        }
                        
                        // Marcar top 3
                        $isTop = $contador <= 3;
                    @endphp
                    <tr class="{{ $isTop ? 'top-diagnosis' : '' }}">
                        <td class="text-center">
                            {{ $contador }}
                            @if($isTop)
                            <span class="ranking-badge">TOP {{ $contador }}</span>
                            @endif
                        </td>
                        <td><strong>{{ $item->diagnostico_cie10 }}</strong></td>
                        <td class="text-center">{{ number_format($item->total, 0, ',', '.') }}</td>
                        <td class="text-center percentage-cell">{{ number_format($porcentaje, 2) }}%</td>
                        <td class="text-center {{ $prioridadClass }}">{{ $prioridad }}</td>
                    </tr>
                    @php $contador++; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">TOTAL CONSULTAS DEL MES</th>
                        <th class="text-center">{{ number_format($totalCasos, 0, ',', '.') }}</th>
                        <th class="text-center">100.00%</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            
            <!-- ANÁLISIS Y RECOMENDACIONES -->
            <div class="insights-box">
                <div class="insights-title">Análisis y Recomendaciones</div>
                <div class="insights-content">
                    @php
                        $top3 = $data->take(3);
                        $concentracionTop3 = number_format(($top3->sum('total') / $totalCasos) * 100, 1);
                    @endphp
                    
                    <p>
                        <strong>Hallazgos Principales:</strong> Durante el mes de {{ $mes }}/{{ $anio }} se registraron 
                        <strong>{{ number_format($totalCasos, 0, ',', '.') }} consultas médicas</strong>, distribuidas en 
                        <strong>{{ $diagnosticosUnicos }} diagnósticos diferentes</strong> según clasificación CIE-10.
                    </p>
                    
                    <p style="margin-top: 8px;">
                        El diagnóstico más frecuente fue <strong>{{ $diagnosticoPrincipal->diagnostico_cie10 ?? 'N/A' }}</strong> 
                        con <strong>{{ number_format($diagnosticoPrincipal->total ?? 0, 0, ',', '.') }} casos</strong> 
                        ({{ $porcentajePrincipal }}% del total).
                    </p>
                    
                    @if($top3->count() >= 3)
                    <p style="margin-top: 8px;">
                        Los tres diagnósticos principales concentran el <strong>{{ $concentracionTop3 }}%</strong> 
                        de las consultas totales:
                    </p>
                    <ul style="margin: 8px 0 0 20px; line-height: 1.8;">
                        @foreach($top3 as $index => $diag)
                        <li>{{ $diag->diagnostico_cie10 }} ({{ number_format(($diag->total / $totalCasos) * 100, 1) }}%)</li>
                        @endforeach
                    </ul>
                    @endif
                    
                    <div class="recommendation">
                        <strong>Recomendaciones:</strong>
                        <ul style="margin: 5px 0 0 20px; line-height: 1.8;">
                            <li>Fortalecer programas preventivos enfocados en los diagnósticos de mayor incidencia</li>
                            <li>Realizar seguimiento específico de casos recurrentes para detectar posibles causas ocupacionales</li>
                            <li>Implementar campañas de educación en salud dirigidas a las patologías más frecuentes</li>
                            <li>Evaluar condiciones ergonómicas y factores de riesgo en las áreas de trabajo</li>
                            <li>Programar revisión mensual de indicadores para identificar tendencias emergentes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- NO HAY DATOS -->
        <div class="no-data-box">
            <div class="no-data-title">No se registraron consultas médicas</div>
            <div class="no-data-text">
                Durante el período de <strong>{{ ucfirst($mes) }}/{{ $anio }}</strong> no se han registrado consultas médicas en el sistema.<br>
                Este reporte se encuentra vacío y no contiene información para analizar.
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