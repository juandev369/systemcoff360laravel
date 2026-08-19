<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntregaHerramienta extends Model
{
    protected $table = 'entrega_herramientas';

    protected $fillable = [
        'herramienta_id',
        'user_id',
        'fecha_entrega',
        'fecha_devolucion',
        'estado_herramienta',
        'observaciones',
    ];
}
