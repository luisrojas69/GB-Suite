<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { 
            margin: 60px 40px 130px 40px; 
        }

        body { 
            font-family: sans-serif; 
            font-size: 11px; 
            color: #333; 
            line-height: 1.4; 
        }
        
        @php
            // Tomamos el primero para sacar datos generales del encabezado
            $first = $assignments->first();
            $isIT = ($first->item->item_group == 'IT');
            $mainColor = $isIT ? '#4e73df' : '#1cc88a'; 
            $headerTitle = $isIT ? 'COMPUTACIÓN Y TECNOLOGÍA' : 'ACTIVOS ADMINISTRATIVOS';
            $isUser = str_contains($first->assignable_type, 'Paciente'); 
            $subjectLabel = $isUser ? 'al empleado' : 'al departamento';
        @endphp

        .header { width: 100%; border-bottom: 2px solid {{ $mainColor }}; padding-bottom: 10px; margin-bottom: 15px; }
        .logo { width: 140px; }
        .title { text-align: center; text-transform: uppercase; font-weight: bold; font-size: 13px; color: {{ $mainColor }}; margin-bottom: 10px; }
        
        .section-title { background: #f8f9fc; padding: 5px; font-weight: bold; border-left: 4px solid {{ $mainColor }}; text-transform: uppercase; font-size: 10px; margin: 15px 0 10px 0; }
        
        table { width: 100%; border-collapse: collapse; }
        .table-items th { background-color: #f8f9fc; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 9px; }
        .table-items td, .table-items th { border: 1px solid #e3e6f0; padding: 6px; }

        .signature-table { width: 100%; margin-top: 30px; }
        .signature-cell { width: 45%; border: none; text-align: center; padding-top: 30px; vertical-align: bottom; }
        .signature-line { border-top: 1px solid #000; width: 100%; display: block; margin-bottom: 5px; }

        .footer-container {
            position: fixed;
            bottom: -100px;
            left: 0;
            right: 0;
            height: 110px;
        }

        .confidentiality { 
            background-color: #f8f9fc; 
            padding: 8px; 
            border: 1px solid #e3e6f0; 
            border-radius: 4px;
        }

        .footer-info { 
            text-align: center; 
            padding-top: 8px; 
            font-size: 8px; 
            color: #b7b9cc; 
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 30%;">
                    {{-- Verifica que la imagen exista o usa base64 si da problemas en servidor --}}
                    <img src="{{ public_path('img/logogb.png') }}" class="logo">
                </td>
                <td style="border: none; text-align: right; font-size: 9px;">
                    <strong>ACTA DE ENTREGA POR LOTE</strong><br>
                    <strong>Fecha Emisión:</strong> {{ date('d/m/Y H:i') }}<br>
                    <strong>Responsable:</strong> {{ $first->assignable->nombre_completo ?? $first->assignable->name }}
                </td>
            </tr>
        </table>
    </div>

    <div class="title">Acta de Entrega de Equipos de {{ $headerTitle }}</div>

    <div class="section">
        <p>Por medio de la presente, se hace entrega formal de los <strong>activos informáticos y/o administrativos</strong> detallados en el siguiente listado {{ $subjectLabel }} <strong>{{ strtoupper($first->assignable->nombre_completo ?? $first->assignable->name) }}</strong>, 
        quien declara recibirlos en perfecto estado de funcionamiento y se compromete a su cuido y uso exclusivo para fines laborales en <strong>Granja Boraure CA</strong>.</p>
    </div>

    <div class="section">
        <div class="section-title">Listado de Equipos Entregados (Lote)</div>
        <table class="table-items">
            <thead>
                <tr>
                    <th>Cód. Activo</th>
                    <th>Descripción / Nombre</th>
                    <th>Marca / Modelo</th>
                    <th>Serial S/N</th>
                    <th>Ubicación Destino</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $asig)
                <tr>
                    <td style="text-align: center;">{{ $asig->item->asset_tag ?? 'N/A' }}</td>
                    <td>{{ $asig->item->name }}</td>
                    <td>{{ $asig->item->brand }} {{ $asig->item->model }}</td>
                    <td>{{ $asig->item->serial ?? 'N/A' }}</td>
                    <td>{{ $asig->location->nombre ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Observaciones Generales</div>
        <p style="font-style: italic; font-size: 10px;">
            El receptor se hace responsable por la integridad física de los equipos anteriormente listados. 
            Cualquier falla técnica debe ser reportada de inmediato al departamento de {{ $isIT ? 'Sistemas' : 'Administración' }}.
        </p>
    </div>

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
                    <strong>C.I:</strong> {{ $first->assignable->cedula ?? '__________' }}
                @else
                    <strong>Sello del Departamento</strong>
                @endif
            </td>
        </tr>
    </table>

    <div class="footer-container">
        <div class="confidentiality">
            <strong style="font-size: 8px; color: {{ $mainColor }}; text-transform: uppercase;">Cláusula de Confidencialidad:</strong>
            <p style="font-size: 8px; text-align: justify; color: #5a5c69; margin: 2px 0 0 0;">
                Este documento es de carácter <strong>PRIVADO</strong>. Su contenido está destinado únicamente al uso interno de Granja Boraure. 
                Queda prohibida la reproducción total o parcial sin autorización. El uso indebido acarreará sanciones legales.
            </p>
        </div>
        <div class="footer-info">
            Generado automáticamente por <strong>GBSuite</strong> | Granja Boraure | sistemas@granjaboraure.com
        </div>
    </div>
</body>
</html>