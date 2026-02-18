<!DOCTYPE html>
<html>
<head><style>/* Estilos simples para email */</style></head>
<body style="font-family: Arial, sans-serif;">
    <div style="background-color: #f8f9fa; padding: 20px;">
        <div style="background-color: white; padding: 20px; border-radius: 5px; border-left: 5px solid #1cc88a;">
            <h3>Solicitud de Despacho de EPP #{{ $dotacion->id }}</h3>
            <p>Saludos Almacén,</p>
            <p>El servicio médico ha autorizado la siguiente dotación para el trabajador:</p>
            
            <h2 style="color: #333;">{{ $dotacion->paciente->nombre_completo }}</h2>
            <p>C.I: {{ $dotacion->paciente->cedula }} | Dept: {{ $dotacion->paciente->des_depart }}</p>
            
            <hr>
            
            <h4>Artículos a preparar (Códigos Profit):</h4>
            <ul>
                @if($dotacion->co_art_calzado)
                    <li>🥾 <strong>Calzado:</strong> {{ $dotacion->co_art_calzado }}</li>
                @endif
                @if($dotacion->co_art_pantalon)
                    <li>👖 <strong>Pantalón:</strong> {{ $dotacion->co_art_pantalon }}</li>
                @endif
                @if($dotacion->co_art_camisa)
                    <li>👕 <strong>Camisa:</strong> {{ $dotacion->co_art_camisa }}</li>
                @endif
                {{-- Aquí podrías iterar el JSON de otros si lo deseas --}}
            </ul>

            <div style="text-align: center; margin-top: 30px;">
                <p>Escanee este código al momento de la entrega o use el botón:</p>
                <--img src="data:image/png;base64, {{ $qrCode }}" alt="QR Code"-->
                {!! $qrCode !!}
                <br><br>
                <a href="{{ route('dotaciones.confirmar', $dotacion->id) }}" 
                   style="background-color: #4e73df; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                   Ir a Confirmar Despacho en GB-Suite
                </a>
            </div>
        </div>
    </div>
</body>
</html>