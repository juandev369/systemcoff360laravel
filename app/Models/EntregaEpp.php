<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntregaEpp extends Model
{
    protected $table = 'entrega_epps';

    protected $fillable = [
        'epp_id',
        'user_id',
        'fecha_entrega',
        'fecha_devolucion',
        'estado_elemento',
        'observaciones',
    ];
}
