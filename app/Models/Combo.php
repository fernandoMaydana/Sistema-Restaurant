<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $table = 'combos';

    protected $fillable = ['nombre', 'descripcion', 'precio_total', 'activo', 'imagen', 'tipo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(ComboItem::class, 'combo_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function getPrecioMostrarAttribute()
    {
        if ($this->precio_total !== null) {
            return $this->precio_total;
        }

        // Si no tiene un precio fijo especificado, sumamos los items (excluyendo gratuitos)
        return $this->items->reduce(function ($carry, $item) {
            if ($item->es_gratuito) {
                return $carry;
            }
            // Evitar errores si no se ha cargado la relación producto
            $precio = $item->producto ? $item->producto->precio : 0;
            return $carry + ($precio * $item->cantidad);
        }, 0);
    }
}
