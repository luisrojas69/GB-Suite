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
        <h4>J-08500570-6</h4>
        <h3>CONSTANCIA DE ASISTENCIA MÉDICA</h3>
    </div>

    <div class="paciente-info">
        <strong>Trabajador:</strong> {{ $consulta->paciente->nombre_completo }} <br>
        <strong>Cédula:</strong> {{ $consulta->paciente->ci }} | <strong>Cargo:</strong> {{ $consulta->paciente->des_cargo }} <br>
        <strong>Fecha de Ingreso:</strong> {{ \Carbon\Carbon::parse($consulta->paciente->fecha_ingreso)->format('d/m/Y') }}
    </div>


    <div style="border: 1px solid #ccc; padding: 20px;">

        <p>Se hace constar que el trabajador <strong>{{ $consulta->paciente->nombre_completo }}</strong> 
           asistió a este servicio médico el día <strong>{{ $consulta->created_at->format('d/m/Y') }}</strong> 
           desde las {{ $consulta->created_at->format('h:i A') }}.</p>
        
        @if($consulta->genera_reposo)
            <p><strong>RECOMENDACIÓN:</strong> Se indica reposo médico por un periodo de 
               ({{ $consulta->dias_reposo }}) días, debiendo reintegrarse a sus labores el 
               {{ \Carbon\Carbon::parse($consulta->fecha_retorno)->format('d/m/Y') }}.</p>
        @else
            <p>El trabajador puede reintegrarse a sus labores inmediatamente.</p>
        @endif
    </div>


    <div style="margin-top: 30px; font-size: 9px; text-align: center;">
        Documento generado el {{ date('d/m/Y') }} - Confidencialidad bajo ética médica.
    </div>
</body>
</html>