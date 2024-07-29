<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
    use HasFactory;

    protected $table = 'evaluation_re_questions';

    protected $fillable = [
        'evaluation_id',
        'question_id',
        'status'
    ];

    public $incrementing = false;

    protected $primaryKey = ['evaluation_id', 'question_id'];

    protected $keyType = 'string';

    public $timestamps = true;

    // Sobrescribir el método para obtener la clave primaria
    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('evaluation_id', '=', $this->getAttribute('evaluation_id'))
            ->where('question_id', '=', $this->getAttribute('question_id'));

        return $query;
    }

}
