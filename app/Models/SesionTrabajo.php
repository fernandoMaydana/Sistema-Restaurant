<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionTrabajo extends Model
{
    protected $table = 'sesiones_trabajo';

    protected $fillable = [
        'user_id',
        'fecha_entrada',
        'fecha_salida',
    ];

    protected $casts = [
        'fecha_entrada' => 'datetime',
        'fecha_salida' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
