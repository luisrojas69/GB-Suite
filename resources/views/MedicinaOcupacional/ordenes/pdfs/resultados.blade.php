<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de Exámenes - Orden #{{ $orden->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.4; margin: 0; padding: 15px; }
        .header { width: 100%; border-bottom: 2px solid #1cc88a; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { max-width: 130px; }
        .title { text-align: right; color: #1cc88a; font-size: 20px; font-weight: bold; text-transform: uppercase; }
        .subtitle { text-align: right; font-size: 11px; color: #666; }
        
        .patient-card { background-color: #f8f9fc; border: 1px solid #eaecf4; padding: 12px; margin-bottom: 20px; border-radius: 5px; }
        .patient-card table { width: 100%; }
        .patient-card td { padding: 3px 0; }
        .lbl { font-weight: bold; color: #5a5c69; width: 18%; font-size: 11px; text-transform: uppercase; }
        .val { color: #000; font-size: 12px; }

        .section-title { font-size: 14px; font-weight: bold; color: #4e73df; border-bottom: 1px solid #4e73df; padding-bottom: 5px; margin-top: 20px; margin-bottom: 10px; text-transform: uppercase; }
        
        .exam-list { margin-bottom: 15px; }
        .exam-item { display: inline-block; background-color: #eaecf4; border: 1px solid #d1d3e2; padding: 4px 10px; border-radius: 15px; font-size: 11px; margin-right: 5px; margin-bottom: 5px; font-weight: bold; color: #4e73df; }

        .result-box { border: 1px solid #d1d3e2; padding: 15px; min-height: 250px; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 3px; font-weight: bold; font-size: 12px; color: white; margin-bottom: 15px; }
        .status-normal { background-color: #1cc88a; }
        .status-alterado { background-color: #e74a3b; }

        .footer-table { width: 100%; margin-top: 40px; page-break-inside: avoid; }
        .signature-cell { width: 60%; text-align: right; padding-right: 40px; }
        .qr-cell { width: 40%; text-align: left; padding-left: 20px; border-left: 1px solid #ddd; }
        .signature-line { border-top: 1px solid #000; width: 250px; margin-left: auto; margin-top: 40px; padding-top: 5px; text-align: center; }
        
        .confidencial { font-size: 9px; color: #888; text-align: justify; margin-top: 30px; border-top: 1px dashed #ccc; padding-top: 10px; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td width="30%">
                <img src="{{ public_path('img/logo.png') }}" alt="Logo Empresa" class="logo">
            </td>
            <td width="70%">
                <div class="title">Informe de Hallazgos Clínicos</div>
                <div class="subtitle">
                    Orden N°: <strong>ORD-{{ str_pad($orden->id, 5, '0', STR_PAD_LEFT) }}</strong> | 
                    Consulta Asociada: <strong>#{{ $orden->consulta_id }}</strong><br>
                    Fecha de Emisión: {{ now()->format('d/m/Y h:i A') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="patient-card">
        <table>
            <tr>
                <td class="lbl">Paciente:</td>
                <td class="val" colspan="3"><strong>{{ $orden->paciente->nombre_completo }}</strong></td>
            </tr>
            <tr>
                <td class="lbl">Cédula:</td>
                <td class="val">{{ number_format($orden->paciente->ci, 0, ',', '.') }}</td>
                <td class="lbl">Edad / Sexo:</td>
                <td class="val">{{ $orden->paciente->edad }} años / {{ $orden->paciente->sexo }}</td>
            </tr>
            <tr>
                <td class="lbl">Departamento:</td>
                <td class="val">{{ $orden->paciente->departamento ?? 'N/A' }}</td>
                <td class="lbl">Cargo:</td>
                <td class="val">{{ $orden->paciente->cargo ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">1. Estudios Solicitados</div>
    <div class="exam-list">
        @foreach($orden->examenes as $examen)
            <span class="exam-item">{{ $examen }}</span>
        @endforeach
    </div>

    <div class="section-title">2. Interpretación y Hallazgos Médicos</div>
    <div class="result-box">
        @if($orden->interpretacion == 'Normal')
            <div class="status-badge status-normal">ESTADO CLÍNICO: DENTRO DE LÍMITES NORMALES</div>
        @else
            <div class="status-badge status-alterado">ESTADO CLÍNICO: VALORES ALTERADOS / EN OBSERVACIÓN</div>
        @endif

        <div style="white-space: pre-line; font-size: 13px; line-height: 1.6;">
            <strong>Observaciones detalladas:</strong><br>
            {{ $orden->hallazgos ?? 'Sin observaciones transcritas. Por favor referirse a los soportes originales del laboratorio adjuntos en el expediente digital.' }}
        </div>
    </div>

    <table class="footer-table">
        <tr>
            <td class="qr-cell">
                <div style="font-size: 10px; color: #555; margin-bottom: 5px;">Validación de Autenticidad:</div>
                {{-- Aquí puedes inyectar el código QR generado por tu backend en Base64 --}}
                {{-- <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(80)->generate('GB-SUITE|ORD-'.$orden->id.'|'.$orden->paciente->ci)) !!} "> --}}
                <div style="width: 80px; height: 80px; border: 1px solid #333; display: table-cell; vertical-align: middle; text-align: center; font-size: 9px; background: #eee;">
                    [ QR Code ]<br>Validación
                </div>
            </td>
            <td class="signature-cell">
                <div class="signature-line">
                    <strong>Dr(a). {{ $orden->medico->name . ' ' . $orden->medico->last_name }}</strong><br>
                    Médico Ocupacional<br>
                    M.P.P.S: {{ $orden->medico->mpps ?? '_________' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="confidencial">
        <strong>AVISO DE CONFIDENCIALIDAD:</strong> La información contenida en este reporte médico es de carácter estrictamente confidencial y está amparada por la legislación venezolana en materia de salud en el trabajo (INPSASEL / LOPCYMAT) y protección a la privacidad. Este documento refleja la interpretación médica de resultados técnicos y es para el uso exclusivo del departamento de Salud Ocupacional y el médico tratante. Queda prohibida su reproducción o distribución sin autorización expresa.
    </div>

</body>
</html>