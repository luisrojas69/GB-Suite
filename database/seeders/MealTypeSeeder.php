<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RRHH\Comedor\MealType;

class MealTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Desayuno', 
                'status_code' => 0, 
                'start_time' => '06:00:00', 
                'end_time' => '10:00:00', 
                'price' => 2.4
            ],
            [
                'name' => 'Almuerzo',  
                'status_code' => 1, 
                'start_time' => '12:00:00', 
                'end_time' => '14:00:00', 
                'price' => 3.0
            ],
            [
                'name' => 'Cena',      
                'status_code' => 2, 
                'start_time' => '14:01:00', 
                'end_time' => '23:59:59', 
                'price' => 2.86
            ],
        ];

        foreach ($types as $type) {
            MealType::updateOrCreate(['status_code' => $type['status_code']], $type);
        }
    }
}