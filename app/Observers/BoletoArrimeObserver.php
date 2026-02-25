<?php

namespace App\Observers;

use App\Models\Produccion\Arrimes\BoletoArrime;
use App\Models\Produccion\Arrimes\MoliendaEjecutada;

class BoletoArrimeObserver
{
    // Se ejecuta después de crear o actualizar un boleto
    public function saved(BoletoArrime $boleto)
    {
        $this->actualizarConsolidado($boleto);
    }

    // Se ejecuta al eliminar un boleto
    public function deleted(BoletoArrime $boleto)
    {
        $this->actualizarConsolidado($boleto);
    }

    protected function actualizarConsolidado($boleto)
    {
        // Buscamos o creamos el registro de ejecución para ese tablón/zafra
        $ejecucion = MoliendaEjecutada::firstOrCreate(
            ['zafra_id' => $boleto->zafra_id, 'tablon_id' => $boleto->tablon_id],
            ['estado_cosecha' => 'En Proceso']
        );

        // Recalculamos los totales solo para este tablón
        $totales = BoletoArrime::where('tablon_id', $boleto->tablon_id)
            ->where('zafra_id', $boleto->zafra_id)
            ->selectRaw('SUM(toneladas_netas) as total_tons, AVG(rendimiento_real) as avg_rend')
            ->first();

        $ejecucion->update([
            'toneladas_reales' => $totales->total_tons ?? 0,
            'rendimiento_real_avg' => $totales->avg_rend ?? 0,
            'fecha_fin_cosecha' => $boleto->fecha_arrime // Se actualiza con el último boleto
        ]);
    }
}