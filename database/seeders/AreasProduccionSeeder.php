<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produccion\Areas\Sector;
use App\Models\Produccion\Areas\Lote;
use App\Models\Produccion\Areas\Tablon;
use Illuminate\Support\Str;

class AreasProduccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definición de Sectores
        $sectoresData = [
            ['01', 'Charco'],
            ['02', 'Piedras Negras'],
            ['03', 'Becerrera'],
            ['04', 'Paraíso'],
            ['05', 'Caimana'],
            ['06', 'Palo a Pique'],
            ['07', 'Purgatorio'],
            ['08', 'La Bandera'],
        ];

        $suelos = ['Arcilloso', 'Franco-Arenoso', 'Franco-Arcilloso', 'Limoso'];
        $variedades = [1, 2, 3]; // IDs de variedades de caña

        foreach ($sectoresData as $data) {
            // 1. Crear Sector
            $sector = Sector::create([
                'codigo_sector' => $data[0],
                'nombre'        => $data[1],
                'descripcion'   => "Sector ubicado en la zona " . ($data[0] < 5 ? 'Norte' : 'Sur'),
            ]);

            $this->command->info("🏗️ Creando Sector: {$sector->nombre}");

            // 2. Crear 2 Lotes por cada Sector
            for ($l = 1; $l <= 2; $l++) {
                $loteCodigo = str_pad($l, 2, '0', STR_PAD_LEFT);
                $lote = Lote::create([
                    'sector_id'           => $sector->id,
                    'codigo_lote_interno' => $loteCodigo,
                    'nombre'              => "Lote {$sector->nombre} {$loteCodigo}",
                ]);

                // 3. Crear 15 Tablones por Lote (Total 30 por Sector)
                for ($t = 1; $t <= 15; $t++) {
                    // Formato de código de 3 dígitos: 001, 002...
                    $tablonCodigo = str_pad($t, 3, '0', STR_PAD_LEFT);
                    
                    Tablon::create([
                        'lote_id'               => $lote->id,
                        'codigo_tablon_interno' => $tablonCodigo,
                        'nombre'                => "Tablón {$tablonCodigo}",
                        'hectareas_documento'   => fake()->randomFloat(2, 5, 25), // Entre 5 y 25 Ha
                        'variedad_id'           => fake()->randomElement($variedades),
                        'tipo_suelo'            => fake()->randomElement($suelos),
                        'estado'                => fake()->randomElement(['Crecimiento', 'Maduro', 'Preparacion']),
                        'descripcion'           => "Tablón de control productivo en {$lote->nombre}",
                        'meta_ton_ha'           => fake()->numberBetween(80, 120),
                        'fecha_inicio_ciclo'    => now()->subMonths(fake()->numberBetween(1, 10)),
                    ]);
                }
            }
        }

        $this->command->info('✅ Estructura de producción creada con éxito.');
    }
}