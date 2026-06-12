<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Factura;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $fecha = $request->input('fecha', today()->toDateString());
        
        $totalVentas = Factura::where('estado', 'activa')->whereDate('created_at', $fecha)->sum('monto_pagado');
        $facturasHoy = Factura::with(['pedido.mesa', 'cajero'])->whereDate('created_at', $fecha)->orderBy('created_at', 'desc')->get();
        
        // Métricas de Stock de Bebidas para Dashboard
        $categoryNames = ['Refrescos', 'Jugos', 'Cerveza', 'Bebidas'];
        $categoryIds = \App\Models\Categoria::whereIn('nombre', $categoryNames)->pluck('id')->toArray();
        
        $stockBebidasCritico = \App\Models\Producto::whereIn('categoria_id', $categoryIds)
            ->where('usa_inventario', true)
            ->where('stock', '<=', 10)
            ->count();
            
        $totalBebidasStock = \App\Models\Producto::whereIn('categoria_id', $categoryIds)
            ->where('usa_inventario', true)
            ->sum('stock');
        
        return view('admin.dashboard', compact('totalVentas', 'facturasHoy', 'fecha', 'stockBebidasCritico', 'totalBebidasStock'));
    }
}
