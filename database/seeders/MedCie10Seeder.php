<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedCie10Seeder extends Seeder {
    public function run() {
        $diagnosticos = [
            // Preventivos (Tus dudas)
            ['codigo' => 'Z00.0', 'descripcion' => 'Examen médico general (Chequeo de rutina)'],
            ['codigo' => 'Z02.7', 'descripcion' => 'Expedición de certificado médico (Vacaciones/Aptitud)'],
            ['codigo' => 'Z10.0', 'descripcion' => 'Examen de salud ocupacional de rutina'],
            // Comunes en campo
            ['codigo' => 'M54.5', 'descripcion' => 'Lumbago no especificado (Dolor de espalda)'],
            ['codigo' => 'J00',   'descripcion' => 'Rinofaringitis aguda (Resfriado común)'],
            ['codigo' => 'S61.0', 'descripcion' => 'Herida de dedo(s) de la mano sin daño de la uña'],
            ['codigo' => 'T14.0', 'descripcion' => 'Traumatismo superficial no especificado'],
            ['codigo' => 'I10',   'descripcion' => 'Hipertensión esencial (primaria)'],
            ['codigo' => 'E11',   'descripcion' => 'Diabetes mellitus no insulinodependiente'],
            ['codigo' => 'A09',   'descripcion' => 'Diarrea y gastroenteritis de presunto origen infeccioso'],
        ];

        foreach ($diagnosticos as $diag) {
            DB::table('med_cie10')->updateOrInsert(['codigo' => $diag['codigo']], $diag);
        }
    }
}