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
                'medicina.pacientes.ver', 'medicina.pacientes.editar',
                'medicina.pacientes.gestionar',
                'medicina.pacientes.reportes',
                'medicina.pacientes.sincronizar',
                'medicina.dotaciones.gestionar',
                'medicina.consultas.ver', 'medicina.consultas.gestionar',
                'medicina.ordenes.ver', 'medicina.ordenes.gestionar',
                'medicina.reposos.gestionar',
                'medicina.accidentes.ver', 'medicina.accidentes.gestionar',
                'medicina.reportes.ver', 'medicina.reportes.descargar',
            ],
            'SSL' => [
                'ssl.dashboard',
                'ssl.inspecciones.ver', 'ssl.inspecciones.gestionar',
                'ssl.dotaciones.ver', 'ssl.dotaciones.gestionar',
                'ssl.riesgos.ver', 'ssl.riesgos.gestionar',
                'ssl.accidentes.ver', 'ssl.accidentes.investigar',
                'ssl.reportes.ver', 'ssl.reportes.descargar',
            ],
            'TALLER' => [
                'taller.dashboard',
                'taller.maquinaria.ver', 'taller.maquinaria.gestionar',
                'taller.mantenimiento.ver', 'taller.mantenimiento.crear', 'taller.mantenimiento.finalizar',
                'taller.repuestos.ver', 'taller.repuestos.gestionar',
                'taller.reportes.ver', 'taller.reportes.descargar',
            ],
            'POZOS' => [
                'pozos.dashboard',
                'pozos.monitoreo.ver',
                'pozos.bombas.ver', 'pozos.bombas.gestionar',
                'pozos.caudal.ver', 'pozos.caudal.registrar',
                'pozos.reportes.ver', 'pozos.reportes.descargar',
            ],
            'SEGURIDAD' => [
                'gestionar_seguridad',
                'seguridad.dashboard',
                'seguridad.usuarios.ver', 'seguridad.usuarios.crear', 'seguridad.usuarios.editar', 'seguridad.usuarios.eliminar',
                'seguridad.roles.ver', 'seguridad.roles.gestionar',
                'seguridad.permisos.ver', 'seguridad.permisos.gestionar',
                'seguridad.logs.ver',
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
        $medicoRole->givePermissionTo(['ssl.accidentes.ver', 'ssl.dotaciones.ver']); // Permisos cruzados

        // --- ROLE: ANALISTA SSL ---
        $sslRole = Role::create(['name' => 'analista_ssl']);
        $sslRole->givePermissionTo(Permission::where('name', 'like', 'ssl.%')->get());
        $sslRole->givePermissionTo(['medicina.accidentes.ver', 'medicina.pacientes.ver']);

        // --- ROLE: JEFE DE TALLER ---
        $tallerRole = Role::create(['name' => 'jefe_taller']);
        $tallerRole->givePermissionTo(Permission::where('name', 'like', 'taller.%')->get());

        // --- ROLE: OPERADOR DE POZOS ---
        $pozosRole = Role::create(['name' => 'operador_pozos']);
        $pozosRole->givePermissionTo(Permission::where('name', 'like', 'pozos.%')->get());

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