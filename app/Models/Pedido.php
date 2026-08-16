<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['mesa_id', 'mesero_id', 'estado', 'total', 'descuento', 'notas'];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    public function mesero()
    {
        return $this->belongsTo(User::class, 'mesero_id');
    }

    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class);
    }

    public function factura()
    {
        return $this->hasOne(Factura::class);
    }
}
