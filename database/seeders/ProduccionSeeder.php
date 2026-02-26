<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Produccion\Agro\Zafra;
use App\Models\Produccion\Agro\Central;
use App\Models\Produccion\Agro\Variedad;
use App\Models\Produccion\Agro\Contratista; // Ajusta el namespace si es diferente
use App\Models\Produccion\Agro\Destino;     // Ajusta el namespace si es diferente

class ProduccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // --- 1. Contratistas (Ejemplo: Maestros)
       // Contratista::truncate(); // Limpiar la tabla antes de sembrar (solo para desarrollo/testing)
        Contratista::create(['nombre' => 'Cosecha La Palma C.A.', 'rif' => 'J-12345678-0']);
        Contratista::create(['nombre' => 'Transportes El Flete R.L.', 'rif' => 'J-87654321-0']);
        
        $this->command->info('Contratistas creados.');

        // --- 2. Destinos (Ejemplo: Maestros)
        //Destino::truncate();
        Destino::create(['nombre' => 'Central Azucarero La Pastora', 'codigo' => 'CLP']);
        Destino::create(['nombre' => 'Astillero y Depósito Principal', 'codigo' => 'CEP']);
        
        $this->command->info('Destinos creados.');

        // --- 3. Variedades (Ejemplo: Produccion/Agro)
        //Variedad::truncate();
        Variedad::create(['nombre' => 'CP 72-2086', 'codigo' => '0103', 'descripcion' => 'Caña de alto rendimiento y resistencia.']);
        Variedad::create(['nombre' => 'PR 69-2503', 'codigo' => '0104', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'V 98120', 'codigo' => '0105', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'C 26670', 'codigo' => '0106', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'CC 8592', 'codigo' => '0107', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'FV 1050', 'codigo' => '0108', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'RB 74454', 'codigo' => '0109', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'RB 855453', 'codigo' => '0110', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'RSP 724928', 'codigo' => '0111', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'V 9862', 'codigo' => '0112', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'V 99236', 'codigo' => '0113', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'FV08 1050', 'codigo' => '0114', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'FV08 1379', 'codigo' => '0115', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        Variedad::create(['nombre' => 'DIVERSAS', 'codigo' => '0116', 'descripcion' => 'Adaptable a suelos pesados y buena soca.']);
        
        $this->command->info('Variedades creadas.');

        // --- 4. Zafras (Ejemplo: Produccion/Agro)
       // Zafra::truncate();
        Zafra::create([
            'nombre' => 'Zafra 2024-2025',
            'anio_inicio' => 2024,
            'anio_fin' => 2025,
            'fecha_inicio' => '2024-11-01',
            'fecha_fin' => '2025-05-31',
            'estado' => 'Cerrada',
        ]);
        Zafra::create([
            'nombre' => 'Zafra 2025-2026',
            'anio_inicio' => 2025,
            'anio_fin' => 2026,
            'fecha_inicio' => '2025-11-01',
            'fecha_fin' => '2026-05-31',
            'estado' => 'Activa',
        ]);
        
        $this->command->info('Zafras creadas.');

        // --- 5. Centrales (Ejemplo: La Pastora)
        Central::create([
            'nombre' => 'Central La Pastora',
            'rif' => 'J-12345679-0',
            'ubicacion' => 'Sector La Pastora',
            'activo' => true,
        ]);
        Central::create([
            'nombre' => 'Central Carora',
            'rif' => 'J-12345676-0',
            'ubicacion' => 'Sector Carora Panamericana',
            'activo' => false,
        ]);
        $this->command->info('Centrales Creados.');

        // Reactivar la verificación de claves foráneas
       // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}