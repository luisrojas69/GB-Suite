<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ubicaciones = [
            [
                'nombre' => 'Oficina Administrativa Central',
                'codigo_sucursal' => 'ADM-01',
                'descripcion' => 'Sede principal de administración y gerencia.'
            ],
            [
                'nombre' => 'Taller de Maquinaria Pesada',
                'codigo_sucursal' => 'TALL-01',
                'descripcion' => 'Área de mantenimiento de tractores y cosechadoras.'
            ],
            [
                'nombre' => 'Garita de Entrada Principal',
                'codigo_sucursal' => 'SEC-01',
                'descripcion' => 'Puesto de control de acceso y seguridad.'
            ],
            [
                'nombre' => 'Almacén de Repuestos y Consumibles',
                'codigo_sucursal' => 'ALM-01',
                'descripcion' => 'Depósito central de materiales.'
            ],
            [
                'nombre' => 'Laboratorio de Calidad',
                'codigo_sucursal' => 'LAB-01',
                'descripcion' => 'Área de análisis de suelo y muestras de caña.'
            ],
            [
                'nombre' => 'Comedor de Empleados',
                'codigo_sucursal' => 'COM-01',
                'descripcion' => 'Área de servicios de alimentación.'
            ],
            [
                'nombre' => 'Hacienda El Oasis - Casa Principal',
                'codigo_sucursal' => 'HAC-01',
                'descripcion' => 'Oficina remota en zona de cultivo.'
            ],
            [
                'nombre' => 'Departamento de Recursos Humanos',
                'codigo_sucursal' => 'RRHH-01',
                'descripcion' => 'Oficina de gestión de personal y nómina.'
            ],
            [
                'nombre' => 'Sistemas e Infraestructura IT',
                'codigo_sucursal' => 'IT-01',
                'descripcion' => 'Data Center y oficina de soporte técnico.'
            ],
            [
                'nombre' => 'Unidad Móvil de Diagnóstico',
                'codigo_sucursal' => 'MOV-01',
                'descripcion' => 'Camión de servicio equipado para reparaciones en campo.'
            ],
        ];

        foreach ($ubicaciones as $ubicacion) {
            DB::table('ubicaciones')->updateOrInsert(
                ['codigo_sucursal' => $ubicacion['codigo_sucursal']], // Evita duplicados si corres el seeder varias veces
                [
                    'nombre' => $ubicacion['nombre'],
                    'descripcion' => $ubicacion['descripcion'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}