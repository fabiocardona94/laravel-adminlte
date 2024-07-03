<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankQuestion extends Model
{
    use HasFactory;

    protected $table = 'bank_questions';
    protected $fillable = [
        'question',
    ];

    public function evaluations(): BelongsToMany
    {
        return $this->belongsToMany(Evaluation::class, 'evaluation_questions', 'id_pregunta', 'id_evaluacion');
    }
    
    public function options() : HasMany
    {
        return $this->hasMany(Option::class, 'id_question');
    }
}


