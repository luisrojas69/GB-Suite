 <meta charset="UTF-8">
<div style="border: 1px solid #ccc; padding: 20px;">
    <h4>CONSTANCIA DE ASISTENCIA MÉDICA</h4>
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