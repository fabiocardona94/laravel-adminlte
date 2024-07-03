<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    use HasFactory;

    protected $table = 'options';
    protected $fillable = [
        'id_question',
        'option',
    ];

    public function pregunta() : BelongsTo
    {
        return $this->belongsTo(BankQuestion::class, 'id_question');
    }
}
