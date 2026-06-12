<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Factura;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * Reporte de productos más vendidos.
     */
    public function productosVendidos(Request $request)
    {
        // Rango de fechas por defecto: desde el inicio del mes actual hasta hoy
        $fecha_desde = $request->input('fecha_desde', Carbon::now()->startOfMonth()->toDateString());
        $fecha_hasta = $request->input('fecha_hasta', Carbon::now()->toDateString());

        $fecha_especifica = $request->input('fecha_especifica');

        $query = PedidoDetalle::select(
                'pedido_detalles.producto_id',
                DB::raw('SUM(pedido_detalles.cantidad) as total_cantidad'),
                DB::raw('SUM(pedido_detalles.cantidad * pedido_detalles.precio_unitario) as total_recaudado')
            )
            ->join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->join('facturas', 'facturas.pedido_id', '=', 'pedidos.id')
            ->where('facturas.estado', 'activa');

        if ($request->filled('fecha_especifica')) {
            $query->whereDate('facturas.created_at', $fecha_especifica);
        } else {
            if ($request->filled('fecha_desde')) {
                $query->whereDate('facturas.created_at', '>=', $fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('facturas.created_at', '<=', $fecha_hasta);
            }
        }

        $productos = $query->groupBy('pedido_detalles.producto_id')
            ->orderBy('total_cantidad', 'desc')
            ->with('producto.categoria')
            ->get();

        $totalCantidad = $productos->sum('total_cantidad');
        $totalRecaudado = $productos->sum('total_recaudado');

        // Top 10 para el gráfico
        $topProductos = $productos->take(10);

        return view('admin.reportes.productos_vendidos', compact(
            'productos',
            'fecha_desde',
            'fecha_hasta',
            'fecha_especifica',
            'totalCantidad',
            'totalRecaudado',
            'topProductos'
        ));
    }

    /**
     * Reporte de rendimiento y ventas por mesero.
     */
    public function meseros(Request $request)
    {
        $fecha_desde = $request->input('fecha_desde', Carbon::now()->startOfMonth()->toDateString());
        $fecha_hasta = $request->input('fecha_hasta', Carbon::now()->toDateString());

        $fecha_especifica = $request->input('fecha_especifica');

        $query = Pedido::select(
                'pedidos.mesero_id',
                DB::raw('COUNT(pedidos.id) as total_pedidos'),
                DB::raw('SUM(facturas.monto_pagado) as total_ventas')
            )
            ->join('facturas', 'facturas.pedido_id', '=', 'pedidos.id')
            ->where('facturas.estado', 'activa')
            ->whereNotNull('pedidos.mesero_id');

        if ($request->filled('fecha_especifica')) {
            $query->whereDate('facturas.created_at', $fecha_especifica);
        } else {
            if ($request->filled('fecha_desde')) {
                $query->whereDate('facturas.created_at', '>=', $fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('facturas.created_at', '<=', $fecha_hasta);
            }
        }

        $meseros = $query->groupBy('pedidos.mesero_id')
            ->orderBy('total_ventas', 'desc')
            ->with('mesero')
            ->get();

        $totalPedidos = $meseros->sum('total_pedidos');
        $totalVentas = $meseros->sum('total_ventas');

        return view('admin.reportes.meseros', compact(
            'meseros',
            'fecha_desde',
            'fecha_hasta',
            'fecha_especifica',
            'totalPedidos',
            'totalVentas'
        ));
    }

    /**
     * Gráficos Estadísticos e históricos de ventas por meses.
     */
    public function graficos(Request $request)
    {
        $anio = $request->input('anio', date('Y'));

        // Obtener ventas agrupadas por mes para el año seleccionado
        $ventasDb = Factura::select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('SUM(monto_pagado) as total_ventas'),
                DB::raw('COUNT(id) as transacciones')
            )
            ->where('estado', 'activa')
            ->whereYear('created_at', $anio)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('mes', 'asc')
            ->get()
            ->keyBy('mes');

        // Mapear los 12 meses asegurando que se muestren todos
        $mesesNombres = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $datosMensuales = [];
        $totalAnual = 0;
        $totalTransaccionesAnual = 0;

        foreach ($mesesNombres as $num => $nombre) {
            $totalMes = isset($ventasDb[$num]) ? floatval($ventasDb[$num]->total_ventas) : 0;
            $transaccionesMes = isset($ventasDb[$num]) ? intval($ventasDb[$num]->transacciones) : 0;
            
            $datosMensuales[] = [
                'numero' => $num,
                'nombre' => $nombre,
                'ventas' => $totalMes,
                'transacciones' => $transaccionesMes
            ];

            $totalAnual += $totalMes;
            $totalTransaccionesAnual += $transaccionesMes;
        }

        // Métodos de pago
        $metodosPago = Factura::select(
                'metodo_pago',
                DB::raw('SUM(monto_pagado) as total'),
                DB::raw('COUNT(id) as cantidad')
            )
            ->where('estado', 'activa')
            ->whereYear('created_at', $anio)
            ->groupBy('metodo_pago')
            ->get();

        // Años disponibles en las facturas para el filtro
        $aniosDisponibles = Factura::select(DB::raw('YEAR(created_at) as anio'))
            ->groupBy('anio')
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        if ($aniosDisponibles->isEmpty()) {
            $aniosDisponibles = collect([date('Y')]);
        }

        return view('admin.reportes.graficos', compact(
            'datosMensuales',
            'totalAnual',
            'totalTransaccionesAnual',
            'metodosPago',
            'anio',
            'aniosDisponibles'
        ));
    }

    /**
     * Reporte de Stock crítico y alertas de inventario.
     */
    public function stockCritico(Request $request)
    {
        $categoria_id = $request->input('categoria_id');

        $query = Producto::with('categoria')->where('usa_inventario', true);

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $categoria_id);
        }

        // Obtener productos ordenados por stock ascendente
        $productos = $query->orderBy('stock', 'asc')->get();

        // Totales generales para los KPIs (basados en todos los productos con inventario, sin filtrar por categoría para consistencia)
        $totalMonitoreados = Producto::where('usa_inventario', true)->count();
        $totalAgotados = Producto::where('usa_inventario', true)->where('stock', 0)->count();
        $totalCriticos = Producto::where('usa_inventario', true)->where('stock', '>', 0)->where('stock', '<=', 5)->count();

        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.reportes.stock_critico', compact(
            'productos',
            'categorias',
            'categoria_id',
            'totalMonitoreados',
            'totalAgotados',
            'totalCriticos'
        ));
    }

    /**
     * Reporte de Rentabilidad y Utilidades.
     */
    public function rentabilidad(Request $request)
    {
        $fecha_desde = $request->input('fecha_desde', Carbon::now()->startOfMonth()->toDateString());
        $fecha_hasta = $request->input('fecha_hasta', Carbon::now()->toDateString());
        $fecha_especifica = $request->input('fecha_especifica');
        $categoria_id = $request->input('categoria_id');

        $query = PedidoDetalle::select(
                'pedido_detalles.producto_id',
                'pedido_detalles.precio_unitario',
                DB::raw('SUM(pedido_detalles.cantidad) as total_cantidad')
            )
            ->join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->join('facturas', 'facturas.pedido_id', '=', 'pedidos.id')
            ->join('productos', 'pedido_detalles.producto_id', '=', 'productos.id')
            ->where('facturas.estado', 'activa');

        if ($request->filled('fecha_especifica')) {
            $query->whereDate('facturas.created_at', $fecha_especifica);
        } else {
            if ($request->filled('fecha_desde')) {
                $query->whereDate('facturas.created_at', '>=', $fecha_desde);
            }
            if ($request->filled('fecha_hasta')) {
                $query->whereDate('facturas.created_at', '<=', $fecha_hasta);
            }
        }

        if ($request->filled('categoria_id')) {
            $query->where('productos.categoria_id', $categoria_id);
        }

        $detalles = $query->groupBy('pedido_detalles.producto_id', 'pedido_detalles.precio_unitario')
            ->with('producto.categoria')
            ->get();

        $reporte = [];
        $totalVentas = 0;
        $totalCosto = 0;

        foreach ($detalles as $det) {
            if (!$det->producto) continue;

            $cant = $det->total_cantidad;
            $precio = floatval($det->precio_unitario);
            $p2 = floatval($det->producto->precio_2);
            $p3 = floatval($det->producto->precio_3);

            // Determinar costo unitario
            $costoUnit = 0;
            if ($p2 > 0 && abs($precio - $p2) < 0.01) {
                $costoUnit = floatval($det->producto->costo_2 ?? 0);
            } elseif ($p3 > 0 && abs($precio - $p3) < 0.01) {
                $costoUnit = floatval($det->producto->costo_3 ?? 0);
            } else {
                $costoUnit = floatval($det->producto->costo ?? 0);
            }

            $ingresos = $precio * $cant;
            $costoTotal = $costoUnit * $cant;
            $utilidad = $ingresos - $costoTotal;

            $totalVentas += $ingresos;
            $totalCosto += $costoTotal;

            $reporte[] = [
                'producto' => $det->producto,
                'nombre_mostrar' => $det->producto->nombre . ($p2 > 0 && abs($precio - $p2) < 0.01 ? ' (' . ($det->producto->precio_2_nombre ?: 'Opción 2') . ')' : ($p3 > 0 && abs($precio - $p3) < 0.01 ? ' (' . ($det->producto->precio_3_nombre ?: 'Opción 3') . ')' : ($det->producto->precio_nombre ? ' (' . $det->producto->precio_nombre . ')' : ''))),
                'precio' => $precio,
                'costo' => $costoUnit,
                'shadow_costo' => $costoUnit, // cost holder
                'cantidad' => $cant,
                'ingresos' => $ingresos,
                'costo_total' => $costoTotal,
                'utilidad' => $utilidad,
            ];
        }

        // Ordenar reporte por utilidad de forma descendente
        usort($reporte, function($a, $b) {
            return $b['utilidad'] <=> $a['utilidad'];
        });

        $totalUtilidad = $totalVentas - $totalCosto;
        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.reportes.rentabilidad', compact(
            'reporte',
            'fecha_desde',
            'fecha_hasta',
            'fecha_especifica',
            'totalVentas',
            'totalCosto',
            'totalUtilidad',
            'categorias',
            'categoria_id'
        ));
    }
}
