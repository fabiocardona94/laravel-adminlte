<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationQuestionOption extends Model
{
    use HasFactory;

    protected $table = 'evaluation_question_options';

    protected $fillable = [
        'question_id',
        'question_option',
        'is_correct',
        'percentage_value'
    ];

    public function question() : BelongsTo
    {
        return $this->belongsTo(EvaluationBankQuestion::class, 'question_id');
    }
}
