<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetsUsersSap extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo_solicitud',
        'observacion',
        'password_tmp',
    ];
}
