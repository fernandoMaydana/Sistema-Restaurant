<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Compra;
use App\Models\PedidoDetalle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener categorías objetivo en el orden deseado
        $categoryNames = ['Refrescos', 'Jugos', 'Cerveza', 'Bebidas'];
        $categoryIds = Categoria::whereIn('nombre', $categoryNames)->pluck('id')->toArray();

        // 2. Lista de productos habilitados para stock de estas categorías
        //    Ordenados por categoría (orden personalizado) y luego por nombre
        $productosList = Producto::with('categoria')
            ->whereIn('categoria_id', $categoryIds)
            ->where('usa_inventario', true)
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->orderByRaw("FIELD(categorias.nombre, 'Refrescos', 'Jugos', 'Cerveza', 'Bebidas')")
            ->orderBy('productos.nombre')
            ->select('productos.*')
            ->get();

        // 3. Filtros
        $mes = $request->input('mes', Carbon::now()->format('Y-m')); // Formato YYYY-MM
        $productoId = $request->input('producto_id');

        // Determinar qué productos calcular
        if ($productoId) {
            $targetProducts = Producto::where('id', $productoId)->get();
        } else {
            $targetProducts = $productosList;
        }

        $targetProductIds = $targetProducts->pluck('id')->toArray();

        // Si no hay productos habilitados, retornar vacío
        if (empty($targetProductIds)) {
            $matrix = [];
            $activeDates = [];
            $totalStockInicial = 0;
            $totalCompras = 0;
            $totalVentas = 0;
            $totalStockFinal = 0;
            
            $mesesFiltro = [];
            for ($i = 0; $i < 12; $i++) {
                $m = Carbon::now()->subMonths($i);
                $mesesFiltro[$m->format('Y-m')] = ucfirst($m->translatedFormat('F Y'));
            }

            return view('admin.stock.index', compact(
                'productosList', 'mes', 'productoId', 'matrix', 'activeDates', 'mesesFiltro',
                'totalStockInicial', 'totalCompras', 'totalVentas', 'totalStockFinal'
            ));
        }

        // 4. Calcular rango de fechas del mes
        $selectedMonth = Carbon::parse($mes . '-01');
        $daysInMonth = $selectedMonth->daysInMonth;
        $startOfMonth = $selectedMonth->toDateString();
        $today = Carbon::now()->toDateString();

        // Generar lista de fechas activas del mes (cronológico)
        $activeDates = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDayDate = Carbon::parse($mes . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));
            $dString = $currentDayDate->toDateString();

            // Evitar mostrar días futuros si es el mes actual
            if ($dString > $today) {
                break;
            }
            $activeDates[] = [
                'date_str' => $dString,
                'formatted' => $currentDayDate->format('d/m'), // Formato corto para no sobrecargar el header
                'day' => $day
            ];
        }

        // 5. Consultar ventas y compras desde el inicio del mes seleccionado hasta HOY agrupadas por producto y fecha
        $salesData = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->join('facturas', 'facturas.pedido_id', '=', 'pedidos.id')
            ->where('facturas.estado', 'activa')
            ->whereIn('pedido_detalles.producto_id', $targetProductIds)
            ->whereDate('facturas.created_at', '>=', $startOfMonth)
            ->select(
                'pedido_detalles.producto_id',
                DB::raw('DATE(facturas.created_at) as fecha'),
                DB::raw('SUM(pedido_detalles.cantidad) as qty')
            )
            ->groupBy('pedido_detalles.producto_id', DB::raw('DATE(facturas.created_at)'))
            ->get();

        $purchasesData = Compra::whereIn('producto_id', $targetProductIds)
            ->whereDate('created_at', '>=', $startOfMonth)
            ->select(
                'producto_id',
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('SUM(cantidad) as qty')
            )
            ->groupBy('producto_id', DB::raw('DATE(created_at)'))
            ->get();

        // Mapear ventas y compras por producto y fecha
        $salesMap = [];
        foreach ($salesData as $item) {
            $salesMap[$item->producto_id][$item->fecha] = intval($item->qty);
        }

        $purchasesMap = [];
        foreach ($purchasesData as $item) {
            $purchasesMap[$item->producto_id][$item->fecha] = intval($item->qty);
        }

        // Totales históricos para cada producto
        $productTotals = [];
        foreach ($targetProducts as $prod) {
            $pId = $prod->id;
            $salesP = isset($salesMap[$pId]) ? array_sum($salesMap[$pId]) : 0;
            $purchasesP = isset($purchasesMap[$pId]) ? array_sum($purchasesMap[$pId]) : 0;

            $productTotals[$pId] = [
                'total_sales' => $salesP,
                'total_purchases' => $purchasesP,
                'current_stock' => $prod->stock
            ];
        }

        // 6. Generar la matriz de productos y su evolución diaria
        $matrix = [];
        foreach ($targetProducts as $prod) {
            $pId = $prod->id;
            
            $totalSalesP = $productTotals[$pId]['total_sales'];
            $totalPurchasesP = $productTotals[$pId]['total_purchases'];
            $currentStockP = $productTotals[$pId]['current_stock'];
            
            $accumulatedSalesBeforeD = 0;
            $accumulatedPurchasesBeforeD = 0;
            
            $productHistory = [];
            foreach ($activeDates as $dateInfo) {
                $dString = $dateInfo['date_str'];
                
                $salesOnD = isset($salesMap[$pId][$dString]) ? $salesMap[$pId][$dString] : 0;
                $purchasesOnD = isset($purchasesMap[$pId][$dString]) ? $purchasesMap[$pId][$dString] : 0;
                
                $salesFromDToNow = $totalSalesP - $accumulatedSalesBeforeD;
                $purchasesFromDToNow = $totalPurchasesP - $accumulatedPurchasesBeforeD;
                
                $stockInicialD = $currentStockP + $salesFromDToNow - $purchasesFromDToNow;
                $stockFinalD = $stockInicialD + $purchasesOnD - $salesOnD;
                
                $productHistory[$dString] = [
                    'stock_inicial' => $stockInicialD,
                    'compras' => $purchasesOnD,
                    'ventas' => $salesOnD,
                    'stock_final' => $stockFinalD
                ];
                
                $accumulatedSalesBeforeD += $salesOnD;
                $accumulatedPurchasesBeforeD += $purchasesOnD;
            }
            
            $matrix[] = [
                'producto' => $prod,
                'history' => $productHistory
            ];
        }

        // Totales globales para las tarjetas KPI (usando la matriz y fechas activas)
        $totalStockInicial = 0;
        $totalCompras = 0;
        $totalVentas = 0;
        $totalStockFinal = 0;

        if (count($activeDates) > 0) {
            $firstDateStr = $activeDates[0]['date_str'];
            $lastDateStr = $activeDates[count($activeDates) - 1]['date_str'];
            
            foreach ($matrix as $row) {
                $totalStockInicial += $row['history'][$firstDateStr]['stock_inicial'] ?? 0;
                $totalStockFinal += $row['history'][$lastDateStr]['stock_final'] ?? 0;
                
                foreach ($row['history'] as $dayData) {
                    $totalCompras += $dayData['compras'];
                    $totalVentas += $dayData['ventas'];
                }
            }
        }

        // Generar lista de meses para el filtro
        $mesesFiltro = [];
        for ($i = 0; $i < 12; $i++) {
            $m = Carbon::now()->subMonths($i);
            $mesesFiltro[$m->format('Y-m')] = ucfirst($m->translatedFormat('F Y'));
        }

        return view('admin.stock.index', compact(
            'productosList', 'mes', 'productoId', 'matrix', 'activeDates', 'mesesFiltro',
            'totalStockInicial', 'totalCompras', 'totalVentas', 'totalStockFinal'
        ));
    }

    public function registrarCompra(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        if (!$producto->usa_inventario) {
            return redirect()->back()->with('error', 'El producto seleccionado no maneja inventario.');
        }

        // 1. Registrar compra
        Compra::create([
            'producto_id' => $producto->id,
            'cantidad' => $request->cantidad,
        ]);

        // 2. Incrementar stock actual
        $producto->increment('stock', $request->cantidad);

        return redirect()->back()->with('success', '✅ Compra registrada. Se sumaron ' . $request->cantidad . ' unidades a "' . $producto->nombre . '".');
    }
}
