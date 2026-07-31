<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        'pedido_id', 'cajero_id', 'cliente_nombre', 'cliente_nit_ci', 'monto_pagado', 
        'descuento', 'recargo', 'metodo_pago', 'efectivo_recibido', 'estado',
        'cuf', 'cufd_codigo', 'numero_factura_siat', 'estado_siat', 'codigo_recepcion', 
        'leyenda_sin', 'xml_path'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function cajero()
    {
        return $this->belongsTo(User::class, 'cajero_id');
    }
}
