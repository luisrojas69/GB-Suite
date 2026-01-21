<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { width: 100%; border-bottom: 2px solid #e74a3b; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 150px; }
        .title { text-align: center; text-transform: uppercase; font-weight: bold; font-size: 16px; color: #e74a3b; }
        .section { margin-bottom: 20px; }
        /* Cambiamos el color a rojo/naranja para diferenciar que es un retorno */
        .section-title { background: #f8f9fc; padding: 5px; font-weight: bold; border-left: 4px solid #e74a3b; border-bottom: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background-color: #f8f9fc; width: 25%; }
        .footer { margin-top: 60px; width: 100%; }
        .signature-box { width: 40%; border-top: 1px solid #000; text-align: center; padding-top: 5px; }
        .observation-box { border: 1px solid #ddd; padding: 15px; min-height: 60px; background-color: #fffdf0; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 30%;">
                    <img src="{{ public_path('img/logoB.png') }}" class="logo">
                </td>
                <td style="border: none; text-align: right;">
                    <strong>Acta de Retorno No:</strong> IT-RET-{{ str_pad($assignment->id, 5, '0', STR_PAD_LEFT) }}<br>
                    <strong>Fecha de Retorno:</strong> {{ $assignment->returned_at->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="title">Acta de Recepción y Devolución de Activos</div>

    <div class="section">
        <p>Por medio de la presente, se deja constancia de la <strong>devolución formal</strong> del equipo informático que estaba bajo la responsabilidad de: 
        <strong>{{ $assignment->assignable->nombre_completo }}</strong>, adscrito al departamento de <strong>{{ $assignment->location->nombre ?? 'N/A' }}</strong>.</p>
    </div>

    <div class="section">
        <div class="section-title">DATOS DEL EQUIPO DEVUELTO</div>
        <table>
            <tr>
                <th>Equipo / Nombre:</th><td>{{ $assignment->item->name }}</td>
                <th>Nro. Activo:</th><td>{{ $assignment->item->asset_tag ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Marca / Modelo:</th><td>{{ $assignment->item->brand }} {{ $assignment->item->model }}</td>
                <th>Serial:</th><td>{{ $assignment->item->serial ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">ESTADO DE RECEPCIÓN Y OBSERVACIONES</div>
        <div class="observation-box">
            {{ $assignment->return_notes ?: 'El equipo se recibe conforme, sin observaciones adicionales al momento de la entrega.' }}
        </div>
    </div>

    <div class="section">
        <p style="font-size: 11px;">
            Al firmar este documento, el departamento de Sistemas/Almacén valida la entrega física del equipo. 
            La liberación de responsabilidad queda sujeta a la revisión técnica posterior si se detectaran daños ocultos no declarados en este acto.
        </p>
    </div>

    <div class="footer">
        <table style="border: none; width: 100%;">
            <tr style="border: none;">
                <td style="border: none; width: 45%; text-align: center;">
                    <div style="border-top: 1px solid #000; padding-top: 5px;">
                        Entregado por (Usuario)<br>
                        C.I: {{ $assignment->assignable->cedula ?? '__________' }}
                    </div>
                </td>
                <td style="border: none; width: 10%;"></td>
                <td style="border: none; width: 45%; text-align: center;">
                    <div style="border-top: 1px solid #000; padding-top: 5px;">
                        Recibido por (Sistemas/Almacén)<br>
                        Nombre: ____________________
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>