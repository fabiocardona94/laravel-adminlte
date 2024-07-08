<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OptionQuestionEvaluation extends Model
{
    use HasFactory;
    protected $table = 'option_question_evaluations';
    protected $fillable = [
        'question_id',
        'option',
    ];

    public function pregunta() : BelongsTo
    {
        return $this->belongsTo(BankQuestion::class, 'question_id');
    }
}
