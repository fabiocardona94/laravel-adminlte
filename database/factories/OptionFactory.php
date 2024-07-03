<?php

namespace Database\Factories;

use App\Models\BankQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Option>
 */
class OptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_question' => BankQuestion::factory(), // Esto genera una nueva pregunta si no existe
            'option' => $this->faker->text(20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
