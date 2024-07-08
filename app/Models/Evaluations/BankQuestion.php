<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankQuestion extends Model
{
    use HasFactory;
    protected $table = 'bank_questions';
    
    protected $fillable = [
        'question_title',
        'status',
    ];

    public function evaluations(): BelongsToMany
    {
        return $this->belongsToMany(Evaluation::class, 'tr_evaluation_questions', 'question_id', 'evluation_id');
    }
    
    public function options() : HasMany
    {
        return $this->hasMany(EvaluationQuestion::class, 'question_id');
    }
}
