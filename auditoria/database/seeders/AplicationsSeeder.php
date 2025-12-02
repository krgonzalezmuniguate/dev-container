<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AplicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = Menu::create([
            'name' => 'Recursos Humanos',
            'description' => 'Gestión integral de personal y talento humano',
            'icon' => 'UsersIcon',
            'color' => '#3B82F6',
            'gradient' => 'linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%)',
        ]);
        $app = Application::create([
            'menu_id' => $menu->id,
            'name' => 'Gestión del Personal',
            'slug' => 'gestion-del-personal',
            'url' => 'http://localhost/',
            'description' => 'Permisos y accesos',
            'icon' => 'ShieldCheckIcon',
            'color'=> '#EF4444',
        ]);
        $menu1 = Menu::create([
            'name' => 'Suministros',
            'description' => 'Gestión integral de suministros',
            'icon' => 'ContainerIcon',
            'color' => '#F59E0B',
            'gradient' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)',
        ]);
        $app1 = Application::create([
            'menu_id' => $menu1->id,
            'name' => 'Gestión de Suministros',
            'slug' => 'gestion-de-suministros',
            'url' => 'http://172.23.53.186/auditoria/suministros/',
            'description' => 'Permisos y accesos',
            'icon' => 'ShieldCheckIcon',
            'color'=> '#EF4444',
        ]);
        $menu2 = Menu::create([
            'name' => 'Inventarios',
            'description' => 'Control de inventario',
            'icon' => 'BarChart3Icon',
            'color' => '#10B981',
            'gradient' => 'linear-gradient(135deg, #10B981 0%, #059669 100%)',
        ]);
        $app2 = Application::create([
            'menu_id' => $menu2->id,
            'name' => 'Gestionar Inventario',
            'slug' => 'gestion-de-inventario',
            'url' => 'http://localhost/',
            'description' => 'Permisos y accesos',
            'icon' => 'ShieldCheckIcon',
            'color'=> '#EF4444',
        ]);
        $menu3 = Menu::create([
            'name' => 'Biblioteca',
            'description' => 'Gestión documental y archivo digital',
            'icon' => 'BookIcon',
            'color' => '#8B5CF6',
            'gradient' => 'linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%)',
        ]);
        $app3 = Application::create([
            'menu_id' => $menu3->id,
            'name' => 'Gestionar Biblioteca',
            'slug' => 'gestionar-biblioteca',
            'url' => 'http://172.23.53.186/auditoria/biblioteca/admin',
            'description' => 'Permisos y accesos',
            'icon' => 'ShieldCheckIcon',
            'color'=> '#EF4444',
        ]);
        $app31 = Application::create([
            'menu_id' => $menu3->id,
            'name' => 'Ver Biblioteca',
            'slug' => 'ver-biblioteca',
            'url' => 'http://172.23.53.186/auditoria/biblioteca/',
            'description' => 'Permisos y accesos',
            'icon' => 'ShieldCheckIcon',
            'color'=> '#EF4444',
        ]);
        $permission1 = Permission::firstOrCreate(['name' => $app->slug, 'guard_name' => 'api']);
        $permission2 = Permission::firstOrCreate(['name' => $app1->slug, 'guard_name' => 'api']);
        $permission3 = Permission::firstOrCreate(['name' => $app2->slug, 'guard_name' => 'api']);
        $permission4 = Permission::firstOrCreate(['name' => $app3->slug, 'guard_name' => 'api']);
        $permission5 = Permission::firstOrCreate(['name' => $app31->slug, 'guard_name' => 'api']);
        $adminRole = Role::where('name', 'Administrador')
            ->where('guard_name', 'api')
            ->first();
        if ($adminRole) {
            $adminRole->givePermissionTo([
                $permission1,
                $permission2,
                $permission3,
                $permission4,
                $permission5
            ]);
        } else {
            // Maneja el caso en que el rol no se encuentre (opcional)
            // Por ejemplo, puedes loggear un error o crear el rol dinámicamente
            echo "El rol 'admin' para el guard 'api' no existe. Por favor, créalo.\n";
        }
    }
}
