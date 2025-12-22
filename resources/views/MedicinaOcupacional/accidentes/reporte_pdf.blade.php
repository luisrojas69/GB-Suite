<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .section-title { background: #eee; padding: 5px; font-weight: bold; border: 1px solid #000; margin-top: 10px; }
        .field { padding: 4px; border: 0.5px solid #ccc; }
        .table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .table td { border: 1px solid #000; padding: 5px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background: #ffc; padding: 10px; text-align: center;">
        Pulse Ctrl+P para imprimir el reporte oficial.
    </div>

    <div class="header">
        <h2>GRANJA BORAURE</h2>
        <h3>NOTIFICACIÓN DE ACCIDENTE DE TRABAJO (INPSASEL)</h3>
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