<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #4e73df; }
        .section-title { background: #f2f2f2; padding: 5px; font-weight: bold; margin-top: 10px; }
        .row { margin-bottom: 10px; }
        .label { font-weight: bold; color: #555; }
        .cut-line { border-top: 1px dashed #000; margin: 30px 0; text-align: center; }
        .footer { position: fixed; bottom: 0; text-align: center; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h2 style="margin:0">GRANJA BORAURE</h2>
        <p style="margin:0">SERVICIO DE MEDICINA OCUPACIONAL</p>
    </div>

    <h3 style="text-align: center;">CONSTANCIA DE APTITUD MÉDICA</h3>

    <div class="row">
        <span class="label">Paciente:</span> {{ $consulta->paciente->nombre_completo }} <br>
        <span class="label">Cédula:</span> {{ $consulta->paciente->ci }} | <span class="label">Ficha:</span> {{ $consulta->paciente->cod_emp }}
    </div>

    <div class="section-title">DETALLES DE LA ATENCIÓN</div>
    <div class="row">
        <span class="label">Fecha:</span> {{ $consulta->fecha_formateada }} <br>
        <span class="label">Motivo:</span> {{ $consulta->motivo_consulta }} <br>
        <span class="label">Diagnóstico:</span> {{ $consulta->diagnostico_cie10 }}
    </div>

    <div class="section-title">RESULTADO DE EVALUACIÓN</div>
    <div class="row">
        <span class="label">Aptitud:</span> {{ $consulta->aptitud }} <br>
        @if($consulta->genera_reposo)
            <span class="label">REPOSO MÉDICO:</span> CONCEDIDO POR {{ $consulta->dias_reposo }} DÍA(S).
        @endif
    </div>

    <div style="margin-top: 50px;">
        <div style="float: left; width: 45%; border-top: 1px solid #000; text-align: center;">
            Firma del Médico <br> <small>Dr. {{ $consulta->medico->name." ".$consulta->medico->last_name  }}</small>
        </div>
        <div style="float: right; width: 45%; border-top: 1px solid #000; text-align: center;">
            Firma del Trabajador <br> <small>{{ $consulta->paciente->ci }}</small>
        </div>
    </div>

    <div style="clear: both;"></div>

    <div class="cut-line"> - - - - - - - - - - - - - - - Corte aquí (Récipe Médico) - - - - - - - - - - - - - - - </div>

    <div class="header">
        <h3 style="margin:0">RÉCIPE E INDICACIONES</h3>
    </div>

    <div class="row">
        <span class="label">Paciente:</span> {{ $consulta->paciente->nombre_completo }} <br>
        <span class="label">Fecha:</span> {{ $consulta->fecha_formateada }}
    </div>

    <div class="section-title">TRATAMIENTO Y RECOMENDACIONES</div>
    <div style="padding: 15px; border: 1px solid #ddd; min-height: 150px;">
        {!! nl2br(e($consulta->plan_tratamiento)) !!}
    </div>

    <div class="footer">
        <br><br>
        Granja Boraure - Sistema de Gestión Médica GB Suite - {{ date('Y') }}
    </div>

</body>
</html>