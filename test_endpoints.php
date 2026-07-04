<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CajaSesion;
use App\Models\Factura;

try {
    echo "Probando CajaSesion...\n";
    $caja = CajaSesion::first();
    if ($caja) {
        echo "Caja ID: " . $caja->id . "\n";
        $c = new \App\Http\Controllers\Admin\CajaController();
        $response = $c->getDetalleCaja($caja->id);
        echo "CajaResponse status: " . $response->getStatusCode() . "\n";
        echo "CajaResponse content: " . substr($response->getContent(), 0, 500) . "\n";
    } else {
        echo "No hay cajas.\n";
    }

    echo "\nProbando Factura...\n";
    $factura = Factura::first();
    if ($factura) {
        echo "Factura ID: " . $factura->id . "\n";
        $c = new \App\Http\Controllers\Admin\HistorialVentaController();
        $response = $c->getDetalle($factura->id);
        echo "FacturaResponse status: " . $response->getStatusCode() . "\n";
        echo "FacturaResponse content: " . substr($response->getContent(), 0, 500) . "\n";
    } else {
        echo "No hay facturas.\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
