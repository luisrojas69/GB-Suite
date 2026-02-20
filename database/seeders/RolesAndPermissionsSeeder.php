<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB; 

class RolesAndPermissionsSeeder extends Seeder
{
  public function run(): void
  {
    // 1. Resetear el caché de permisos
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // -------------------------------------------------------------------
    // LIMPIEZA Y RESETEO DE TABLAS
    // -------------------------------------------------------------------

    // A. Limpiar la asignación de roles del usuario 1 (Super Administrador)
    $user = User::find(1);
    if ($user) {
      $user->syncRoles([]);
      $user->syncPermissions([]);
    }
   
    // B. Limpiar Tablas Pivot de Spatie (Usando DELETE)
    DB::table('model_has_roles')->delete();
    DB::table('role_has_permissions')->delete();
    DB::table('model_has_permissions')->delete();

    // C. Limpiar Tablas Principales de Spatie (Usando DELETE)
    Role::query()->delete();
    Permission::query()->delete();
   
    // --- 2. Definición de Permisos ---
   
    $permisos_base = [
      'acceder_modulo_medicina'
    ];

    $permisos_base_admin = [
      'gestionar_seguridad'
    ];

    $permisos_base_medicina= [
      'gestionar_pacientes',
      'gestionar_consultas',
      'gestionar_ordenes',
      'gestionar_accidentes',
      'gestionar_dotaciones',
      'descargar_reporte_medicos',
      'descargar_reporte_ssl',
    ];



    $all_permissions = array_merge(
      $permisos_base,
      $permisos_base_admin,
      $permisos_base_medicina,
    );

    // Crear todos los permisos en la base de datos
    foreach ($all_permissions as $permiso) {
      Permission::create(['name' => $permiso]);
    }

    // --- 3. Definición de Roles ---
   
    $adminRole = Role::create(['name' => 'super_administrador']);
    $medicoLaboral = Role::create(['name' => 'medico_laboral']);

    // --- 4. Asignación de Permisos a Roles ---
   
    // El Super Administrador obtiene TODOS los permisos
    $adminRole->givePermissionTo(Permission::all());

    // El Medico Laboral
    $medicoLaboral->givePermissionTo([
      $permisos_base_medicina
    ]);


    // 5. Asignar un Rol a un Usuario (Asumiendo que el User ID 1 es su usuario)
    foreach (User::all() as $user) {
      $user = User::find($user->id);
      $user->assignRole(1);
    }
  }
}