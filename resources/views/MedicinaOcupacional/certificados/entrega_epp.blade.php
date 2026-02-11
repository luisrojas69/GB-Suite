<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Constancia de Entrega de EPP</title>
    <style>
        @page { 
            margin: 1.5cm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10px;
            color: #2c3e50;
            line-height: 1.6;
        }
        
        /* DOCUMENT BORDER */
        .document-border {
            border: 3px solid #1a592e;
            padding: 20px;
            min-height: 95vh;
        }
        
        /* HEADER */
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid #1a592e;
            padding-bottom: 15px;
        }
        
        .header-left {
            display: table-cell;
            width: 35%;
            vertical-align: middle;
        }
        
        .header-center {
            display: table-cell;
            width: 65%;
            text-align: center;
            vertical-align: middle;
        }
        
        .logo {
            max-width: 120px;
            height: auto;
        }
        
        .document-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a592e;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .document-subtitle {
            font-size: 10px;
            color: #555;
            font-weight: 600;
        }
        
        /* METADATA BOX */
        .metadata-box {
            background: #f8f9fc;
            border: 1px solid #e1e4e8;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
        
        .metadata-row {
            margin-bottom: 8px;
        }
        
        .metadata-row:last-child {
            margin-bottom: 0;
        }
        
        .metadata-label {
            font-weight: 700;
            color: #1a592e;
            font-size: 9px;
            text-transform: uppercase;
        }
        
        .metadata-value {
            color: #1f2937;
            font-size: 10px;
        }
        
        /* DECLARATION BOX */
        .declaration-box {
            background: linear-gradient(to bottom, #fffbeb, #ffffff);
            border: 2px solid #fbbf24;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: justify;
            font-size: 10px;
            line-height: 1.7;
        }
        
        /* TABLE */
        .epp-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 9px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        
        .epp-table thead {
            background: linear-gradient(to bottom, #1a592e, #2d7a4a);
            color: white;
        }
        
        .epp-table th {
            border: 1px solid #1a592e;
            padding: 10px 8px;
            text-align: center;
            font-weight: 700;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .epp-table td {
            border: 1px solid #d1d5db;
            padding: 12px 8px;
            color: #1f2937;
            vertical-align: middle;
        }
        
        .epp-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .epp-table tbody tr:hover {
            background-color: #f0fdf4;
        }
        
        .text-center {
            text-align: center;
        }
        
        .signature-cell {
            min-height: 50px;
            background: #ffffff;
        }
        
        /* CATEGORY HEADER */
        .category-row {
            background: linear-gradient(to right, #f8f9fc, #ffffff) !important;
            font-weight: 700;
            color: #1a592e;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.3px;
        }
        
        .category-row td {
            padding: 8px !important;
            border: 1px solid #1a592e !important;
        }
        
        /* COMMITMENT BOX */
        .commitment-box {
            background: linear-gradient(to bottom, #fee2e2, #ffffff);
            border: 2px solid #dc2626;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .commitment-title {
            font-size: 11px;
            font-weight: 700;
            color: #991b1b;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .commitment-content {
            font-size: 9.5px;
            color: #7f1d1d;
            line-height: 1.8;
            text-align: justify;
        }
        
        .commitment-list {
            margin: 10px 0 0 20px;
            line-height: 1.8;
        }
        
        /* SIGNATURE SECTION */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-container {
            display: table;
            width: 100%;
            border-spacing: 20px 0;
        }
        
        .signature-box {
            display: table-cell;
            width: 48%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 2px solid #1a592e;
            margin-top: 60px;
            padding-top: 8px;
        }
        
        .signature-label {
            font-weight: 700;
            color: #1a592e;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .signature-sublabel {
            color: #6b7280;
            font-size: 8px;
            font-style: italic;
        }
        
        /* FOOTER INFO */
        .footer-box {
            margin-top: 30px;
            padding: 10px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            font-size: 8px;
            color: #6b7280;
            text-align: center;
        }
        
        /* BADGE */
        .badge-new {
            display: inline-block;
            background: #d1fae5;
            color: #065f46;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="document-border">
        <!-- HEADER -->
        <div class="header-container">
            <div class="header-left">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" class="logo" alt="Logo Granja Boraure">
            </div>
            <div class="header-center">
                <div class="document-title">
                    Constancia de Entrega de Equipos de Protección Personal (EPP)
                </div>
                <div class="document-subtitle">
                    Granja Boraure, C.A. | RIF: J-08500570-6<br>
                    Servicio de Seguridad y Salud en el Trabajo
                </div>
            </div>
        </div>

        <!-- METADATA -->
        <div class="metadata-box">
            <div class="metadata-row">
                <span class="metadata-label">Trabajador:</span>
                <span class="metadata-value">{{ $paciente->nombre_completo }}</span>
            </div>
            <div class="metadata-row">
                <span class="metadata-label">Cédula de Identidad:</span>
                <span class="metadata-value">{{ $paciente->ci }}</span>
            </div>
            <div class="metadata-row">
                <span class="metadata-label">Código de Empleado:</span>
                <span class="metadata-value">{{ $paciente->cod_emp }}</span>
            </div>
            <div class="metadata-row">
                <span class="metadata-label">Cargo:</span>
                <span class="metadata-value">{{ $paciente->des_cargo }}</span>
            </div>
            <div class="metadata-row">
                <span class="metadata-label">Fecha de Entrega:</span>
                <span class="metadata-value">{{ date('d/m/Y') }}</span>
            </div>
        </div>

        <!-- DECLARATION -->
        <div class="declaration-box">
            Yo, <strong>{{ $paciente->nombre_completo }}</strong>, titular de la cédula de identidad <strong>{{ $paciente->ci }}</strong>, 
            hago constar mediante la presente que he recibido de <strong>GRANJA BORAURE, C.A.</strong> los siguientes 
            equipos de protección personal (EPP), los cuales me comprometo a utilizar correctamente durante mi jornada laboral 
            conforme a las normas de seguridad establecidas por la empresa.
        </div>

        <!-- EPP TABLE -->
        <table class="epp-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Cant.</th>
                    <th style="width: 47%;">Descripción del Equipo / Implemento</th>
                    <th style="width: 10%;">Estado</th>
                    <th style="width: 35%;">Firma de Recibido</th>
                </tr>
            </thead>
            <tbody>
                <!-- CATEGORÍA: PROTECCIÓN DE CABEZA -->
                <tr class="category-row">
                    <td colspan="4">PROTECCIÓN DE CABEZA</td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Casco de Seguridad Industrial</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Gorra / Sombrero de Protección Solar</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>

                <!-- CATEGORÍA: PROTECCIÓN FACIAL Y OCULAR -->
                <tr class="category-row">
                    <td colspan="4">PROTECCIÓN FACIAL Y OCULAR</td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Lentes de Seguridad / Protector Visual</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Careta / Protector Facial Completo</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>

                <!-- CATEGORÍA: PROTECCIÓN RESPIRATORIA -->
                <tr class="category-row">
                    <td colspan="4">PROTECCIÓN RESPIRATORIA</td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Mascarilla para Polvos y Partículas</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Respirador para Químicos / Vapores Orgánicos</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>

                <!-- CATEGORÍA: PROTECCIÓN AUDITIVA -->
                <tr class="category-row">
                    <td colspan="4">PROTECCIÓN AUDITIVA</td>
                </tr>
                <tr>
                    <td class="text-center">1 Par</td>
                    <td>Tapones Auditivos / Protectores de Oído</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Orejeras de Protección Auditiva</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>

                <!-- CATEGORÍA: PROTECCIÓN DE MANOS -->
                <tr class="category-row">
                    <td colspan="4">PROTECCIÓN DE MANOS</td>
                </tr>
                <tr>
                    <td class="text-center">1 Par</td>
                    <td>Guantes de Carnaza / Protección Mecánica</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
                <tr>
                    <td class="text-center">1 Par</td>
                    <td>Guantes de Nitrilo / Protección Química</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
                <tr>
                    <td class="text-center">1 Par</td>
                    <td>Guantes de Látex / Protección Biológica</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>

                <!-- CATEGORÍA: PROTECCIÓN CORPORAL -->
                <tr class="category-row">
                    <td colspan="4">PROTECCIÓN CORPORAL</td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Overol / Braga de Trabajo</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Delantal de Protección (PVC / Carnaza)</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Chaleco Reflectivo / Alta Visibilidad</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Impermeable / Capa de Lluvia</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>

                <!-- CATEGORÍA: PROTECCIÓN DE PIES -->
                <tr class="category-row">
                    <td colspan="4">PROTECCIÓN DE PIES</td>
                </tr>
                <tr>
                    <td class="text-center">1 Par</td>
                    <td>Botas de Seguridad (Caña Alta / Punta de Acero)</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
                <tr>
                    <td class="text-center">1 Par</td>
                    <td>Botas de Goma / Impermeables</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>

                <!-- CATEGORÍA: PROTECCIÓN EN ALTURA -->
                <tr class="category-row">
                    <td colspan="4">PROTECCIÓN EN ALTURA Y OTROS</td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Arnés de Seguridad / Protección en Altura</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
                <tr>
                    <td class="text-center">1 Und</td>
                    <td>Línea de Vida / Cabo de Anclaje</td>
                    <td class="text-center"><span class="badge-new">NUEVO</span></td>
                    <td class="signature-cell"></td>
                </tr>
            </tbody>
        </table>

        <!-- COMMITMENT -->
        <div class="commitment-box">
            <div class="commitment-title">Compromiso del Trabajador</div>
            <div class="commitment-content">
                Al firmar esta constancia, declaro que:
                <ul class="commitment-list">
                    <li>He recibido los equipos de protección personal en buen estado y funcionamiento</li>
                    <li>Me comprometo a utilizar obligatoriamente los EPP durante mi jornada laboral en las áreas que lo requieran</li>
                    <li>Entiendo que el uso correcto de estos implementos es fundamental para garantizar mi integridad física</li>
                    <li>Asumo la responsabilidad del cuidado y mantenimiento básico de los equipos recibidos</li>
                    <li>Reportaré inmediatamente cualquier daño, pérdida o deterioro de los equipos al Departamento de SST</li>
                    <li>Cumpliré con las normas de Seguridad y Salud Laboral establecidas por la empresa</li>
                    <li>Al momento de mi retiro o cambio de cargo, devolveré los equipos recibidos</li>
                </ul>
            </div>
        </div>

        <!-- SIGNATURES -->
        <div class="signature-section">
            <div class="signature-container">
                <div class="signature-box">
                    <div class="signature-line">
                        <div class="signature-label">Firma del Trabajador</div>
                        <div class="signature-sublabel">{{ $paciente->nombre_completo }}</div>
                        <div class="signature-sublabel">C.I.: {{ $paciente->ci }}</div>
                        <div style="margin-top: 10px; font-size: 8px; color: #6b7280;">Huella Dactilar</div>
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">
                        <div class="signature-label">Responsable de Entrega</div>
                        <div class="signature-sublabel">Departamento de Seguridad y Salud Laboral</div>
                        <div class="signature-sublabel">Sello de la Empresa</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer-box">
            <strong>IMPORTANTE:</strong> Esta constancia es un documento oficial que certifica la entrega de equipos de protección personal.
            El trabajador debe conservar una copia para sus registros personales.<br>
            Documento generado el {{ date('d/m/Y H:i') }} | Granja Boraure, C.A. | Departamento de Seguridad y Salud en el Trabajo
        </div>
    </div>
</body>
</html>