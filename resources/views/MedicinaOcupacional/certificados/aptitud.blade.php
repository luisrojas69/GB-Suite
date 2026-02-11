<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Constancia de Aptitud Médica</title>
    <style>
        @page { 
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
            margin-bottom: 15px;
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
            width: 120px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .info-value {
            display: table-cell;
            color: #1f2937;
            font-size: 11px;
        }
        
        /* SECTION HEADER */
        .section-header {
            background: linear-gradient(to right, #f8f9fc, #ffffff);
            border-left: 4px solid #1a592e;
            padding: 8px 12px;
            font-weight: 700;
            font-size: 11px;
            margin: 15px 0 10px 0;
            color: #1a592e;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        /* APTITUD BADGE */
        .aptitud-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 12px;
            margin: 5px 0;
            letter-spacing: 0.3px;
        }
        
        .aptitud-apto {
            background: #d1fae5;
            color: #065f46;
            border: 2px solid #10b981;
        }
        
        .aptitud-no-apto {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #dc2626;
        }
        
        .aptitud-condicional {
            background: #fef3c7;
            color: #92400e;
            border: 2px solid #f59e0b;
        }
        
        /* REPOSO BOX */
        .reposo-box {
            background: linear-gradient(to bottom, #fef3c7, #ffffff);
            border: 2px solid #f59e0b;
            border-radius: 6px;
            padding: 12px 15px;
            margin: 10px 0;
        }
        
        .reposo-title {
            font-weight: 700;
            color: #92400e;
            font-size: 11px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .reposo-content {
            color: #78350f;
            font-size: 11px;
        }
        
        /* SIGNATURE SECTION */
        .signature-section {
            margin-top: 40px;
            margin-bottom: 30px;
        }
        
        .signature-container {
            display: table;
            width: 100%;
            border-spacing: 20px 0;
        }
        
        .signature-box {
            display: table-cell;
            width: 48%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 2px solid #1a592e;
            margin-top: 50px;
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
        
        /* CUT LINE */
        .cut-line {
            border-top: 2px dashed #d1d5db;
            margin: 35px 0 25px 0;
            padding-top: 10px;
            text-align: center;
            color: #9ca3af;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* RECIPE SECTION */
        .recipe-header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 10px 15px;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);
        }
        
        .treatment-box {
            border: 2px solid #e1e4e8;
            border-radius: 6px;
            padding: 15px;
            min-height: 180px;
            background: #ffffff;
            line-height: 1.8;
            font-size: 11px;
        }
        
        /* FOOTER */
        .footer-info {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        
        /* METADATA SMALL */
        .metadata-small {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .metadata-col {
            display: table-cell;
            width: 50%;
        }
    </style>
</head>
<body>
    <!-- PRIMERA PARTE: CONSTANCIA DE APTITUD -->
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
        CONSTANCIA DE APTITUD MÉDICA OCUPACIONAL
    </div>

    <!-- DATOS DEL TRABAJADOR -->
    <div class="section-header">Datos del Trabajador</div>
    <div class="info-box">
        <div class="info-row">
            <div class="info-label">Nombre Completo:</div>
            <div class="info-value">{{ $paciente->nombre_completo }}</div>
        </div>
        <div class="metadata-small">
            <div class="metadata-col">
                <div class="info-row">
                    <div class="info-label">Cédula:</div>
                    <div class="info-value">{{ $paciente->ci }}</div>
                </div>
            </div>
            <div class="metadata-col">
                <div class="info-row">
                    <div class="info-label">Código Empleado:</div>
                    <div class="info-value">{{ $paciente->cod_emp }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- DETALLES DE LA ATENCIÓN -->
    <div class="section-header">Detalles de la Atención Médica</div>
    <div class="info-box">
        <div class="info-row">
            <div class="info-label">Fecha de Evaluación:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($ultimaConsulta->created_at)->format('d/m/Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Motivo de Consulta:</div>
            <div class="info-value">{{ $ultimaConsulta->motivo_consulta }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Diagnóstico CIE-10:</div>
            <div class="info-value">{{ $ultimaConsulta->diagnostico_cie10 }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Médico Evaluador:</div>
            <div class="info-value">Dr(a). {{ $ultimaConsulta->medico->name }} {{ $ultimaConsulta->medico->last_name }}</div>
        </div>
    </div>

    <!-- RESULTADO DE EVALUACIÓN -->
    <div class="section-header">Resultado de Evaluación Médica</div>
    <div class="info-box" style="text-align: center;">
        @php
            $aptitudLower = strtolower($ultimaConsulta->aptitud);
            if (str_contains($aptitudLower, 'apto') && !str_contains($aptitudLower, 'no')) {
                $badgeClass = 'aptitud-apto';
            } elseif (str_contains($aptitudLower, 'no apto')) {
                $badgeClass = 'aptitud-no-apto';
            } else {
                $badgeClass = 'aptitud-condicional';
            }
        @endphp
        <div class="aptitud-badge {{ $badgeClass }}">
            {{ strtoupper($ultimaConsulta->aptitud) }}
        </div>
    </div>

    @if($ultimaConsulta->genera_reposo)
    <div class="reposo-box">
        <div class="reposo-title">Reposo Médico Concedido</div>
        <div class="reposo-content">
            Se otorga reposo médico por un período de <strong>{{ $ultimaConsulta->dias_reposo }} día(s)</strong> a partir de la fecha de evaluación.
            El trabajador deberá permanecer en reposo y evitar actividades laborales durante este período.
        </div>
    </div>
    @endif

    <!-- FIRMAS -->
    <div class="signature-section">
        <div class="signature-container">
            <div class="signature-box">
                <div class="signature-line">
                    <div class="signature-label">Firma del Médico Ocupacional</div>
                    <div class="signature-name">Dr(a). {{ $ultimaConsulta->medico->name }} {{ $ultimaConsulta->medico->last_name }}</div>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <div class="signature-label">Firma del Trabajador</div>
                    <div class="signature-name">C.I.: {{ $ultimaConsulta->paciente->ci }}</div>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 30px; padding: 10px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; font-size: 9px; color: #6b7280; text-align: justify;">
        <strong>Nota Legal:</strong> Esta constancia certifica el estado de aptitud médica del trabajador evaluado en la fecha indicada. 
        Es válida únicamente para fines ocupacionales y no constituye un certificado médico general. 
        Cualquier cambio en las condiciones de salud del trabajador debe ser reportado inmediatamente al Servicio de Medicina Ocupacional.
    </div>

   

    <!-- FOOTER -->
    <div class="footer-info">
        Granja Boraure, C.A. - Sistema de Gestión Médica GB Suite<br>
        Generado el {{ date('d/m/Y H:i') }} - Este documento es válido únicamente con firma y sello del médico tratante<br>
        © {{ date('Y') }} Todos los derechos reservados
    </div>
</body>
</html>