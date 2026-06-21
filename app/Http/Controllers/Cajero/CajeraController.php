<?php

namespace App\Http\Controllers\Cajero;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Factura;
use App\Models\Mesa;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\CajaSesion;
use App\Models\Gasto;
use App\Models\ConsumoPersonal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Exception;

class CajeraController extends Controller
{
    /**
     * Verifica si el cajero tiene una sesión de caja abierta para hoy.
     */
    private function obtenerCajaAbierta()
    {
        return CajaSesion::where('user_id', auth()->id())
            ->where('estado', 'abierta')
            ->whereDate('fecha_apertura', today())
            ->first();
    }

    /**
     * Pantalla de bienvenida / Apertura de Caja.
     */
    public function bienvenida()
    {
        // Si ya tiene una caja abierta, mandarlo al dashboard
        if ($this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.dashboard');
        }

        return view('cajero.bienvenida');
    }

    /**
     * Procesa la apertura de caja con el monto inicial.
     */
    public function abrirCaja(Request $request)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0',
        ]);

        CajaSesion::create([
            'user_id' => auth()->id(),
            'monto_inicial' => $request->monto_inicial,
            'estado' => 'abierta',
            'fecha_apertura' => now(),
        ]);

        return redirect()->route('cajero.dashboard')
            ->with('success', "✅ Caja iniciada con Bs " . number_format($request->monto_inicial, 2));
    }

    /**
     * Panel principal del cajero.
     * - Sección 1: Pedidos con items pendientes de imprimir (comanda a cocina).
     * - Sección 2: Mesas que han solicitado su cuenta (cobrar).
     */
    public function dashboard()
    {
        $caja = $this->obtenerCajaAbierta();

        // Pedidos que tienen items con estado_comanda = 'pendiente'
        $pedidosConComandaPendiente = Pedido::with(['mesa', 'mesero', 'detalles.producto'])
            ->whereIn('estado', ['abierto', 'cuenta_solicitada'])
            ->whereHas('detalles', fn($q) => $q->where('estado_comanda', 'pendiente'))
            ->orderBy('updated_at', 'asc')
            ->get();
 
        // Pedidos con estado cuenta_solicitada (listo para cobrar)
        $mesasParaCobrar = Pedido::with(['mesa', 'mesero', 'detalles.producto.categoria'])
            ->where('estado', 'cuenta_solicitada')
            ->orderBy('updated_at', 'asc')
            ->get();
 
        // --- NUEVO: Resumen de ventas e historial ---
        $totalVentasHoy = Factura::where('estado', 'activa')->whereDate('created_at', today())->sum('monto_pagado');
        $facturasHoy = Factura::with(['pedido.mesa', 'cajero'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();
 
        if ($caja) {
            $gastosHoy = $caja->gastos()->orderBy('created_at', 'desc')->get();
            $totalGastosHoy = $gastosHoy->sum('monto');
        } else {
            $gastosHoy = collect();
            $totalGastosHoy = 0;
        }

        return view('cajero.dashboard', compact(
            'pedidosConComandaPendiente', 
            'mesasParaCobrar', 
            'totalVentasHoy', 
            'facturasHoy',
            'gastosHoy',
            'totalGastosHoy',
            'caja'
        ));
    }
 
    /**
     * Vista previa del cierre de caja.
     */
    public function cierrePreview()
    {
        $caja = $this->obtenerCajaAbierta();
        if (!$caja) return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para acceder a esta sección.');

        // Calcular total recaudado en esta sesión
        $facturas = Factura::where('estado', 'activa')
            ->where('cajero_id', auth()->id())
            ->where('created_at', '>=', $caja->fecha_apertura)
            ->get();

        $totalVentas = $facturas->sum('monto_pagado');
            
        $ventasPorMetodo = [
            'efectivo' => 0,
            'qr' => 0,
            'tarjeta' => 0,
            'transferencia' => 0
        ];
        
        foreach ($facturas as $factura) {
            $metodo = $factura->metodo_pago;
            if (isset($ventasPorMetodo[$metodo])) {
                $ventasPorMetodo[$metodo] += $factura->monto_pagado;
            }
        }
            
        $totalGastos = $caja->gastos()->sum('monto');

        return view('cajero.cierre_preview', compact('caja', 'totalVentas', 'totalGastos', 'ventasPorMetodo'));
    }

    /**
     * Procesa el cierre definitivo y genera el reporte.
     */
    public function confirmarCierre(Request $request)
    {
        $caja = $this->obtenerCajaAbierta();
        if (!$caja) return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para acceder a esta sección.');

        // 1. Obtener todas las facturas de esta sesión
        $facturas = Factura::with('pedido.detalles.producto.categoria')
            ->where('estado', 'activa')
            ->where('cajero_id', auth()->id())
            ->where('created_at', '>=', $caja->fecha_apertura)
            ->get();

        $totalVentas = $facturas->sum('monto_pagado');
        
        $ventasPorMetodo = [
            'efectivo' => 0,
            'qr' => 0,
            'tarjeta' => 0,
            'transferencia' => 0
        ];
        
        foreach ($facturas as $factura) {
            $metodo = $factura->metodo_pago;
            if (isset($ventasPorMetodo[$metodo])) {
                $ventasPorMetodo[$metodo] += $factura->monto_pagado;
            }
        }
        
        // 2. Agrupar productos vendidos por categoría
        $resumenProductos = [];
        foreach ($facturas as $factura) {
            foreach ($factura->pedido->detalles as $detalle) {
                $catName = $detalle->producto->categoria->nombre ?? 'Sin Categoría';
                $prodName = $detalle->nombre_mostrar;
                
                if (!isset($resumenProductos[$catName])) {
                    $resumenProductos[$catName] = [];
                }

                if (!isset($resumenProductos[$catName][$prodName])) {
                    $resumenProductos[$catName][$prodName] = 0;
                }

                $resumenProductos[$catName][$prodName] += $detalle->cantidad;
            }
        }
        
        $gastos = $caja->gastos;
        $totalGastos = $gastos->sum('monto');

        // 3. Actualizar la sesión de caja
        // El monto final físico es: Inicial + Ventas Efectivo - Gastos
        $caja->update([
            'monto_final' => ($caja->monto_inicial + $ventasPorMetodo['efectivo']) - $totalGastos,
            'total_ventas' => $totalVentas,
            'fecha_cierre' => now(),
            'estado' => 'cerrada',
        ]);

        return view('cajero.cierre_reporte', [
            'caja' => $caja,
            'resumen' => $resumenProductos,
            'totalRecaudado' => $totalVentas,
            'ventasPorMetodo' => $ventasPorMetodo,
            'gastos' => $gastos,
            'totalGastos' => $totalGastos
        ]);
    }

    /**
     * Registra un gasto en la sesión actual.
     */
    public function registrarGasto(Request $request)
    {
        $caja = $this->obtenerCajaAbierta();
        if (!$caja) return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para acceder a esta sección.');

        $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'descripcion' => 'required|string|max:255',
        ]);

        Gasto::create([
            'caja_sesion_id' => $caja->id,
            'monto' => $request->monto,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('cajero.dashboard')->with('success', '✅ Gasto registrado correctamente.');
    }

    /**
     * Salón: cuadrícula visual de todas las mesas para el cajero.
     */
    public function salon()
    {
        $caja = $this->obtenerCajaAbierta();
        if (!$caja) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para acceder al Salón de Mesas.');
        }

        $mesas = Mesa::withCount(['pedidos as tiene_pedido_activo' => function ($q) {
            $q->whereIn('estado', ['abierto', 'cuenta_solicitada']);
        }])->with(['pedidos' => function ($q) {
            $q->whereIn('estado', ['abierto', 'cuenta_solicitada'])->with('detalles.producto')->latest();
        }])->orderBy('numero')->get();
 
        return view('cajero.salon', compact('mesas'));
    }

    /**
     * Devuelve el estado actual del salón para actualizaciones en tiempo real (Polling).
     */
    public function getSalonStatus()
    {
        $ultimoCambioPedido = Pedido::whereIn('estado', ['abierto', 'cuenta_solicitada'])
            ->max('updated_at');

        $totalOcupadas = Pedido::whereIn('estado', ['abierto', 'cuenta_solicitada'])->count();

        // Generamos un "hash" o firma del estado actual
        $signature = md5($ultimoCambioPedido . $totalOcupadas);

        return response()->json([
            'signature' => $signature
        ]);
    }
 
    /**
     * Reutilización de la lógica para ver mesa y agregar productos.
     */
    public function verMesa($mesa_id)
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para acceder a esta sección.');
        }

        $mesa = Mesa::findOrFail($mesa_id);
        $pedido = Pedido::with(['detalles.producto'])
            ->where('mesa_id', $mesa_id)
            ->whereIn('estado', ['abierto', 'cuenta_solicitada'])
            ->first();
 
        $categorias = Categoria::with(['productos' => function ($q) {
            $q->where('disponible', true)->orderBy('id');
        }])->get()->filter(fn($c) => $c->productos->count() > 0);

        $combos = \App\Models\Combo::activos()->with('items.producto')->get();
 
        return view('cajero.mesa_update', compact('mesa', 'pedido', 'categorias', 'combos'));
    }

    /**
     * Reutilización de la lógica para registrar items.
     */
    public function registrarItems(Request $request, $mesa_id)
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para realizar esta acción.');
        }

        $request->validate([
            'items'                        => 'required|array|min:1',
            'items.*.producto_id'          => 'required|exists:productos,id',
            'items.*.cantidad'             => 'required|integer|min:1',
            'items.*.precio_seleccionado'  => 'required|numeric|min:0',
            'items.*.notas'                => 'nullable|string|max:255',
        ]);
 
        $mesa = Mesa::findOrFail($mesa_id);
 
        DB::transaction(function () use ($request, $mesa) {
            $pedido = Pedido::firstOrCreate(
                ['mesa_id' => $mesa->id, 'estado' => 'abierto'],
                ['mesero_id' => auth()->id(), 'total' => 0]
            );
 
            if ($mesa->estado === 'libre') {
                $mesa->update(['estado' => 'ocupada']);
            }
 
            foreach ($request->items as $item) {
                $producto = Producto::find($item['producto_id']);
                if ($producto && $producto->usa_inventario) {
                    if ($producto->stock < $item['cantidad']) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'items' => "No hay suficiente stock para: {$producto->nombre}. Disponible: {$producto->stock}."
                        ]);
                    }
                    $producto->decrement('stock', $item['cantidad']);
                }
 
                PedidoDetalle::create([
                    'pedido_id'       => $pedido->id,
                    'producto_id'     => $item['producto_id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_seleccionado'],
                    'estado_comanda'  => 'pendiente',
                    'notas'           => $item['notas'] ?? null,
                ]);
            }
 
            $total = PedidoDetalle::where('pedido_id', $pedido->id)
                ->selectRaw('SUM(cantidad * precio_unitario) as total')
                ->value('total');
 
            $pedido->update(['total' => $total ?? 0]);
        });
 
        return redirect()->route('cajero.salon')
            ->with('success', "✅ Pedido actualizado para Mesa {$mesa->numero}.");
    }

    /**
     * Actualización masiva de pedido (Cajero).
     * Procesa cambios en cantidades existentes, eliminaciones y nuevos items.
     */
    public function actualizarPedido(Request $request, $mesa_id)
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para realizar esta acción.');
        }

        $mesa = Mesa::findOrFail($mesa_id);

        DB::transaction(function () use ($request, $mesa) {
            $pedido = Pedido::where('mesa_id', $mesa->id)
                ->whereIn('estado', ['abierto', 'cuenta_solicitada'])
                ->first();

            if (!$pedido && $request->has('items')) {
                $pedido = Pedido::create([
                    'mesa_id'   => $mesa->id,
                    'mesero_id' => auth()->id(),
                    'estado'    => 'abierto',
                    'total'     => 0
                ]);
                $mesa->update(['estado' => 'ocupada']);
            }

            if (!$pedido) return;

            // 1. Procesar Cambios en Detalles Existentes
            if ($request->has('detalles')) {
                foreach ($request->detalles as $det_id => $data) {
                    $detalle = PedidoDetalle::find($det_id);
                    if (!$detalle) continue;

                    $nuevaCant = intval($data['cantidad']);
                    $diferencia = $nuevaCant - $detalle->cantidad;

                    // SI AUMENTA O DISMINUYE LA CANTIDAD, AJUSTAR STOCK
                    if ($diferencia != 0) {
                        $producto = Producto::find($detalle->producto_id);
                        if ($producto && $producto->usa_inventario) {
                            if ($diferencia > 0 && $producto->stock < $diferencia) {
                                throw \Illuminate\Validation\ValidationException::withMessages([
                                    'detalles' => "No hay suficiente stock para: {$producto->nombre}. Disponible: {$producto->stock}."
                                ]);
                            }
                            $producto->decrement('stock', $diferencia);
                        }
                    }

                    if ($nuevaCant <= 0) {
                        $detalle->delete();
                    } else {
                        $detalle->update(['cantidad' => $nuevaCant]);
                    }
                }
            }

            // 2. Procesar Nuevos Items (Seleccionados en el menú izquierdo)
            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    $producto = Producto::find($item['producto_id']);
                    if ($producto && $producto->usa_inventario) {
                        if ($producto->stock < $item['cantidad']) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'items' => "No hay suficiente stock para: {$producto->nombre}. Disponible: {$producto->stock}."
                            ]);
                        }
                        $producto->decrement('stock', $item['cantidad']);
                    }

                    PedidoDetalle::create([
                        'pedido_id'       => $pedido->id,
                        'producto_id'     => $item['producto_id'],
                        'cantidad'        => $item['cantidad'],
                        'precio_unitario' => $item['precio_seleccionado'],
                        'estado_comanda'  => 'pendiente',
                        'notas'           => $item['notas'] ?? null,
                    ]);
                }
            }

            // 3. Recalcular Total
            $total = PedidoDetalle::where('pedido_id', $pedido->id)
                ->selectRaw('SUM(cantidad * precio_unitario) as total')
                ->value('total');

            // Si el pedido se quedó sin items, liberamos la mesa
            if (!$total || $total <= 0) {
                $pedido->delete();
                $mesa->update(['estado' => 'libre']);
            } else {
                $pedido->update(['total' => $total]);
            }
        });

        return redirect()->route('cajero.salon')
            ->with('success', "✅ Mesa {$mesa->numero} actualizada correctamente.");
    }

    /**
     * Eliminar un producto específico del pedido activo (Solo Cajero).
     */
    public function eliminarItem($id)
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para realizar esta acción.');
        }

        $detalle = PedidoDetalle::findOrFail($id);
        $pedido = Pedido::findOrFail($detalle->pedido_id);
        $mesa_id = $pedido->mesa_id;

        DB::transaction(function () use ($detalle, $pedido) {
            // DEVOLVER STOCK
            $producto = Producto::find($detalle->producto_id);
            if ($producto && $producto->usa_inventario) {
                $producto->increment('stock', $detalle->cantidad);
            }

            $detalle->delete();

            // Recalcular total
            $total = PedidoDetalle::where('pedido_id', $pedido->id)
                ->selectRaw('SUM(cantidad * precio_unitario) as total')
                ->value('total');

            $pedido->update(['total' => $total ?? 0]);
        });

        return redirect()->route('cajero.mesa', $mesa_id)
            ->with('success', 'Producto eliminado de la cuenta.');
    }

    /**
     * Anula un pedido completo, devuelve el stock al inventario y libera la mesa (Solo Cajero).
     */
    public function anularPedido($pedido_id)
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para realizar esta acción.');
        }

        $pedido = Pedido::with('detalles')->findOrFail($pedido_id);
        $mesa = Mesa::findOrFail($pedido->mesa_id);

        DB::transaction(function () use ($pedido, $mesa) {
            // 1. Devolver stock al inventario
            foreach ($pedido->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto && $producto->usa_inventario) {
                    $producto->increment('stock', $detalle->cantidad);
                }
            }

            // 2. Eliminar detalles del pedido
            $pedido->detalles()->delete();

            // 3. Eliminar el pedido
            $pedido->delete();

            // 4. Liberar la mesa
            $mesa->update(['estado' => 'libre']);
        });

        return redirect()->route('cajero.salon')
            ->with('success', '✅ El pedido ha sido anulado y la mesa ha sido liberada correctamente.');
    }

    /**
     * Vista de la comanda (items pendientes de una mesa → imprimir para cocina).
     */
    public function verComanda($pedido_id)
    {
        $pedido = Pedido::with(['mesa', 'mesero', 'detalles' => function ($q) {
            $q->where('estado_comanda', 'pendiente')->with('producto');
        }])->findOrFail($pedido_id);

        return view('cajero.comanda', compact('pedido'));
    }
    /**
     * Marca los items pendientes como 'impreso' (fueron enviados a cocina).
     */
    public function imprimirComanda($pedido_id)
    {
        $pedido = Pedido::findOrFail($pedido_id);

        PedidoDetalle::where('pedido_id', $pedido->id)
            ->where('estado_comanda', 'pendiente')
            ->update(['estado_comanda' => 'impreso']);

        return redirect()->route('cajero.dashboard')
            ->with('success', "Comanda de Mesa {$pedido->mesa->numero} marcada como impresa.");
    }

    private function obtenerColumnasImpresora()
    {
        $width = intval(env('PRINTER_PAPER_WIDTH', 58));
        return $width === 80 ? 48 : 32;
    }

    /**
     * API: Verifica el estado de conexión de la ticketera física.
     */
    public function checkPrinterStatus()
    {
        try {
            $nombreImpresora = env('PRINTER_NAME', 'EPSON_TM');
            $connector = new WindowsPrintConnector($nombreImpresora);
            $printer = new Printer($connector);
            $printer->close();
            
            return response()->json([
                'success' => true,
                'connected' => true,
                'printer_name' => $nombreImpresora
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'connected' => false,
                'printer_name' => env('PRINTER_NAME', 'EPSON_TM'),
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * API: Imprime la comanda directamente usando ESC/POS
     */
    public function apiImprimirComanda($pedido_id)
    {
        $pedido = Pedido::with(['mesa', 'mesero', 'detalles' => function ($q) {
            $q->where('estado_comanda', 'pendiente')->with('producto');
        }])->findOrFail($pedido_id);

        $esReimpresion = false;

        if ($pedido->detalles->isEmpty()) {
            // Si no hay pendientes, cargamos todos para permitir la reimpresión
            $pedido = Pedido::with(['mesa', 'mesero', 'detalles.producto'])->findOrFail($pedido_id);
            if ($pedido->detalles->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No hay ítems en esta mesa.']);
            }
            $esReimpresion = true;
        }

        try {
            $nombreImpresora = env('PRINTER_NAME', 'EPSON_TM');
            $connector = new WindowsPrintConnector($nombreImpresora);
            $printer = new Printer($connector);

            $cols = $this->obtenerColumnasImpresora();

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            if ($esReimpresion) {
                $printer->setTextSize(1, 1);
                $printer->text("*** REIMPRESION ***\n");
            }
            $printer->setTextSize(4, 4); // Más grande y llamativo
            if ($pedido->mesa->es_para_llevar) {
                $printer->text("LLEVAR " . $pedido->mesa->numero . "\n");
            } else {
                $printer->text("MESA " . $pedido->mesa->numero . "\n");
            }
            $printer->setTextSize(1, 1);
            $printer->text(str_repeat("-", $cols) . "\n");
            $printer->text("Mesero: " . strtoupper($pedido->mesero->name) . "\n");
            $printer->text("Fecha: " . now()->format('d/m/Y H:i') . "\n");
            $printer->text(str_repeat("-", $cols) . "\n\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 2); // 1 de ancho y 2 de alto para legibilidad en cocina
            
            foreach ($pedido->detalles as $det) {
                $prefix = $det->cantidad . "  ";
                $indent = str_repeat(" ", strlen($prefix));
                $wrapWidth = $cols - strlen($prefix);
                
                $nombreCompleto = strtoupper($det->nombre_mostrar);
                $lineasNombre = explode("\n", wordwrap($nombreCompleto, $wrapWidth, "\n", true));
                
                $primeraLinea = array_shift($lineasNombre);
                $printer->text($prefix . $primeraLinea . "\n");
                
                foreach ($lineasNombre as $lineaExtra) {
                    $printer->text($indent . $lineaExtra . "\n");
                }

                // Imprimir notas si existen
                if ($det->notes || $det->notas) {
                    $notaTexto = "* NOTA: " . strtoupper($det->notes ?? $det->notas);
                    $lineasNota = explode("\n", wordwrap($notaTexto, $wrapWidth, "\n", true));
                    foreach ($lineasNota as $lineaNota) {
                        $printer->text($indent . $lineaNota . "\n");
                    }
                }
            }

            $printer->text("\n");
            $printer->setTextSize(1, 1);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text(str_repeat("-", $cols) . "\n");
            $printer->text("#" . $pedido->id . " - COPIA COCINA\n");
            $printer->text("*** RESTO-SISTEMA ***\n\n\n");
            $printer->cut();
            $printer->close();

            // Marcar como impreso
            PedidoDetalle::where('pedido_id', $pedido->id)
                ->where('estado_comanda', 'pendiente')
                ->update(['estado_comanda' => 'impreso']);

            return response()->json(['success' => true, 'message' => 'Comanda enviada a impresora.']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ No se pudo establecer conexión con la ticketera. Verifica que la impresora esté encendida, conectada y que el nombre en el archivo .env sea correcto. Detalles: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Imprime la pre-cuenta directamente usando ESC/POS
     */
    public function apiImprimirCuenta($pedido_id)
    {
        $pedido = Pedido::with(['mesa', 'mesero', 'detalles.producto'])->findOrFail($pedido_id);

        try {
            $nombreImpresora = env('PRINTER_NAME', 'EPSON_TM');
            $connector = new WindowsPrintConnector($nombreImpresora);
            $printer = new Printer($connector);

            $cols = $this->obtenerColumnasImpresora();

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("RESTAURANTE\n");
            $printer->setTextSize(1, 1);
            $printer->text("DETALLE DE CONSUMO\n");
            $printer->setTextSize(2, 2);
            if ($pedido->mesa->es_para_llevar) {
                $printer->text("LLEVAR " . $pedido->mesa->numero . "\n");
            } else {
                $printer->text("MESA " . $pedido->mesa->numero . "\n");
            }
            $printer->setTextSize(1, 1);
            $printer->text(str_repeat("-", $cols) . "\n");
            $printer->text("Mesero: " . strtoupper($pedido->mesero->name) . "\n");
            $printer->text("Fecha: " . now()->format('d/m/Y H:i') . "\n");
            $printer->text(str_repeat("-", $cols) . "\n\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            
            if ($cols === 48) {
                $wCant = 5;
                $wPreu = 7;
                $wSubt = 8;
            } else {
                $wCant = 4;
                $wPreu = 5;
                $wSubt = 7;
            }
            $wDetalle = $cols - $wCant - $wPreu - $wSubt;

            // Encabezados de columnas
            $hCant = str_pad("CANT", $wCant, " ", STR_PAD_RIGHT);
            $hDetalle = str_pad("DETALLE", $wDetalle, " ", STR_PAD_RIGHT);
            $hPreu = str_pad("PREU", $wPreu, " ", STR_PAD_LEFT);
            $hSubt = str_pad("SUBT", $wSubt, " ", STR_PAD_LEFT);
            
            $printer->text($hCant . $hDetalle . $hPreu . $hSubt . "\n");
            $printer->text(str_repeat("-", $cols) . "\n");

            foreach ($pedido->detalles as $det) {
                $cantStr = str_pad($det->cantidad, $wCant, " ", STR_PAD_RIGHT);
                
                $precioVal = number_format($det->precio_unitario, 1, '.', '');
                $preuStr = str_pad($precioVal, $wPreu, " ", STR_PAD_LEFT);
                
                $subtVal = number_format($det->cantidad * $det->precio_unitario, 2, '.', '');
                $subtStr = str_pad($subtVal, $wSubt, " ", STR_PAD_LEFT);
                
                $nombre = strtoupper($det->nombre_mostrar);
                
                $lineasNombre = explode("\n", wordwrap($nombre, $wDetalle, "\n", true));
                $primeraLinea = array_shift($lineasNombre);
                
                $printer->text($cantStr . str_pad($primeraLinea, $wDetalle, " ", STR_PAD_RIGHT) . $preuStr . $subtStr . "\n");
                
                $spacesBefore = str_repeat(" ", $wCant);
                foreach ($lineasNombre as $lineaExtra) {
                    $printer->text($spacesBefore . str_pad($lineaExtra, $wDetalle, " ", STR_PAD_RIGHT) . str_repeat(" ", $wPreu + $wSubt) . "\n");
                }
            }

            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setTextSize(2, 2);
            $printer->text("TOTAL: Bs " . number_format($pedido->total, 2) . "\n");
            $printer->setTextSize(1, 1);

            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text(str_repeat("-", $cols) . "\n");
            $printer->text("GRACIAS POR SU VISITA\n");
            $printer->text("#" . $pedido->id . " - PRE-CUENTA\n");
            $printer->text("*** RESTO-SISTEMA ***\n\n\n");
            $printer->cut();
            $printer->close();

            return response()->json(['success' => true, 'message' => 'Cuenta enviada a impresora.']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ No se pudo establecer conexión con la ticketera. Verifica que la impresora esté encendida, conectada y que el nombre en el archivo .env sea correcto. Detalles: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Imprime la factura final directamente usando ESC/POS
     */
    public function apiImprimirFactura($factura_id)
    {
        $factura = Factura::with(['pedido.detalles.producto', 'pedido.mesa', 'cajero'])->findOrFail($factura_id);

        try {
            $nombreImpresora = env('PRINTER_NAME', 'EPSON_TM');
            $connector = new WindowsPrintConnector($nombreImpresora);
            $printer = new Printer($connector);

            $cols = $this->obtenerColumnasImpresora();

            // Cabecera
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("RESTAURANTE PROFESIONAL\n");
            $printer->setEmphasis(false);
            $printer->text("SISTEMA DE GESTION GASTRONOMICA\n");
            $printer->setTextSize(2, 2);
            $printer->text("FACTURA\n");
            $printer->setTextSize(1, 1);
            $printer->text(str_repeat("-", $cols) . "\n");
            
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Fecha: " . $factura->created_at->format('d/m/Y H:i:s') . "\n");
            $printer->text("Nro. Factura: #" . str_pad($factura->id, 6, '0', STR_PAD_LEFT) . "\n");
            $printer->text("Cajero: " . strtoupper($factura->cajero->name) . "\n");
            if ($factura->pedido->mesa->es_para_llevar) {
                $printer->text("Pedido: Llevar #" . $factura->pedido->mesa->numero . "\n");
            } else {
                $printer->text("Mesa: " . $factura->pedido->mesa->numero . "\n");
            }
            $printer->text(str_repeat("-", $cols) . "\n");
            $printer->text("CLIENTE: " . strtoupper($factura->cliente_nombre ?? 'CONSUMIDOR FINAL') . "\n");
            $printer->text("NIT/CI: " . ($factura->cliente_nit_ci ?? '-----------') . "\n");
            $printer->text(str_repeat("-", $cols) . "\n\n");

            // Detalles
            foreach ($factura->pedido->detalles as $det) {
                $cant = $det->cantidad . "x ";
                $precio = "Bs " . number_format($det->cantidad * $det->precio_unitario, 2);
                
                $nombreMaxLen = max(10, $cols - strlen($cant) - strlen($precio) - 2);
                $nombre = strtoupper(substr($det->nombre_mostrar, 0, $nombreMaxLen));
                
                $linea_izq = $cant . $nombre;
                $espacios = max(1, $cols - strlen($linea_izq) - strlen($precio));
                $printer->text($linea_izq . str_repeat(" ", $espacios) . $precio . "\n");
            }

            // Totales
            $printer->text("\n" . str_repeat("-", $cols) . "\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);

            $printer->setTextSize(1, 2);
            $printer->text("TOTAL: Bs " . number_format($factura->monto_pagado, 2) . "\n");
            $printer->setTextSize(1, 1);
            
            $entregado = $factura->efectivo_recibido ?? $factura->monto_pagado;
            $cambio = max(0, $entregado - $factura->monto_pagado);

            $printer->text("ENTREGADO: Bs " . number_format($entregado, 2) . "\n");
            $printer->setEmphasis(true);
            $printer->text("CAMBIO: Bs " . number_format($cambio, 2) . "\n");
            $printer->setEmphasis(false);

            // Pie de pagina
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("\n" . str_repeat("-", $cols) . "\n");
            $printer->text("GRACIAS POR SU PREFERENCIA\n");
            $printer->text("PROVEA ESTE TICKET PARA RECLAMOS\n");
            $printer->text("*** RESTO-SISTEMA ***\n\n\n");

            // Abrir cajón y cortar
            $printer->pulse();
            $printer->cut();
            $printer->close();

            return response()->json(['success' => true, 'message' => 'Factura enviada a impresora.']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ No se pudo establecer conexión con la ticketera. Verifica que la impresora esté encendida, conectada y que el nombre en el archivo .env sea correcto. Detalles: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Imprime el reporte de cierre directamente usando ESC/POS
     */
    public function apiImprimirCierre($caja_id)
    {
        $caja = CajaSesion::with('gastos', 'user')->findOrFail($caja_id);
        
        $facturas = Factura::with('pedido.detalles.producto.categoria')
            ->where('estado', 'activa')
            ->where('cajero_id', $caja->user_id)
            ->where('created_at', '>=', $caja->fecha_apertura)
            ->where('created_at', '<=', $caja->fecha_cierre ?? now())
            ->get();
            
        $totalVentas = $facturas->sum('monto_pagado');
        
        $ventasPorMetodo = [
            'efectivo' => 0,
            'qr' => 0,
            'tarjeta' => 0,
            'transferencia' => 0
        ];
        
        foreach ($facturas as $factura) {
            $metodo = $factura->metodo_pago;
            if (isset($ventasPorMetodo[$metodo])) {
                $ventasPorMetodo[$metodo] += $factura->monto_pagado;
            }
        }
        
        $resumenProductos = [];
        foreach ($facturas as $factura) {
            foreach ($factura->pedido->detalles as $detalle) {
                $catName = $detalle->producto->categoria->nombre ?? 'Sin Categoría';
                $prodName = $detalle->nombre_mostrar;
                
                if (!isset($resumenProductos[$catName])) {
                    $resumenProductos[$catName] = [];
                }

                if (!isset($resumenProductos[$catName][$prodName])) {
                    $resumenProductos[$catName][$prodName] = 0;
                }

                $resumenProductos[$catName][$prodName] += $detalle->cantidad;
            }
        }
        
        $gastos = $caja->gastos;
        $totalGastos = $gastos->sum('monto');
        
        try {
            $nombreImpresora = env('PRINTER_NAME', 'EPSON_TM');
            $connector = new WindowsPrintConnector($nombreImpresora);
            $printer = new Printer($connector);

            $cols = $this->obtenerColumnasImpresora();

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("REPORTE DE CIERRE\n");
            $printer->setTextSize(1, 1);
            $printer->text("RESTO-SISTEMA\n");
            $printer->text(str_repeat("-", $cols) . "\n");
            
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Cajero: " . $caja->user->name . "\n");
            $printer->text("Fecha: " . \Carbon\Carbon::parse($caja->fecha_cierre ?? now())->format('d/m/Y H:i') . "\n");
            $printer->text(str_repeat("-", $cols) . "\n\n");

            // Helper para formatear línea izquierda/derecha
            $formatoLinea = function($desc, $monto) use ($cols) {
                $espacios = max(1, $cols - strlen($desc) - strlen($monto));
                return $desc . str_repeat(" ", $espacios) . $monto;
            };

            $printer->text($formatoLinea("Monto Inicial:", "Bs " . number_format($caja->monto_inicial, 2)) . "\n");
            $printer->text($formatoLinea("Ventas Totales:", "Bs " . number_format($totalVentas, 2)) . "\n");
            $printer->text(str_repeat("-", $cols) . "\n");
            $printer->text($formatoLinea("Ventas Efectivo:", "Bs " . number_format($ventasPorMetodo['efectivo'], 2)) . "\n");
            $printer->text($formatoLinea("Ventas QR:", "Bs " . number_format($ventasPorMetodo['qr'], 2)) . "\n");
            $printer->text($formatoLinea("Ventas Tarjeta:", "Bs " . number_format($ventasPorMetodo['tarjeta'], 2)) . "\n");
            $printer->text($formatoLinea("Ventas Transf.:", "Bs " . number_format($ventasPorMetodo['transferencia'], 2)) . "\n");
            
            if ($gastos->count() > 0) {
                $printer->text(str_repeat("-", $cols) . "\n");
                $printer->setEmphasis(true);
                $printer->text("GASTOS (-)\n");
                $printer->setEmphasis(false);
                foreach ($gastos as $gasto) {
                    $descLimit = $cols - 12; // Dejar espacio para monto
                    $desc = substr($gasto->descripcion, 0, $descLimit);
                    $monto = "-Bs " . number_format($gasto->monto, 2);
                    $printer->text($formatoLinea($desc, $monto) . "\n");
                }
                $txtTotalG = "TOTAL GASTOS:";
                $montoTotalG = "-Bs " . number_format($totalGastos, 2);
                $printer->setEmphasis(true);
                $printer->text($formatoLinea($txtTotalG, $montoTotalG) . "\n");
                $printer->setEmphasis(false);
            }

            $printer->text(str_repeat("-", $cols) . "\n");
            $printer->setTextSize(1, 2);
            $txtEfectivo = "EFECTIVO CAJA:";
            $montoEfectivo = "Bs " . number_format($caja->monto_final, 2);
            $printer->text($formatoLinea($txtEfectivo, $montoEfectivo) . "\n");
            $printer->setTextSize(1, 1);
            
            $printer->text("\n" . str_repeat("-", $cols) . "\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("RESUMEN DE PRODUCTOS\n");
            $printer->setEmphasis(false);
            $printer->text(str_repeat("-", $cols) . "\n");
            
            foreach ($resumenProductos as $categoria => $productos) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setEmphasis(true);
                $printer->text("* " . strtoupper($categoria) . " *\n");
                $printer->setEmphasis(false);
                foreach ($productos as $nombre => $cantidad) {
                    $nomLimit = $cols - 6;
                    $nom = substr($nombre, 0, $nomLimit);
                    $cant = "x" . $cantidad;
                    $printer->text($formatoLinea($nom, $cant) . "\n");
                }
                $printer->text("\n");
            }

            $printer->text(str_repeat("-", $cols) . "\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Gracias por su jornada.\n");
            $printer->text("#" . $caja->id . "\n\n\n\n");
            
            $printer->cut();
            $printer->close();

            return response()->json(['success' => true, 'message' => 'Reporte de Cierre enviado a impresora.']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ No se pudo establecer conexión con la ticketera. Verifica que la impresora esté encendida, conectada y que el nombre en el archivo .env sea correcto. Detalles: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Descargar reporte de cierre en PDF
     */
    public function descargarPdfCierre($caja_id)
    {
        $caja = CajaSesion::with('gastos', 'user')->findOrFail($caja_id);
        
        $facturas = Factura::with('pedido.detalles.producto.categoria')
            ->where('estado', 'activa')
            ->where('cajero_id', $caja->user_id)
            ->where('created_at', '>=', $caja->fecha_apertura)
            ->where('created_at', '<=', $caja->fecha_cierre ?? now())
            ->get();
            
        $totalVentas = $facturas->sum('monto_pagado');
        
        $ventasPorMetodo = [
            'efectivo' => 0,
            'qr' => 0,
            'tarjeta' => 0,
            'transferencia' => 0
        ];
        
        foreach ($facturas as $factura) {
            $metodo = $factura->metodo_pago;
            if (isset($ventasPorMetodo[$metodo])) {
                $ventasPorMetodo[$metodo] += $factura->monto_pagado;
            }
        }
        
        $resumenProductos = [];
        foreach ($facturas as $factura) {
            foreach ($factura->pedido->detalles as $detalle) {
                $catName = $detalle->producto->categoria->nombre ?? 'Sin Categoría';
                $prodName = $detalle->nombre_mostrar;
                
                if (!isset($resumenProductos[$catName])) {
                    $resumenProductos[$catName] = [];
                }
 
                if (!isset($resumenProductos[$catName][$prodName])) {
                    $resumenProductos[$catName][$prodName] = 0;
                }
 
                $resumenProductos[$catName][$prodName] += $detalle->cantidad;
            }
        }
        
        $gastos = $caja->gastos;
        $totalGastos = $gastos->sum('monto');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cajero.cierre_pdf', [
            'caja' => $caja,
            'resumen' => $resumenProductos,
            'totalRecaudado' => $totalVentas,
            'ventasPorMetodo' => $ventasPorMetodo,
            'gastos' => $gastos,
            'totalGastos' => $totalGastos
        ]);

        return $pdf->download('cierre_caja_' . $caja->id . '_' . now()->format('Ymd_Hi') . '.pdf');
    }

    /**
     * Vista de la cuenta completa de la mesa (para imprimir y llevar al cliente).
     */
    public function verCuenta($pedido_id)
    {
        $pedido = Pedido::with(['mesa', 'mesero', 'detalles.producto.categoria'])
            ->findOrFail($pedido_id);

        return view('cajero.cuenta', compact('pedido'));
    }

    /**
     * Formulario para registrar el pago y emitir la factura.
     */
    public function formCobrar($pedido_id)
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para realizar cobros.');
        }

        $pedido = Pedido::with(['mesa', 'detalles.producto'])
            ->findOrFail($pedido_id);

        return view('cajero.cobrar', compact('pedido'));
    }

    /**
     * Procesa el pago: crea la factura, cierra el pedido y libera la mesa.
     */
    public function procesarPago(Request $request, $pedido_id)
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para procesar pagos.');
        }

        $request->validate([
            'cliente_nombre' => 'nullable|string|max:255',
            'cliente_nit_ci' => 'nullable|string|max:50',
            'monto_pagado'   => 'required|numeric|min:0',
            'metodo_pago'    => 'required|in:efectivo,tarjeta,qr,transferencia',
        ]);

        $pedido = Pedido::with('mesa')->findOrFail($pedido_id);

        if ($pedido->estado === 'pagado') {
            return redirect()->route('cajero.dashboard')
                ->with('error', 'Este pedido ya fue pagado.');
        }

        $factura = DB::transaction(function () use ($request, $pedido) {
            $montoFinal = $pedido->total;

            // 1. Crear la factura
            $f = Factura::create([
                'pedido_id'         => $pedido->id,
                'cajero_id'         => auth()->id(),
                'cliente_nombre'    => $request->cliente_nombre,
                'cliente_nit_ci'    => $request->cliente_nit_ci,
                'monto_pagado'      => $montoFinal, // Guardamos el total neto
                'descuento'         => 0,
                'recargo'           => 0,
                'efectivo_recibido' => $request->monto_pagado, // Guardamos el efectivo recibido del cliente
                'metodo_pago'       => $request->metodo_pago,
            ]);

            // 2. Cerrar el pedido
            $pedido->update(['estado' => 'pagado']);

            // 3. Liberar la mesa
            $pedido->mesa->update(['estado' => 'libre']);

            return $f;
        });

        return redirect()->route('cajero.factura', $factura->id)
            ->with('success', "Pago procesado correctamente. Mesa {$pedido->mesa->numero} liberada.");
    }

    /**
     * Vista de la factura en formato térmico (comprobante final).
     */
    public function verFactura($factura_id)
    {
        $factura = Factura::with(['pedido.detalles.producto', 'pedido.mesa', 'cajero'])
            ->findOrFail($factura_id);

        return view('cajero.factura', compact('factura'));
    }

    /**
     * Anula una factura ya cobrada.
     */
    public function anularFactura($factura_id)
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para anular facturas.');
        }

        $factura = Factura::findOrFail($factura_id);

        if ($factura->estado === 'anulada') {
            return redirect()->back()->with('error', 'Esta factura ya está anulada.');
        }

        DB::transaction(function () use ($factura) {
            $factura->update(['estado' => 'anulada']);
            
            // Devolver stock de todos los items
            foreach ($factura->pedido->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto && $producto->usa_inventario) {
                    $producto->increment('stock', $detalle->cantidad);
                }
            }
        });

        return redirect()->back()->with('success', '✅ La venta ha sido anulada con éxito. Se ha descontado de los reportes de caja de hoy.');
    }

    /**
     * Gestión de inventario para el cajero.
     */
    public function inventario(Request $request)
    {
        $query = Producto::with('categoria')->where('usa_inventario', true);

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        $productos = $query->orderBy('nombre')->get();
        $categorias = Categoria::all();

        return view('cajero.inventario', compact('productos', 'categorias'));
    }

    /**
     * Incrementa el stock de un producto específico.
     */
    public function agregarStock(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($id);

        if (!$producto->usa_inventario) {
            return redirect()->back()->with('error', 'El producto seleccionado no maneja inventario.');
        }

        $producto->increment('stock', $request->cantidad);

        // Registrar compra en historial
        \App\Models\Compra::create([
            'producto_id' => $producto->id,
            'cantidad' => $request->cantidad,
        ]);

        return redirect()->back()->with('success', '✅ Stock de "' . $producto->nombre . '" actualizado correctamente. Se sumaron ' . $request->cantidad . ' unidades (Nuevo stock: ' . $producto->stock . ').');
    }

    /**
     * Descuenta stock por consumo del personal.
     */
    public function descontarConsumoPersonal(Request $request, $id)
    {
        $request->validate([
            'cantidad'    => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $producto = Producto::findOrFail($id);

        if (!$producto->usa_inventario) {
            return redirect()->back()->with('error', 'El producto seleccionado no maneja inventario.');
        }

        if ($producto->stock < $request->cantidad) {
            return redirect()->back()->with('error', "No hay suficiente stock para descontar. Stock disponible: {$producto->stock} ud.");
        }

        // Descontar stock
        $producto->decrement('stock', $request->cantidad);

        // Registrar consumo del personal
        ConsumoPersonal::create([
            'producto_id' => $producto->id,
            'user_id'     => auth()->id(),
            'cantidad'    => $request->cantidad,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->back()->with('success', '✅ Consumo del personal registrado. Se descontaron ' . $request->cantidad . ' ud. de "' . $producto->nombre . '" (Stock restante: ' . $producto->fresh()->stock . ').');
    }

    /**
     * Formulario para dividir cuenta.
     */
    public function formDividir($pedido_id)
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para realizar cobros.');
        }

        $pedido = Pedido::with(['mesa', 'detalles.producto'])
            ->findOrFail($pedido_id);

        if ($pedido->estado === 'pagado') {
            return redirect()->route('cajero.dashboard')->with('error', 'Este pedido ya fue pagado.');
        }

        return view('cajero.dividir', compact('pedido'));
    }

    /**
     * Procesa la división de cuenta: crea un pedido separado pagado,
     * descuenta cantidades del pedido original, crea factura y libera mesa si queda vacía.
     */
    public function procesarDivision(Request $request, $pedido_id)
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para procesar pagos.');
        }

        $request->validate([
            'split_items' => 'required|array|min:1',
            'split_items.*.detalle_id' => 'required|exists:pedido_detalles,id',
            'split_items.*.cantidad' => 'required|integer|min:0',
            'cliente_nombre' => 'nullable|string|max:255',
            'cliente_nit_ci' => 'nullable|string|max:50',
            'monto_pagado'   => 'required|numeric|min:0',
            'metodo_pago'    => 'required|in:efectivo,tarjeta,qr,transferencia',
        ]);

        $pedidoOriginal = Pedido::with(['mesa', 'detalles'])->findOrFail($pedido_id);

        if ($pedidoOriginal->estado === 'pagado') {
            return redirect()->route('cajero.dashboard')->with('error', 'Este pedido ya fue pagado.');
        }

        // Validar cantidades a dividir
        $totalDividir = 0;
        $detallesMap = $pedidoOriginal->detalles->keyBy('id');
        
        $itemsProcesables = [];
        foreach ($request->split_items as $item) {
            $detId = $item['detalle_id'];
            $cantDividir = intval($item['cantidad']);
            
            if ($cantDividir <= 0) continue;

            if (!isset($detallesMap[$detId])) {
                return redirect()->back()->with('error', 'Producto no encontrado en el pedido.');
            }

            $detalleOriginal = $detallesMap[$detId];
            if ($cantDividir > $detalleOriginal->cantidad) {
                return redirect()->back()->with('error', "No puedes dividir más cantidad de la existente para: {$detalleOriginal->nombre_mostrar}.");
            }

            $itemsProcesables[] = [
                'detalle' => $detalleOriginal,
                'cantidad' => $cantDividir
            ];
            $totalDividir += $cantDividir * $detalleOriginal->precio_unitario;
        }

        if (empty($itemsProcesables)) {
            return redirect()->back()->with('error', 'Debes seleccionar al menos un producto con cantidad mayor a 0 para dividir.');
        }

        // Procesar DB Transaction
        $factura = DB::transaction(function () use ($request, $pedidoOriginal, $itemsProcesables, $totalDividir) {
            $montoFinal = $totalDividir;

            // 1. Crear un pedido nuevo paralelo para la parte pagada
            $pedidoNuevo = Pedido::create([
                'mesa_id' => $pedidoOriginal->mesa_id,
                'mesero_id' => $pedidoOriginal->mesero_id,
                'estado' => 'pagado',
                'total' => $montoFinal
            ]);

            // 2. Transferir/descontar los items
            foreach ($itemsProcesables as $proc) {
                $det = $proc['detalle'];
                $cant = $proc['cantidad'];

                // Crear detalle en el nuevo pedido
                PedidoDetalle::create([
                    'pedido_id' => $pedidoNuevo->id,
                    'producto_id' => $det->producto_id,
                    'cantidad' => $cant,
                    'precio_unitario' => $det->precio_unitario,
                    'estado_comanda' => 'impreso',
                ]);

                // Descontar del original
                if ($cant === $det->cantidad) {
                    $det->delete();
                } else {
                    $det->decrement('cantidad', $cant);
                }
            }

            // 3. Recalcular total del pedido original
            $totalRestante = PedidoDetalle::where('pedido_id', $pedidoOriginal->id)
                ->selectRaw('SUM(cantidad * precio_unitario) as total')
                ->value('total');

            if (!$totalRestante || $totalRestante <= 0) {
                // Si ya no queda nada, marcamos el original como pagado y liberamos mesa
                $pedidoOriginal->update(['estado' => 'pagado', 'total' => 0]);
                $pedidoOriginal->mesa->update(['estado' => 'libre']);
                // Eliminar el pedido original vacío para no ensuciar reportes
                $pedidoOriginal->delete();
            } else {
                $pedidoOriginal->update(['total' => $totalRestante]);
            }

            // 4. Crear la factura para el pedido dividido
            $f = Factura::create([
                'pedido_id' => $pedidoNuevo->id,
                'cajero_id' => auth()->id(),
                'cliente_nombre' => $request->cliente_nombre,
                'cliente_nit_ci' => $request->cliente_nit_ci,
                'monto_pagado' => $montoFinal,
                'descuento' => 0,
                'recargo' => 0,
                'efectivo_recibido' => $request->monto_pagado,
                'metodo_pago' => $request->metodo_pago,
            ]);

            return $f;
        });

        $pedidoOriginalExiste = Pedido::find($pedido_id);

        if ($pedidoOriginalExiste) {
            return redirect()->route('cajero.cobrar', $pedidoOriginalExiste->id)
                ->with('success', "✅ Parte dividida cobrada con éxito. Aún queda saldo pendiente por cobrar.");
        }

        return redirect()->route('cajero.factura', $factura->id)
            ->with('success', "✅ Cuenta dividida y pago procesado correctamente (Mesa totalmente cobrada y liberada).");
    }
}
