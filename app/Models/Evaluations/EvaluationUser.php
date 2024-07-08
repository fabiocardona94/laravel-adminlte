<?php

namespace App\Models\Evaluations;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationUser extends Model
{
    use HasFactory;

    protected $table = 'evaluations_re_users';

    protected $fillable = [
        'evaluation_id',
        'user_id',
        'status',
        'start_time',
        'end_time',
    ];

}
