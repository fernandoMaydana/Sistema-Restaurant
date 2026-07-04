<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'cliente_nombre',
        'cliente_telefono',
        'fecha',
        'hora',
        'cantidad_personas',
        'mesa_id',
        'notes',
        'notas',
        'estado'
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }
}
