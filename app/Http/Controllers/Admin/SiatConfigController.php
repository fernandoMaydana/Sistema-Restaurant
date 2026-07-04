<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class SiatConfigController extends Controller
{
    protected $siatService;

    public function __construct(SiatService $siatService)
    {
        $this->siatService = $siatService;
    }

    /**
     * Muestra la pantalla de configuración del SIAT.
     */
    public function index()
    {
        $config = $this->siatService->getConfig();
        
        $leyendasCount = DB::table('siat_leyendas')->count();
        $productosSinCount = DB::table('siat_productos_servicios')->count();

        return view('admin.siat.index', compact('config', 'leyendasCount', 'productosSinCount'));
    }

    /**
     * Actualiza los parámetros de configuración.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nit' => 'required|string|max:30',
            'token_delegado' => 'required|string',
            'ambiente' => 'required|in:piloto,produccion',
            'modalidad' => 'required|in:computarizada,electronica',
            'codigo_sucursal' => 'required|integer|min:0',
            'codigo_punto_venta' => 'required|integer|min:0',
            'actividad_economica' => 'required|string|max:30',
        ]);

        $config = $this->siatService->getConfig();

        DB::table('siat_configs')->where('id', $config->id)->update([
            'nit' => $request->nit,
            'token_delegado' => $request->token_delegado,
            'ambiente' => $request->ambiente,
            'modalidad' => $request->modalidad,
            'codigo_sucursal' => $request->codigo_sucursal,
            'codigo_punto_venta' => $request->codigo_punto_venta,
            'actividad_economica' => $request->actividad_economica,
            'is_enabled' => $request->has('is_enabled'),
            'modo_prueba_sin_conexion' => $request->has('modo_prueba_sin_conexion'),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.siat.index')
            ->with('success', '✅ Configuración del SIAT actualizada con éxito.');
    }

    /**
     * Realiza un test de conexión SOAP con el SIN.
     */
    public function testConnection()
    {
        $online = $this->siatService->verificarComunicacion();
        $config = $this->siatService->getConfig();

        if ($online) {
            $status = $config->modo_prueba_sin_conexion ? 'conectado_mock' : 'conectado_real';
            $msg = $config->modo_prueba_sin_conexion 
                ? 'Conexión Exitosa (Simulado / Demostración)' 
                : 'Conexión Establecida Exitosamente con Impuestos Nacionales.';
            return response()->json(['success' => true, 'status' => $status, 'message' => $msg]);
        }

        return response()->json([
            'success' => false, 
            'message' => 'No se pudo establecer conexión. Verifique su internet o si la extensión SOAP de PHP está activa.'
        ]);
    }

    /**
     * Sincroniza los catálogos de Impuestos (productos, servicios, leyendas).
     */
    public function syncCatalogos()
    {
        try {
            $this->siatService->sincronizarCatalogos();
            return response()->json([
                'success' => true, 
                'message' => 'Catálogos sincronizados correctamente con Impuestos Nacionales.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error al sincronizar catálogos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Fuerza la renovación manual del código CUIS.
     */
    public function renewCuis()
    {
        try {
            $cuis = $this->siatService->obtenerCuis(true);
            return response()->json([
                'success' => true, 
                'cuis' => $cuis,
                'message' => 'Nuevo código CUIS obtenido exitosamente.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error al obtener CUIS: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Fuerza la renovación manual del código CUFD.
     */
    public function renewCufd()
    {
        try {
            $cufd = $this->siatService->obtenerCufd(true);
            return response()->json([
                'success' => true, 
                'cufd' => $cufd['codigo'],
                'message' => 'Nuevo código de facturación diaria CUFD obtenido exitosamente.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error al obtener CUFD: ' . $e->getMessage()
            ]);
        }
    }
}
