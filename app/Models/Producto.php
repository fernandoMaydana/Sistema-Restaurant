<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'categoria_id', 'codigo_sin', 'nombre', 'imagen', 'disponible', 'usa_inventario', 'stock',
        'precio_nombre', 'precio', 'costo',
        'precio_2_nombre', 'precio_2', 'costo_2',
        'precio_3_nombre', 'precio_3', 'costo_3'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
