<!DOCTYPE html>
<html>
<head>
     <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        .border-box { border: 2px solid #000; padding: 20px; height: 95%; }
        .header { text-align: center; margin-bottom: 30px; }
        .table-epp { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table-epp th, .table-epp td { border: 1px solid #000; padding: 10px; }
        .legal-text { text-align: justify; font-size: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="border-box">
        <div class="header">
            <img src="{{ public_path('img/logoB.png') }}" width="70">
            <h3>CONSTANCIA DE ENTREGA DE EQUIPOS DE PROTECCIÓN PERSONAL (EPP)</h3>
        </div>

        <p>Yo, <strong>{{ $paciente->nombre_completo }}</strong>, titular de la C.I. <strong>{{ $paciente->ci }}</strong>, 
        hago constar que he recibido por parte de <strong>GRANJA BORAURE, C.A.</strong> los siguientes implementos:</p>

        <table class="table-epp">
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Descripción del Equipo</th>
                    <th>Estado</th>
                    <th>Firma Recibido</th>
                </tr>
            </thead>
            <tbody>
                {{-- Esto puede venir de una base de datos o ser una lista fija para marcar --}}
                <tr><td>1 Par</td><td>Botas de Seguridad (Caña alta/Punta de acero)</td><td>Nuevo</td><td></td></tr>
                <tr><td>1 Par</td><td>Guantes de Carnaza / Protección Mecánica</td><td>Nuevo</td><td></td></tr>
                <tr><td>1 Und</td><td>Mascarilla para polvos/químicos</td><td>Nuevo</td><td></td></tr>
                <tr><td>1 Und</td><td>Protector Visual (Lentes de Seguridad)</td><td>Nuevo</td><td></td></tr>
            </tbody>
        </table>

        <div class="legal-text">
            <strong>COMPROMISO:</strong> Me comprometo a utilizar de forma obligatoria y correcta los equipos aquí recibidos durante mi jornada laboral en las áreas de campo o taller. Entiendo que el cuidado de estos implementos es mi responsabilidad para garantizar mi integridad física y cumplir con las normas de Seguridad y Salud Laboral de la empresa.
        </div>

        <div style="margin-top: 80px;">
            <table width="100%">
                <tr>
                    <td align="center" width="50%">__________________________<br>Firma del Trabajador<br>Huella Dactilar</td>
                    <td align="center" width="50%">__________________________<br>Responsable de Entrega<br>Seguridad y Salud Laboral</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>