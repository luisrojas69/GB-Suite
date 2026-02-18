<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Accidente - INPSASEL</title>
    <style>
        @page { margin: 1cm; size: letter; }
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 10px; 
            color: #000; 
            line-height: 1.2; 
        }
        
        /* Utilidades */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .w-100 { width: 100%; }
        
        /* Estilos de Tabla Principal */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 5px;
        }
        
        td, th { 
            border: 1px solid #000; 
            padding: 4px; 
            vertical-align: top;
        }

        /* Cabeceras de secciones (gris) */
        .section-header {
            background-color: #d9d9d9;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px;
            border: 1px solid #000;
        }

        /* Header sin bordes internos visibles para el logo */
        .header-table td { border: none; border-bottom: 2px solid #000; }
        
        /* Inputs simulados (espacios vacíos o rellenos) */
        .input-line { border-bottom: 1px solid #000; display: inline-block; padding-left: 5px; }
        
        /* Checkbox simulado */
        .check-box { 
            display: inline-block; 
            width: 12px; 
            height: 12px; 
            border: 1px solid #000; 
            text-align: center; 
            line-height: 10px; 
            font-size: 9px;
            margin-left: 2px;
        }

        /* Área de firmas */
        .footer-signatures td {
            border: 1px solid #000;
            height: 60px;
            vertical-align: bottom;
        }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { width: 150px; }

        /* Clases para ocultar al imprimir */
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="background: #ffc; padding: 10px; text-align: center; border: 1px solid #ddd; margin-bottom: 20px;">
        Pulse <strong>Ctrl+P</strong> para imprimir o guardar como PDF.
    </div>

    <table class="header">
        <tr>
            <td style="width: 30%;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" class="logo">
            </td>
            <td style="width: 70%; text-align: center; vertical-align: middle;">
                <h2 style="margin: 0; font-size: 16px;">GRANJA BORAURE, C.A.</h2>
                <p style="margin: 2px 0;">RIF: J-08500570-6</p>
                <h3 style="margin: 5px 0; font-size: 14px;">NOTIFICACIÓN INMEDIATA DE ACCIDENTES LABORALES</h3>
            </td>
        </tr>
    </table>

    <div style="text-align: right; font-size: 9px; margin-bottom: 5px;">
        <strong>FORMATO: 019-PSST</strong>
    </div>

    <div class="section-header">DATOS DEL TRABAJADOR ACCIDENTADO</div>
    <table>
        <tr>
            <td colspan="3">
                <strong>Nombres y Apellidos:</strong><br>
                {{ $accidente->paciente->nombre_completo }}
            </td>
            <td colspan="1">
                <strong>Fecha Nacimiento:</strong><br>
                {{ \Carbon\Carbon::parse($accidente->paciente->fecha_nac)->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <td style="width: 25%;">
                <strong>Cédula de Identidad:</strong><br>
                {{ $accidente->paciente->ci }}
            </td>
            <td style="width: 15%;">
                <strong>Edad:</strong><br>
                {{ \Carbon\Carbon::parse($accidente->paciente->fecha_nac)->age }} años
            </td>
            <td style="width: 30%;">
                <strong>Teléfono:</strong><br>
                {{ $accidente->paciente->telefono }}
            </td>
            <td style="width: 30%;">
                <table style="width: 100%; border: none; margin: 0;">
                    <tr>
                        <td style="border: none; border-right: 1px solid #000; width: 50%;">
                            <strong>Nro. de Accidente:</strong><br>
                            #{{ str_pad($accidente->id, 4, "0", STR_PAD_LEFT); }} </td>
                        <td style="border: none;">
                            <strong>Mano Dominante:</strong><br>
                            {{ $accidente->paciente->es_zurdo == '1' ? 'IZQUIERDA' : 'DERECHA' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Edo Civil:</strong> &nbsp;
                S <span class="input-line" style="width:15px; text-align:center;">{{ $accidente->paciente->edo_civ == 'S' ? 'X' : '' }}</span> &nbsp;
                C <span class="input-line" style="width:15px; text-align:center;">{{ $accidente->paciente->edo_civ == 'C' ? 'X' : '' }}</span> &nbsp;
                OTRO <span class="input-line" style="width:15px; text-align:center;">{{ !in_array($accidente->paciente->edo_civ, ['S','C']) ? 'X' : '' }}</span>
            </td>
            <td style="border-right: none;">
                <strong>Nº Hijos:</strong> {{ $accidente->paciente->cantidad_hijos }}
            </td>
            <td style="border-left: 1px solid #000;">
                @php
                    switch ($accidente->paciente->nivel_acad) {
                    case 0:
                        $nivel_academico = 'Analfabeta';
                        break;
                    case 1:
                        $nivel_academico = 'Bachiller';
                        break;
                    case 2:
                        $nivel_academico = 'Universitario';
                        break;
                    default:
                        $nivel_academico = 'N/A';
                }
                @endphp
                <strong>Nivel Educativo:</strong> {{ $nivel_academico }}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>Dirección de Habitación:</strong><br>
                {{ $accidente->paciente->direccion ?? 'No especificada' }} 
                {{ $accidente->paciente->lugar_nac ?? 'No especificada' }} 
                {{ $accidente->paciente->municipio ? ', Mun. '.$accidente->paciente->municipio : '' }}
                {{ $accidente->paciente->estado ? ', Edo. '.$accidente->paciente->estado : '' }}
            </td>
        </tr>
    </table>

    <div class="section-header">DATOS OCUPACIONALES</div>
    <table>
        <tr>
            <td style="width: 20%;">
                <strong>Fecha Ingreso:</strong><br>
                {{ \Carbon\Carbon::parse($accidente->paciente->fecha_ing)->format('d/m/Y') }}
            </td>
            <td style="width: 20%;">
                <strong>Salario:</strong><br>
                Bs. {{ number_format((float)$accidente->paciente->sueldo_mensual, 2, ',', '.') }}
            </td>
            <td style="width: 20%;">
                <strong>Código Ocup:</strong><br>
                {{ $accidente->paciente->co_cargo }}
            </td>
            <td style="width: 40%;">
                <strong>Situación de Empleo / Cargo:</strong><br>
                {{ $accidente->paciente->des_cargo }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>Asegurado IVSS:</strong><br>
                SI [ X ] &nbsp; NO [ &nbsp; ]
            </td>
            <td colspan="2">
                <strong>Antigüedad en el Cargo:</strong><br>
                @php
                    $ingreso = \Carbon\Carbon::parse($accidente->paciente->fecha_ing);
                    $accidenteDate = \Carbon\Carbon::parse($accidente->fecha_hora_accidente);
                    $antiguedad = $ingreso->diff($accidenteDate);
                @endphp
                {{ $antiguedad->y }} años, {{ $antiguedad->m }} meses
            </td>
            <td>
                <strong>Jornada:</strong><br>
                DIURNA
            </td>
        </tr>
    </table>

    <div class="section-header">DATOS DEL ACCIDENTE</div>
    <table>
        <tr>
            <td colspan="3">
                <strong>Gravedad:</strong> &nbsp;&nbsp;
                Leve [ <span style="font-weight:bold">{{ $accidente->gravedad == 'Leve' ? 'X' : ' ' }}</span> ] &nbsp;&nbsp;
                Grave [ <span style="font-weight:bold">{{ $accidente->gravedad == 'Grave' ? 'X' : ' ' }}</span> ] &nbsp;&nbsp;
                Mortal [ <span style="font-weight:bold">{{ $accidente->gravedad == 'Mortal' ? 'X' : ' ' }}</span> ]
            </td>
            <td>
                <strong>Fecha Accidente:</strong><br>
                {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="width: 50%;">
                <strong>Lugar Preciso del Accidente:</strong><br>
                {{ $accidente->lugar_exacto }}
            </td>
            <td>
                <strong>Hora (militar):</strong><br>
                {{ \Carbon\Carbon::parse($accidente->fecha_hora_accidente)->format('H:i') }}
            </td>
            <td>
                <strong>Horas Trabajadas:</strong><br>
                {{ $accidente->horas_trabajadas }}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>Lugar Donde Ocurre (Departamento/Área):</strong><br>
                {{ $accidente->paciente->des_depart }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="height: 80px;">
                <strong>Descripción del accidente (Relato):</strong><br>
                {{ $accidente->descripcion_relato }}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>Agente Material que lo Causa:</strong><br>
                {{-- Usamos causas_inmediatas como proxy si no tienes campo de agente material específico --}}
                {{ $accidente->causas_inmediatas ?? 'No especificado' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Tipo de Accidente / Evento:</strong><br>
                {{ $accidente->tipo_evento }}
            </td>
            <td colspan="2">
                <strong>Testigos:</strong><br>
                {{ $accidente->testigos ?? 'Sin testigos' }}

                 <div style="text-align: center; font-size: 9px; margin-bottom: 5px;">
                    <br>
                ____________________________________________________________<br>
                    <strong>FIRMA DE LOS TESTIGOS</strong>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>Parte del Cuerpo Lesionada / Descripcion de la Lesi&oacute;n:</strong><br>
                {{ $accidente->parte_lesionada }} / ({{ $accidente->lesion_detallada }})
            </td>
        </tr>
        <tr>
            <td colspan="4" style="height: 40px;">
                <strong>Medidas tomadas (Acciones Correctivas):</strong><br>
                {{ $accidente->acciones_correctivas }}
            </td>
        </tr>
    </table>

    <table class="footer-signatures" style="margin-top: 20px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <br><br>
                ________________________________<br>
                <strong>FIRMA DE SSL O SUPERVISOR</strong>
                <br><span style="font-size: 10px;">{{ $accidente->user->name ." ".$accidente->user->last_name }}</span>
                <br><span style="font-size: 8px;">(Investigador)</span>
            </td>
            <td style="width: 50%; text-align: center;">
                <br><br>
                ________________________________<br>
                <strong>FIRMA DEL TRABAJADOR LESIONADO</strong>
                <br><span style="font-size: 10px;">{{ $accidente->paciente->nombre_completo }}</span>
                <br><span style="font-size: 8px;">C.I.: {{ $accidente->paciente->ci }}</span>
            </td>
        </tr>
    </table>

    <div style="text-align: center; font-size: 8px; margin-top: 10px; color: #555;">
        Programa de Seguridad y Salud Laboral - Aprobado por el Comité de Seguridad y Salud Laboral con la participaci&oacute;n de los trabajadores
    </div>
    <div style="text-align: right; font-size: 9px; margin-bottom: 5px;">
        <strong>FORMATO: 019-PSST</strong>
    </div>

</body>
</html>