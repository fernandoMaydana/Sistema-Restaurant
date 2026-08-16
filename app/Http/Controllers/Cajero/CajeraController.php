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
    protected $siatService;

    public function __construct(\App\Services\SiatService $siatService)
    {
        $this->siatService = $siatService;
    }

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

        // Auto-cerrar cualquier sesión previa que se haya quedado abierta sin cerrar en días pasados
        $cajasAbiertasPrevias = CajaSesion::where('user_id', auth()->id())
            ->where('estado', 'abierta')
            ->get();

        foreach ($cajasAbiertasPrevias as $cajaPrevia) {
            $this->ejecutarCierreForzado($cajaPrevia);
        }

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

        // --- Resumen de ventas e historial ---
        $totalVentasHoy = Factura::where('estado', 'activa')->whereDate('created_at', today())->sum('monto_pagado');
        $facturasHoy = Factura::with(['pedido.mesa', 'cajero'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        // --- Ventas estimadas por cobrar en mesas y pedidos activos ---
        $pedidosActivosHoy = Pedido::whereIn('estado', ['abierto', 'cuenta_solicitada'])->get();
        $totalEstimadoMesasHoy = $pedidosActivosHoy->sum('total');
        $cantMesasActivas = $pedidosActivosHoy->count();

        if ($caja) {
            $gastosHoy = $caja->gastos()->orderBy('created_at', 'desc')->get();
            $totalGastosHoy = $gastosHoy->sum('monto');
        } else {
            $gastosHoy = collect();
            $totalGastosHoy = 0;
        }

        $productosInventario = Producto::where('usa_inventario', true)
            ->where('disponible', true)
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        $consumosHoy = ConsumoPersonal::with('producto')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cajero.dashboard', compact(
            'pedidosConComandaPendiente', 
            'mesasParaCobrar', 
            'totalVentasHoy', 
            'totalEstimadoMesasHoy',
            'cantMesasActivas',
            'facturasHoy',
            'gastosHoy',
            'totalGastosHoy',
            'caja',
            'productosInventario',
            'consumosHoy'
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
     * Cierre manual/forzado de una sesión de caja desde el historial.
     */
    public function cerrarCajaForzada($id)
    {
        $caja = CajaSesion::with('gastos')->findOrFail($id);

        if ($caja->estado === 'cerrada') {
            return redirect()->back()->with('info', 'La caja ya está cerrada.');
        }

        $this->ejecutarCierreForzado($caja);

        return redirect()->back()->with('success', "✅ Caja #{$caja->id} cerrada exitosamente. Ya puedes descargar su PDF e imprimir el Ticket.");
    }

    /**
     * Ejecuta los cálculos y cierra la sesión de caja dada.
     */
    private function ejecutarCierreForzado(CajaSesion $caja)
    {
        if ($caja->estado === 'cerrada') return;

        $siguienteCaja = CajaSesion::where('user_id', $caja->user_id)
            ->where('id', '>', $caja->id)
            ->orderBy('id', 'asc')
            ->first();

        $queryFacturas = Factura::where('estado', 'activa')
            ->where('cajero_id', $caja->user_id)
            ->where('created_at', '>=', $caja->fecha_apertura);

        if ($siguienteCaja) {
            $queryFacturas->where('created_at', '<', $siguienteCaja->fecha_apertura);
        }

        $facturas = $queryFacturas->get();

        $totalVentas = $facturas->sum('monto_pagado');
        $ventasEfectivo = $facturas->where('metodo_pago', 'efectivo')->sum('monto_pagado');
        $totalGastos = $caja->gastos->sum('monto');

        $fechaUltimaVenta = $facturas->max('created_at');
        $fechaCierre = $fechaUltimaVenta 
            ? $fechaUltimaVenta 
            : (\Carbon\Carbon::parse($caja->fecha_apertura)->isToday() ? now() : \Carbon\Carbon::parse($caja->fecha_apertura)->endOfDay());

        $caja->update([
            'monto_final' => ($caja->monto_inicial + $ventasEfectivo) - $totalGastos,
            'total_ventas' => $totalVentas,
            'fecha_cierre' => $fechaCierre,
            'estado' => 'cerrada',
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

        // Obtener las reservas del día
        $reservas = \App\Models\Reserva::whereDate('fecha', today())
            ->whereIn('estado', ['pendiente', 'asistida'])
            ->get();
 
        return view('cajero.salon', compact('mesas', 'reservas'));
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
 
        if ($mesa->es_para_llevar) {
            $pedido = Pedido::where('mesa_id', $mesa->id)->whereIn('estado', ['abierto', 'cuenta_solicitada'])->first();
            if ($pedido) {
                return redirect()->route('cajero.cobrar', $pedido->id)
                    ->with('success', "✅ Pedido para Llevar registrado. Procede a cobrar.");
            }
        }

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

        if ($mesa->es_para_llevar) {
            $pedido = Pedido::where('mesa_id', $mesa->id)->whereIn('estado', ['abierto', 'cuenta_solicitada'])->first();
            if ($pedido) {
                if ($request->input('opcion_pago') === 'recoger_despues') {
                    return redirect()->route('cajero.salon')
                        ->with('success', "✅ Pedido para Llevar #{$mesa->numero} registrado (Pagar al recoger).");
                } else {
                    return redirect()->route('cajero.cobrar', $pedido->id)
                        ->with('success', "✅ Pedido para Llevar #{$mesa->numero} guardado. Procede con el cobro.");
                }
            }
        }

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
                $printer->text("LLEVAR\n");
            } else {
                $printer->text("MESA " . $pedido->mesa->numero . "\n");
            }
            $printer->setTextSize(1, 1);
            $printer->text(str_repeat("-", $cols) . "\n");
            
            if ($pedido->mesa->es_para_llevar) {
                // Cargar la relación factura si no está cargada
                if (!$pedido->relationLoaded('factura')) {
                    $pedido->load('factura');
                }
                $clienteNombre = $pedido->factura ? strtoupper($pedido->factura->cliente_nombre) : 'GENERAL';
                $printer->text("Cliente: " . $clienteNombre . "\n");
            } else {
                $printer->text("Mesero: " . strtoupper($pedido->mesero->name) . "\n");
            }
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
            
            if ($factura->cuf) {
                $printer->text("NIT: " . ($this->siatService->getConfig()->nit ?? '1020304050') . "\n");
                $printer->text("FACTURA EN LINEA\n");
            } else {
                $printer->text("TICKET DE VENTA\n");
            }
            
            $printer->setTextSize(2, 2);
            $printer->text("FACTURA\n");
            $printer->setTextSize(1, 1);
            $printer->text(str_repeat("-", $cols) . "\n");
            
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Fecha: " . $factura->created_at->format('d/m/Y H:i:s') . "\n");
            
            if ($factura->cuf) {
                $printer->text("Nro. Factura: " . ($factura->numero_factura_siat ?? $factura->id) . "\n");
            } else {
                $printer->text("Nro. Factura: #" . str_pad($factura->id, 6, '0', STR_PAD_LEFT) . "\n");
            }
            
            $printer->text("Cajero: " . strtoupper($factura->cajero->name) . "\n");
            if ($factura->pedido->mesa->es_para_llevar) {
                $printer->text("Pedido: Llevar #" . $factura->pedido->mesa->numero . "\n");
            } else {
                $printer->text("Mesa: " . $factura->pedido->mesa->numero . "\n");
            }
            $printer->text(str_repeat("-", $cols) . "\n");
            $printer->text("CLIENTE: " . strtoupper($factura->cliente_nombre ?? 'CONSUMIDOR FINAL') . "\n");
            $printer->text("NIT/CI: " . ($factura->cliente_nit_ci ?? '-----------') . "\n");
            
            if ($factura->cuf) {
                $printer->text("CUF: " . wordwrap($factura->cuf, $cols - 5, "\n     ", true) . "\n");
            }
            
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
            
            if ($factura->cuf) {
                $printer->setEmphasis(true);
                $printer->text("ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAIS, EL USO ILICITO DE ESTA SERA SANCIONADO DE ACUERDO A LEY\n\n");
                $printer->setEmphasis(false);
                $printer->text(wordwrap($factura->leyenda_sin ?? 'Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices.', $cols, "\n", true) . "\n\n");

                // Generar QR para el SIN
                $config = $this->siatService->getConfig();
                $nit = $config->nit ?? '1020304050';
                $qrUrl = "https://siat.impuestos.gob.bo/consulta/QR?nit={$nit}&cuf={$factura->cuf}&numero={$factura->numero_factura_siat}&t=1";
                
                $printer->qrCode($qrUrl, Printer::QR_ECLEVEL_L, 4);
                $printer->text("\nEscanea para verificar la factura digital\n");
            } else {
                $printer->text("GRACIAS POR SU PREFERENCIA\n");
                $printer->text("PROVEA ESTE TICKET PARA RECLAMOS\n");
            }
            
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
                $catName = $detalle->producto?->categoria?->nombre ?? 'Sin Categoría';
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

        try {
            $factura = DB::transaction(function () use ($request, $pedido) {
                $montoFinal = $pedido->total;

                // 1. Crear la factura localmente
                $f = Factura::create([
                    'pedido_id'         => $pedido->id,
                    'cajero_id'         => auth()->id(),
                    'cliente_nombre'    => $request->cliente_nombre ?? 'CONSUMIDOR FINAL',
                    'cliente_nit_ci'    => $request->cliente_nit_ci ?? '99001',
                    'monto_pagado'      => $montoFinal, // Guardamos el total neto
                    'descuento'         => 0,
                    'recargo'           => 0,
                    'efectivo_recibido' => $request->monto_pagado, // Guardamos el efectivo recibido del cliente
                    'metodo_pago'       => $request->metodo_pago,
                ]);

                // 2. Si SIAT está habilitado, procesar la facturación en línea
                if ($this->siatService->isEnabled()) {
                    $siatResult = $this->siatService->enviarFactura($f);

                    if (!$siatResult['success']) {
                        throw new Exception($siatResult['mensaje']);
                    }

                    // Guardar campos SIAT devueltos
                    $f->update([
                        'cuf' => $siatResult['cuf'],
                        'cufd_codigo' => $siatResult['cufd_codigo'],
                        'numero_factura_siat' => $siatResult['numero_factura_siat'],
                        'estado_siat' => $siatResult['estado_siat'],
                        'codigo_recepcion' => $siatResult['codigo_recepcion'],
                        'leyenda_sin' => $siatResult['leyenda_sin'],
                        'xml_path' => $siatResult['xml_path'],
                    ]);
                }

                // 3. Cerrar el pedido
                $pedido->update(['estado' => 'pagado']);

                // 4. Liberar la mesa
                $pedido->mesa->update(['estado' => 'libre']);

                return $f;
            });
        } catch (Exception $e) {
            \Log::error("Error al procesar pago con SIAT: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', '⚠️ Error al emitir Factura SIAT: ' . $e->getMessage());
        }

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

        $pedidoPendienteId = session('pedido_pendiente_id') ?? request('pedido_pendiente_id');

        return view('cajero.factura', compact('factura', 'pedidoPendienteId'));
    }

    /**
     * Anula una factura ya cobrada.
     */
    public function anularFactura($factura_id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', '⛔ No tiene permisos para anular ventas. Contacte con el administrador.');
        }

        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para anular facturas.');
        }

        $factura = Factura::findOrFail($factura_id);

        if ($factura->estado === 'anulada') {
            return redirect()->back()->with('error', 'Esta factura ya está anulada.');
        }

        try {
            DB::transaction(function () use ($factura) {
                // Si la factura fue enviada al SIAT, intentar anularla en Impuestos
                if ($this->siatService->isEnabled() && $factura->cuf && in_array($factura->estado_siat, ['enviada', 'pendiente'])) {
                    $this->siatService->anularFactura($factura, 1); // 1 = Factura Mal Emitida
                    $factura->update(['estado_siat' => 'anulada_siat']);
                }

                $factura->update(['estado' => 'anulada']);
                
                // Devolver stock de todos los items
                foreach ($factura->pedido->detalles as $detalle) {
                    $producto = Producto::find($detalle->producto_id);
                    if ($producto && $producto->usa_inventario) {
                        $producto->increment('stock', $detalle->cantidad);
                    }
                }
            });
        } catch (Exception $e) {
            \Log::error("Error al anular factura en SIAT: " . $e->getMessage());
            return redirect()->back()->with('error', '⚠️ Error al anular factura en el SIAT: ' . $e->getMessage());
        }

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
     * Registrar consumo del personal desde el dashboard de caja.
     */
    public function registrarConsumoPersonalDashboard(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad'    => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

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

        // Validar que el monto pagado no sea menor al total a cobrar
        if (floatval($request->monto_pagado) < floatval($totalDividir)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'El monto recibido (Bs ' . number_format($request->monto_pagado, 2) . ') no puede ser menor al total de la fracción a cobrar (Bs ' . number_format($totalDividir, 2) . ').');
        }

        // Procesar DB Transaction
        try {
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
                        'notas' => $det->notas,
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
                    'cliente_nombre' => $request->cliente_nombre ?? 'CONSUMIDOR FINAL',
                    'cliente_nit_ci' => $request->cliente_nit_ci ?? '99001',
                    'monto_pagado' => $montoFinal,
                    'descuento' => 0,
                    'recargo' => 0,
                    'efectivo_recibido' => $request->monto_pagado,
                    'metodo_pago' => $request->metodo_pago,
                ]);

                // 5. Si SIAT está habilitado, procesar la facturación en línea
                if ($this->siatService->isEnabled()) {
                    $siatResult = $this->siatService->enviarFactura($f);

                    if (!$siatResult['success']) {
                        throw new Exception($siatResult['mensaje']);
                    }

                    // Guardar campos SIAT devueltos
                    $f->update([
                        'cuf' => $siatResult['cuf'],
                        'cufd_codigo' => $siatResult['cufd_codigo'],
                        'numero_factura_siat' => $siatResult['numero_factura_siat'],
                        'estado_siat' => $siatResult['estado_siat'],
                        'codigo_recepcion' => $siatResult['codigo_recepcion'],
                        'leyenda_sin' => $siatResult['leyenda_sin'],
                        'xml_path' => $siatResult['xml_path'],
                    ]);
                }

                return $f;
            });
        } catch (Exception $e) {
            \Log::error("Error al procesar división de pago con SIAT: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', '⚠️ Error al emitir Factura SIAT: ' . $e->getMessage());
        }

        $pedidoOriginalExiste = Pedido::find($pedido_id);

        if ($pedidoOriginalExiste) {
            return redirect()->route('cajero.factura', $factura->id)
                ->with('pedido_pendiente_id', $pedidoOriginalExiste->id)
                ->with('success', "✅ Fracción cobrada con éxito (Factura #" . $factura->id . " emitida). Aún queda saldo de Bs " . number_format($pedidoOriginalExiste->total, 2) . " en la mesa.");
        }

        return redirect()->route('cajero.factura', $factura->id)
            ->with('success', "✅ Cuenta dividida y pago final procesado correctamente (Mesa totalmente cobrada y liberada).");
    }

    /**
     * Muestra el historial de cajas del propio cajero.
     */
    public function historialCajas(Request $request)
    {
        $query = CajaSesion::where('user_id', auth()->id())->orderBy('created_at', 'desc');

        if ($request->filled('fecha_especifica')) {
            $query->whereDate('fecha_apertura', $request->fecha_especifica);
        } else {
            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_apertura', '>=', $request->fecha_desde);
            }
            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_apertura', '<=', $request->fecha_hasta);
            }
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $cajas = $query->paginate(15)->withQueryString();

        return view('cajero.cajas_historial', compact('cajas'));
    }

    /**
     * Muestra el historial completo de ventas con filtros de búsqueda para el cajero.
     */
    public function historialVentas(Request $request)
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para acceder a las opciones.');
        }

        $query = Factura::with(['pedido.mesa', 'cajero', 'pedido.detalles.producto'])
            ->orderBy('created_at', 'desc');

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

        if ($request->filled('cliente')) {
            $cliente = trim($request->cliente);
            $query->where(function($q) use ($cliente) {
                $q->where('cliente_nombre', 'like', "%{$cliente}%")
                  ->orWhere('cliente_nit_ci', 'like', "%{$cliente}%");
            });
        }

        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $totalFiltrado = (clone $query)->where('estado', 'activa')->sum('monto_pagado');
        $facturas = $query->paginate(20)->appends($request->all());

        return view('cajero.ventas_historial', compact('facturas', 'totalFiltrado'));
    }

    /**
     * Retorna el detalle de la sesión de caja en formato JSON (solo si le pertenece al cajero).
     */
    public function getDetalleCaja($id)
    {
        $caja = CajaSesion::where('user_id', auth()->id())->with(['gastos', 'user'])->findOrFail($id);
        
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
            if (!$factura->pedido) {
                continue;
            }
            foreach ($factura->pedido->detalles as $detalle) {
                $catName = $detalle->producto?->categoria?->nombre ?? 'Sin Categoría';
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

        // Aplanar el resumen de productos para facilidad de uso en front-end
        $productosAplanados = [];
        foreach ($resumenProductos as $categoria => $productos) {
            foreach ($productos as $nombre => $cantidad) {
                $productosAplanados[] = [
                    'categoria' => $categoria,
                    'nombre' => $nombre,
                    'cantidad' => $cantidad
                ];
            }
        }

        return response()->json([
            'success' => true,
            'caja' => [
                'id' => $caja->id,
                'cajero_nombre' => $caja->user?->name ?? 'N/A',
                'fecha_apertura' => \Carbon\Carbon::parse($caja->fecha_apertura)->format('d/m/Y H:i'),
                'fecha_cierre' => $caja->fecha_cierre ? \Carbon\Carbon::parse($caja->fecha_cierre)->format('d/m/Y H:i') : 'En progreso',
                'monto_inicial' => floatval($caja->monto_inicial),
                'monto_final' => floatval($caja->monto_final ?? 0),
                'estado' => $caja->estado,
            ],
            'total_ventas' => floatval($totalVentas),
            'ventas_por_metodo' => array_map('floatval', $ventasPorMetodo),
            'gastos' => $caja->gastos->map(function($gasto) {
                return [
                    'descripcion' => $gasto->descripcion,
                    'monto' => floatval($gasto->monto),
                    'hora' => $gasto->created_at->format('H:i')
                ];
            }),
            'total_gastos' => floatval($caja->gastos->sum('monto')),
            'resumen_productos' => $productosAplanados,
        ]);
    }

    /**
     * Retorna el detalle de los productos consumidos en una factura específica en formato JSON.
     */
    public function getDetalleVenta($id)
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

    /**
     * Crea un pedido para llevar rápido.
     */
    public function crearPedidoLlevar()
    {
        if (!$this->obtenerCajaAbierta()) {
            return redirect()->route('cajero.bienvenida')->with('error', 'Debe iniciar caja para crear pedidos.');
        }

        // Buscar una mesa libre que sea para llevar
        $mesa = Mesa::where('es_para_llevar', true)
            ->whereDoesntHave('pedidos', function ($q) {
                $q->whereIn('estado', ['abierto', 'cuenta_solicitada']);
            })->first();

        if (!$mesa) {
            // Si no hay mesa libre, crear una automáticamente en la BD
            $maxMesaNumero = Mesa::max('numero') ?? 0;
            $mesa = Mesa::create([
                'numero' => $maxMesaNumero + 1,
                'capacidad' => 1,
                'estado' => 'libre',
                'es_para_llevar' => true
            ]);
        }

        return redirect()->route('cajero.mesa', $mesa->id);
    }

    /**
     * Obtiene y devuelve las reservas del día en formato JSON.
     */
    public function listarReservas()
    {
        $reservas = \App\Models\Reserva::with('mesa')
            ->whereDate('fecha', today())
            ->orderBy('hora')
            ->get();

        return response()->json($reservas);
    }

    /**
     * Guarda una nueva reserva.
     */
    public function guardarReserva(Request $request)
    {
        $request->validate([
            'cliente_nombre' => 'required|string|max:255',
            'cliente_telefono' => 'nullable|string|max:50',
            'fecha' => 'required|date',
            'hora' => 'required',
            'cantidad_personas' => 'required|integer|min:1',
            'mesa_id' => 'nullable|exists:mesas,id',
            'notas' => 'nullable|string|max:500',
        ]);

        $reserva = \App\Models\Reserva::create([
            'cliente_nombre' => $request->cliente_nombre,
            'cliente_telefono' => $request->cliente_telefono,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'cantidad_personas' => $request->cantidad_personas,
            'mesa_id' => $request->mesa_id,
            'notas' => $request->notas,
            'estado' => 'pendiente'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reserva registrada con éxito.',
            'reserva' => $reserva
        ]);
    }

    /**
     * Marca una reserva como Asistida.
     */
    public function asistirReserva($id)
    {
        $reserva = \App\Models\Reserva::findOrFail($id);
        $reserva->update(['estado' => 'asistida']);

        // Opcional: Si tiene mesa asignada, podemos redirigir a tomar el pedido de esa mesa
        if ($reserva->mesa_id) {
            $mesa = Mesa::find($reserva->mesa_id);
            if ($mesa && $mesa->estado === 'libre') {
                return response()->json([
                    'success' => true,
                    'message' => 'Reserva marcada como Asistida.',
                    'redirect_to' => route('cajero.mesa', $reserva->mesa_id)
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Reserva marcada como Asistida.'
        ]);
    }

    /**
     * Cancela una reserva.
     */
    public function cancelarReserva($id)
    {
        $reserva = \App\Models\Reserva::findOrFail($id);
        $reserva->update(['estado' => 'cancelada']);

        return response()->json([
            'success' => true,
            'message' => 'Reserva cancelada.'
        ]);
    }

    /**
     * Elimina una reserva.
     */
    public function eliminarReserva($id)
    {
        $reserva = \App\Models\Reserva::findOrFail($id);
        $reserva->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reserva eliminada con éxito.'
        ]);
    }

    /**
     * Cambia la mesa asignada a un pedido activo (Mover a mesa libre).
     */
    public function cambiarMesa(Request $request, $pedido_id)
    {
        $request->validate([
            'nueva_mesa_id' => 'required|exists:mesas,id'
        ]);

        $pedido = Pedido::findOrFail($pedido_id);
        $mesaAnterior = Mesa::findOrFail($pedido->mesa_id);
        $nuevaMesa = Mesa::findOrFail($request->nueva_mesa_id);

        $tienePedidosActivos = Pedido::where('mesa_id', $nuevaMesa->id)
            ->whereIn('estado', ['abierto', 'cuenta_solicitada'])
            ->where('id', '!=', $pedido->id)
            ->exists();

        if ($tienePedidosActivos) {
            return redirect()->back()->with('error', "La Mesa {$nuevaMesa->numero} ya está ocupada.");
        }

        $pedido->update(['mesa_id' => $nuevaMesa->id]);

        $mesaAnterior->update(['estado' => 'libre']);
        $nuevaMesa->update(['estado' => 'ocupada']);

        return redirect()->route('cajero.salon')->with('success', "✅ Pedido movido con éxito a la Mesa {$nuevaMesa->numero}.");
    }

    /**
     * Une el consumo de una mesa a otra mesa ocupada (Fusionar pedidos).
     */
    public function unirMesas(Request $request, $pedido_id)
    {
        $request->validate([
            'pedido_destino_id' => 'required|exists:pedidos,id'
        ]);

        $pedidoOrigen = Pedido::with('detalles')->findOrFail($pedido_id);
        $pedidoDestino = Pedido::with('detalles')->findOrFail($request->pedido_destino_id);

        if ($pedidoOrigen->id === $pedidoDestino->id) {
            return redirect()->back()->with('error', "No puedes unir un pedido consigo mismo.");
        }

        foreach ($pedidoOrigen->detalles as $detalle) {
            $detalle->update(['pedido_id' => $pedidoDestino->id]);
        }

        $nuevoSubtotal = $pedidoDestino->detalles()->sum(DB::raw('cantidad * precio_unitario'));
        $nuevoTotal = max(0, $nuevoSubtotal - ($pedidoDestino->descuento ?? 0));
        $pedidoDestino->update(['total' => $nuevoTotal]);

        $mesaOrigen = Mesa::find($pedidoOrigen->mesa_id);
        if ($mesaOrigen) {
            $mesaOrigen->update(['estado' => 'libre']);
        }
        $pedidoOrigen->delete();

        return redirect()->route('cajero.salon')->with('success', "✅ Pedidos unidos exitosamente.");
    }

    /**
     * Aplica un descuento en Bs o Porcentaje al pedido.
     */
    public function aplicarDescuento(Request $request, $pedido_id)
    {
        $request->validate([
            'tipo_descuento' => 'required|in:monto,porcentaje',
            'valor_descuento' => 'required|numeric|min:0'
        ]);

        $pedido = Pedido::with('detalles')->findOrFail($pedido_id);
        $subtotal = $pedido->detalles->sum(fn($d) => $d->cantidad * $d->precio_unitario);

        $descuentoCalculado = 0;
        if ($request->tipo_descuento === 'porcentaje') {
            $pct = min(100, floatval($request->valor_descuento));
            $descuentoCalculado = ($subtotal * $pct) / 100;
        } else {
            $descuentoCalculado = min($subtotal, floatval($request->valor_descuento));
        }

        $nuevoTotal = max(0, $subtotal - $descuentoCalculado);

        $pedido->update([
            'descuento' => $descuentoCalculado,
            'total' => $nuevoTotal
        ]);

        return redirect()->back()->with('success', "✅ Descuento de Bs " . number_format($descuentoCalculado, 2) . " aplicado a la mesa.");
    }

    /**
     * Guarda o actualiza la nota especial de la mesa.
     */
    public function guardarNotaMesa(Request $request, $pedido_id)
    {
        $request->validate([
            'notas' => 'nullable|string|max:255'
        ]);

        $pedido = Pedido::findOrFail($pedido_id);
        $pedido->update(['notas' => $request->notas]);

        return redirect()->back()->with('success', "✅ Nota guardada para la mesa.");
    }
}
