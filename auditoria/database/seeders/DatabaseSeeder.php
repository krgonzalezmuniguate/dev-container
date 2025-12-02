<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RolesAndPermissionsSeeder::class);

        $users = [
            ['name' => 'Manuel De Jesús', 'last_name' => 'López Cruz', 'email' => 'mjcruz@muniguate.com'],
            ['name' => 'Alina Magaly', 'last_name' => 'Carreño Llamas', 'email' => 'acarreno@muniguate.com'],
            ['name' => 'Adelina', 'last_name' => 'Noj Camey', 'email' => 'Sin correo'],
            ['name' => 'Isidro Martín', 'last_name' => 'Hernández Alvarado', 'email' => 'martinhernandez@gmail.com'],
            ['name' => 'Walter Rafael', 'last_name' => 'Tejeda Calderón', 'email' => 'wtejeda@muniguate.com'],
            ['name' => 'Hugo Homero', 'last_name' => 'Chávez Ávila', 'email' => 'hhchavez@muniguate.com'],
            ['name' => 'Leslie Julissa', 'last_name' => 'Torresblanca Chajón', 'email' => 'ltorresblanca@muniguate.com'],
            ['name' => 'Karla Alejandra', 'last_name' => 'Castillo Zeceña', 'email' => 'kcastillo@muniguate.com'],
            ['name' => 'Evelyn Noemi', 'last_name' => 'Rodriguez Monroy', 'email' => 'enrodriguez@muniguate.com'],
            ['name' => 'Kevin Ricardo', 'last_name' => 'González Martínez', 'email' => 'krgonzalez@muniguate.com'],
            ['name' => 'Javier', 'last_name' => 'Ramírez Fajardo', 'email' => 'jrfajardo@muniguate.com'],
            ['name' => 'Jorge Alfredo', 'last_name' => 'Sazo Martínez', 'email' => 'jsazo@muniguate.com'],
            ['name' => 'Jose Jorge', 'last_name' => 'Argueta Turcios', 'email' => 'jargueta@muniguate.com'],
            ['name' => 'Sergio Guadalupe', 'last_name' => 'Recinos López', 'email' => 'srecinos@muniguate.com'],
            ['name' => 'Claudia Izabel', 'last_name' => 'Pereira Román', 'email' => 'cpereira@muniguate.com'],
            ['name' => 'Lorena Viviana', 'last_name' => 'Coronado Flores', 'email' => 'lcoronado@muniguate.com'],
            ['name' => 'Franklin Kevin', 'last_name' => 'Hernández Guevara', 'email' => 'fkhernandez@muniguate.com'],
            ['name' => 'Marlon Estuardo', 'last_name' => 'Vásquez Jacobo', 'email' => 'mevasquez@muniguate.com'],
            ['name' => 'Eber Renato', 'last_name' => 'Estrada Canales', 'email' => 'restrada@muniguate.com'],
            ['name' => 'Rocio Elizabeth', 'last_name' => 'Ramirez Villatoro', 'email' => 'reramirez@muniguate.com'],
            ['name' => 'Edgar Leonel', 'last_name' => 'Xicara Santos', 'email' => 'exicara@muniguate.com'],
        ];

        foreach ($users as $userData) {
            $newUser = User::factory()->create([
                'name' => $userData['name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email']
            ]);

            if ($newUser->email == 'krgonzalez@muniguate.com') {
                // 1. Encuentra el rol 'admin' específico para el guard 'api'
                $adminRole = Role::where('name', 'Administrador')
                    ->where('guard_name', 'api')
                    ->first();
                        $this->call(AplicationsSeeder::class);
                // 2. Verifica si el rol existe antes de asignarlo para evitar errores
                if ($adminRole) {
                    $newUser->assignRole($adminRole); // Asigna el objeto Role directamente
                } else {
                    // Maneja el caso en que el rol no se encuentre (opcional)
                    // Por ejemplo, puedes loggear un error o crear el rol dinámicamente
                    echo "El rol 'admin' para el guard 'api' no existe. Por favor, créalo.\n";
                }
            }
        }
        $this->call(PersonalDetailsSeeder::class);
        $this->call(MeasurementUnitSeeder::class);
        $this->call(SupplyCategorySeeder::class);
        $this->call(SuppliesSeeder::class);
    }
}
