<?php

namespace App\Console\Commands\RRHH\Comedor;

use Illuminate\Console\Command;
use Jmrashed\Zkteco\Lib\ZKTeco;
use App\Models\RRHH\Comedor\DiningRecord;
use App\Models\RRHH\Comedor\MealType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SyncZkTecoCommand extends Command
{
    // Nombre del comando para el CRON
    protected $signature = 'zkteco:sync-comedor';
    protected $description = 'Sincroniza las marcaciones del ZKTeco iClock 360 con el sistema de comedor';

    public function handle()
    {

       $zk = new ZKTeco(env('DEVICE_IP'), env('DEVICE_PORT') );

        if (!$zk->connect()) {
            Log::error("Comedor: No se pudo conectar al biométrico en $ip");
            $this->error("Error de conexión con el dispositivo.");
            return;
        }

        $zk->disableDevice(); 

        try {
            $this->info("Dispositivo bloqueado temporalmente para descarga segura...");
            
            // 2. Realizamos todas las operaciones de lectura
            $attendance = $zk->getAttendance(5);
            $mealTypes = MealType::where('is_active', true)->get();

            $count = 0;

            foreach ($attendance as $log) {
                $employeeId = $log['id'];
                $punchTime = Carbon::parse($log['timestamp']);
                $statusCode = $log['type']; // El F1, F2 o F3 configurado

                // 1. Identificar el tipo de comida por el status_code (0, 1, 2)
                $mealType = $mealTypes->where('status_code', $statusCode)->first();

                if (!$mealType) {
                    continue; // Si el código no está mapeado, ignoramos
                }

                // 2. Lógica Anti-Passback (Evitar duplicados)
                // Regla: No se puede repetir el mismo TIPO de comida el mismo DÍA.
                $alreadyExists = DiningRecord::where('employee_id', $employeeId)
                    ->where('meal_type_id', $mealType->id)
                    ->whereDate('punch_time', $punchTime->format('Y-m-d'))
                    ->exists();

                if ($alreadyExists) {
                    continue; // El usuario ya consumió este servicio hoy
                }

                // 3. Filtro Temporal de Seguridad (10 minutos entre marcajes para empleados reales)
                // Los invitados (IDs 100-999 según lo conversado) están exentos de este tiempo 
                // para permitir marcaciones rápidas de grupos.
                if ($employeeId > 1000) { // Rango de invitados
                    // Pasa directo sin filtro de tiempo
                } else {
                    // Empleados reales: validar últimos 10 minutos
                    $recentPunch = DiningRecord::where('employee_id', $employeeId)
                        ->where('punch_time', '>=', $punchTime->copy()->subMinutes(10))
                        ->exists();
                    
                    if ($recentPunch) continue;
                }

                // 4. Registro en Base de Datos
                DiningRecord::create([
                    'employee_id'  => $employeeId,
                    'meal_type_id' => $mealType->id,
                    'punch_time'   => $punchTime,
                    'status_code'  => $statusCode,
                    'cost'         => $mealType->price,
                    'source'       => 'biometric',
                ]);

                $count++;
                $this->info("Sincronizando. $count nuevos registros procesadados.");
                Log::info("Comedor: Sincronización exitosa. $count registros nuevos.");
        }

        // 5. Opcional: Limpiar marcaciones del equipo para que no se sature
        // $zk->clearAttendance(); 

        } finally {
            // 3. IMPORTANTÍSIMO: Liberar el equipo pase lo que pase.
            // Usamos 'finally' para que incluso si el código falla, el equipo se desbloquee.
            $zk->enableDevice();
            $this->info("Dispositivo liberado.");
            $zk->disconnect();
            
        }
        
        
    }
}