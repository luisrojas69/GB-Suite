<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
    
        .field { padding: 4px; border: 0.5px solid #ccc; }

        @media print { .no-print { display: none; } }


         @page { margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .header-table { width: 100%; border-bottom: 2px solid #1a592e; margin-bottom: 20px; }
        .logo { width: 70px; }
        .company-info { text-align: right; }
        .company-name { font-size: 16px; font-weight: bold; color: #1a592e; }
        .section-title { background: #f2f2f2; padding: 8px; font-weight: bold; margin-top: 20px; border-left: 5px solid #1a592e; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f8f9fc; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #777; border-top: 1px solid #ddd; padding-top: 5px; }
        .chart-sim { width: 15px; height: 15px; display: inline-block; margin-right: 5px; }

    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background: #ffc; padding: 10px; text-align: center;">
        Pulse Ctrl+P para imprimir el reporte oficial.
    </div>

    <div class="header-table">
        <tr>
            <td style="border:none;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logoB.png'))) }}" class="logo">
            </td>
            <td style="border:none;" class="company-info">
                <div class="company-name">GRANJA BORAURE, C.A.</div>
                <div>RIF: J-08500570-6</div>
                <div>Departamento de Medicina Ocupacional</div>
                <strong>NOTIFICACIÓN DE ACCIDENTE DE TRABAJO (INPSASEL)</strong>
            </td>
        </tr>
    </div>

    <div class="section-title">1. DATOS DEL TRABAJADOR</div>
    <table class="table">
        <tr>
            <td><strong>Nombres:</strong> {{ $accidente->paciente->nombre_completo }}</td>
            <td><strong>Cédula:</strong> {{ $accidente->paciente->ci }}</td>
        </tr>
        <tr>
            <td><strong>Cargo:</strong> {{ $accidente->paciente->des_cargo }}</td>
            <td><strong>Departamento:</strong> {{ $accidente->paciente->des_depart }}</td>
        </tr>
    </table>

    <div class="section-title">2. DATOS DEL ACCIDENTE</div>
    <table class="table">
        <tr>
            <td><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('d/m/Y') }}</td>
            <td><strong>Hora:</strong> {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('h:i A') }}</td>
            <td><strong>Lugar:</strong> {{ $accidente->lugar_exacto }}</td>
        </tr>
        <tr>
            <td colspan="3"><strong>Descripción del Evento:</strong><br>{{ $accidente->descripcion_relato }}</td>
        </tr>
        <tr>
            <td colspan="3"><strong>Naturaleza de la Lesión:</strong><br>{{ $accidente->lesion_detallada }}</td>
        </tr>
    </table>

    <div class="section-title">3. ANÁLISIS DE CAUSAS Y MEDIDAS CORRECTIVAS</div>
    <table class="table">
        <tr>
            <td><strong>Causas Identificadas:</strong><br>{{ $accidente->causas_raiz }}</td>
        </tr>
        <tr>
            <td><strong>Medidas a Adoptar:</strong><br>{{ $accidente->acciones_correctivas }}</td>
        </tr>
    </table>

    <br><br><br>
    <table class="table" style="border: none;">
        <tr style="border: none;">
            <td style="border: none; text-align: center;">__________________________<br>Firma del Trabajador</td>
            <td style="border: none; text-align: center;">__________________________<br>Firma del Investigador / Médico</td>
        </tr>
    </table>
</body>
</html>