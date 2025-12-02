<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reiniciar los roles y permisos en caché para evitar problemas de caché
        // app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions


        // create roles and assign created permissions
        $roleAdmin = Role::create(['name' => 'Administrador', 'guard_name' => 'api']);
        $roleAdmin->givePermissionTo(Permission::all()); // El admin tiene todos los permisos

        $roleAuditor = Role::create(['name' => 'auditor','guard_name' => 'api']);


        $roleGuest = Role::create(['name' => 'guest',  'guard_name' => 'api']);
        // A guest can't do anything
    }
}
