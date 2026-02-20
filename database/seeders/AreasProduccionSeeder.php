<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produccion\Areas\Sector; // Usando el nuevo namespace
use App\Models\Produccion\Areas\Lote;   // Usando el nuevo namespace
use App\Models\Produccion\Areas\Tablon; // Usando el nuevo namespace

class AreasProduccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Sectores
        $sectorCharco = Sector::create([
            'codigo_sector' => '01',
            'nombre' => 'Sector Charco',
            'descripcion' => 'Área cercana al río, propensa a humedad.',
        ]);

        $sectorAlto = Sector::create([
            'codigo_sector' => '08',
            'nombre' => 'Sector Tamarindo',
            'descripcion' => 'Área más elevada, ideal para cultivos que requieren buen drenaje.',
        ]);

        $this->command->info('✅ Sectores creados (01, 08).');
        
        // 2. Crear Lotes (Dependen de Sector)
        
        // Lotes para Sector 01 (Sector Charco)
        $lote0101 = Lote::create([
            'sector_id' => $sectorCharco->id,
            'codigo_lote_interno' => '01',
            'nombre' => 'Lote Húmedo 01',
        ]);
        
        $lote0102 = Lote::create([
            'sector_id' => $sectorCharco->id,
            'codigo_lote_interno' => '02',
            'nombre' => 'Lote Central 02',
        ]);
        
        // Lotes para Sector 08 (Sector Alto)
        $lote0802 = Lote::create([
            'sector_id' => $sectorAlto->id,
            'codigo_lote_interno' => '02',
            'nombre' => 'Lote de Montaña 02',
        ]);

        $this->command->info('✅ Lotes creados. Los códigos autogenerados son: ' . $lote0101->codigo_completo . ', ' . $lote0102->codigo_completo . ', ' . $lote0802->codigo_completo . '.');
        
        // 3. Crear Tablones (Dependen de Lote)
        
        // Tablones para Lote 0102 (Lote Central)
        Tablon::create([
            'lote_id' => $lote0102->id,
            'codigo_tablon_interno' => '01',
            'codigo_completo' => $lote0102->id,
            'nombre' => 'Tablón 01',
            'hectareas_documento' => 2.75,
            'variedad_id' => '1',
            'tipo_suelo' => 'Arcilloso',
        ]);
        
        // Tablones con código interno de letras para Lote 0802 (Lote de Montaña)
        $tablon0802AB = Tablon::create([
            'lote_id' => $lote0802->id,
            'codigo_tablon_interno' => 'AB',
            'codigo_completo' => $lote0102->id,
            'nombre' => 'Tablón Alto AB',
            'hectareas_documento' => 1.50,
            'variedad_id' => '2',
            'tipo_suelo' => 'Franco-Arenoso',
        ]);

        $tablon080203 = Tablon::create([
            'lote_id' => $lote0802->id,
            'codigo_tablon_interno' => '03',
            'codigo_completo' => $lote0102->id,
            'nombre' => 'Tablón de Prueba 03',
            'hectareas_documento' => 0.50,
            'variedad_id' => '1',
            'tipo_suelo' => 'Pedregoso',
            'estado' => 'Preparacion',
        ]);
        
        $this->command->info('✅ Tablones creados. Los códigos autogenerados son: ' . $tablon0802AB->codigo_completo . ', ' . $tablon080203->codigo_completo . '.');
    }
}