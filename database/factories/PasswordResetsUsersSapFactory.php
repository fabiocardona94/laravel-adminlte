<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PasswordResetsUsersSap>
 */
class PasswordResetsUsersSapFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tipo_solicitud' => $this->faker->randomElement(['OLVIDO', 'BLOQUEO']),
            'observacion' => $this->faker->sentence,
            'password_tmp' => $this->faker->bothify('?#?#?#'),
            'status' => $this->faker->randomElement([0, 1]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
