<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            ['nombre_completo' => 'Administración y Finanzas', 'descripcion' => 'Gerencia, contabilidad, Tesorería y Sistemas'],
            ['nombre_completo' => 'Recursos Humanos', 'descripcion' => 'Nómina, captación y bienestar social.'],
            ['nombre_completo' => 'Tecnología e Información', 'descripcion' => 'Soporte técnico, redes y sistemas.'],
            ['nombre_completo' => 'Taller Mecánico', 'descripcion' => 'Reparación de maquinaria agrícola y flota pesada.'],
            ['nombre_completo' => 'Taller Metalmecánico y Torneria', 'descripcion' => 'Reparación y COnstruccion de Partes metalicas de Maquinaria.'],
            ['nombre_completo' => 'Taller Electrico', 'descripcion' => 'Mantenimiento de Sistemas Electricos Internos.'],
            ['nombre_completo' => 'Haras / Caballerizas', 'descripcion' => 'Haras / Potreros /Caballerizas.'],
            ['nombre_completo' => 'Planta Electrica Boraure', 'descripcion' => 'Planta Electrica Boraure / Campo.'],
            ['nombre_completo' => 'Caimana', 'descripcion' => 'Oficinas de Operaciones directas de produccion.'],
            ['nombre_completo' => 'Seguridad y Salud Laboral', 'descripcion' => 'Vigilancia y prevención de riesgos.'],
            ['nombre_completo' => 'Medicina Ocupacional', 'descripcion' => 'Servicio médico y atención a pacientes.'],
            ['nombre_completo' => 'Servicios Generales / Comedor', 'descripcion' => 'Mantenimiento de infraestructura'],
            ['nombre_completo' => 'Comedor', 'descripcion' => 'Servicios de alimentación.'],
            ['nombre_completo' => 'Logística y Almacén', 'descripcion' => 'Control de inventarios de repuestos y suministros.'],
            ['nombre_completo' => 'Casa Grande', 'descripcion' => 'Residencia Interna de Directivos.'],
            ['nombre_completo' => 'Hotel Granja Boraure', 'descripcion' => 'Residencia Visitantes y Operadores.'],
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