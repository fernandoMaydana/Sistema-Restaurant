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
}
