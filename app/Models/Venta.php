<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'cosecha_id',
        'fecha',
        'cantidad_kg',
        'precio_kg',
        'total',
        'comprador',
        'tipo_cafe',
        'observaciones',
    ];
}
