<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class MeasurementUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
            // Disable foreign key checks to allow truncation if there are related tables
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Truncate the table
        DB::table('measurement_units')->truncate();

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
        $units = [
            'Unidad',
            'Caja',
            'Paquete',
            'Juego',
            'Resma',
            'Galón',
            'Litro',
            'Mililitro',
            'Metro',
            'Centímetro',
            'Milímetro',
            'Kilogramo',
            'Gramo',
            'Libra',
            'Onza',
            'Pie',
            'Pulgada',
            'Yarda',
            'Docena',
            'Par',
            'Bolsa',
            'Bote',
            'Barra',
            'Pliego',
            'Hojas',
            'Rollo',
            'Tubo',
            'Frasco',
            'Block'
        ];

        foreach ($units as $unit) {
            DB::table('measurement_units')->insert([
                'name' => $unit,
                'state' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
