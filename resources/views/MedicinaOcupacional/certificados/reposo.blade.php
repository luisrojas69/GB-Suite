<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Reposo Médico - {{ $consulta->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.5; margin: 0; padding: 20px; }
        .header { width: 100%; border-bottom: 3px solid #4e73df; padding-bottom: 15px; margin-bottom: 25px; }
        .header td { vertical-align: middle; }
        .logo { max-width: 150px; }
        .title { text-align: right; color: #4e73df; font-size: 22px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .subtitle { text-align: right; font-size: 12px; color: #666; }
        
        .box { border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 20px; background-color: #fcfcfc; }
        .box-title { font-size: 11px; text-transform: uppercase; color: #858796; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        
        table.info-table { width: 100%; border-collapse: collapse; }
        table.info-table td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; color: #555; width: 25%; font-size: 12px; }
        .value { color: #000; font-size: 13px; }

        .diagnostico-box { border-left: 5px solid #e74a3b; background-color: #fff; padding: 15px; margin: 20px 0; border-top: 1px solid #eee; border-right: 1px solid #eee; border-bottom: 1px solid #eee; }
        .reposo-highlight { background-color: #f8f9fc; border: 1px solid #4e73df; text-align: center; padding: 15px; margin: 20px 0; font-size: 16px; border-radius: 5px; }
        
        .footer { margin-top: 50px; width: 100%; page-break-inside: avoid; }
        .signature-area { text-align: center; width: 300px; margin: 0 auto; border-top: 1px solid #000; padding-top: 5px; }
        .confidencial { font-size: 9px; color: #888; text-align: justify; margin-top: 30px; border-top: 1px dashed #ccc; padding-top: 10px; }
        
        /* Utilidades Snappy */
        .text-center { text-align: center; }
        .text-danger { color: #e74a3b; }
        .text-primary { color: #4e73df; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td width="30%">
                {{-- Usa public_path() para imágenes en SnappyPDF --}}
                <img src="{{ public_path('img/logo.png') }}" alt="Logo Empresa" class="logo">
            </td>
            <td width="70%">
                <div class="title">Certificado Médico de Reposo</div>
                <div class="subtitle">Folio: REP-{{ str_pad($consulta->id, 6, '0', STR_PAD_LEFT) }} | Fecha: {{ now()->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="box">
        <div class="box-title">Datos del Trabajador</div>
        <table class="info-table">
            <tr>
                <td class="label">Nombres y Apellidos:</td>
                <td class="value" colspan="3">{{ $consulta->paciente->nombre_completo }}</td>
            </tr>
            <tr>
                <td class="label">Cédula de Identidad:</td>
                <td class="value">{{ number_format($consulta->paciente->ci, 0, ',', '.') }}</td>
                <td class="label">Ficha / Legajo:</td>
                <td class="value">{{ $consulta->paciente->ficha ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Cargo:</td>
                <td class="value">{{ $consulta->paciente->cargo ?? 'No definido' }}</td>
                <td class="label">Departamento:</td>
                <td class="value">{{ $consulta->paciente->departamento ?? 'No definido' }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 15px; text-align: justify;">
        Por medio de la presente, la unidad de Salud Ocupacional certifica que el trabajador arriba identificado fue evaluado en nuestras instalaciones, determinando la necesidad de incapacidad temporal para sus labores habituales debido al siguiente cuadro clínico:
    </div>

    <div class="diagnostico-box">
        <strong>Motivo de Consulta:</strong> {{ $consulta->motivo_consulta }}<br><br>
        <strong>Diagnóstico Clínico (CIE-10):</strong> <span class="text-danger">{{ $consulta->diagnostico_cie10 ?? 'No especificado' }}</span>
    </div>

    <div class="reposo-highlight">
        Se otorga un reposo médico por <strong>{{ $consulta->dias_reposo }} DÍAS</strong> continuos.<br>
        <span style="font-size: 13px; color: #555; display: block; margin-top: 5px;">
            Inicia el: <strong>{{ \Carbon\Carbon::parse($consulta->fecha_inicio_reposo)->format('d/m/Y') }}</strong> | 
            Culmina el: <strong>{{ \Carbon\Carbon::parse($consulta->fecha_fin_reposo)->format('d/m/Y') }}</strong>
        </span>
        <span class="text-primary" style="font-size: 13px; display: block; margin-top: 5px;">
            <strong>Fecha de reintegro laboral: {{ \Carbon\Carbon::parse($consulta->fecha_fin_reposo)->addDay()->format('d/m/Y') }}</strong>
        </span>
    </div>

    <div class="box">
        <div class="box-title">Recomendaciones Médicas</div>
        <div style="white-space: pre-line; font-size: 12px;">
            {{ $consulta->recomendaciones ?? 'Cumplir tratamiento médico indicado. Reposo absoluto en domicilio. Acudir a urgencias en caso de presentar signos de alarma.' }}
        </div>
    </div>

    <table class="footer">
        <tr>
            <td style="width: 100%; text-align: center;">
                <div class="signature-area">
                    <br>
                    <strong>Dr(a). {{ $consulta->medico->name . ' ' . $consulta->medico->last_name }}</strong><br>
                    Médico Ocupacional / Tratante<br>
                    M.P.P.S: {{ $consulta->medico->mpps ?? '_________' }} | C.M: {{ $consulta->medico->colegio_medicos ?? '_________' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="confidencial">
        <strong>AVISO DE CONFIDENCIALIDAD:</strong> Este documento contiene información médica protegida por el secreto profesional y normativas de privacidad vigentes. Su uso es estrictamente confidencial y está destinado únicamente para fines de justificación laboral y de seguridad social ante el empleador. Queda terminantemente prohibida su reproducción, alteración o divulgación a terceros no autorizados sin el consentimiento expreso y por escrito del paciente titular de estos datos.
    </div>

</body>
</html>