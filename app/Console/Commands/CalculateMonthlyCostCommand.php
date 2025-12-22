<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProfitCostService;
use Carbon\Carbon;

class CalculateMonthlyCostCommand extends Command
{
    /**
     * El nombre y la firma del comando de consola.
     * La firma es el comando que se ejecuta: 'pecuario:calculate-costs'
     */
    protected $signature = 'pecuario:calculate-costs';

    /**
     * Descripción del comando de consola.
     */
    protected $description = 'Calcula y almacena el costo unitario mensual de los semovientes a partir de Profit Plus.';

    /**
     * Ejecuta el comando de consola.
     */
    public function handle(ProfitCostService $costService)
    {
        // El análisis es mensual. Se calcula el costo del mes COMPLETO anterior.
        $lastMonth = Carbon::now()->subMonth();

        $startDate = $lastMonth->startOfMonth();
        $endDate = $lastMonth->endOfMonth();

        $this->info("Calculando costos pecuarios para el mes de {$lastMonth->format('Y-m')}.");

        try {
            // Llama al servicio que contiene la lógica de conexión al SP de Profit
            $costService->calculateMonthlyCost($startDate, $endDate);
            $this->info('Cálculo de costos completado exitosamente.');
        } catch (\Exception $e) {
            $this->error('Ocurrió un error durante el cálculo de costos: ' . $e->getMessage());
            // En un entorno de producción, también se debe enviar una notificación de error aquí.
            return 1;
        }
        return 0;
    }
}