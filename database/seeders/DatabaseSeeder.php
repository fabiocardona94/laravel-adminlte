<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PasswordResetsUsersSap;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        PasswordResetsUsersSap::factory(50)->create();

        $this->call(UserSeeder::class);
        // $user = User::factory()
        //     ->has(PasswordResetsUsersSap::factory()->count(3))
        //     ->create();

    }
}
