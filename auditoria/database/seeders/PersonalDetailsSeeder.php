<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PersonalDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('personal_details')->insert([
        //     [
        //         'user_id' => 1,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234007890123', // 13 dígitos
        //         'dpi' => '0101123456789', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 2,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '123456789003', // 13 dígitos
        //         'dpi' => '0101123422289', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 3,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234575057623', // 13 dígitos
        //         'dpi' => '0204712345789', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],

        //      [
        //         'user_id' => 4,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '123456780101', // 13 dígitos
        //         'dpi' => '0101123456711', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 5,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '123456780034', // 13 dígitos
        //         'dpi' => '0101113456789', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 6,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234567892233', // 13 dígitos
        //         'dpi' => '0101123489789', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 7,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '134567906765', // 13 dígitos
        //         'dpi' => '0108823456789', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 8,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '123456789773', // 13 dígitos
        //         'dpi' => '0201123456789', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 9,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234567890003', // 13 dígitos
        //         'dpi' => '0101123452289', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 10,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234567000123', // 13 dígitos
        //         'dpi' => '0101123456259', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 11,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234447890123', // 13 dígitos
        //         'dpi' => '0101883456789', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 12,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234014000123', // 13 dígitos
        //         'dpi' => '0101123456779', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 13,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1299567890123', // 13 dígitos
        //         'dpi' => '9101123456799', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 14,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234565600123', // 13 dígitos
        //         'dpi' => '0101123456703', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 15,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234522678983', // 13 dígitos
        //         'dpi' => '0101123456629', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 16,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234999011123', // 13 dígitos
        //         'dpi' => '0105123456789', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 17,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234089012300', // 13 dígitos
        //         'dpi' => '0101123456788', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 18,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '123456119123', // 13 dígitos
        //         'dpi' => '0101123266789', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 19,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '1234567690123', // 13 dígitos
        //         'dpi' => '0101123434589', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 20,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '12345690123', // 13 dígitos
        //         'dpi' => '0107723456789', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //      [
        //         'user_id' => 21,
        //         'date_of_birth' => '1990-05-15',
        //         'phone_number' => '50212345678',
        //         'address' => '10ma Calle 1-23 Zona 1',
        //         'nit' => '123456789023', // 13 dígitos
        //         'dpi' => '0101128456789', // 13 dígitos (ejemplo con cero inicial)
        //         'gender' => 'M',
        //         'email_personal' => 'juan.perez@example.com',
        //         'city' => 'Ciudad de Guatemala',
        //         'department' => 'Guatemala',
        //         'country' => 'Guatemala',
        //         'ethnic_group' => 'Mestizo',
        //         'profile_picture_url' => 'https://example.com/profiles/juan_perez.jpg',
        //         'code_job' => 11,
        //         'state' => '1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     // Puedes añadir más registros ficticios aquí si lo necesitas
        // ]);
    }
}
