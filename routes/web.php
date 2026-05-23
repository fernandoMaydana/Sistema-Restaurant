<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\MesaController;
use App\Http\Controllers\Mesero\PedidoController;
use App\Http\Controllers\Cajero\CajeraController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde registras las rutas para tu aplicación.
|
*/

// 1. Ruta Raíz: Si no estás logueado, te manda al login. 
// Si ya estás logueado, te manda a tu panel.
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// 2. Rutas de Autenticación (Login, Logout, etc.)
Auth::routes();

// 3. El "Distribuidor": Esta ruta decide a qué panel enviarte según tu rol
Route::get('/home', [HomeController::class, 'index'])->name('home');

// ==========================================================
// RUTAS PROTEGIDAS POR ROL
// ==========================================================

// Grupo para el ADMINISTRADOR
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('usuarios', UserController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('mesas', MesaController::class);

    // Historial de Cajas
    Route::get('/cajas', [App\Http\Controllers\Admin\CajaController::class, 'index'])->name('cajas.index');
    Route::get('/cajas/{id}/pdf', [App\Http\Controllers\Admin\CajaController::class, 'descargarPdf'])->name('cajas.pdf');
    Route::post('/cajas/{id}/imprimir', [App\Http\Controllers\Admin\CajaController::class, 'imprimirTicket'])->name('cajas.imprimir');
    
    // Historial de Ventas
    Route::get('/ventas', [App\Http\Controllers\Admin\HistorialVentaController::class, 'index'])->name('ventas.index');
});

// Grupo para el CAJERO
Route::middleware(['auth', 'role:cajero'])->prefix('cajero')->name('cajero.')->group(function () {
    // Bienvenida y Apertura de Caja
    Route::get('/bienvenida', [CajeraController::class, 'bienvenida'])->name('bienvenida');
    Route::post('/abrir-caja', [CajeraController::class, 'abrirCaja'])->name('abrir_caja');

    // Cierre de Caja
    Route::get('/cierre', [CajeraController::class, 'cierrePreview'])->name('cierre');
    Route::post('/cierre', [CajeraController::class, 'confirmarCierre'])->name('cierre.confirmar');
    Route::post('/gasto', [CajeraController::class, 'registrarGasto'])->name('gasto.registrar');
    Route::get('/cierre/pdf/{caja_id}', [CajeraController::class, 'descargarPdfCierre'])->name('cierre.pdf');

    // Panel principal: comandas pendientes + mesas a cobrar
    Route::get('/', [CajeraController::class, 'dashboard'])->name('dashboard');

    // Ver e imprimir comanda a cocina
    Route::get('/comanda/{pedido_id}', [CajeraController::class, 'verComanda'])->name('comanda');
    Route::post('/comanda/{pedido_id}/imprimir', [CajeraController::class, 'imprimirComanda'])->name('comanda.imprimir');

    // API Impresión Directa ESC/POS
    Route::post('/api/imprimir/comanda/{pedido_id}', [CajeraController::class, 'apiImprimirComanda'])->name('api.imprimir.comanda');
    Route::post('/api/imprimir/cuenta/{pedido_id}', [CajeraController::class, 'apiImprimirCuenta'])->name('api.imprimir.cuenta');
    Route::post('/api/imprimir/factura/{factura_id}', [CajeraController::class, 'apiImprimirFactura'])->name('api.imprimir.factura');
    Route::post('/api/imprimir/cierre/{caja_id}', [CajeraController::class, 'apiImprimirCierre'])->name('api.imprimir.cierre');

    // Ver cuenta del cliente (detalle de consumo)
    Route::get('/cuenta/{pedido_id}', [CajeraController::class, 'verCuenta'])->name('cuenta');

    // Salón: cuadrícula de mesas (vista similar a mesero)
    Route::get('/salon', [CajeraController::class, 'salon'])->name('salon');
    Route::get('/api/salon-status', [CajeraController::class, 'getSalonStatus'])->name('salon.status');
    
    // Ver mesa y agregar productos (al igual que el mesero)
    Route::get('/mesa/{mesa_id}', [CajeraController::class, 'verMesa'])->name('mesa');
    Route::post('/mesa/{mesa_id}/registrar', [CajeraController::class, 'registrarItems'])->name('registrar');
    Route::post('/mesa/{mesa_id}/actualizar', [CajeraController::class, 'actualizarPedido'])->name('mesa.actualizar');
    Route::delete('/pedido-detalle/{id}', [CajeraController::class, 'eliminarItem'])->name('pedido.eliminar_item');

    // Formulario y procesamiento del pago
    Route::get('/cobrar/{pedido_id}', [CajeraController::class, 'formCobrar'])->name('cobrar');
    Route::post('/cobrar/{pedido_id}', [CajeraController::class, 'procesarPago'])->name('cobrar.pagar');
    Route::get('/factura/{factura_id}', [CajeraController::class, 'verFactura'])->name('factura');
    Route::post('/factura/{factura_id}/anular', [CajeraController::class, 'anularFactura'])->name('factura.anular');

    // Inventario y Control de Stock
    Route::get('/inventario', [CajeraController::class, 'inventario'])->name('inventario');
    Route::post('/inventario/agregar-stock/{id}', [CajeraController::class, 'agregarStock'])->name('inventario.agregar_stock');
});

// Grupo para el MESERO
Route::middleware(['auth', 'role:mesero'])->prefix('mesero')->name('mesero.')->group(function () {
    // Pantalla de bienvenida (primer destino tras login)
    Route::get('/', [PedidoController::class, 'bienvenida'])->name('dashboard');

    // Salón: cuadrícula de mesas
    Route::get('/salon', [PedidoController::class, 'salon'])->name('salon');

    // Ver mesa: productos + detalle del pedido activo
    Route::get('/mesa/{mesa_id}', [PedidoController::class, 'verMesa'])->name('mesa');

    // Registrar productos seleccionados
    Route::post('/mesa/{mesa_id}/registrar', [PedidoController::class, 'registrarItems'])->name('registrar');

    // Imprimir pre-cuenta directamente a la impresora térmica
    Route::post('/api/imprimir/cuenta/{pedido_id}', [CajeraController::class, 'apiImprimirCuenta'])->name('api.imprimir.cuenta');
    Route::post('/api/imprimir/comanda/{pedido_id}', [CajeraController::class, 'apiImprimirComanda'])->name('api.imprimir.comanda');
});