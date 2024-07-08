<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationBankQuestion extends Model
{
    use HasFactory;
    protected $table = 'evaluation_bank_questions';
    
    protected $fillable = [
        'question_title',
        'status',
    ];

    public function evaluations(): BelongsToMany
    {
        return $this->belongsToMany(Evaluation::class, 'evaluation_re_questions', 'question_id', 'evluation_id');
    }
    
    public function options() : HasMany
    {
        return $this->hasMany(EvaluationQuestion::class, 'question_id');
    }
}
