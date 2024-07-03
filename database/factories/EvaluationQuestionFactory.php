<?php

namespace Database\Factories;

use App\Models\BankQuestion;
use App\Models\Evaluation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EvaluationQuestion>
 */
class EvaluationQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_evaluation' =>Evaluation::factory(),
            'id_question' =>BankQuestion::factory(),
        ];
    }
}
