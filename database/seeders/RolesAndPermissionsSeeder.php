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
      // ACCESOS A MENÚS PRINCIPALES
      'acceder_menu_produccion',
      'acceder_menu_pozos',
      'acceder_menu_logistica',
      'acceder_menu_rrhh',
      'gestionar_seguridad',
      'gestionar_areas',
      'gestionar_agro',
      'acceder_menu_liquidacion',
      'acceder_modulo_medicina',
    ];


  // 🟢 PERMISOS DEL MÓDULO LOGÍSTICA (TALLER) 🟢
    $permisos_total_logistica= [
      'gestionar_activos',
      'gestionar_ordenes',
      'gestionar_checklists',
      'gestionar_meal_types',
      'gestionar_dining_records',
      'eliminar_orden', 
      'programar_mp',  
      'gestionar_repuestos',
      'ver_reportes_taller',
      'gestionar_lecturas',
    ];


     $permisos_base_produccion= [
      'gestionar_pozos',
      'gestionar_mtto_pozos',
      'gestionar_aforos',
      'cambiar_status_pozos',
      'ver_dashboard_pozos',
    ];


    $permisos_base_medicina= [
      'gestionar_pacientes',
      'gestionar_consultas',
      'gestionar_accidentes',
      'gestionar_dotaciones',
      'descargar_reporte_profit'
    ];


    $permisos_crud_logistica= [
      // ACTIVOS
      'ver_activos', 'crear_activos', 'editar_activos', 'eliminar_activos',
    ];

    $permisos_lecturas = [
      // GESTIÓN DE ÁREAS (SECTORES, LOTES, TABLONES)
      'ver_lecturas', 'crear_lecturas', 'editar_lecturas', 'eliminar_lecturas',
    ];


    $permisos_produccion_areas = [
      // GESTIÓN DE ÁREAS (SECTORES, LOTES, TABLONES)
      'ver_sectores', 'crear_sectores', 'editar_sectores', 'eliminar_sectores',
    ];

    $permisos_produccion_variedades = [
      // GESTIÓN DE ÁREAS (SECTORES, LOTES, TABLONES)
      'ver_variedades', 'crear_variedades', 'editar_variedades', 'eliminar_variedades',
    ];

    $permisos_produccion_contratistas = [
      // GESTIÓN DE ÁREAS (SECTORES, LOTES, TABLONES)
      'ver_contratistas', 'crear_contratistas', 'editar_contratistas', 'eliminar_contratistas',
    ];

    $permisos_produccion_zafras = [
      // GESTIÓN DE ÁREAS (SECTORES, LOTES, TABLONES)
      'ver_zafras', 'crear_zafras', 'editar_zafras', 'eliminar_zafras',
    ];

    $permisos_produccion_moliendas = [
      // GESTIÓN DE ÁREAS (SECTORES, LOTES, TABLONES)
      'ver_moliendas', 'crear_moliendas', 'editar_moliendas', 'eliminar_moliendas',
    ];

    $permisos_produccion_liquidaciones = [
      // GESTIÓN DE ÁREAS (SECTORES, LOTES, TABLONES)
      'ver_liquidaciones', 'generar_liquidaciones', 'editar_liquidaciones', 'eliminar_liquidaciones', 'gestionar_tarifas',
    ];

    $permisos_produccion_pozos = [
      // GESTIÓN DE ÁREAS (SECTORES, LOTES, TABLONES)
      'ver_pozos', 'crear_pozos', 'editar_pozos', 'eliminar_pozos',
    ];

    $permisos_produccion_aforos = [
      // GESTIÓN DE ÁREAS (SECTORES, LOTES, TABLONES)
      'ver_aforos', 'crear_aforos', 'editar_aforos', 'eliminar_aforos',
    ];

    $permisos_produccion_mtto_pozos = [
      // GESTIÓN DE ÁREAS (SECTORES, LOTES, TABLONES)
      'ver_mtto_pozos', 'crear_mtto_pozos', 'editar_mtto_pozos', 'eliminar_mtto_pozos',
    ];

    $permisos_produccion_destinos = [
      // GESTIÓN DE ÁREAS (SECTORES, LOTES, TABLONES)
      'ver_destinos', 'crear_destinos', 'editar_destinos', 'eliminar_destinos',
    ];
        
        
        
    $permisos_produccion_animal = [
      // ANIMALES
      'ver_animales', 'crear_animal', 'editar_animal', 'eliminar_animal',
     
      // PESAJES
      'ver_pesajes', 'crear_pesaje', 'eliminar_pesaje',
     
      // BAJAS
      'ver_bajas', 'crear_baja', 'eliminar_baja',
     
      // CONFIGURACIÓN
      'gestionar_especies',
      'gestionar_categorias',
      'gestionar_ubicaciones',
      'gestionar_dueños',
    ];


    // 🟢 PERMISOS DEL MÓDULO RECURSOS HUMANOS (RRHH) 🟢
    $permisos_rrhh = [
      'ver_empleados', 
      'crear_empleado', 
      'editar_empleado',
      'eliminar_empleado',
      'ver_reportes_rrhh',
      'ver_meal_types',
      'crear_meal_types',
      'editar_meal_types',
      'eliminar_meal_types',
      'ver_registros_comedor',
      'crear_registros_manuales',
      'ver_dashboard_comedor',
      'eliminar_registros_comedor',
      'controlar_dispositivo_comedor',
      'ver_empleados_comedor',
      'editar_empleados_comedor',
      'ver_reportes_comedor',
    ];


    $all_permissions = array_merge(
      $permisos_base,
      $permisos_total_logistica,
      $permisos_lecturas,
      $permisos_base_produccion,
      $permisos_base_medicina,
      $permisos_produccion_pozos,
      $permisos_produccion_mtto_pozos,
      $permisos_produccion_aforos,
      $permisos_crud_logistica,
      $permisos_produccion_areas,
      $permisos_produccion_animal,
      $permisos_produccion_zafras,
      $permisos_produccion_variedades,
      $permisos_produccion_contratistas,
      $permisos_produccion_liquidaciones,
      $permisos_produccion_moliendas,
      $permisos_produccion_destinos,
      $permisos_rrhh
    );

    // Crear todos los permisos en la base de datos
    foreach ($all_permissions as $permiso) {
      Permission::create(['name' => $permiso]);
    }

    // --- 3. Definición de Roles ---
   
    $adminRole = Role::create(['name' => 'super_administrador']);
    $gerenteLogistica = Role::create(['name' => 'gerente_logistica']);
    $usuarioLogistica = Role::create(['name' => 'usuario_logistica']);
    $gerenteProduccion = Role::create(['name' => 'gerente_produccion']);
    $usuarioProduccion = Role::create(['name' => 'usuario_produccion']);
    $gerenteRRHH = Role::create(['name' => 'gerente_rrhh']); 
    $usuarioRRHH = Role::create(['name' => 'usuario_rrhh']); 

    // --- 4. Asignación de Permisos a Roles ---
   
    // El Super Administrador obtiene TODOS los permisos
    $adminRole->givePermissionTo(Permission::all());

    // El Gerente de Logística
    $gerenteLogistica->givePermissionTo([
      $permisos_total_logistica,
      $permisos_crud_logistica,
      'ver_lecturas',
      'crear_lecturas',
      'editar_lecturas',
      'eliminar_lecturas',
    ]);

    //Usuario de Logistica
    $usuarioLogistica->givePermissionTo([
      'acceder_menu_logistica',
      'gestionar_lecturas',
      'ver_lecturas',
      'crear_lecturas',
      'editar_lecturas',
    ]);


    // El Gerente de RRHH
    $gerenteRRHH->givePermissionTo([
      'acceder_menu_rrhh',
      'ver_empleados',
      'crear_empleado',
      'editar_empleado',
      'eliminar_empleado',
      'ver_reportes_rrhh',
    ]);
   


    // El Gerente de Producción
    $gerenteProduccion->givePermissionTo(array_merge(
      $permisos_produccion_areas, // ASIGNACIÓN DE PERMISOS DE ÁREAS
      [
      'acceder_menu_produccion',
      'gestionar_especies',
      'gestionar_categorias',
      'gestionar_ubicaciones',
      'gestionar_dueños',
      'ver_animales', 'crear_animal', 'editar_animal',
      'ver_pesajes', 'crear_pesaje',
      'ver_bajas', 'crear_baja',
    ]
        ));

    // El Operador de Campo
    $usuarioProduccion->givePermissionTo([
      'acceder_menu_produccion',
      'ver_animales',
      'crear_animal',
      'crear_pesaje',
    ]);


    // 5. Asignar un Rol a un Usuario (Asumiendo que el User ID 1 es su usuario)
    foreach (User::all() as $user) {
      $user = User::find($user->id);
      $user->assignRole($user->id);
    }


  }
}