<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluations';
    protected $fillable = [
        'title',
        'description',
        'star_date',
        'end_date',
    ];

    public function preguntas(): BelongsToMany
    {
        return $this->belongsToMany(BankQuestion::class, 'evaluation_questions', 'id_evaluation', 'id_question');
    }
}
