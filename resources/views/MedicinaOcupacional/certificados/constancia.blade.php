<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Constancia de Asistencia Médica</title>
    <style>
        @page {
          size: letter;
          margin: 1.5cm 1.5cm 2cm 1.5cm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            color: #2c3e50;
            line-height: 1.6;
        }
        
        /* HEADER */
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid #1a592e;
            padding-bottom: 12px;
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
            max-width: 140px;
            height: auto;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: 700;
            color: #1a592e;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }
        
        .company-details {
            font-size: 9px;
            color: #555;
            line-height: 1.3;
        }
        
        /* TITLE */
        .document-title {
            background: linear-gradient(135deg, #1a592e 0%, #2d7a4a 100%);
            color: white;
            padding: 12px 15px;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(26, 89, 46, 0.15);
        }
        
        /* INFO BOX */
        .info-box {
            background: #f8f9fc;
            border: 1px solid #e1e4e8;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
        
        .info-row {
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            display: table-cell;
            font-weight: 700;
            color: #1a592e;
            width: 140px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .info-value {
            display: table-cell;
            color: #1f2937;
            font-size: 11px;
        }
        
        /* CONTENT BOX */
        .content-box {
            border: 2px solid #1a592e;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
            background: #ffffff;
            line-height: 1.8;
        }
        
        .content-box p {
            margin-bottom: 15px;
            text-align: justify;
        }
        
        .content-box p:last-child {
            margin-bottom: 0;
        }
        
        /* REPOSO BOX */
        .reposo-box {
            background: linear-gradient(to bottom, #fef3c7, #ffffff);
            border: 2px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .reposo-title {
            font-weight: 700;
            color: #92400e;
            font-size: 11px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .reposo-content {
            color: #78350f;
            font-size: 11px;
            line-height: 1.7;
        }
        
        /* REINTEGRO BOX */
        .reintegro-box {
            background: linear-gradient(to bottom, #d1fae5, #ffffff);
            border: 2px solid #10b981;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .reintegro-content {
            color: #065f46;
            font-size: 11px;
            font-weight: 600;
            text-align: center;
        }
        
        /* SIGNATURE SECTION */
        .signature-section {
            margin-top: 50px;
            margin-bottom: 20px;
        }
        
        .signature-box {
            text-align: center;
            max-width: 350px;
            margin: 0 auto;
        }
        
        .signature-line {
            border-top: 2px solid #1a592e;
            margin-top: 60px;
            padding-top: 8px;
        }
        
        .signature-label {
            font-weight: 700;
            color: #1a592e;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .signature-name {
            color: #6b7280;
            font-size: 9px;
        }
        
        /* FOOTER */
        .footer-info {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        
        .confidential-note {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 4px;
            padding: 8px 12px;
            margin-top: 15px;
            font-size: 9px;
            color: #991b1b;
            text-align: center;
            font-weight: 600;
        }
        
        /* METADATA */
        .metadata-grid {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .metadata-col {
            display: table-cell;
            width: 50%;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="header-left">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" class="logo" alt="Logo Granja Boraure">
        </div>
        <div class="header-right">
            <div class="company-name">GRANJA BORAURE, C.A.</div>
            <div class="company-details">
                RIF: J-08500570-6<br>
                Servicio de Medicina Ocupacional<br>
                Departamento de Seguridad y Salud en el Trabajo
            </div>
        </div>
    </div>

    <div class="document-title">
        CONSTANCIA DE ASISTENCIA A SERVICIO MÉDICO
    </div>

    <!-- DATOS DEL TRABAJADOR -->
    <div class="info-box">
        <div class="info-row">
            <div class="info-label">Trabajador:</div>
            <div class="info-value">{{ $consulta->paciente->nombre_completo }}</div>
        </div>
        <div class="metadata-grid">
            <div class="metadata-col">
                <div class="info-row">
                    <div class="info-label">Cédula:</div>
                    <div class="info-value">{{ $consulta->paciente->ci }}</div>
                </div>
            </div>
            <div class="metadata-col">
                <div class="info-row">
                    <div class="info-label">Código:</div>
                    <div class="info-value">{{ $consulta->paciente->cod_emp }}</div>
                </div>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Cargo:</div>
            <div class="info-value">{{ $consulta->paciente->des_cargo }}</div>
        </div>
        <div class="metadata-grid">
            <div class="metadata-col">
                <div class="info-row">
                    <div class="info-label">Fecha de Ingreso:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($consulta->paciente->fecha_ingreso)->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="metadata-col">
                <div class="info-row">
                    <div class="info-label">Antigüedad:</div>
                    <div class="info-value">
                        @php
                            $fechaIngreso = \Carbon\Carbon::parse($consulta->paciente->fecha_ingreso);
                            $antiguedad = $fechaIngreso->diff(\Carbon\Carbon::now());
                            $anos = $antiguedad->y;
                            $meses = $antiguedad->m;
                            echo ($anos > 0 ? $anos . ' año' . ($anos > 1 ? 's' : '') : '') . 
                                 ($anos > 0 && $meses > 0 ? ' y ' : '') . 
                                 ($meses > 0 ? $meses . ' mes' . ($meses > 1 ? 'es' : '') : '');
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="content-box">
        <p>
            Por medio de la presente se hace <strong>CONSTAR</strong> que el trabajador 
            <strong>{{ strtoupper($consulta->paciente->nombre_completo) }}</strong>, 
            titular de la cédula de identidad <strong>{{ $consulta->paciente->ci }}</strong>, 
            asistió al Servicio de Medicina Ocupacional de esta empresa el día 
            <strong>{{ $consulta->created_at->format('d/m/Y') }}</strong> 
            a las <strong>{{ $consulta->created_at->format('h:i A') }}</strong>.
        </p>
        
        <p>
            <strong>Motivo de Consulta:</strong> {{ $consulta->motivo_consulta }}
        </p>

        <p>
            El trabajador fue debidamente atendido y evaluado por el personal médico de este servicio, 
            cumpliendo con los protocolos establecidos en el Sistema de Gestión de Seguridad y Salud en el Trabajo.
        </p>
    </div>

    <!-- RESULTADO -->
    @if($consulta->genera_reposo)
        <div class="reposo-box">
            <div class="reposo-title">Indicación Médica - Reposo Laboral</div>
            <div class="reposo-content">
                <p style="margin-bottom: 10px;">
                    Se indica <strong>REPOSO MÉDICO</strong> por un período de 
                    <strong>{{ $consulta->dias_reposo }} día(s)</strong> contados a partir de la fecha de esta consulta.
                </p>
                <p style="margin-bottom: 10px;">
                    <strong>Fecha de Reintegro:</strong> 
                    {{ \Carbon\Carbon::parse($consulta->fecha_retorno)->format('d/m/Y') }}
                </p>
                <p>
                    El trabajador deberá permanecer en reposo durante este período y evitar la realización de 
                    actividades laborales. Se recomienda seguir las indicaciones médicas establecidas en el 
                    plan de tratamiento correspondiente.
                </p>
            </div>
        </div>
    @else
        <div class="reintegro-box">
            <div class="reintegro-content">
                El trabajador puede REINTEGRARSE a sus labores habituales de forma INMEDIATA
            </div>
        </div>
    @endif

    <!-- OBSERVACIONES ADICIONALES -->
    <div style="margin: 20px 0; padding: 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px;">
        <p style="font-size: 10px; color: #6b7280; margin: 0;">
            <strong>Observaciones:</strong> Esta constancia certifica únicamente la asistencia del trabajador 
            al servicio médico en la fecha y hora indicadas. No constituye un diagnóstico médico ni certificado 
            de incapacidad, los cuales se expiden por separado cuando corresponda.
        </p>
    </div>

    <!-- FIRMA -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">
                <div class="signature-label">Médico Ocupacional</div>
                <div class="signature-name">
                    Dr(a). {{ $consulta->medico->name }} {{ $consulta->medico->last_name }}<br>
                    Servicio de Medicina Ocupacional
                </div>
            </div>
        </div>
    </div>

    <!-- NOTA DE CONFIDENCIALIDAD -->
    <div class="confidential-note">
        DOCUMENTO CONFIDENCIAL - Información protegida bajo secreto médico profesional
    </div>

    <!-- FOOTER -->
    <div class="footer-info">
        Granja Boraure, C.A. - Sistema de Gestión Médica GB Suite<br>
        Documento generado el {{ date('d/m/Y') }} a las {{ date('H:i') }}<br>
        Este documento tiene validez únicamente con firma y sello del médico tratante<br>
        © {{ date('Y') }} Todos los derechos reservados
    </div>
</body>
</html>