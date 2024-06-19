<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargue extends Model
{
    protected $table = 'cargue';
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

