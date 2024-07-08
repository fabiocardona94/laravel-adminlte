<?php

namespace Database\Factories;

use App\Models\BankQuestion;
use App\Models\Evaluation;
use App\Models\Evaluations\BankQuestion as EvaluationsBankQuestion;
use App\Models\Evaluations\Evaluation as EvaluationsEvaluation;
use App\Models\Evaluations\EvaluationBankQuestion;
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
            'id_evaluation' =>EvaluationsEvaluation::factory(),
            'id_question' =>EvaluationBankQuestion::factory(),
        ];
    }
}
