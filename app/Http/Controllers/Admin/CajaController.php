<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CajaSesion;
use App\Models\Factura;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Exception;

class CajaController extends Controller
{
    /**
     * Lista todas las sesiones de caja (historial).
     */
    public function index(Request $request)
    {
        $query = CajaSesion::with('user')->orderBy('created_at', 'desc');

        // Filtros
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

        if ($request->filled('cajero_id')) {
            $query->where('user_id', $request->cajero_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $cajas = $query->paginate(15)->withQueryString();
        
        $cajeros = \App\Models\User::where('role', 'cajero')->get();

        return view('admin.cajas.index', compact('cajas', 'cajeros'));
    }

    /**
     * Descargar reporte de cierre en PDF
     */
    public function descargarPdf($id)
    {
        $caja = CajaSesion::with('gastos', 'user')->findOrFail($id);
        
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
     * API: Imprime el reporte de cierre directamente usando ESC/POS
     */
    public function imprimirTicket($id)
    {
        $caja = CajaSesion::with('gastos', 'user')->findOrFail($id);
        
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

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("REPORTE DE CIERRE\n");
            $printer->setTextSize(1, 1);
            $printer->text("RESTO-SISTEMA\n");
            $printer->text("--------------------------------\n");
            
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Cajero: " . $caja->user->name . "\n");
            $printer->text("Fecha: " . \Carbon\Carbon::parse($caja->fecha_cierre ?? now())->format('d/m/Y H:i') . "\n");
            $printer->text("--------------------------------\n\n");

             $printer->text("Monto Inicial:     Bs " . number_format($caja->monto_inicial, 2) . "\n");
             $printer->text("Ventas Totales:    Bs " . number_format($totalVentas, 2) . "\n");
             $printer->text("--------------------------------\n");
             $printer->text("Ventas Efectivo:   Bs " . number_format($ventasPorMetodo['efectivo'], 2) . "\n");
             $printer->text("Ventas QR/Trans:   Bs " . number_format($ventasPorMetodo['qr'] + $ventasPorMetodo['transferencia'], 2) . "\n");
             $printer->text("Ventas Tarjeta:    Bs " . number_format($ventasPorMetodo['tarjeta'], 2) . "\n");
             
             if ($gastos->count() > 0) {
                 $printer->text("--------------------------------\n");
                 $printer->setEmphasis(true);
                 $printer->text("GASTOS (-)\n");
                 $printer->setEmphasis(false);
                 foreach ($gastos as $gasto) {
                     $desc = substr($gasto->descripcion, 0, 20);
                     $monto = "-Bs " . number_format($gasto->monto, 2);
                     $espacios = max(1, 32 - strlen($desc) - strlen($monto));
                     $printer->text($desc . str_repeat(" ", $espacios) . $monto . "\n");
                 }
                 $txtTotalG = "TOTAL GASTOS:";
                 $montoTotalG = "-Bs " . number_format($totalGastos, 2);
                 $esp = max(1, 32 - strlen($txtTotalG) - strlen($montoTotalG));
                 $printer->setEmphasis(true);
                 $printer->text($txtTotalG . str_repeat(" ", $esp) . $montoTotalG . "\n");
                 $printer->setEmphasis(false);
             }
 
             $printer->text("--------------------------------\n");
             $printer->setTextSize(1, 2);
             $txtEfectivo = "EFECTIVO CAJA:";
             $montoEfectivo = "Bs " . number_format($caja->monto_final, 2);
             $esp = max(1, 32 - strlen($txtEfectivo) - strlen($montoEfectivo));
             $printer->text($txtEfectivo . str_repeat(" ", $esp) . $montoEfectivo . "\n");
             $printer->setTextSize(1, 1);
            
            $printer->text("\n--------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("RESUMEN DE PRODUCTOS\n");
            $printer->setEmphasis(false);
            $printer->text("--------------------------------\n");
            
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            foreach ($resumenProductos as $categoria => $productos) {
                $printer->setEmphasis(true);
                $printer->text("* " . strtoupper($categoria) . " *\n");
                $printer->setEmphasis(false);
                foreach ($productos as $nombre => $cantidad) {
                    $nom = substr($nombre, 0, 26);
                    $cant = "x" . $cantidad;
                    $espacios = max(1, 32 - strlen($nom) - strlen($cant));
                    $printer->text($nom . str_repeat(" ", $espacios) . $cant . "\n");
                }
                $printer->text("\n");
            }

            $printer->text("--------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Gracias por su jornada.\n");
            $printer->text("#" . $caja->id . "\n\n\n\n");
            
            $printer->cut();
            $printer->close();

            return response()->json(['success' => true, 'message' => 'Ticket de historial enviado a impresora.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de impresora: ' . $e->getMessage()]);
        }
    }
}
