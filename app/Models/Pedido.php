<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['mesa_id', 'caja_sesion_id', 'numero_turno', 'mesero_id', 'estado', 'total', 'descuento', 'notas'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pedido) {
            if (!$pedido->caja_sesion_id || !$pedido->numero_turno || $pedido->numero_turno == 1) {
                $cajaAbierta = CajaSesion::where('estado', 'abierta')->latest()->first();
                $cajaSesionId = $pedido->caja_sesion_id ?? ($cajaAbierta ? $cajaAbierta->id : null);
                
                if ($cajaSesionId) {
                    $maxNumero = self::where('caja_sesion_id', $cajaSesionId)->max('numero_turno') ?? 0;
                    $pedido->caja_sesion_id = $cajaSesionId;
                    $pedido->numero_turno = $maxNumero + 1;
                } else {
                    $maxNumeroDia = self::whereDate('created_at', today())->max('numero_turno') ?? 0;
                    $pedido->numero_turno = $maxNumeroDia + 1;
                }
            }
        });
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    public function cajaSesion()
    {
        return $this->belongsTo(CajaSesion::class, 'caja_sesion_id');
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
