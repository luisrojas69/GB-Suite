<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Definición del Schedule para el proyecto Pecuario

// 1. Tarea de Cálculo de Costos (Mensual - Primer día del mes a las 02:00 AM)
// Se usa el comando definido previamente: pecuario:calculate-costs
Schedule::command('pecuario:calculate-costs')
         ->monthly()
         ->at('02:00')
         ->emailOutputOnFailure('lrojas@granjaboraure.com')
         ->withoutOverlapping()
         ->runInBackground();


// 2. Tarea de Reporte Productivo (Semanal - Lunes a las 07:00 AM)
// Crearemos este nuevo comando a continuación: pecuario:generate-report
Schedule::command('pecuario:generate-report') 
         ->weeklyOn(1, '07:00') // El 1 es Lunes
         ->emailOutputOnFailure('lrojas@granjaboraure.com')
         ->withoutOverlapping();

// Ejemplo de otra tarea
// Schedule::command('inspire')->hourly();