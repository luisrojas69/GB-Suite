<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            ['nombre_completo' => 'Administración y Finanzas', 'descripcion' => 'Gerencia, contabilidad y tesorería.'],
            ['nombre_completo' => 'Recursos Humanos', 'descripcion' => 'Nómina, captación y bienestar social.'],
            ['nombre_completo' => 'Tecnología e Información', 'descripcion' => 'Soporte técnico, redes y sistemas.'],
            ['nombre_completo' => 'Taller y Mantenimiento', 'descripcion' => 'Reparación de maquinaria agrícola y flota pesada.'],
            ['nombre_completo' => 'Cosecha y Campo', 'descripcion' => 'Operaciones directas en los lotes de caña.'],
            ['nombre_completo' => 'Seguridad y Salud Laboral', 'descripcion' => 'Vigilancia y prevención de riesgos.'],
            ['nombre_completo' => 'Medicina Ocupacional', 'descripcion' => 'Servicio médico y atención a pacientes.'],
            ['nombre_completo' => 'Servicios Generales / Comedor', 'descripcion' => 'Mantenimiento de infraestructura y alimentación.'],
            ['nombre_completo' => 'Logística y Almacén', 'descripcion' => 'Control de inventarios de repuestos y suministros.'],
            ['nombre_completo' => 'Control de Calidad', 'descripcion' => 'Laboratorio y análisis de muestras.'],
        ];

        foreach ($departamentos as $depto) {
            DB::table('departamentos')->updateOrInsert(
                ['nombre_completo' => $depto['nombre_completo']],
                [
                    'descripcion' => $depto['descripcion'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}