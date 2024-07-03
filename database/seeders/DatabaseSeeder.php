<?php

namespace Database\Seeders;

use App\Models\BankQuestion;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\Option;
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
        Evaluation::factory(10)->create();
        BankQuestion::factory(10)->create();
        Option::factory(10)->create();
        EvaluationQuestion::factory(10)->create();


        User::create([
            'username' => '1234',
            'name' => 'Prueva',
            'cell_phone' => '+57828823',
            'email' => 'prueba@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'profile_photo_path' => null,
            'rol' => 'developer',
            'active' => 1,
            'update_password' => 0,
            'license' => date('Y-m-d', strtotime('+1 year'))
        ]);
    }
}
