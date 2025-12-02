<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Papelería',
            'Adhesivos y pegamentos',
            'Escritura y corrección',
            'Tintas',
            'Herramientas',
            'Instrumentos de medición',
        ];

        foreach ($categories as $category) {
            DB::table('supply_categories')->insert([
                'name' => $category,
                'state' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
