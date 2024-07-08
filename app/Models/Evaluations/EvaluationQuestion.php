<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
    use HasFactory;

    protected $table = 'evaluation_re_questions';

    protected $fillable = [
        'evaluation_id',
        'question_id',
    ];

}
