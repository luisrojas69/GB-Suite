<?php

namespace App\Console\Commands\RRHH\Comedor;

use Illuminate\Console\Command;
use Jmrashed\Zkteco\Lib\ZKTeco;
use App\Models\RRHH\Comedor\DiningEmployee;
use Illuminate\Support\Facades\Log;

class SyncZkUsersCommand extends Command
{
    protected $signature = 'zkteco:sync-users';
    protected $description = 'Sincroniza la lista de empleados y visitantes desde el equipo';

    public function handle()
    {

        $zk = new ZKTeco(env('DEVICE_IP'), env('DEVICE_PORT') );

        if (!$zk->connect()) {
            Log::warning("COMEDOR-USERS: No se pudo conectar al equipo. Es posible que la VPN esté caída.");
            return;
        }

        try {
            $this->info("Bloqueando equipo para lectura de usuarios...");
            $zk->disableDevice();
            
            $users = $zk->getUser();
            
            $zk->enableDevice(); // Liberar después de obtener la lista

            $updatedCount = 0;

            foreach ($users as $user) {
                // Solo registramos/actualizamos si el ID es válido
                $employee = DiningEmployee::updateOrCreate(
                    ['biometric_id' => $user['userid']],
                    [
                        'name' => $user['name'],
                        'card_number' => $user['cardno'] ?? null,
                        'department' => ($user['userid'] < 1000) ? 'GRANJA BORAURE' : 'INVITADOS',
                        'is_active' => true
                    ]
                );

                if ($employee->wasRecentlyCreated) {
                    $updatedCount++;
                }
            }

            Log::info("COMEDOR-USERS: Sincronización de usuarios completa. $updatedCount nuevos encontrados.");
            $this->info("Proceso terminado. Usuarios nuevos: $updatedCount");

        } catch (\Exception $e) {
            Log::error("COMEDOR-USERS: Error durante la sincronización: " . $e->getMessage());
        } finally {
            $zk->enableDevice();
            $zk->disconnect();
        }
    }
}