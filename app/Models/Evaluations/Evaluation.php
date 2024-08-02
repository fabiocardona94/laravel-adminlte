<?php

namespace App\Models\Evaluations;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluations';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'evaluations_re_users', 'evaluation_id', 'user_id');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(EvaluationBankQuestion::class, 'evaluation_re_questions', 'evaluation_id', 'question_id')
                                                                           ->wherePivot('status',1);
    }
}
