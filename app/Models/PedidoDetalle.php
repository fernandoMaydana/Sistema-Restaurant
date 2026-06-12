<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    protected $table = 'pedido_detalles';

    protected $fillable = ['pedido_id', 'producto_id', 'cantidad', 'precio_unitario', 'estado_comanda', 'notas'];

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
            // Caso 2: Es el Precio 3 (Triple, etc.)
            elseif ($this->producto->precio_3 > 0 && floatval($this->precio_unitario) == floatval($this->producto->precio_3)) {
                $variante = $this->producto->precio_3_nombre ?: 'Opción 3';
                $nombre .= ' (' . $variante . ')';
            }
            // Caso 3: Es el Precio 1 (Principal) y tiene un nombre asignado (ej: Personal)
            elseif (floatval($this->precio_unitario) == floatval($this->producto->precio) && $this->producto->precio_nombre) {
                $nombre .= ' (' . $this->producto->precio_nombre . ')';
            }
        }
        
        return $nombre;
    }

    public function getCostoUnitarioAttribute()
    {
        if (!$this->producto) {
            return 0;
        }
        
        $precioUnit = floatval($this->precio_unitario);
        $p2 = floatval($this->producto->precio_2);
        $p3 = floatval($this->producto->precio_3);
        
        if ($p2 > 0 && abs($precioUnit - $p2) < 0.01) {
            return floatval($this->producto->costo_2 ?? 0);
        }
        
        if ($p3 > 0 && abs($precioUnit - $p3) < 0.01) {
            return floatval($this->producto->costo_3 ?? 0);
        }
        
        return floatval($this->producto->costo ?? 0);
    }
    
    public function getCostoTotalAttribute()
    {
        return $this->costo_unitario * $this->cantidad;
    }

    public function getGananciaTotalAttribute()
    {
        return ($this->precio_unitario * $this->cantidad) - $this->costo_total;
    }
}
