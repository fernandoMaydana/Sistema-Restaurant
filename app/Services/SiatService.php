<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\Producto;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Exception;

class SiatService
{
    protected $config;

    public function __construct()
    {
        $this->config = DB::table('siat_configs')->first();
    }

    /**
     * Obtiene la configuración actual del SIAT.
     */
    public function getConfig()
    {
        if (!$this->config) {
            // Configuración por defecto si no existe
            DB::table('siat_configs')->insert([
                'nit' => '1020304050',
                'token_delegado' => 'TOKEN_MOCK_SIAT_1234567890',
                'ambiente' => 'piloto',
                'modalidad' => 'computarizada',
                'codigo_sucursal' => 0,
                'codigo_punto_venta' => 0,
                'actividad_economica' => '561010', // Servicios de restaurantes
                'is_enabled' => false,
                'modo_prueba_sin_conexion' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->config = DB::table('siat_configs')->first();
        }
        return $this->config;
    }

    /**
     * Verifica si la facturación en línea está habilitada.
     */
    public function isEnabled()
    {
        return $this->getConfig()->is_enabled ?? false;
    }

    /**
     * Verifica si está en modo de simulación sin conexión.
     */
    public function isMockMode()
    {
        return $this->getConfig()->modo_prueba_sin_conexion ?? true;
    }

    /**
     * Paso 1: Verificar comunicación con Impuestos Nacionales.
     */
    public function verificarComunicacion()
    {
        if ($this->isMockMode()) {
            return true;
        }

        try {
            // URL WSDL de ejemplo para verificar comunicación
            $url = $this->config->ambiente === 'produccion' 
                ? 'https://siatapi.impuestos.gob.bo/jacaranda/FacturacionSincronizacion?wsdl'
                : 'https://pilotosiat.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl';
                
            $client = new \SoapClient($url, [
                'trace' => true,
                'exceptions' => true,
                'connection_timeout' => 5
            ]);
            
            // Si el cliente Soap se crea correctamente, hay conexión
            return true;
        } catch (Exception $e) {
            \Log::error("SIAT: Error de comunicación: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Paso 2: Obtener o renovar el código CUIS.
     * El CUIS identifica al punto de venta y sucursal y dura 365 días.
     */
    public function obtenerCuis($forceRenew = false)
    {
        $config = $this->getConfig();

        // Si ya tenemos un CUIS vigente por menos de 360 días, usarlo
        if (!$forceRenew && $config->cuis && $config->cuis_creado_el && Carbon::parse($config->cuis_creado_el)->addDays(360)->isFuture()) {
            return $config->cuis;
        }

        if ($this->isMockMode()) {
            $fakeCuis = "CUIS-" . strtoupper(bin2hex(random_bytes(8)));
            DB::table('siat_configs')->where('id', $config->id)->update([
                'cuis' => $fakeCuis,
                'cuis_creado_el' => now(),
                'updated_at' => now(),
            ]);
            $this->config = DB::table('siat_configs')->first();
            return $fakeCuis;
        }

        try {
            // Aquí iría el consumo SOAP real a SolicitudCuis
            // Por ejemplo:
            // $url = $this->getWsdlUrl('FacturacionCodigos');
            // ... implementar llamada SOAP ...
            throw new Exception("Llamadas SOAP reales no configuradas. Active el modo demostración.");
        } catch (Exception $e) {
            \Log::error("SIAT Error al obtener CUIS: " . $e->getMessage());
            return $config->cuis; // Retornar el existente en caso de falla
        }
    }

    /**
     * Paso 3: Obtener el código de facturación diaria CUFD.
     * El CUFD dura 24 horas y habilita la emisión de facturas.
     */
    public function obtenerCufd($forceRenew = false)
    {
        $config = $this->getConfig();
        $cuis = $this->obtenerCuis();

        if (!$cuis) {
            throw new Exception("No se puede obtener el CUFD sin un CUIS válido.");
        }

        // Si ya tenemos un CUFD vigente, usarlo
        if (!$forceRenew && $config->cufd_codigo && $config->cufd_expiracion && Carbon::parse($config->cufd_expiracion)->isFuture()) {
            return [
                'codigo' => $config->cufd_codigo,
                'codigo_control' => $config->cufd_codigo_control
            ];
        }

        if ($this->isMockMode()) {
            $fakeCufd = "CUFD-" . strtoupper(bin2hex(random_bytes(16)));
            $fakeControl = strtoupper(substr(md5(uniqid()), 0, 10));
            $expiracion = now()->addHours(24);

            DB::table('siat_configs')->where('id', $config->id)->update([
                'cufd_codigo' => $fakeCufd,
                'cufd_codigo_control' => $fakeControl,
                'cufd_expiracion' => $expiracion,
                'updated_at' => now(),
            ]);
            $this->config = DB::table('siat_configs')->first();
            return [
                'codigo' => $fakeCufd,
                'codigo_control' => $fakeControl
            ];
        }

        try {
            // Aquí iría el consumo SOAP real a SolicitudCufd
            throw new Exception("Llamadas SOAP reales no configuradas. Active el modo demostración.");
        } catch (Exception $e) {
            \Log::error("SIAT Error al obtener CUFD: " . $e->getMessage());
            // Si falla, intentamos devolver el actual si no está muy vencido
            if ($config->cufd_codigo) {
                return [
                    'codigo' => $config->cufd_codigo,
                    'codigo_control' => $config->cufd_codigo_control
                ];
            }
            throw $e;
        }
    }

    /**
     * Genera el correlativo de factura local.
     */
    public function obtenerSiguienteNumeroFactura()
    {
        $max = Factura::max('numero_factura_siat');
        return ($max ?? 0) + 1;
    }

    /**
     * Calcula el CUF (Código Único de Factura).
     * Sigue el algoritmo de concatenación y control Mod11 del SIN Bolivia.
     */
    public function generarCuf($numeroFactura, $fechaHora, $montoTotal, $tipoEmision = 1)
    {
        $config = $this->getConfig();
        
        $nit = str_pad($config->nit ?? '1000000000', 13, '0', STR_PAD_LEFT);
        
        // Formato fecha: YYYYMMDDHHMMSSsss
        $fechaFormatted = Carbon::parse($fechaHora)->format('YmdHis000');
        
        $sucursal = str_pad($config->codigo_sucursal, 4, '0', STR_PAD_LEFT);
        
        // Modalidad: 1 = Electrónica en línea, 2 = Computarizada en línea
        $modality = $config->modalidad === 'electronica' ? '1' : '2';
        
        // Tipo de Emisión: 1 = Online, 2 = Offline (Contingencia)
        $emision = (string)$tipoEmision;
        
        // Tipo Documento Sector: 1 = Compra Venta
        $documentoSector = '01';
        
        $numFact = str_pad($numeroFactura, 10, '0', STR_PAD_LEFT);
        
        $puntoVenta = str_pad($config->codigo_punto_venta, 4, '0', STR_PAD_LEFT);

        // Concatenamos la cadena base numérica
        // NIT (13) + FechaHora (17) + Sucursal (4) + Modalidad (1) + Tipo Emisión (1) + Tipo Doc Sector (2) + Nro Factura (10) + Punto Venta (4)
        $cadenaBase = $nit . $fechaFormatted . $sucursal . $modality . $emision . $documentoSector . $numFact . $puntoVenta;

        // Calculamos el dígito verificador Modulo 11
        $digitoVerificador = $this->calcularModulo11($cadenaBase);
        $cadenaCompleta = $cadenaBase . $digitoVerificador;

        // Convertimos la cadena numérica de 53 dígitos a Base 16 (Hexadecimal)
        $cufHex = $this->convertirABase16($cadenaCompleta);

        // Concatenamos el código de control del CUFD al final del CUF en Hexadecimal
        $cufdControl = $config->cufd_codigo_control ?? '';
        
        return strtoupper($cufHex . $cufdControl);
    }

    /**
     * Algoritmo de Módulo 11 para obtener el dígito verificador del SIN.
     */
    protected function calcularModulo11($cadena)
    {
        $mult = 2;
        $sum = 0;
        for ($i = strlen($cadena) - 1; $i >= 0; $i--) {
            $sum += (int)$cadena[$i] * $mult;
            $mult++;
            if ($mult > 9) {
                $mult = 2;
            }
        }
        $mod = $sum % 11;
        if ($mod == 0) {
            return 0;
        } elseif ($mod == 1) {
            return 9; // En la especificación SIN, si el residuo es 10 (cuando resta de 11) se asume 9
        } else {
            return 11 - $mod;
        }
    }

    /**
     * Convierte una cadena de dígitos de longitud arbitraria a base 16 (hexadecimal).
     * Usamos BC Math si está disponible para manejar números gigantes.
     */
    protected function convertirABase16($numeroString)
    {
        if (function_exists('bcdiv')) {
            $hex = '';
            $number = $numeroString;
            while (bccomp($number, '0') > 0) {
                $remainder = bcmod($number, '16');
                $hex = dechex((int)$remainder) . $hex;
                $number = bcdiv($number, '16', 0);
            }
            return $hex;
        } else {
            // Fallback usando float de precisión (menos seguro para números de 53 dígitos, pero útil si falta bcmath)
            return dechex((float)$numeroString);
        }
    }

    /**
     * Construye la estructura XML en base al Anexo Técnico (Factura Compra y Venta).
     */
    public function generarFacturaXml($factura, $cuf, $cufdCodigo, $nroFactura)
    {
        $config = $this->getConfig();
        $pedido = Pedido::with('detalles.producto')->findOrFail($factura->pedido_id);
        
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><facturaComputarizadaCompraVenta xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="facturaComputarizadaCompraVenta.xsd"></facturaComputarizadaCompraVenta>');
        
        $cabecera = $xml->addChild('cabecera');
        $cabecera->addChild('nitEmisor', $config->nit ?? '1020304050');
        $cabecera->addChild('razonSocialEmisor', 'RESTAURANTE PROFESIONAL');
        $cabecera->addChild('municipio', 'Santa Cruz');
        $cabecera->addChild('telefono', '77000000');
        $cabecera->addChild('numeroFactura', $nroFactura);
        $cabecera->addChild('cuf', $cuf);
        $cabecera->addChild('cufd', $cufdCodigo);
        $cabecera->addChild('codigoSucursal', $config->codigo_sucursal);
        $cabecera->addChild('direccion', 'Av. Principal #123');
        $cabecera->addChild('codigoPuntoVenta', $config->codigo_punto_venta);
        $cabecera->addChild('fechaEmision', Carbon::parse($factura->created_at)->format('Y-m-d\TH:i:s.v'));
        $cabecera->addChild('nombreRazonSocial', htmlspecialchars($factura->cliente_nombre ?? 'CONSUMIDOR FINAL'));
        
        // Mapeo tipo documento de identidad SIN
        // Si el NIT tiene longitud > 6 y es puramente numérico, es probable un NIT (5), de lo contrario CI (1)
        $tipoDoc = 1; // CI
        $nitCi = preg_replace('/[^0-9]/', '', $factura->cliente_nit_ci ?? '');
        if (strlen($nitCi) >= 8) {
            $tipoDoc = 5; // NIT
        }
        $cabecera->addChild('codigoTipoDocumentoIdentidad', $tipoDoc);
        $cabecera->addChild('numeroDocumento', $factura->cliente_nit_ci ?? '99001'); // 99001 es Consumidor sin nombre en SIN
        $cabecera->addChild('codigoCliente', 'CLI-' . ($factura->cliente_nit_ci ?? '99001'));
        
        // Metodo Pago SIN: 1 = Efectivo, 2 = Tarjeta, 7 = Transferencia, 39 = QR
        $metodoPagoSin = 1;
        if ($factura->metodo_pago === 'tarjeta') $metodoPagoSin = 2;
        if ($factura->metodo_pago === 'transferencia') $metodoPagoSin = 7;
        if ($factura->metodo_pago === 'qr') $metodoPagoSin = 39;
        
        $cabecera->addChild('codigoMetodoPago', $metodoPagoSin);
        $cabecera->addChild('montoTotal', number_format($factura->monto_pagado, 2, '.', ''));
        $cabecera->addChild('montoTotalSujetoIva', number_format($factura->monto_pagado, 2, '.', ''));
        $cabecera->addChild('codigoMoneda', 1); // 1 = Bolivianos
        $cabecera->addChild('tipoCambio', 1.0);
        $cabecera->addChild('montoTotalMoneda', number_format($factura->monto_pagado, 2, '.', ''));
        $cabecera->addChild('descuentoAdicional', number_format($factura->descuento ?? 0, 2, '.', ''));
        
        $leyenda = $factura->leyenda_sin ?? 'Ley N° 453: Tienes derecho a un trato equitativo sin discriminación.';
        $cabecera->addChild('leyenda', htmlspecialchars($leyenda));
        $cabecera->addChild('usuario', htmlspecialchars(auth()->user()->name ?? 'Cajero'));
        $cabecera->addChild('codigoDocumentoSector', 1); // 1 = Compra Venta estándar

        // Detalles de Productos
        foreach ($pedido->detalles as $det) {
            $detalle = $xml->addChild('detalle');
            $detalle->addChild('actividadEconomica', $config->actividad_economica ?? '561010');
            
            // Código de producto de impuestos (por defecto 57111 = servicios de expendio de comidas, o el homologado)
            $codSin = $det->producto->codigo_sin ?? '57111';
            $detalle->addChild('codigoProductoSin', $codSin);
            $detalle->addChild('codigoProducto', 'PROD-' . $det->producto_id);
            $detalle->addChild('descripcion', htmlspecialchars($det->nombre_mostrar));
            $detalle->addChild('cantidad', $det->cantidad);
            $detalle->addChild('unidadMedida', 58); // 58 = Unidad (Servicio/Plato) en SIN
            $detalle->addChild('precioUnitario', number_format($det->precio_unitario, 2, '.', ''));
            $detalle->addChild('montoDescuento', '0.00');
            $detalle->addChild('subTotal', number_format($det->cantidad * $det->precio_unitario, 2, '.', ''));
        }

        // Formatear XML bonito
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        
        return $dom->saveXML();
    }

    /**
     * Envía la factura a Impuestos Nacionales.
     * Retorna un arreglo con el resultado de la emisión.
     */
    public function enviarFactura($factura, $tipoEmision = 1)
    {
        $config = $this->getConfig();
        
        // 1. Obtener CUFD y validar
        $cufd = $this->obtenerCufd();
        $nroFactura = $this->obtenerSiguienteNumeroFactura();
        
        // 2. Generar el CUF
        $cuf = $this->generarCuf($nroFactura, $factura->created_at, $factura->monto_pagado, $tipoEmision);
        
        // 3. Asignar leyenda aleatoria del catálogo local
        $leyenda = DB::table('siat_leyendas')->inRandomOrder()->value('descripcion') 
            ?? "Ley N° 453: El proveedor debe exhibir precios y tarifas en forma visible.";
            
        $factura->leyenda_sin = $leyenda;

        // 4. Generar el XML de la factura
        $xmlContent = $this->generarFacturaXml($factura, $cuf, $cufd['codigo'], $nroFactura);
        
        // Guardar archivo XML localmente para respaldo
        $xmlFilename = "siat/facturas/factura_{$nroFactura}_{$cuf}.xml";
        Storage::disk('public')->put($xmlFilename, $xmlContent);
        $xmlPath = Storage::url($xmlFilename);

        // Si está en modo demostración/simulado, no llamar SOAP reales
        if ($this->isMockMode()) {
            // Simulamos éxito
            $codigoRecepcion = "REC-" . strtoupper(bin2hex(random_bytes(10)));
            
            return [
                'success' => true,
                'cuf' => $cuf,
                'cufd_codigo' => $cufd['codigo'],
                'numero_factura_siat' => $nroFactura,
                'estado_siat' => 'enviada',
                'codigo_recepcion' => $codigoRecepcion,
                'leyenda_sin' => $leyenda,
                'xml_path' => $xmlPath,
                'mensaje' => 'Factura emitida y validada correctamente (Modo Simulado).'
            ];
        }

        // Si no es modo simulado, hacer la conexión SOAP real
        try {
            // 1. Gzip compresión del XML
            $gzipContent = gzencode($xmlContent, 9);
            
            // 2. Obtener el hash SHA256 del archivo comprimido
            $hashArchivo = hash('sha256', $gzipContent);

            // 3. Establecer conexión SOAP real
            $url = $config->ambiente === 'produccion'
                ? 'https://siatapi.impuestos.gob.bo/jacaranda/FacturacionCompraVenta?wsdl'
                : 'https://pilotosiat.impuestos.gob.bo/v2/FacturacionCompraVenta?wsdl';

            $client = new \SoapClient($url, [
                'trace' => true,
                'exceptions' => true,
                'connection_timeout' => 10
            ]);

            // Configurar parámetros SOAP basados en el anexo técnico
            $params = [
                'SolicitudServicioRecepcionFactura' => [
                    'codigoAmbiente' => $config->ambiente === 'produccion' ? 1 : 2,
                    'codigoDocumentoSector' => 1,
                    'codigoEmision' => $tipoEmision,
                    'codigoModalidad' => $config->modalidad === 'electronica' ? 1 : 2,
                    'codigoPuntoVenta' => $config->codigo_punto_venta,
                    'codigoSistema' => 'RESTO-SIAT-DELEGADO', // Reemplazar con el autorizado
                    'codigoSucursal' => $config->codigo_sucursal,
                    'cufd' => $cufd['codigo'],
                    'cuis' => $config->cuis,
                    'nit' => $config->nit,
                    'tipoFacturaDocumento' => 1, // Con derecho a crédito fiscal
                    'archivo' => $gzipContent,
                    'hashArchivo' => $hashArchivo
                ]
            ];

            // Configurar API Key / Token Delegado en el header SOAP
            $header = new \SoapHeader(
                'http://servicios.facturacion.sin.gob.bo', 
                'apikey', 
                "TokenApi " . $config->token_delegado
            );
            $client->__setSoapHeaders([$header]);

            // Enviar la petición
            $response = $client->recepcionFactura($params);
            
            $resultado = $response->RespuestaServicioFacturacion ?? null;
            
            if ($resultado && $resultado->codigoEstado == 908) { // 908 = VALIDADA exitosamente en SIN
                return [
                    'success' => true,
                    'cuf' => $cuf,
                    'cufd_codigo' => $cufd['codigo'],
                    'numero_factura_siat' => $nroFactura,
                    'estado_siat' => 'enviada',
                    'codigo_recepcion' => $resultado->codigoRecepcion,
                    'leyenda_sin' => $leyenda,
                    'xml_path' => $xmlPath,
                    'mensaje' => 'Factura validada correctamente por Impuestos Nacionales.'
                ];
            } else {
                // Factura rechazada por negocio
                $errores = [];
                if (isset($resultado->mensajesList)) {
                    $mensajes = is_array($resultado->mensajesList) ? $resultado->mensajesList : [$resultado->mensajesList];
                    foreach ($mensajes as $msg) {
                        $errores[] = "Código {$msg->codigo}: {$msg->descripcion}";
                    }
                }
                $errorStr = implode("; ", $errores);
                return [
                    'success' => false,
                    'estado_siat' => 'rechazada',
                    'mensaje' => 'Factura rechazada por el SIN. Detalles: ' . ($errorStr ?: 'Desconocido')
                ];
            }

        } catch (Exception $e) {
            \Log::error("SIAT Error de envío a SIN: " . $e->getMessage());
            
            // Falla de comunicación/red -> Activar modo Contingencia offline
            return [
                'success' => true, // Lo consideramos éxito local para no trancar la venta en la caja
                'cuf' => $cuf,
                'cufd_codigo' => $cufd['codigo'],
                'numero_factura_siat' => $nroFactura,
                'estado_siat' => 'pendiente', // Marcar como pendiente para envío posterior
                'codigo_recepcion' => null,
                'leyenda_sin' => $leyenda,
                'xml_path' => $xmlPath,
                'mensaje' => '⚠️ Error de conexión con el SIN. Factura guardada en Contingencia para envío posterior.'
            ];
        }
    }

    /**
     * Envía la solicitud de anulación de factura al SIN.
     */
    public function anularFactura($factura, $codigoMotivo = 1)
    {
        $config = $this->getConfig();
        if (!$factura->cuf) {
            throw new Exception("La factura no tiene un código CUF asociado.");
        }

        if ($this->isMockMode()) {
            return [
                'success' => true,
                'mensaje' => 'Factura anulada correctamente en el SIAT (Modo Simulado).'
            ];
        }

        try {
            $url = $config->ambiente === 'produccion'
                ? 'https://siatapi.impuestos.gob.bo/jacaranda/FacturacionCompraVenta?wsdl'
                : 'https://pilotosiat.impuestos.gob.bo/v2/FacturacionCompraVenta?wsdl';

            $client = new \SoapClient($url, [
                'trace' => true,
                'exceptions' => true,
                'connection_timeout' => 10
            ]);

            $cufd = $this->obtenerCufd();

            $params = [
                'SolicitudServicioAnulacionFactura' => [
                    'codigoAmbiente' => $config->ambiente === 'produccion' ? 1 : 2,
                    'codigoDocumentoSector' => 1,
                    'codigoEmision' => 1,
                    'codigoModalidad' => $config->modalidad === 'electronica' ? 1 : 2,
                    'codigoPuntoVenta' => $config->codigo_punto_venta,
                    'codigoSistema' => 'RESTO-SIAT-DELEGADO',
                    'codigoSucursal' => $config->codigo_sucursal,
                    'cufd' => $cufd['codigo'],
                    'cuis' => $config->cuis,
                    'nit' => $config->nit,
                    'tipoFacturaDocumento' => 1,
                    'cuf' => $factura->cuf,
                    'codigoMotivo' => $codigoMotivo // Ej. 1 = Factura mal emitida, 2 = Devolución
                ]
            ];

            $header = new \SoapHeader(
                'http://servicios.facturacion.sin.gob.bo',
                'apikey',
                "TokenApi " . $config->token_delegado
            );
            $client->__setSoapHeaders([$header]);

            $response = $client->anulacionFactura($params);
            $resultado = $response->RespuestaServicioAnulacion ?? null;

            if ($resultado && $resultado->codigoEstado == 905) { // 905 = ANULACION ACEPTADA
                return [
                    'success' => true,
                    'mensaje' => 'Factura anulada con éxito en Impuestos Nacionales.'
                ];
            } else {
                $errores = [];
                if (isset($resultado->mensajesList)) {
                    $mensajes = is_array($resultado->mensajesList) ? $resultado->mensajesList : [$resultado->mensajesList];
                    foreach ($mensajes as $msg) {
                        $errores[] = "Código {$msg->codigo}: {$msg->descripcion}";
                    }
                }
                $errorStr = implode("; ", $errores);
                throw new Exception("Error al anular en SIN: " . ($errorStr ?: 'Desconocido'));
            }

        } catch (Exception $e) {
            \Log::error("SIAT Error de anulación: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Descarga y guarda localmente los catálogos del SIN.
     */
    public function sincronizarCatalogos()
    {
        $config = $this->getConfig();

        if ($this->isMockMode()) {
            // Llenar datos de prueba locales
            DB::table('siat_productos_servicios')->truncate();
            DB::table('siat_productos_servicios')->insert([
                ['codigo_actividad' => '561010', 'codigo_producto' => '57111', 'descripcion' => 'Servicios de expendio de platos preparados (comida para restaurante)', 'created_at' => now(), 'updated_at' => now()],
                ['codigo_actividad' => '561010', 'codigo_producto' => '57112', 'descripcion' => 'Servicios de expendio de bebidas no alcohólicas preparadas en el local', 'created_at' => now(), 'updated_at' => now()],
                ['codigo_actividad' => '561010', 'codigo_producto' => '57113', 'descripcion' => 'Servicios de expendio de bebidas alcohólicas preparadas en el local', 'created_at' => now(), 'updated_at' => now()],
                ['codigo_actividad' => '561010', 'codigo_producto' => '57114', 'descripcion' => 'Servicios de entrega a domicilio de platos preparados (delivery)', 'created_at' => now(), 'updated_at' => now()],
            ]);

            DB::table('siat_leyendas')->truncate();
            DB::table('siat_leyendas')->insert([
                ['codigo_actividad' => '561010', 'descripcion' => 'Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices.', 'created_at' => now(), 'updated_at' => now()],
                ['codigo_actividad' => '561010', 'descripcion' => 'Ley N° 453: Tienes derecho a un trato equitativo sin discriminación en la prestación de servicios.', 'created_at' => now(), 'updated_at' => now()],
                ['codigo_actividad' => '561010', 'descripcion' => 'Ley N° 453: El proveedor debe exhibir precios y tarifas de forma visible.', 'created_at' => now(), 'updated_at' => now()],
            ]);

            return true;
        }

        try {
            // Aquí iría el consumo SOAP real de FacturacionSincronizacion
            // sincronizarProductosServicios() y sincronizarParametricaLeyendas()
            throw new Exception("Llamadas SOAP reales no configuradas. Active el modo demostración.");
        } catch (Exception $e) {
            \Log::error("SIAT Error de sincronización de catálogos: " . $e->getMessage());
            throw $e;
        }
    }
}
