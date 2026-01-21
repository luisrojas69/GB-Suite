<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaboresCriticasSeeder extends Seeder
{
    public function run()
    {
        $labores = [
            [
                'nombre' => 'Reparación de Cinta',
                'dias_meta_pos_cosecha' => 15,
                'reinicia_ciclo' => false,
                'requiere_maquinaria' => false, // Es labor manual mayormente
            ],
            [
                'nombre' => 'Rajado de Soca',
                'dias_meta_pos_cosecha' => 15,
                'reinicia_ciclo' => false,
                'requiere_maquinaria' => true,
            ],
            [
                'nombre' => 'Cultivado',
                'dias_meta_pos_cosecha' => 15,
                'reinicia_ciclo' => false,
                'requiere_maquinaria' => true,
            ],
            [
                'nombre' => 'Aporque',
                'dias_meta_pos_cosecha' => 30,
                'reinicia_ciclo' => false,
                'requiere_maquinaria' => true,
            ],
            [
                'nombre' => 'Herbicida (1ra Aplicación)',
                'dias_meta_pos_cosecha' => 45,
                'reinicia_ciclo' => false,
                'requiere_maquinaria' => true,
            ],
            [
                'nombre' => 'Fertilización (Urea/Fórmulas)',
                'dias_meta_pos_cosecha' => 60,
                'reinicia_ciclo' => false,
                'requiere_maquinaria' => true,
            ],
            [
                'nombre' => 'Cosecha / Quema',
                'dias_meta_pos_cosecha' => 0,
                'reinicia_ciclo' => true, // Esta labor dispara el nuevo ciclo
                'requiere_maquinaria' => true,
            ],
        ];

        foreach ($labores as $labor) {
            DB::table('cat_labores_criticas')->updateOrInsert(
                ['nombre' => $labor['nombre']],
                $labor
            );
        }
    }
}