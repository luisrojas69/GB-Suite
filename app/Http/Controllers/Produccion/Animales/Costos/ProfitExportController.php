<?php

namespace App\Http\Controllers\Produccion\Animales\Costos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\Produccion\Animales\Costos\Expense;
use App\Models\Produccion\Animales\Costos\AccountingExport;
use App\Models\User; // Asumiendo que necesita el modelo User para 'exportedBy'
// Modelos adicionales requeridos por las relaciones: Animal, Location
use App\Models\Produccion\Animales\Animal;
use App\Models\Produccion\Animales\Location;
use Carbon\Carbon;

class ProfitExportController extends Controller
{
    /**
     * Muestra la vista con los gastos pendientes de exportar y el formulario de selección de período.
     */
    public function index()
    {
        // Obtener todos los gastos que aún no han sido exportados (export_id es NULL)
        $pendingExpenses = Expense::whereNull('export_id')
            // CORRECCIÓN APLICADA: Se cargan las dos posibles relaciones explícitamente
            ->with('costType', 'referenceAnimal', 'referenceLocation') 
            ->orderBy('expense_date', 'asc')
            ->get();

        return view('produccion.animales.costos.profit_export_index', compact('pendingExpenses'));
    }
    /**
     * Genera y descarga el archivo XML con los asientos contables para Profit.
     */
    public function generateExport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $exportedBy = auth()->user(); // Asumiendo autenticación

        // 1. Obtener gastos pendientes en el período
        $expensesToExport = Expense::whereNull('export_id')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->with('costType')
            ->get();

        if ($expensesToExport->isEmpty()) {
            return redirect()->back()->with('error', 'No hay gastos pendientes de exportación en el período seleccionado.');
        }

        // --- INICIO DE LA LÓGICA DE GENERACIÓN DE XML Y BALANCEO ---

        $accountingLines = collect();
        $totalDebitAmount = 0;
        $totalCreditAmount = 0;

        foreach ($expensesToExport as $expense) {
            $costType = $expense->costType;
            $amount = $expense->amount;
            $cecoId = $this->getCostCenterId($expense); // Lógica para obtener el CeCo

            // 1.1. Generar la descripción del asiento
            $description = $this->generateDescription($expense);
            $uidComment = $expense->uid; // El UID va en el campo Comentario para trazabilidad
            
            // Línea 1: DÉBITO (Gasto/Activo)
            $accountingLines->push($this->createLine(
                $costType->debit_account, 
                $description, 
                $uidComment, 
                $amount, 
                0, // Haber
                $cecoId
            ));
            $totalDebitAmount += $amount;

            // Línea 2: CRÉDITO (Pasivo/Banco)
            $accountingLines->push($this->createLine(
                $costType->credit_account, 
                $description, 
                $uidComment, 
                0, // Debe
                $amount, 
                $cecoId
            ));
            $totalCreditAmount += $amount;
        }

        // 2. Validación de Balance
        $isBalanced = ($totalDebitAmount === $totalCreditAmount);
        if (!$isBalanced) {
             // Esto NUNCA debería pasar si el mapeo es 1:1, pero es una protección
             return redirect()->back()->with('error', 'Error Contable: El archivo generado está desbalanceado. No se puede exportar.');
        }

        // 3. Generar el XML de Profit
        $xmlContent = $this->generateProfitXML($accountingLines);
        $fileName = 'ComprobanteDiario_' . Carbon::now()->format('Ymd_His') . '.xml';
        
        // 4. Registrar la Exportación y Marcar Gastos (Transacción Segura)
        DB::transaction(function () use ($exportedBy, $startDate, $endDate, $fileName, $totalDebitAmount, $accountingLines, $expensesToExport) {
            
            // Crear el registro en la bitácora
            $exportRecord = AccountingExport::create([
                'exported_by_user_id' => $exportedBy->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'export_date' => now(),
                'file_name' => $fileName,
                'total_expenses_exported' => $expensesToExport->count(),
                'total_accounting_lines' => $accountingLines->count(),
                'total_debit_amount' => $totalDebitAmount,
                'total_credit_amount' => $totalDebitAmount, // Debe ser igual por el balanceo
                'is_balanced' => true,
            ]);

            // Marcar los gastos como exportados
            Expense::whereIn('id', $expensesToExport->pluck('id'))->update(['export_id' => $exportRecord->id]);
        });

