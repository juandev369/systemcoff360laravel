<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $fillable = [
        'nombre',
        'tipo_plantacion',
        'ubicacion',
        'area_hectareas',
        'estado'
    ];
}