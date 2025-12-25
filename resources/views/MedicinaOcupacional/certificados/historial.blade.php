<!DOCTYPE html>
<html>
<head>
     <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1a592e; }
        .paciente-info { background: #f8f9fc; padding: 10px; border: 1px solid #e3e6f0; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #1a592e; color: white; padding: 8px; text-align: left; }
        td { border: 1px solid #e3e6f0; padding: 8px; }
        .titulo-seccion { color: #1a592e; font-weight: bold; text-transform: uppercase; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('img/logoB.png') }}" width="60">
        <h2>GRANJA BORAURE, C.A.</h2>
        <h3>FICHA EPIDEMIOLÓGICA INDIVIDUAL</h3>
    </div>

    <div class="paciente-info">
        <strong>Trabajador:</strong> {{ $paciente->nombre_completo }} <br>
        <strong>Cédula:</strong> {{ $paciente->ci }} | <strong>Cargo:</strong> {{ $paciente->des_cargo }} <br>
        <strong>Fecha de Ingreso:</strong> {{ \Carbon\Carbon::parse($paciente->fecha_ingreso)->format('d/m/Y') }}
    </div>

    <div class="titulo-seccion">Historial de Consultas Médicas</div>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Motivo / Tipo</th>
                <th>Diagnóstico (CIE-10)</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paciente->consultas as $consulta)
            <tr>
                <td>{{ $consulta->created_at->format('d/m/Y') }}</td>
                <td>{{ $consulta->motivo_consulta }}</td>
                <td>{{ $consulta->diagnostico_cie10 }}</td>
                <td>{{ Str::limit($consulta->observaciones, 50) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="titulo-seccion">Registro de Accidentes / Incidentes</div>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Lugar</th>
                <th>Tipo de Evento</th>
                <th>Lesión Detallada</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paciente->accidentes as $accidente)
            <tr>
                <td>{{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('d/m/Y') }}</td>
                <td>{{ $accidente->lugar_exacto }}</td>
                <td>{{ $accidente->tipo_evento }}</td>
                <td>{{ $accidente->lesion_detallada }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;">No registra accidentes laborales.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 9px; text-align: center;">
        Documento generado el {{ date('d/m/Y') }} - Confidencialidad bajo ética médica.
    </div>
</body>
</html>