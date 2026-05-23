<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    protected $table = 'pedido_detalles';

    protected $fillable = ['pedido_id', 'producto_id', 'cantidad', 'precio_unitario', 'estado_comanda'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function getNombreMostrarAttribute()
    {
        $nombre = $this->producto->nombre ?? 'Producto Eliminado';
        
        if ($this->producto) {
            // Caso 1: Es el Precio 2 (Doble, etc.)
            if ($this->producto->precio_2 > 0 && floatval($this->precio_unitario) == floatval($this->producto->precio_2)) {
                $variante = $this->producto->precio_2_nombre ?: 'Opción 2';
                $nombre .= ' (' . $variante . ')';
            } 
            // Caso 2: Es el Precio 1 (Principal) y tiene un nombre asignado (ej: Personal)
            elseif (floatval($this->precio_unitario) == floatval($this->producto->precio) && $this->producto->precio_nombre) {
                $nombre .= ' (' . $this->producto->precio_nombre . ')';
            }
        }
        
        return $nombre;
    }
}
