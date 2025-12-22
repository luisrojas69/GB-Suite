<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Animal;
use App\Models\AnimalCost;
use Carbon\Carbon;

class ProfitCostService
{
    // Centros de Costo de la actividad pecuaria confirmados (Pregunta 1)
    private const COST_CENTERS = [
        '5241', // Bovinos
        '5242', // Haras (Equinos)
        '5243', // Ovejos (Ovinos)
        // Se pueden agregar más si los necesita
    ];

    // Cuenta Contable de Inventario de Animales (debe ajustarse al código real)
    private const INVENTORY_ACCOUNT_CODE = '11201000000000000000'; // EJEMPLO: Ajuste este valor

    /**
     * Calcula y registra el costo unitario para cada Centro de Costo en un período dado.
     *
     * @param Carbon $startDate Fecha de inicio del periodo de análisis (ej: 2025-11-01)
     * @param Carbon $endDate Fecha de fin del periodo de análisis (ej: 2025-11-30)
     */
    public function calculateMonthlyCost(Carbon $startDate, Carbon $endDate): void
    {
        $startDateSql = $startDate->format('Y-m-d');
        $endDateSql = $endDate->format('Y-m-d');
        $periodDate = $startDate;

        echo "Iniciando cálculo de costos para el periodo: {$startDateSql} a {$endDateSql}\n";

        // Iterar sobre cada Centro de Costo relevante
        foreach (self::COST_CENTERS as $ceco) {
            $this->processCostCenter($ceco, $periodDate, $startDateSql, $endDateSql);
        }

        echo "Cálculo de costos finalizado.\n";
    }

    private function processCostCenter(string $ceco, Carbon $periodDate, string $startDateSql, string $endDateSql): void
    {
        // 1. OBTENER TOTAL DE ANIMALES ACTIVOS PARA EL PRORRATEO (Desde BD GB_APP)
        $activeAnimals = Animal::where('cost_center_id', $ceco)
                              ->where('is_active', true)
                              ->count();
        
        if ($activeAnimals === 0) {
            echo "CeCo {$ceco}: 0 animales activos, saltando cálculo.\n";
            return;
        }

        // 2. EJECUTAR EL SP EN PROFIT PLUS (Conexión sqlsrv_contabilidad)
        try {
            // El RepMayorAnalitico2KDoce devuelve múltiples filas. Queremos la fila resumen 
            // que contiene los totales (MontoD y MontoH) para la cuenta contable de inventario.
            
            // Los parámetros que usamos del SP RepMayorAnalitico2KDoce son:
            // @sCo_cue_d/@sCo_cue_h (Cuenta contable)
            // @sdFec_emis_d/@sdFec_emis_h (Período)
            // @sCo_cen_d/@sCo_cen_h (Centro de Costo)
            
            $results = DB::connection('sqlsrv_contabilidad')->select("
                EXEC [dbo].[RepMayorAnalitico2KDoce]
                    @sCo_cue_d = ?,
                    @sCo_cue_h = ?,
                    @sdFec_emis_d = ?,
                    @sdFec_emis_h = ?,
                    @sCo_cen_d = ?,
                    @sCo_cen_h = ?
            ", [
                self::INVENTORY_ACCOUNT_CODE, 
                self::INVENTORY_ACCOUNT_CODE,
                $startDateSql, 
                $endDateSql,
                $ceco, 
                $ceco
            ]);

            // Filtrar los resultados para encontrar el resumen de la cuenta de inventario
            // Buscamos la fila que no tiene comp_num (es el resumen o saldo inicial) y tiene la cuenta de inventario
            $accountSummary = collect($results)->first(function ($item) {
                // Buscamos el resumen de la cuenta contable. En el SP de Profit, 
                // las filas de resumen de cuenta tienen el campo 'comp_num' como NULL.
                return $item->co_cue === self::INVENTORY_ACCOUNT_CODE && $item->comp_num === null;
            });

        } catch (\Exception $e) {
            echo "Error al conectar o ejecutar SP en Profit para CeCo {$ceco}: " . $e->getMessage() . "\n";
            // Manejo de errores: log, notificar, etc.
            return;
        }

        if (empty($accountSummary)) {
            echo "CeCo {$ceco}: No se encontró resumen de cuenta contable en Profit.\n";
            return;
        }

        // 3. CÁLCULO DEL COSTO UNITARIO (Prorrateo)
        // El movimiento neto de la cuenta de inventario del CeCo es (Débito - Crédito)
        $montoD = (float) $accountSummary->MontoD;
        $montoH = (float) $accountSummary->MontoH;

        $totalAccumulatedExpense = $montoD - $montoH;
        
        // Fórmula de Costo Unitario Promedio:
        $unitCost = $totalAccumulatedExpense / $activeAnimals;

        echo "CeCo {$ceco}: Gasto Total: {$totalAccumulatedExpense} / Animales: {$activeAnimals} = Costo Unitario: {$unitCost}\n";

        // 4. ALMACENAMIENTO DEL RESULTADO (En BD GB_APP)
        // Ahora, se debe guardar un registro de AnimalCost por cada animal activo.
        
        // En un enfoque más eficiente, se actualizaría una tabla de resumen de costos unitarios
        // y luego se podría asignar ese costo a cada animal. Por simplicidad, aquí guardamos el unitario.
        
        // Opción 1: Guardar el costo unitario solo para los animales del CeCo
        Animal::where('cost_center_id', $ceco)
            ->where('is_active', true)
            ->get()
            ->each(function (Animal $animal) use ($periodDate, $ceco, $totalAccumulatedExpense, $activeAnimals, $unitCost) {
                AnimalCost::updateOrCreate(
                    [
                        'animal_id' => $animal->id,
                        'period_date' => $periodDate->format('Y-m-d'),
                    ],
                    [
                        'cost_center_id' => $ceco,
                        'total_accumulated_expense' => $totalAccumulatedExpense,
                        'active_animal_count' => $activeAnimals,
                        'unit_cost' => $unitCost,
                    ]
                );
            });
    }
}