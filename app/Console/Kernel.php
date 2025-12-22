<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Programación del comando de Mantenimiento Preventivo
        // ASUMIMOS que el comando se llama 'mantenimiento:check-mp'
        //$schedule->command('mantenimiento:check-mp')
          //  ->dailyAt('04:00') // Ejecución diaria a las 4:00 AM
            //->withoutOverlapping(); // Evita ejecuciones simultáneas

        //comando para sincronizar marcajes del biometrico
        $schedule->command('zkteco:sync-comedor')->everyFiveMinutes()->withoutOverlapping();

        //comando para sincronizar usuarios del biometrico
        $schedule->command('zkteco:sync-users')->dailyAt('01:00');
    }
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}