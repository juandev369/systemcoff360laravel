<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoNomina extends Model
{
    protected $fillable = [
        'user_id',
        'registrado_por',
        'fecha',
        'monto',
        'tipo_pago',
        'descripcion'
    ];

    // Relación: El pago pertenece a un trabajador (usuario)
    public function trabajador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: El pago fue registrado por un admin (usuario)
    public function admin()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
