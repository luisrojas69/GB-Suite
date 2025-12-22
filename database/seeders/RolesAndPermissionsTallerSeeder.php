<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB; 

class RolesAndPermissionsTallerSeeder extends Seeder
{
  public function run(): void
  {
   
    $permisos_base = [
      'acceder_menu_logistica',
    ];


  // 🟢 PERMISOS DEL MÓDULO LOGÍSTICA (TALLER) 🟢
    $permisos_total_logistica= [
      'gestionar_activos',
      'gestionar_ordenes',
      'gestionar_checklists',
      'eliminar_orden', 
      'programar_mp',  
      'gestionar_repuestos',
      'ver_reportes_taller',
      'registrar_lecturas',
    ];

    $permisos_crud_logistica= [
      // ACTIVOS
      'ver_activos', 'crear_activos', 'editar_activos', 'eliminar_activos',
    ];
          

    $all_permissions = array_merge(
      $permisos_base,
      $permisos_total_logistica,
      $permisos_crud_logistica,
    );

    // Crear todos los permisos en la base de datos
    foreach ($all_permissions as $permiso) {
      Permission::create(['name' => $permiso]);
    }

    // --- 3. Definición de Roles ---
    $gerenteLogistica = Role::create(['name' => 'gerente_logistica']);
    $usuarioLogistica = Role::create(['name' => 'usuario_logistica']);
   
    // --- 4. Asignación de Permisos a Roles ---
   
    // El Gerente de Logística
    $gerenteLogistica->givePermissionTo([
      $permisos_total_logistica,
      $permisos_crud_logistica,
    ]);

    //Usuario de Logistica
    $usuarioLogistica->givePermissionTo([
      $permisos_total_logistica,
    ]);

  }
}