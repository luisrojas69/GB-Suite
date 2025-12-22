<?php

namespace App\Services;

class ArrimoParserService
{
    /**
     * Procesa el TXT completo y devuelve un arreglo de arrimes normalizados.
     */
    public function processTxtContent(string $txtContent): array
    {
        // Dividir el contenido por saltos de línea (\r\n o \n)
        $lines = preg_split('/\r?\n/', $txtContent);
        $arrimes = [];

        foreach ($lines as $line) {
            $row = $this->parseArrimoLine($line);
            // Solo añadimos la fila si el parsing fue exitoso y el boleto es numérico
            if ($row && is_numeric($row['boleto_remesa'])) {
                $arrimes[] = $row;
            }
        }

        return $arrimes;
    }

    /**
     * Parser robusto por espacios (tolerante a columnas variables).
     * Extrae y normaliza los 15 campos del reporte.
     * @param string $line La línea de texto del reporte.
     * @return array|null Los datos estructurados o null si la línea es inválida.
     */
    protected function parseArrimoLine(string $line): ?array
    {
        $line = trim($line);

        // 1. Descartar encabezados, totales, y líneas de control
        if ($line === '' ||
            preg_match('/^(Sistema|Caña|Día|Proceso|Transportista|Cañicultor|Tab\s|Hda:|Total|T\.T\.P)/', $line)) {
            return null;
        }

        // 2. Tokenizar por uno o más espacios para manejar el ancho variable
        $parts = preg_split('/\s+/', $line);
        // Debe tener al menos 14 tokens para asegurar que todos los campos clave están presentes.
        if (!$parts || count($parts) < 14) {
            return null;
        }

        // 3. Extracción de Tokens (por posición esperada)
        $tab     = $parts[0] ?? null;   // 015
        $boleto  = $parts[1] ?? null;   // 13442
        $remesa  = $parts[2] ?? null;   // 0160975
        $jaiba   = $parts[3] ?? null;   // GBC11
        $empuje  = $parts[4] ?? null;   // GBT20
        $canaStr = $parts[5] ?? null;   // 29,250
        $rdtoStr = $parts[6] ?? null;   // 5.09
        $difStr  = $parts[7] ?? null;   // 0.00
        $ttpStr  = $parts[8] ?? null;   // 4.25
        $fecha   = $parts[9] ?? null;   // 24/07/17
        $trashStr= $parts[10] ?? null;  // .00
        $placa   = $parts[11] ?? null;  // C66-000
        
        // El nombre y el contratista están al final y pueden tener espacios dentro del nombre
        // El código de contratista (ej: 04-01) siempre es el ÚLTIMO token.
        $contr = $parts[count($parts) - 1] ?? null; // 04-01
        
        // El nombre del chofer son los tokens entre la placa (11) y el contratista (último-1)
        $nombreTokens = array_slice($parts, 12, count($parts) - 13);
        $nombre  = implode(' ', $nombreTokens); // VICTOR TIMAURE

        // 4. Validaciones Mínimas (Patrones)
        if (!preg_match('/^\d{3}$/', (string)$tab)) return null;            // Tablón (e.g., 015)
        if (!preg_match('/^\d{5}$/', (string)$boleto)) return null;         // Boleto (e.g., 13442)
        if (!preg_match('/^\d{7}$/', (string)$remesa)) return null;         // Remesa (e.g., 0160975)
        if (!preg_match('/^\d{2}\/\d{2}\/\d{2}$/', (string)$fecha)) return null; // Fecha (e.g., 24/07/17)
        if (!preg_match('/^\d{2}-\d{2}$/', (string)$contr)) return null;     // Contratista (e.g., 04-01)

        // 5. Normalizaciones de Datos y Formato
        // Caña: Eliminar comas/puntos para obtener el valor numérico (29,250 -> 29250.0)
        $cana = (float) str_replace([',','.'], ['',''], (string)$canaStr); 
        $rdto = (float) $rdtoStr;
        $dif  = (float) $difStr;
        
        // TTP y Trash: Convertir '.00' a 0.0
        $ttp  = ($ttpStr === '.00') ? 0.0 : (float) $ttpStr;
        $trash= ($trashStr === '.00') ? 0.0 : (float) $trashStr;

        // Fecha: Convertir DD/MM/YY a YYYY-MM-DD (Laravel format)
        $fechaIso = \DateTime::createFromFormat('d/m/y', $fecha)?->format('Y-m-d') ?? null;
        if (is_null($fechaIso)) return null; // Descartar si la fecha es inválida

        // 6. Retorno de Array Limpio
        return [
            'tablon_codigo'      => $tab,
            'boleto_remesa'      => $boleto,
            'remesa_externa'     => $remesa,
            'jaiba'              => $jaiba,
            'empuje'             => $empuje,
            'toneladas_bruto'    => $cana,
            'rendimiento'        => $rdto,
            'dif'                => $dif,
            'ttp'                => $ttp,
            'fecha_arrime'       => $fechaIso,
            'trash'              => $trash,
            'placa'              => $placa,
            'nombre_chofer'      => $nombre,
            'contratista_codigo' => $contr,
        ];
    }
}