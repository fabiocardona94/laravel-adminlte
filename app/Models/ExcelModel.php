<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelModel extends Model
{
    protected $table = 'excel';
    protected $fillable = [
        'centro',
        'almacen',
        'material',
        'texto_breve_de_material',
        'grupo_de_articulos',
        'lote',
        'unidad_de_medida',
        'libre_utilizacion'
    ];
}

