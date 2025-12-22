

<div style="text-align: center; font-family: sans-serif;">
    <h4>GRANJA BORAURE</h4>
    <p>Comprobante de Entrega EPP #{{ $dotacion->id }}</p>
    <hr>
    <p><strong>Trabajador:</strong> {{ $dotacion->paciente->nombre_completo }}</p>
    <p><strong>Artículos:</strong> 
        {{ $dotacion->calzado_entregado ? 'Botas (T:'.$dotacion->calzado_talla.'), ' : '' }}
        {{ $dotacion->pantalon_entregado ? 'Pantalón (T:'.$dotacion->pantalon_talla.'), ' : '' }}
    </p>
    
    <div style="margin: 20px 0;">
        {!! $qrCode !!}
        <br><small>Escanee para validar originalidad</small>
    </div>

    <div>
        <p>Firma Digital registrada:</p>
        <img src="{{ $dotacion->firma_digital }}" width="200">
    </div>
</div>
