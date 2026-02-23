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

        // 2. Definición del Diccionario de Permisos por Módulo
        $permissionsByModule = [
            'MEDICINA' => [
                'medicina.dashboard',
                'medicina.menu',
                'medicina.reportes',
                //PACIENTES
                'medicina.pacientes.gestionar', 
                'medicina.pacientes.sincronizar',
                'medicina.pacientes.crear', 'medicina.pacientes.eliminar', 'medicina.pacientes.ver', 'medicina.pacientes.editar', 'medicina.pacientes.reportes',
                //CONSULTAS
                'medicina.consultas.gestionar', 
                'medicina.consultas.ver', 'medicina.consultas.editar', 'medicina.consultas.reportes',
                //ORDENES
                'medicina.ordenes.gestionar', 
                'medicina.ordenes.ver', 'medicina.ordenes.editar', 'medicina.ordenes.reportes',
            ],
            'SSL' => [
                'ssl.dashboard',
                'ssl.menu',
                'ssl.reportes',
                //DOTACIONES                
                'ssl.dotaciones.gestionar',
                'ssl.dotaciones.crear', 'ssl.dotaciones.eliminar', 'ssl.dotaciones.ver', 'ssl.dotaciones.editar', 'ssl.dotaciones.reportes', 'ssl.dotaciones.aprobar',
                //ACCIDENTES
                'ssl.accidentes.gestionar', 
                'ssl.accidentes.crear', 'ssl.accidentes.eliminar', 'ssl.accidentes.ver', 'ssl.accidentes.editar', 'ssl.accidentes.reportes',
            ],
            'POZOS' => [
                'pozos.dashboard',
                'pozos.menu',
                'pozos.reportes',
                //POZOS
                'produccion.pozos.gestionar', 
                'produccion.pozos.crear', 'produccion.pozos.eliminar', 'produccion.pozos.ver', 'produccion.pozos.editar', 'produccion.pozos.reportes',
                //AFOROS
                'produccion.aforos.gestionar', 
                'produccion.aforos.crear', 'produccion.aforos.eliminar', 'produccion.aforos.ver', 'produccion.aforos.editar', 'produccion.aforos.reportes',
            ],
            'PLUVIOMETRIA' => [
                'pluviometria.dashboard',
                'pluviometria.menu',
                'pluviometria.reportes',
                //PLUVIOMETRIA
                'produccion.pluviometria.gestionar', 
                'produccion.pluviometria.crear', 'produccion.pluviometria.eliminar', 'produccion.pluviometria.ver', 'produccion.pluviometria.editar', 'produccion.pluviometria.reportes',
            ],
            'ANIMALES' => [
                'animales.dashboard',
                'animales.menu',
                'animales.reportes',
                //ANIMALES
                'produccion.animales.gestionar', 
                'produccion.animales.crear', 'produccion.animales.eliminar', 'produccion.animales.ver', 'produccion.animales.editar', 'produccion.animales.reportes',
                //EVENTOS
                'animales.eventos.gestionar', 
                'animales.eventos.crear', 'animales.eventos.eliminar', 'animales.eventos.ver', 'animales.eventos.editar', 'animales.eventos.reportes',
                //TABLAS MAESTRAS
                'animales.maestras.gestionar', 
                'animales.maestras.crear', 'animales.maestras.eliminar', 'animales.maestras.ver', 'animales.maestras.editar', 'animales.maestras.reportes',
                //COSTOS
                'animales.costos.gestionar', 
                'animales.costos.crear', 'animales.costos.eliminar', 'animales.costos.ver', 'animales.costos.editar', 'animales.costos.reportes',
            ],
            'AREAS' => [
                'areas.dashboard',
                'areas.menu',
                'areas.reportes',
                //AREAS
                'produccion.areas.gestionar', 
                'produccion.areas.crear', 'produccion.areas.eliminar', 'produccion.areas.ver', 'produccion.areas.editar', 'produccion.areas.reportes',
            ],
            'LABORES' => [
                'labores.dashboard',
                'labores.menu',
                'labores.reportes',
                //LABORES
                'produccion.labores.gestionar', 
                'produccion.labores.crear', 'produccion.labores.eliminar', 'produccion.labores.ver', 'produccion.labores.editar', 'produccion.labores.reportes',
            ],
            'AGRO' => [
                'arrimes.dashboard',
                'arrimes.menu',
                'arrimes.reportes',
                //AGRO
                'produccion.arrimes.gestionar', 
                'produccion.arrimes.crear', 'produccion.arrimes.eliminar', 'produccion.arrimes.ver', 'produccion.arrimes.editar', 'produccion.arrimes.reportes',
            ],                                                                                      
            'INVENTARIO' => [
                'inventario.dashboard',
                'inventario.menu',
                'inventario.reportes',
                //INVENTARIOS
                'inventario.asignaciones.gestionar', 
                'inventario.asignaciones.crear', 'inventario.asignaciones.eliminar', 'produccion.asignaciones.ver', 'inventario.asignaciones.editar', 'inventario.asignaciones.reportes',
            ],
            'COMEDOR' => [
                'comedor.dashboard',
                'comedor.menu',
                'comedor.reportes',
                //EMPLEADOS
                'comedor.empleados.gestionar', 
                'comedor.empleados.crear', 'comedor.empleados.eliminar', 'produccion.empleados.ver', 'comedor.empleados.editar', 'comedor.empleados.reportes',
                //COMIDAS
                'comedor.comidas.gestionar', 
                'comedor.comidas.crear', 'comedor.comidas.eliminar', 'produccion.comidas.ver', 'comedor.comidas.editar', 'comedor.comidas.reportes',
            ],
            'TALLER' => [
                'taller.dashboard',
                'taller.menu',
                'taller.reportes',
                //MAQUINARIA
                'taller.activos.gestionar', 
                'taller.activos.crear', 'taller.activos.eliminar', 'produccion.activos.ver', 'taller.activos.editar', 'taller.activos.reportes',
                //SERVICIOS
                'taller.servicios.gestionar', 
                'taller.servicios.crear', 'taller.servicios.eliminar', 'produccion.servicios.ver', 'taller.servicios.editar', 'taller.servicios.reportes',
            ],                                        
            'SEGURIDAD' => [
                'seguridad.dashboard',
                'seguridad.menu',
                'seguridad.reportes',
                //USUARIOS
                'seguridad.usuarios.gestionar', 
                'seguridad.usuarios.crear', 'seguridad.usuarios.eliminar', 'seguridad.usuarios.ver', 'seguridad.usuarios.editar', 'seguridad.usuarios.reportes',    //ROLES
                'seguridad.roles.gestionar', 
                'seguridad.roles.crear', 'seguridad.roles.eliminar', 'seguridad.roles.ver', 'seguridad.roles.editar', 'seguridad.roles.reportes',   
                //PERMISOS
                'seguridad.permisos.gestionar', 
                'seguridad.permisos.crear', 'seguridad.permisos.eliminar', 'seguridad.permisos.ver', 'seguridad.permisos.editar', 'seguridad.permisos.reportes',                
            ],

        ];

        // 3. Crear Permisos en la DB
        foreach ($permissionsByModule as $moduleName => $permissions) {
            foreach ($permissions as $permissionName) {
                Permission::create([
                    'name'   => $permissionName,
                    'module' => $moduleName, // Tu columna personalizada
                    'guard_name' => 'web'
                ]);
            }
        }

        // 4. Definición de Roles y Asignación de Permisos
        
        // --- ROLE: SUPER ADMINISTRADOR ---
        $superAdminRole = Role::create(['name' => 'super_admin']);
        // El Super Admin no necesita permisos específicos si usas Gate::before en AuthServiceProvider
        // pero por buena práctica se los asignamos todos.
        $superAdminRole->givePermissionTo(Permission::all());

        // --- ROLE: MÉDICO OCUPACIONAL ---
        $medicoRole = Role::create(['name' => 'medico_ocupacional']);
        $medicoRole->givePermissionTo(Permission::where('name', 'like', 'medicina.%')->get());
        //$medicoRole->givePermissionTo(['ssl.accidentes.ver', 'ssl.dotaciones.ver']); // Permisos cruzados
        $medicoRole->givePermissionTo(Permission::where('name', 'like', 'ssl.%')->get()); // ESTO NO DEBERIA SER. PERO

        // --- ROLE: ANALISTA SSL ---
        $sslRole = Role::create(['name' => 'analista_ssl']);
        $sslRole->givePermissionTo(Permission::where('name', 'like', 'ssl.%')->get());
        $sslRole->givePermissionTo(['medicina.pacientes.ver']);

        // --- ROLE: JEFE DE TALLER ---
        $tallerRole = Role::create(['name' => 'jefe_taller']);
        $tallerRole->givePermissionTo(Permission::where('name', 'like', 'taller.%')->get());

        // --- ROLE: ANALISTA SSL ---
        $sslRole = Role::create(['name' => 'analista_pluvimetria']);
        $sslRole->givePermissionTo(Permission::where('name', 'like', 'pluviometria.%')->get());

        // --- ROLE: OPERADOR DE POZOS ---
        $pozosRole = Role::create(['name' => 'operador_pozos']);
        $pozosRole->givePermissionTo(Permission::where('name', 'like', 'pozos.%')->get());
        $pozosRole->givePermissionTo(Permission::where('name', 'like', 'aforos.%')->get());

        // --- ROLE: VISOR (Solo lectura de todo) ---
        $visorRole = Role::create(['name' => 'gerente_general']);
        $visorRole->givePermissionTo(Permission::where('name', 'like', '%.ver')->orWhere('name', 'like', '%.dashboard')->get());


        // 5. Asignación al Usuario ID 1
        $user = User::find(1);
        if ($user) {
            $user->assignRole($superAdminRole);
            $this->command->info('Usuario ID 1 ahora es Super Administrador.');
        } else {
            $this->command->error('No se encontró el usuario con ID 1.');
        }
    }
}