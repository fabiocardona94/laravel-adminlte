<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'username' => '1234',
            'name' => 'Jose Angarita',
            'cell_phone' => '+573115633158',
            'email' => 'joseangarita014@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'profile_photo_path' => null,
            'rol' => 'Junior',
            'active' => 1,
            'update_password' => 0,
            'license' => date('Y-m-d', strtotime('+1 year'))
        ]);
    }
}
