<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboItem extends Model
{
    protected $table = 'combo_items';

    protected $fillable = ['combo_id', 'producto_id', 'cantidad', 'es_gratuito'];

    protected $casts = [
        'es_gratuito' => 'boolean',
    ];

    public function combo()
    {
        return $this->belongsTo(Combo::class, 'combo_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
