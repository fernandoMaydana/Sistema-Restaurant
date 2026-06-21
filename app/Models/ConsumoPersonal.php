<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumoPersonal extends Model
{
    protected $table = 'consumos_personal';

    protected $fillable = ['producto_id', 'user_id', 'cantidad', 'descripcion'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function cajero()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
