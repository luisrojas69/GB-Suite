<meta charset="UTF-8">
<style>
    .stamp { position: absolute; bottom: 50px; right: 50px; opacity: 0.7; }
    .status-box { border: 2px solid #000; padding: 10px; text-align: center; font-weight: bold; }
</style>

<div class="header">
    <img src="{{ public_path('img/logoB.png') }}" width="80">
    <h2>GRANJA BORAURE, C.A.</h2>
    <p>SERVICIO DE SEGURIDAD Y SALUD LABORAL</p>
</div>

<h3>CERTIFICADO DE APTITUD MÉDICA OCUPACIONAL</h3>

<p>Por medio de la presente se hace constar que el ciudadano(a): 
   <strong>{{ $paciente->nombre_completo }}</strong>, titular de la C.I. <strong>{{ $paciente->ci }}</strong>, 
   quien desempeña el cargo de <strong>{{ $paciente->des_cargo }}</strong>, fue evaluado medicamente.</p>

<div class="status-box">
    RESULTADO: {{ $ultimaConsulta->aptitud ?? 'APTO PARA EL CARGO' }}
</div>

<p><strong>Observaciones / Restricciones:</strong><br>
   {{ $ultimaConsulta->restricciones ?? 'Ninguna' }}</p>

<div class="footer" style="margin-top: 100px;">
    <table width="100%">
        <tr>
            <td align="center">__________________________<br>Firma del Trabajador</td>
            <td align="center">__________________________<br>Médico Ocupacional / Sello</td>
        </tr>
    </table>
</div>