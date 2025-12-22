<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Logistica\Taller\Activo; // <-- ¡Asegúrate de que este namespace sea correcto!
use App\Models\Logistica\Taller\ProgramacionMP; // <-- ¡Asegúrate de que este namespace sea correcto!
use Carbon\Carbon;

class CheckMantenimientoPreventivo extends Command
{
    /**
     * El nombre y la firma del comando de artisan.
     * @var string
     */
    protected $signature = 'mantenimiento:check-mp';

    /**
     * La descripción del comando.
     * @var string
     */
    protected $description = 'Verifica las programaciones de Mantenimiento Preventivo y actualiza su estado (Próximo a Vencer/Vencido).';

    /**
     * Ejecuta el comando de consola.
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando verificación de Mantenimiento Preventivo...');

        $programaciones = ProgramacionMP::whereIn('status', ['Vigente', 'Proximo a Vencer'])->get();
        $totalActualizadas = 0;
        $fechaHoy = Carbon::today();

        foreach ($programaciones as $prog) {
            $activo = $prog->activo;

            if (!$activo) continue; // Asegurarse de que el activo existe

            $lecturaActual = $activo->lectura_actual;
            $updated = false;

            // 1. Verificación por Lectura (KM o Horas)
            $diferenciaLectura = $prog->proximo_valor_lectura - $lecturaActual;
            
            // Regla: Si falta menos del 10% del intervalo original para el mantenimiento (Próximo a Vencer)
            $intervaloTotal = $prog->proximo_valor_lectura - $prog->ultimo_valor_ejecutado;
            $umbralAlerta = $intervaloTotal * 0.10;

            if ($diferenciaLectura <= $umbralAlerta && $diferenciaLectura > 0 && $prog->status === 'Vigente') {
                $prog->status = 'Proximo a Vencer';
                $updated = true;
                $this->warn("Activo {$activo->codigo} - MP {$prog->checklist->nombre} pronto. Lectura actual: {$lecturaActual}");
            }
            
            // Regla: Si ya se superó el valor meta (Vencido)
            if ($lecturaActual >= $prog->proximo_valor_lectura && $prog->status !== 'Vencido') {
                $prog->status = 'Vencido';
                $updated = true;
                $this->error("Activo {$activo->codigo} - MP {$prog->checklist->nombre} VENCIDO. Lectura actual: {$lecturaActual}");
            }
            
            // 2. Verificación por Fecha (Para MPs Anuales, Semestrales, etc.)
            if ($prog->proxima_fecha_mantenimiento && $prog->proxima_fecha_mantenimiento->lessThanOrEqualTo($fechaHoy) && $prog->status !== 'Vencido') {
                $prog->status = 'Vencido';
                $updated = true;
                $this->error("Activo {$activo->codigo} - MP {$prog->checklist->nombre} VENCIDO por fecha.");
            }

            if ($updated) {
                $prog->save();
                $totalActualizadas++;
            }
        }

        $this->info("Verificación finalizada. Se actualizaron {$totalActualizadas} programaciones.");

        return Command::SUCCESS;
    }
}

