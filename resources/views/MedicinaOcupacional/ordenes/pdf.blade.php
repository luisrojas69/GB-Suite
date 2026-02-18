<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden Médica #{{ str_pad($orden->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { margin: 0cm 0cm; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 1.5cm;
            color: #333;
            line-height: 1.4;
            background-color: #fff;
        }

        /* Encabezado */
        .header { width: 100%; border-bottom: 2px solid #4e73df; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 180px; }
        .company-info { text-align: right; font-size: 10px; color: #5a5c69; line-height: 1.2; }
        .company-name { font-size: 16px; font-weight: bold; color: #4e73df; text-transform: uppercase; }

        /* Título de la Orden */
        .order-title { text-align: center; margin-bottom: 25px; }
        .order-title h1 { margin: 0; font-size: 22px; color: #2c3e50; text-transform: uppercase; letter-spacing: 1px; }
        .order-date { font-size: 12px; color: #858796; }

        /* Bloque de Paciente */
        .patient-box {
            background-color: #f8f9fc;
            border: 1px solid #e3e6f0;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .patient-table { width: 100%; border-collapse: collapse; }
        .patient-table td { font-size: 12px; padding: 3px 0; }
        .label { font-weight: bold; color: #4e73df; text-transform: uppercase; font-size: 10px; }

        /* Cuerpo de la Orden */
        .destinatario { font-size: 13px; font-weight: bold; margin-bottom: 15px; color: #2c3e50; }
        .section-header {
            background-color: #4e73df;
            color: white;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        /* Lista de Exámenes */
        .exam-list { width: 100%; margin-bottom: 30px; }
        .exam-item {
            padding: 8px 12px;
            border-bottom: 1px solid #edf0f5;
            font-size: 13px;
            color: #333;
        }
        .exam-item:nth-child(even) { background-color: #fafafa; }

        /* Observaciones */
        .indications {
            font-size: 12px;
            background-color: #fff3cd;
            border-left: 4px solid #f6c23e;
            padding: 10px;
            margin-bottom: 40px;
        }

        /* Firma */
        .signature-container { margin-top: 60px; width: 100%; }
        .signature-box { text-align: center; width: 250px; float: right; }
        .signature-line { border-top: 1px solid #333; margin-bottom: 5px; }
        .signature-text { font-size: 11px; color: #5a5c69; }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 1.5cm;
            left: 1.5cm;
            right: 1.5cm;
            border-top: 1px solid #e3e6f0;
            padding-top: 10px;
        }
        .confidential { font-size: 8px; color: #b7b9cc; text-align: justify; margin-bottom: 5px; }
        .branding { font-size: 10px; color: #4e73df; font-weight: bold; text-align: right; }
        
        .clearfix { clear: both; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td>
                {{-- Nota: Usamos public_path para SnappyPDF --}}
                <img src="{{ public_path('img/logo.png') }}" class="logo">
            </td>
            <td class="company-info">
                <div class="company-name">Granja Boraure C.A</div>
                <div>RIF: J-08500570-6</div>
                <div>Carora, Estado Lara, Venezuela</div>
                <div>Contacto: +58 252-4217655 Ext 12 | ssl@granjaboraure.com</div>
            </td>
        </tr>
    </table>

    <div class="order-title">
        <h1>Orden Médica de Exámenes</h1>
        <div class="order-date">Orden Nº: <strong>{{ str_pad($orden->id, 6, '0', STR_PAD_LEFT) }}</strong> | Fecha: {{ $orden->created_at->format('d/m/Y') }}</div>
    </div>

    <div class="patient-box">
        <table class="patient-table">
            <tr>
                <td width="15%" class="label">Paciente:</td>
                <td width="50%" style="font-size: 14px; font-weight: bold;">{{ $orden->paciente->nombre_completo }}</td>
                <td width="15%" class="label">Cédula:</td>
                <td width="20%">{{ number_format($orden->paciente->ci, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Edad:</td>
                <td>{{ \Carbon\Carbon::parse($orden->paciente->fecha_nac)->age }} Años</td>
                <td class="label">Cargo:</td>
                <td>{{ $orden->paciente->des_cargo ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="destinatario">A LA ATENCIÓN DE: LABORATORIO / SERVICIOS MÉDICOS</div>

    <div class="section-header">DETALLE DE EXÁMENES SOLICITADOS</div>
    
    <div class="exam-list">
        @foreach($orden->examenes as $examen)
            <div class="exam-item">
                <strong>• {{ $examen }}</strong>
            </div>
        @endforeach
    </div>

    @if($orden->observaciones)
    <div class="section-header">INDICACIONES Y PREPARACIÓN</div>
    <div class="indications">
        <i class="fas fa-info-circle"></i> {{ $orden->observaciones }}
    </div>
    @endif

    <div class="signature-container">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-text">
                <strong>Dr. {{ Auth::user()->name ." ".Auth::user()->last_name }}</strong><br>
                Médico Ocupacional<br>
                M.P.P.S: XXXXX | C.M: XXXXX
            </div>
        </div>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br>
    <div class="clearfix"></div>

    <div class="footer">
        <div class="confidential">
            AVISO DE CONFIDENCIALIDAD: Este documento contiene información médica privada y protegida por la ley. Está dirigida exclusivamente al destinatario y su uso por terceros está prohibido. Si ha recibido este documento por error, por favor notifíquelo al emisor y proceda a su destrucción.
        </div>
        <table width="100%">
            <tr>
                <td style="font-size: 9px; color: #858796;">Generado el {{ date('d/m/Y h:i A') }}</td>
                <td class="branding">Generado por GB-Suite <span style="color: #333;">| Health Management</span></td>
            </tr>
        </table>
    </div>

</body>
</html>