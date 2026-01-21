<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* AJUSTE CLAVE: Aumentamos el margen inferior para reservar espacio al footer fijo */
        @page { 
            margin: 60px 40px 110px 40px; 
        }

        body { 
            font-family: sans-serif; 
            font-size: 12px; 
            color: #333; 
            line-height: 1.4; 
        }
        
        @php
            $isIT = ($assignment->item->item_group == 'IT');
            $mainColor = $isIT ? '#4e73df' : '#1cc88a'; 
            $headerTitle = $isIT ? 'COMPUTACIÓN Y TECNOLOGÍA' : 'ACTIVOS ADMINISTRATIVOS';
            $isUser = str_contains($assignment->assignable_type, 'User'); 
            $subjectLabel = $isUser ? 'al empleado' : 'al departamento';
        @endphp

        /* Encabezado */
        .header { width: 100%; border-bottom: 2px solid {{ $mainColor }}; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 150px; }
        .title { text-align: center; text-transform: uppercase; font-weight: bold; font-size: 14px; color: {{ $mainColor }}; }
        
        /* Secciones */
        .section { margin-bottom: 15px; }
        .section-title { background: #f8f9fc; padding: 5px; font-weight: bold; border-left: 4px solid {{ $mainColor }}; text-transform: uppercase; font-size: 10px; margin-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; }
        table th, table td { border: 1px solid #e3e6f0; padding: 7px; text-align: left; font-size: 11px; }
        table th { background-color: #f8f9fc; width: 25%; }

        /* Firmas: Usamos una tabla para asegurar alineación sin floats que rompan el flujo */
        .signature-table { width: 100%; margin-top: 40px; }
        .signature-cell { width: 45%; border: none; text-align: center; padding-top: 35px; vertical-align: bottom; }
        .signature-line { border-top: 1px solid #000; width: 100%; display: block; margin-bottom: 5px; }

        /* footer Fijo */
        .footer-container {
            position: fixed;
            bottom: -80px; /* Ajustado para que quepa en el margen de 110px */
            left: 0;
            right: 0;
            height: 100px;
        }

        .confidentiality { 
            background-color: #f8f9fc; 
            padding: 8px; 
            border: 1px solid #e3e6f0; 
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .footer-info { 
            text-align: center; 
            border-top: 1px solid #eaecf4; 
            padding-top: 8px; 
            font-size: 9px; 
            color: #b7b9cc; 
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 30%;">
                    <img src="{{ public_path('img/logogb.png') }}" class="logo">
                </td>
                <td style="border: none; text-align: right; font-size: 9px;">
                    <strong>Documento No:</strong> GB-ACTA-{{ str_pad($assignment->id, 5, '0', STR_PAD_LEFT) }}<br>
                    <strong>Fecha Emisión:</strong> {{ date('d/m/Y H:i') }}<br>
                    <strong>Grupo:</strong> {{ $assignment->item->item_group }}
                </td>
            </tr>
        </table>
    </div>

    <div class="title">Acta de Entrega de Equipos de {{ $headerTitle }}</div>

    <div class="section" style="margin-top: 15px;">
        <p>Por medio de la presente, se hace entrega formal del activo detallado a continuación 
        {{ $subjectLabel }} <strong>{{ strtoupper($assignment->assignable->nombre_completo) }}</strong>, 
        quien declara recibirlo en perfecto estado de funcionamiento y se compromete a su cuido, 
        preservación y uso exclusivo para fines laborales en <strong>Granja Boraure</strong>.</p>
    </div>

    <div class="section">
        <div class="section-title">Detalles del Activo</div>
        <table>
            <tr>
                <th>Activo / Nombre:</th><td>{{ $assignment->item->name }}</td>
                <th>Código Activo:</th><td>{{ $assignment->item->asset_tag ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Marca / Modelo:</th><td>{{ $assignment->item->brand }} {{ $assignment->item->model }}</td>
                <th>Serial S/N:</th><td>{{ $assignment->item->serial ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Ubicación:</th><td>{{ $assignment->location->nombre ?? 'N/A' }}</td>
                <td colspan="2" style="border:none;"></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Accesorios y Observaciones</div>
        <p style="font-style: italic; font-size: 10px;">
            {{ $assignment->accessories ?: 'No se registraron accesorios adicionales.' }}
        </p>
    </div>

    {{-- Firmas en tabla para mejor control --}}
    <br><br>
    <table class="signature-table">
        <tr>
            <td class="signature-cell">
                <span class="signature-line"></span>
                Entregado por ({{ $isIT ? 'Sistemas' : 'Administración' }})<br>
                Firma y Sello Autorizado
            </td>
            <td style="width: 10%; border:none;"></td>
            <td class="signature-cell">
                <span class="signature-line"></span>
                Recibido por ({{ $isUser ? 'Usuario' : 'Responsable de Área' }})<br>
                @if($isUser)
                    C.I: {{ $assignment->assignable->cedula ?? '__________' }}
                @else
                    Sello del Departamento
                @endif
            </td>
        </tr>
    </table>
<br><br><hr>
    {{-- Footer Fijo que contiene la cláusula y la info del sistema --}}
    <div class="footer-container">
        <div class="confidentiality">
            <strong style="font-size: 9px; color: {{ $mainColor }}; text-transform: uppercase;">Cláusula de Confidencialidad:</strong>
            <p style="font-size: 8px; text-align: justify; color: #5a5c69; margin: 3px 0 0 0;">
                Este documento y la información contenida es de carácter <strong>PRIVADO Y CONFIDENCIAL</strong>. 
                Su contenido está destinado únicamente al uso interno de Granja Boraure. Queda prohibida la reproducción o 
                divulgación total o parcial a terceros sin autorización expresa de la Gerencia. 
                El uso indebido puede acarrear sanciones administrativas y legales según las políticas internas.
            </p>
        </div>

        <div class="footer-info">
            Este reporte fue generado automáticamente desde el <strong>Sistema GBSuite</strong> de Granja Boraure.<br>
            Para soporte o dudas técnicas, comuníquese con: 
            <span style="color: {{ $mainColor }}; font-weight: bold;">sistemas@granjaboraure.com</span>
        </div>
    </div>
</body>
</html>