        // 5. Descargar el archivo
        return Response::make($xmlContent, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Helper para obtener el CeCo (Implementación de la política).
     */
    private function getCostCenterId(Expense $expense): ?string
    {
        // Implementación de la política definida: CeCo se obtiene de la Ubicación/Lote actual.
        
        $reference = $expense->reference;
        
        if (!$reference) {
            return null;
        }

        // Si es Lote/Ubicación
        if ($expense->reference_type === 'location') {
            return $reference->cost_center_id ?? null; // Asumiendo que locations tiene cost_center_id
        }

        // Si es Animal, obtener la ubicación actual y de ahí el CeCo.
        if ($expense->reference_type === 'animal' && $reference instanceof Animal) {
            // Asumiendo que el modelo Animal tiene una relación 'location' y que Location tiene 'cost_center_id'
            return $reference->location->cost_center_id ?? null;
        }

        return null;
    }

    /**
     * Helper para generar la descripción final.
     */
    private function generateDescription(Expense $expense): string
    {
        $template = $expense->costType->description_template;
        $refType = ucfirst($expense->reference_type);
        $refId = $expense->reference_id; // o el código si es mejor

        // Remplazar placeholders en la plantilla
        $description = str_replace(['{ref_type}', '{ref_id}', '{name}'], 
                                   [$refType, $refId, $expense->costType->name], 
                                   $template);

        return Str::limit($description, 50, '...'); // Limitar la longitud de la descripción si Profit tiene restricciones.
    }

    /**
     * Helper para crear una línea de asiento contable.
     */
    private function createLine(string $account, string $description, string $comment, float $debe, float $haber, ?string $cecoId): array
    {
        // Se utilizan los valores fijos confirmados (Moneda: BSD, Cambio: 1.00000)
        return [
            'Cuenta' => $account,
            'Descripcion' => $description,
            'Comentario' => $comment, // Aquí va el UID de trazabilidad
            'Debe' => number_format($debe, 2, '.', ''),
            'Haber' => number_format($haber, 2, '.', ''),
            'C.Costo' => $cecoId,
            'Moneda' => 'BSD',
            'Cambio' => '1.00000',
            'TipoDoc' => '',
            'DocRef' => '',
            'FecDoc' => Carbon::now()->format('Y-m-d'), // Opcionalmente usar la fecha del gasto: $expense->expense_date->format('Y-m-d')
        ];
    }
    
    /**
     * Genera el contenido XML en el formato requerido por Profit.
     */
    private function generateProfitXML(object $accountingLines): string
    {
        // Se genera el XML basado en el formato proporcionado por el usuario.
        
        $xml = '<?xml version="1.0"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
        
        // Estilos necesarios para la Fecha (s62) y Tasa de Cambio (s70)
        $xml .= '  <Styles>' . "\n";
        $xml .= '    <Style ss:ID="Default" ss:Name="Normal"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1" /><Borders /><Font /><Interior /><NumberFormat ss:Format="0.00" /><Protection /></Style>' . "\n";
        $xml .= '    <Style ss:ID="s62"><NumberFormat ss:Format="yyyy-MM-dd" /></Style>' . "\n";
        $xml .= '    <Style ss:ID="s70"><NumberFormat ss:Format="0.00000" /></Style>' . "\n";
        $xml .= '  </Styles>' . "\n";
        
        $xml .= '  <Worksheet ss:Name="Comprobante Diario">' . "\n";
        $xml .= '    <Table ss:ExpandedColumnCount="22" ss:ExpandedRowCount="' . ($accountingLines->count() + 1) . '" x:FullColumns="1">' . "\n";
        
        // Fila de Encabezados (tomada del XML de ejemplo)
        $xml .= '      <Row>' . "\n";
        $xml .= '        <Cell><Data ss:Type="String">Cuenta</Data></Cell><Cell><Data ss:Type="String">Descripcion</Data></Cell><Cell><Data ss:Type="String">Comentario</Data></Cell><Cell><Data ss:Type="String">Debe</Data></Cell><Cell><Data ss:Type="String">Haber</Data></Cell><Cell><Data ss:Type="String">C.Costo</Data></Cell><Cell><Data ss:Type="String">Moneda</Data></Cell><Cell><Data ss:Type="String">Cambio</Data></Cell><Cell><Data ss:Type="String">Tipo Doc.</Data></Cell><Cell><Data ss:Type="String">Doc Ref</Data></Cell><Cell><Data ss:Type="String">Cu. Gastos</Data></Cell><Cell><Data ss:Type="String">Auxiliar</Data></Cell><Cell><Data ss:Type="String">Atributo1</Data></Cell><Cell><Data ss:Type="String">Atributo2</Data></Cell><Cell><Data ss:Type="String">Atributo3</Data></Cell><Cell><Data ss:Type="String">Regla</Data></Cell><Cell><Data ss:Type="String">Fec. Doc</Data></Cell><Cell><Data ss:Type="String">Afecta Flujo Efec.</Data></Cell><Cell><Data ss:Type="String">Monto no afecta</Data></Cell><Cell><Data ss:Type="String">Afecta posición monetaria</Data></Cell><Cell><Data ss:Type="String">Monto no afecta posición</Data></Cell><Cell><Data ss:Type="String">Modifica Patrimonio</Data></Cell>' . "\n";
        $xml .= '      </Row>' . "\n";

        // Filas de Datos Contables
        foreach ($accountingLines as $line) {
            $xml .= '      <Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";
            $xml .= '        <Cell><Data ss:Type="String">' . $line['Cuenta'] . '</Data></Cell>' . "\n";
            $xml .= '        <Cell><Data ss:Type="String">' . htmlspecialchars($line['Descripcion']) . '</Data></Cell>' . "\n";
            $xml .= '        <Cell><Data ss:Type="String">' . htmlspecialchars($line['Comentario']) . '</Data></Cell>' . "\n";
            $xml .= '        <Cell><Data ss:Type="Number">' . $line['Debe'] . '</Data></Cell>' . "\n";
            $xml .= '        <Cell><Data ss:Type="Number">' . $line['Haber'] . '</Data></Cell>' . "\n";
            $xml .= '        <Cell><Data ss:Type="String">' . $line['C.Costo'] . '</Data></Cell>' . "\n";
            $xml .= '        <Cell><Data ss:Type="String">' . $line['Moneda'] . '</Data></Cell>' . "\n";
            $xml .= '        <Cell ss:StyleID="s70"><Data ss:Type="Number">' . $line['Cambio'] . '</Data></Cell>' . "\n";
            $xml .= '        <Cell><Data ss:Type="String">' . $line['TipoDoc'] . '</Data></Cell>' . "\n";
            $xml .= '        <Cell><Data ss:Type="String">' . $line['DocRef'] . '</Data></Cell>' . "\n";
            $xml .= '        <Cell><Data ss:Type="String"></Data></Cell>' . "\n"; // Cu. Gastos (empty)
            $xml .= '        <Cell><Data ss:Type="String"></Data></Cell>' . "\n"; // Auxiliar (empty)
            $xml .= '        <Cell><Data ss:Type="String"></Data></Cell>' . "\n"; // Atributo1 (empty)
            $xml .= '        <Cell><Data ss:Type="String"></Data></Cell>' . "\n"; // Atributo2 (empty)
            $xml .= '        <Cell><Data ss:Type="String"></Data></Cell>' . "\n"; // Atributo3 (empty)
            $xml .= '        <Cell><Data ss:Type="String"></Data></Cell>' . "\n"; // Regla (empty)
            $xml .= '        <Cell ss:StyleID="s62"><Data ss:Type="DateTime">' . $line['FecDoc'] . 'T00:00:00.000</Data></Cell>' . "\n"; // Fec. Doc
            $xml .= '        <Cell><Data ss:Type="String">false</Data></Cell>' . "\n"; // Afecta Flujo Efec.
            $xml .= '        <Cell><Data ss:Type="Number">0</Data></Cell>' . "\n"; // Monto no afecta
            $xml .= '        <Cell><Data ss:Type="String">false</Data></Cell>' . "\n"; // Afecta posición monetaria
            $xml .= '        <Cell><Data ss:Type="Number">0.00</Data></Cell>' . "\n"; // Monto no afecta posición
            $xml .= '        <Cell><Data ss:Type="String">false</Data></Cell>' . "\n"; // Modifica Patrimonio
            $xml .= '      </Row>' . "\n";
        }

        $xml .= '    </Table>' . "\n";
        $xml .= '  </Worksheet>' . "\n";
        $xml .= '</Workbook>';
        
        return $xml;
    }
}