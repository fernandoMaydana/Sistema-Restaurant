<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\User;

class HistorialVentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Factura::with(['pedido.mesa', 'cajero'])->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('fecha_especifica')) {
            $query->whereDate('created_at', $request->fecha_especifica);
        } else {
            if ($request->filled('fecha_desde')) {
                $query->whereDate('created_at', '>=', $request->fecha_desde);
            }
            if ($request->filled('fecha_hasta')) {
                $query->whereDate('created_at', '<=', $request->fecha_hasta);
            }
        }

        if ($request->filled('cajero_id')) {
            $query->where('cajero_id', $request->cajero_id);
        }

        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        $total_filtrado = (clone $query)->where('estado', 'activa')->sum('monto_pagado');
        $facturas = $query->paginate(30)->withQueryString();
        
        $cajeros = User::where('role', 'cajero')->get();

        return view('admin.ventas.index', compact('facturas', 'total_filtrado', 'cajeros'));
    }

    /**
     * Retorna el detalle de los productos de una factura en formato JSON.
     */
    public function getDetalle($id)
    {
        $factura = Factura::with(['pedido.detalles.producto', 'pedido.mesa', 'cajero'])->findOrFail($id);
        
        $detalles = [];
        if ($factura->pedido) {
            foreach ($factura->pedido->detalles as $det) {
                $detalles[] = [
                    'cantidad' => $det->cantidad,
                    'producto_nombre' => $det->nombre_mostrar,
                    'precio_unitario' => floatval($det->precio_unitario),
                    'subtotal' => floatval($det->cantidad * $det->precio_unitario),
                    'notas' => $det->notas
                ];
            }
        }

        return response()->json([
            'success' => true,
            'factura' => [
                'id' => $factura->id,
                'fecha' => $factura->created_at->format('d/m/Y H:i'),
                'mesa_numero' => $factura->pedido?->mesa?->numero ?? 'N/A',
                'cliente_nombre' => $factura->cliente_nombre ?? 'Consumidor Final',
                'cliente_nit_ci' => $factura->cliente_nit_ci ?? 'S/N',
                'metodo_pago' => ucfirst($factura->metodo_pago),
                'cajero_nombre' => $factura->cajero?->name ?? 'N/A',
                'monto_pagado' => floatval($factura->monto_pagado),
                'estado' => $factura->estado,
            ],
            'detalles' => $detalles
        ]);
    }
}
