<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        //$this->call([
            // Agregue su nuevo Seeder aquí
      //      AreasProduccionSeeder::class,
     //       // Otros seeders...
      //  ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

       \App\Models\User::factory()->create([

             'name' => 'Luis',
             'last_name' => 'Rojas',
             'password' =>  '12345678',
             'email' => 'luisrojas19@gmail.com',

         ]);


        \App\Models\User::factory()->create([

             'name' => 'Gerente',
             'last_name' => 'Logistica',
             'password' =>  '12345678',
             'email' => 'logistica@mail.com',

         ]);


       \App\Models\User::factory()->create([

             'name' => 'Usuario',
             'last_name' => 'Logistica',
             'password' =>  '12345678',
             'email' => 'usuariologistica@mail.com',

         ]);

        \App\Models\User::factory()->create([

             'name' => 'Gerente',
             'last_name' => 'Produccion',
             'password' =>  '12345678',
             'email' => 'produccion@mail.com',

         ]);


       \App\Models\User::factory()->create([

             'name' => 'Usuario',
             'last_name' => 'Produccion',
             'password' =>  '12345678',
             'email' => 'usuarioproduccion@mail.com',

         ]);


       \App\Models\User::factory()->create([

             'name' => 'Gerente',
             'last_name' => 'RRHH',
             'password' =>  '12345678',
             'email' => 'rrhh@mail.com',

         ]);


       \App\Models\User::factory()->create([

             'name' => 'Usuario',
             'last_name' => 'RRHH',
             'password' =>  '12345678',
             'email' => 'usuariorrhh@mail.com',

         ]);


        \App\Models\Logistica\Taller\Activo::create([
             'codigo' => 'GBT01',
             'nombre' => 'Tractor de Prueba 01',
             'placa' => 'ABC123',
             'tipo' => 'Tractor',
             'marca' =>  'Landini',
             'modelo' => 'Landini Truck 3000',
             'departamento_asignado' => 'Cosecha',
             'lectura_actual' => '7480',
             'unidad_medida' => 'HRS',
             'fecha_adquisicion' => '2010-10-08 13:31:51.823',
         ]);

        \App\Models\Logistica\Taller\Activo::create([
             'codigo' => 'GBT02',
             'nombre' => 'Tractor de Prueba 01',
             'placa' => 'CDF666',
             'tipo' => 'Tractor',
             'marca' =>  'Case',
             'modelo' => 'Case Truck 780',
             'departamento_asignado' => 'Cosecha',
             'lectura_actual' => '4630',
             'unidad_medida' => 'HRS',
             'fecha_adquisicion' => '2008-10-08 13:31:51.823',
         ]);

        \App\Models\Logistica\Taller\Activo::create([
             'codigo' => 'GBC01',
             'nombre' => 'Camion de Prueba 01',
             'placa' => 'CDF666',
             'tipo' => 'Camión',
             'marca' =>  'Ford',
             'modelo' => 'Truck 780',
             'departamento_asignado' => 'Siembra',
             'lectura_actual' => '14630',
             'unidad_medida' => 'KM',
             'fecha_adquisicion' => '2008-10-08 13:31:51.823',
         ]);

        \App\Models\Logistica\Taller\Activo::create([
             'codigo' => 'GBC02',
             'nombre' => 'Camion de Prueba 02',
             'placa' => 'CDTGH6',
             'tipo' => 'Camión',
             'marca' =>  'Ford',
             'modelo' => '765 Cargo',
             'departamento_asignado' => 'Siembra',
             'lectura_actual' => '13530',
             'unidad_medida' => 'KM',
             'fecha_adquisicion' => '2008-10-08 13:31:51.823',
         ]);


        \App\Models\Produccion\Pozo\Activo::create([
             'nombre' => 'GBP01',
             'ubicacion' => 'Sector Charco',
             'tipo_activo' => 'POZO',
             'subtipo_pozo' =>  'TURBINA',
             'id_pozo_asociado' => NULL,
             'estatus_actual' => 'OPERATIVO',
             'fecha_ultimo_cambio' => '2008-10-08 13:31:51.823',
         ]);

        \App\Models\Produccion\Pozo\Activo::create([
             'nombre' => 'GBE01',
             'ubicacion' => 'Sector Charco',
             'tipo_activo' => 'ESTACION_REBOMBEO',
             'subtipo_pozo' =>  'TURBINA',
             'id_pozo_asociado' => '1',
             'estatus_actual' => 'OPERATIVO',
             'fecha_ultimo_cambio' => '2008-10-08 13:31:51.823',
         ]);

        \App\Models\Produccion\Pozo\Activo::create([
             'nombre' => 'GBP02',
             'ubicacion' => 'Sector Tamarindo',
             'tipo_activo' => 'POZO',
             'subtipo_pozo' =>  'TURBINA',
             'id_pozo_asociado' => NULL,
             'estatus_actual' => 'PARADO',
             'fecha_ultimo_cambio' => '2008-10-08 13:31:51.823',
         ]);

        \App\Models\Produccion\Pozo\Activo::create([
             'nombre' => 'GBP03',
             'ubicacion' => 'Sector Tamarindo',
             'tipo_activo' => 'POZO',
             'subtipo_pozo' =>  'TURBINA',
             'id_pozo_asociado' => NULL,
             'estatus_actual' => 'EN_MANTENIMIENTO',
             'fecha_ultimo_cambio' => '2008-10-08 13:31:51.823',
         ]);





       $this->call(RolesAndPermissionsSeeder::class);
        //$this->call(RolesAndPermissionsTallerSeeder::class);
        $this->call(ProduccionSeeder::class);
        $this->call(AreasProduccionSeeder::class);
        $this->call(MealTypeSeeder::class);
       
    }
}
