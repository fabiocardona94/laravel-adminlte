<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationUserAnswer extends Model
{
    use HasFactory;

    protected $table = 'evaluation_re_user_answers';

    protected $fillable = [
        'evaluations_re_users_id',
        'evaluation_bank_questions_id',
        'evaluation_question_options_id',
    ];
}
