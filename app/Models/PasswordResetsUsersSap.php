<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordResetsUsersSap extends Model
{
    use HasFactory;

    protected $table = 'password_resets_users_saps';

    protected $fillable = [
        'user_id',
        'tipo_solicitud',
        'observacion',
        'password_tmp',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
