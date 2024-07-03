<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
    use HasFactory;
    protected $table = 'evaluation_questions';
    protected $fillable = [
        'id_evaluation',
        'id_question',
    ];
}
