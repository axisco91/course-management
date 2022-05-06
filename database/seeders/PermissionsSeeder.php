<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);
        Permission::create(['name' => 'ver ususarios']);
        Permission::create(['name' => 'crear estudiantes']);
        Permission::create(['name' => 'eliminar estudiantes']);
        Permission::create(['name' => 'editar estudiantes']);
        Permission::create(['name' => 'ver estudiantes']);
        Permission::create(['name' => 'crear docentes']);
        Permission::create(['name' => 'eliminar docentes']);
        Permission::create(['name' => 'editar docentes']);
        Permission::create(['name' => 'ver docentes']);
        Permission::create(['name' => 'crear empresas']);
        Permission::create(['name' => 'eliminar empresas']);
        Permission::create(['name' => 'editar empresas']);
        Permission::create(['name' => 'ver empresas']);
        Permission::create(['name' => 'crear acciones formativas']);
        Permission::create(['name' => 'eliminar acciones formativas']);
        Permission::create(['name' => 'editar acciones formativas']);
        Permission::create(['name' => 'ver acciones formativas']);
        Permission::create(['name' => 'crear cursos']);
        Permission::create(['name' => 'eliminar cursos']);
        Permission::create(['name' => 'editar cursos']);
        Permission::create(['name' => 'ver cursos']);
        Permission::create(['name' => 'crear asesorias']);
        Permission::create(['name' => 'eliminar asesorias']);
        Permission::create(['name' => 'editar asesorias']);
        Permission::create(['name' => 'ver proveedores']);
        Permission::create(['name' => 'ver facturas']);
        Permission::create(['name' => 'editar facturas']);
        Permission::create(['name' => 'ver tareas']);
        Permission::create(['name' => 'editar tareas']);
        Permission::create(['name' => 'crear rol']);
    }
}
