<?php

namespace Database\Seeders;

use App\Models\BankQuestion;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\Evaluations\Evaluation as EvaluationsEvaluation;
use App\Models\Evaluations\EvaluationBankQuestion;
use App\Models\Evaluations\EvaluationQuestion as EvaluationsEvaluationQuestion;
use App\Models\Evaluations\EvaluationQuestionOption;
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
        EvaluationsEvaluation::factory(10)->create();
        EvaluationBankQuestion::factory(10)->create();
        EvaluationQuestionOption::factory(10)->create();
       EvaluationsEvaluationQuestion::factory(10)->create();


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
