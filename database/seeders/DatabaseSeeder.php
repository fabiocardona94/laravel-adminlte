<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'username' => '12345678',
            'name' => 'USUARIO TEST',
            'cell_phone' => '+573109992514',
            'email' => 'usuario@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'profile_photo_path' => null,
            'rol' => 'developer',
            'active' => 1,
            'update_password' => 0,
            'license' => date('Y-m-d', strtotime('+1 year'))
        ]);
    }
}
