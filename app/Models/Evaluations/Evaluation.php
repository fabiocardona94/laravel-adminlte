<?php

namespace App\Models\Evaluations;

use App\Models\User;
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
        'status',
        'end_date',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'evaluation_users', 'evaluation_id', 'user_id');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(BankQuestion::class, 'tr_evaluation_questions', 'evaluation_id', 'question_id');
    }
}
