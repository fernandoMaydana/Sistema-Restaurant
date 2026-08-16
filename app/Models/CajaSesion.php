<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaSesion extends Model
{
    protected $fillable = [
        'user_id',
        'monto_inicial',
        'monto_final',
        'monto_real',
        'diferencia',
        'observaciones',
        'total_ventas',
        'fecha_apertura',
        'fecha_cierre',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'caja_sesion_id');
    }
}
