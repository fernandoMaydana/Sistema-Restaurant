<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $fillable = ['caja_sesion_id', 'monto', 'descripcion'];

    public function cajaSesion()
    {
        return $this->belongsTo(CajaSesion::class, 'caja_sesion_id');
    }
}
