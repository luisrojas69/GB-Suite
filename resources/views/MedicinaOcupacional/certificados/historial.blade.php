<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ficha Epidemiológica Individual</title>
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
            font-size: 10px;
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
        
        /* PATIENT INFO BOX */
        .patient-info-box {
            background: linear-gradient(to bottom, #f8f9fc, #ffffff);
            border: 2px solid #1a592e;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
        }
        
        .patient-header {
            background: linear-gradient(135deg, #1a592e 0%, #2d7a4a 100%);
            color: white;
            padding: 8px 12px;
            margin: -15px -15px 12px -15px;
            border-radius: 4px 4px 0 0;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .patient-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .patient-row:last-child {
            margin-bottom: 0;
        }
        
        .patient-label {
            display: table-cell;
            font-weight: 700;
            color: #1a592e;
            width: 140px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .patient-value {
            display: table-cell;
            color: #1f2937;
            font-size: 10px;
        }
        
        /* STATISTICS SUMMARY */
        .stats-summary {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            border-spacing: 10px;
        }
        
        .stat-box {
            display: table-cell;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }
        
        .stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #1a592e;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* SECTION HEADER */
        .section-header {
            background: linear-gradient(135deg, #1a592e 0%, #2d7a4a 100%);
            color: white;
            padding: 10px 15px;
            font-weight: 700;
            font-size: 11px;
            margin: 25px 0 15px 0;
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
        
        /* TABLES */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        
        thead {
            background: linear-gradient(to bottom, #f8f9fc, #f1f3f9);
        }
        
        th {
            border: 1px solid #d1d5db;
            padding: 10px 8px;
            text-align: left;
            font-weight: 700;
            color: #374151;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            color: #1f2937;
            vertical-align: top;
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
        
        .empty-row {
            background-color: #fef3c7 !important;
            text-align: center;
            font-style: italic;
            color: #92400e;
        }
        
        /* ALERT BOX */
        .alert-box {
            border: 2px solid #dc2626;
            border-radius: 6px;
            padding: 12px 15px;
            background: linear-gradient(to bottom, #fee2e2, #ffffff);
            margin-bottom: 20px;
        }
        
        .alert-title {
            font-size: 10px;
            font-weight: 700;
            color: #991b1b;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .alert-content {
            font-size: 9px;
            color: #7f1d1d;
            line-height: 1.6;
        }
        
        /* SUCCESS BOX */
        .success-box {
            border: 2px solid #10b981;
            border-radius: 6px;
            padding: 12px 15px;
            background: linear-gradient(to bottom, #d1fae5, #ffffff);
            margin-bottom: 20px;
        }
        
        .success-title {
            font-size: 10px;
            font-weight: 700;
            color: #065f46;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .success-content {
            font-size: 9px;
            color: #047857;
            line-height: 1.6;
        }
        
        /* BADGE */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
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
        
        /* TIMELINE MARKER */
        .timeline-marker {
            width: 8px;
            height: 8px;
            background: #1a592e;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
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
                FICHA EPIDEMIOLÓGICA INDIVIDUAL
            </div>
        </div>
    </div>

    <!-- PATIENT INFORMATION -->
    <div class="patient-info-box">
        <div class="patient-header">Datos del Trabajador</div>
        <div class="patient-row">
            <div class="patient-label">Nombre Completo:</div>
            <div class="patient-value">{{ $paciente->nombre_completo }}</div>
        </div>
        <div class="patient-row">
            <div class="patient-label">Cédula de Identidad:</div>
            <div class="patient-value">{{ $paciente->ci }}</div>
        </div>
        <div class="patient-row">
            <div class="patient-label">Código de Empleado:</div>
            <div class="patient-value">{{ $paciente->cod_emp }}</div>
        </div>
        <div class="patient-row">
            <div class="patient-label">Cargo Actual:</div>
            <div class="patient-value">{{ $paciente->des_cargo }}</div>
        </div>
        <div class="patient-row">
            <div class="patient-label">Fecha de Ingreso:</div>
            <div class="patient-value">{{ \Carbon\Carbon::parse($paciente->fecha_ingreso)->format('d/m/Y') }}</div>
        </div>
        <div class="patient-row">
            <div class="patient-label">Antigüedad:</div>
            <div class="patient-value">
                @php
                    $antiguedad = \Carbon\Carbon::parse($paciente->fecha_ingreso)->diffForHumans(null, true);
                @endphp
                {{ $antiguedad }}
            </div>
        </div>
    </div>

    <!-- STATISTICS SUMMARY -->
    @php
        $totalConsultas = $paciente->consultas->count();
        $totalAccidentes = $paciente->accidentes->count();
        $consultasUltimoAnio = $paciente->consultas->where('created_at', '>=', now()->subYear())->count();
        $diasReposoTotal = $paciente->consultas->sum('dias_reposo');
    @endphp
    
    <div class="stats-summary">
        <div class="stat-box">
            <div class="stat-value">{{ $totalConsultas }}</div>
            <div class="stat-label">Total Consultas</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $consultasUltimoAnio }}</div>
            <div class="stat-label">Consultas Último Año</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $totalAccidentes }}</div>
            <div class="stat-label">Accidentes Registrados</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $diasReposoTotal }}</div>
            <div class="stat-label">Días de Reposo Total</div>
        </div>
    </div>

    <!-- ALERTS -->
    @if($totalAccidentes > 0)
    <div class="alert-box">
        <div class="alert-title">Atención: Trabajador con Historial de Accidentes</div>
        <div class="alert-content">
            Este trabajador registra <strong>{{ $totalAccidentes }} accidente(s) laboral(es)</strong>. 
            Se recomienda revisión especial de las condiciones de trabajo y evaluación de riesgos en su área de desempeño.
        </div>
    </div>
    @endif

    @if($totalConsultas == 0 && $totalAccidentes == 0)
    <div class="success-box">
        <div class="success-title">Trabajador sin Historial Médico</div>
        <div class="success-content">
            Este trabajador no registra consultas médicas ni accidentes laborales desde su ingreso. 
            Se recomienda mantener seguimiento preventivo y chequeos periódicos.
        </div>
    </div>
    @endif

    <!-- SECTION 1: HISTORIAL DE CONSULTAS -->
    <div class="section-header">
        <span class="section-number">01</span>
        HISTORIAL DE CONSULTAS MÉDICAS
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Fecha</th>
                <th style="width: 20%;">Motivo / Tipo Consulta</th>
                <th style="width: 25%;">Diagnóstico CIE-10</th>
                <th style="width: 15%;">Reposo</th>
                <th style="width: 30%;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paciente->consultas->sortByDesc('created_at') as $consulta)
            <tr>
                <td>
                    <span class="timeline-marker"></span>
                    {{ $consulta->created_at->format('d/m/Y') }}
                </td>
                <td><strong>{{ $consulta->motivo_consulta }}</strong></td>
                <td>{{ $consulta->diagnostico_cie10 }}</td>
                <td class="text-center">
                    @if($consulta->genera_reposo)
                        <span class="badge badge-warning">{{ $consulta->dias_reposo }} día(s)</span>
                    @else
                        <span class="badge badge-success">No</span>
                    @endif
                </td>
                <td>{{ Str::limit($consulta->observaciones ?? 'Sin observaciones registradas', 80) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="empty-row">
                    No se registran consultas médicas para este trabajador
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($totalConsultas > 0)
    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 10px; margin-bottom: 20px; font-size: 9px;">
        <strong>Resumen de Consultas:</strong> 
        Se han registrado <strong>{{ $totalConsultas }} consultas médicas</strong> en total.
        @if($diasReposoTotal > 0)
            El trabajador ha acumulado <strong>{{ $diasReposoTotal }} días de reposo médico</strong> durante su trayectoria laboral.
        @endif
        La última consulta fue el <strong>{{ $paciente->consultas->sortByDesc('created_at')->first()->created_at->format('d/m/Y') }}</strong>.
    </div>
    @endif

    <!-- SECTION 2: REGISTRO DE ACCIDENTES -->
    <div class="section-header">
        <span class="section-number">02</span>
        REGISTRO DE ACCIDENTES E INCIDENTES LABORALES
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Fecha</th>
                <th style="width: 25%;">Lugar del Accidente</th>
                <th style="width: 15%;">Tipo de Evento</th>
                <th style="width: 30%;">Lesión Detallada</th>
                <th style="width: 20%;">Causas Identificadas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paciente->accidentes->sortByDesc('fecha_hora_accidente') as $accidente)
            <tr>
                <td>
                    <span class="timeline-marker" style="background: #dc2626;"></span>
                    {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('d/m/Y') }}
                </td>
                <td><strong>{{ $accidente->lugar_exacto }}</strong></td>
                <td>
                    <span class="badge badge-danger">{{ $accidente->tipo_evento }}</span>
                </td>
                <td>{{ $accidente->lesion_detallada ?? 'No especificada' }}</td>
                <td style="font-size: 8px;">
                    {{ Str::limit($accidente->causas_inmediatas ?? 'No registradas', 60) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="empty-row">
                    No se registran accidentes laborales para este trabajador
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($totalAccidentes > 0)
    <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 4px; padding: 10px; margin-bottom: 20px; font-size: 9px;">
        <strong>Resumen de Accidentabilidad:</strong> 
        Se han registrado <strong>{{ $totalAccidentes }} accidente(s) laboral(es)</strong> durante la trayectoria del trabajador.
        @php
            $ultimoAccidente = $paciente->accidentes->sortByDesc('fecha_hora_accidente')->first();
        @endphp
        @if($ultimoAccidente)
            El último accidente ocurrió el <strong>{{ \Carbon\Carbon::parse($ultimoAccidente->fecha_hora_accidente)->format('d/m/Y') }}</strong> 
            en <strong>{{ $ultimoAccidente->lugar_exacto }}</strong>.
        @endif
        <br><strong>RECOMENDACIÓN:</strong> Evaluar medidas preventivas específicas para este trabajador.
    </div>
    @endif

    <!-- SECTION 3: ANÁLISIS Y RECOMENDACIONES -->
    @if($totalConsultas > 0 || $totalAccidentes > 0)
    <div class="section-header">
        <span class="section-number">03</span>
        ANÁLISIS EPIDEMIOLÓGICO Y RECOMENDACIONES
    </div>
    
    <div style="background: linear-gradient(to bottom, #fffbeb, #ffffff); border: 2px solid #fbbf24; border-radius: 6px; padding: 15px; font-size: 9.5px; line-height: 1.7;">
        <p style="margin-bottom: 10px;">
            <strong>Perfil de Riesgo del Trabajador:</strong>
        </p>
        <ul style="margin: 0 0 10px 20px; line-height: 1.8;">
            @if($totalConsultas > 5)
            <li>Alta frecuencia de consultas médicas ({{ $totalConsultas }} consultas registradas) - Requiere seguimiento especial</li>
            @elseif($totalConsultas > 0)
            <li>Frecuencia normal de consultas médicas ({{ $totalConsultas }} consultas)</li>
            @endif
            
            @if($totalAccidentes > 0)
            <li><strong style="color: #dc2626;">Trabajador con historial de accidentabilidad</strong> - Prioridad en capacitación de seguridad</li>
            @endif
            
            @if($diasReposoTotal > 30)
            <li>Tiempo significativo de reposo médico ({{ $diasReposoTotal }} días) - Evaluar condiciones ergonómicas del puesto</li>
            @elseif($diasReposoTotal > 0)
            <li>Días de reposo acumulados: {{ $diasReposoTotal }} días</li>
            @endif
        </ul>
        
        <p style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #fbbf24;">
            <strong>Recomendaciones:</strong>
        </p>
        <ul style="margin: 5px 0 0 20px; line-height: 1.8;">
            <li>Realizar evaluación médica periódica ocupacional</li>
            @if($totalAccidentes > 0)
            <li>Reforzar capacitación en prevención de accidentes y uso de EPP</li>
            <li>Evaluar condiciones de seguridad en el área de trabajo</li>
            @endif
            @if($diasReposoTotal > 15)
            <li>Revisión ergonómica del puesto de trabajo</li>
            @endif
            <li>Mantener seguimiento médico continuo y actualización de esta ficha epidemiológica</li>
        </ul>
    </div>
    @endif

    <!-- FOOTER -->
    <div class="footer-content">
        <div class="confidential">DOCUMENTO CONFIDENCIAL - INFORMACIÓN MÉDICA PROTEGIDA</div>
        <div class="footer-info">
            Este documento contiene información médica confidencial protegida por la Ley de Ejercicio de la Medicina y el Código de Ética Médica.<br>
            Queda prohibida su reproducción, distribución o divulgación sin autorización expresa del Departamento de Medicina Ocupacional.<br>
            Generado el {{ date('d/m/Y H:i') }} | <span class="page-number"></span>
        </div>
    </div>
</body>
</html>