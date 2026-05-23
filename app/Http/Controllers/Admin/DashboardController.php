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
        
        return view('admin.dashboard', compact('totalVentas', 'facturasHoy', 'fecha'));
    }
}
