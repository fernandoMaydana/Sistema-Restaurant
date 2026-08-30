<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura N° {{ $factura->numero_factura_siat ?? $factura->id }}</title>
    <style>
        @page {
            margin: 5px 8px;
        }
        body {
            font-family: 'Courier', 'Helvetica', sans-serif;
            font-size: 9px;
            color: #000;
            line-height: 1.2;
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .uppercase {
            text-transform: uppercase;
        }
        .header {
            margin-bottom: 5px;
        }
        .title {
            font-size: 11px;
            font-weight: bold;
        }
        .subtitle {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .dotted-line {
            border-top: 1px dotted #000;
            margin: 4px 0;
        }
        .section-title {
            font-weight: bold;
            margin: 4px 0;
        }
        .info-table, .totals-table, .item-row-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        .info-table td {
            padding: 1px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            text-align: right;
            padding-right: 5px;
            white-space: nowrap;
        }
        .info-val {
            text-align: left;
        }
        .item-block {
            text-align: left;
            margin-bottom: 4px;
        }
        .item-name {
            font-weight: bold;
        }
        .item-unit {
            font-size: 8px;
            color: #333;
        }
        .totals-table td {
            padding: 1px 0;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .literal {
            text-align: left;
            margin: 6px 0;
            font-size: 9px;
        }
        .cuf-box {
            word-break: break-all;
            font-size: 8px;
            margin: 2px 0;
            padding: 0 4px;
        }
        .legend-box {
            font-size: 7.5px;
            margin-top: 6px;
            line-height: 1.25;
        }
        .qr-container {
            margin-top: 8px;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- ENCABEZADO FISCAL EMISOR --}}
    <div class="header">
        <div class="title">FACTURA</div>
        <div class="subtitle">CON DERECHO A CRÉDITO FISCAL</div>
        <div class="bold uppercase">{{ $siatConfig->razon_social ?? 'SISTEMA RESTAURANTE' }}</div>
        <div>CASA MATRIZ</div>
        <div>No. Punto de Venta {{ $siatConfig->codigo_punto_venta ?? 0 }}</div>
        <div>{{ $siatConfig->direccion ?? 'ESQUINA MARIO MERCADO, FRENTE AL PUMA KATARI. D Nro.: 429 Zona/Barrio.: LLOJETA ALTO' }}</div>
        <div>Teléfono: {{ $siatConfig->telefono ?? '72591021' }}</div>
        <div class="bold uppercase">{{ $siatConfig->ciudad ?? 'LA PAZ' }}</div>
    </div>

    <div class="divider"></div>

    {{-- NIT Y DATOS DE AUTORIZACIÓN --}}
    <div>
        <div class="bold">NIT</div>
        <div>{{ $siatConfig->nit ?? '4947627011' }}</div>
        <div class="bold" style="margin-top: 3px;">FACTURA N°</div>
        <div>{{ $factura->numero_factura_siat ?? str_pad($factura->id, 5, '0', STR_PAD_LEFT) }}</div>
        <div class="bold" style="margin-top: 3px;">CÓD. AUTORIZACIÓN</div>
        <div class="cuf-box">{{ $factura->cuf ?? '15287C6D33B13DFC1A378CD78CED61D7745EB3A1462121D97EBC942F74' }}</div>
    </div>

    <div class="divider"></div>

    {{-- DATOS CLIENTE --}}
    <table class="info-table">
        <tr>
            <td class="info-label" width="45%">NOMBRE/RAZÓN SOCIAL:</td>
            <td class="info-val uppercase" width="55%">{{ $factura->cliente_nombre ?? 'SN' }}</td>
        </tr>
        <tr>
            <td class="info-label">NIT/CI/CEX:</td>
            <td class="info-val">{{ $factura->cliente_nit_ci ?? '99001' }}</td>
        </tr>
        <tr>
            <td class="info-label">COD. CLIENTE:</td>
            <td class="info-val">{{ $factura->pedido?->cliente_id ?? $factura->cliente_nit_ci ?? '120' }}</td>
        </tr>
        <tr>
            <td class="info-label">FECHA DE EMISIÓN:</td>
            <td class="info-val">{{ $factura->created_at->format('d/m/Y h:i A') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- DETALLE --}}
    <div class="section-title">DETALLE</div>

    @if($factura->pedido && $factura->pedido->detalles)
        @foreach($factura->pedido->detalles as $det)
            <div class="item-block">
                <div class="item-name">{{ $det->producto?->codigo_sin ?? '100045' }} - {{ strtoupper($det->nombre_mostrar) }}</div>
                <div class="item-unit">Unidad de Medida: UNIDAD (BIENES)</div>
                <table class="item-row-table">
                    <tr>
                        <td class="text-left" width="70%">{{ number_format($det->cantidad, 2) }} X {{ number_format($det->precio_unitario, 2) }} - 0.00</td>
                        <td class="text-right" width="30%">{{ number_format($det->cantidad * $det->precio_unitario, 2) }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    @else
        <div class="item-block">
            <div class="item-name">100045 - CONSUMO</div>
            <div class="item-unit">Unidad de Medida: UNIDAD (BIENES)</div>
            <table class="item-row-table">
                <tr>
                    <td class="text-left" width="70%">1.00 X {{ number_format($factura->monto_pagado, 2) }} - 0.00</td>
                    <td class="text-right" width="30%">{{ number_format($factura->monto_pagado, 2) }}</td>
                </tr>
            </table>
        </div>
    @endif

    <div class="dotted-line"></div>

    {{-- TOTALES --}}
    <table class="totals-table">
        <tr>
            <td class="text-right" width="70%">SUBTOTAL Bs</td>
            <td class="text-right" width="30%">{{ number_format($factura->monto_pagado + ($factura->descuento ?? 0), 2) }}</td>
        </tr>
        <tr>
            <td class="text-right">DESCUENTO Bs</td>
            <td class="text-right">{{ number_format($factura->descuento ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right">TOTAL Bs</td>
            <td class="text-right">{{ number_format($factura->monto_pagado, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right">MONTO GIFT CARD Bs</td>
            <td class="text-right">0.00</td>
        </tr>
        <tr>
            <td class="text-right bold">MONTO A PAGAR Bs</td>
            <td class="text-right bold">{{ number_format($factura->monto_pagado, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right bold">IMPORTE BASE CREDITO FISCAL Bs</td>
            <td class="text-right bold">{{ number_format($factura->monto_pagado, 2) }}</td>
        </tr>
    </table>

    <div class="literal">
        Son: {{ strtoupper($montoLiteral) }}.
    </div>

    <div class="divider"></div>

    {{-- LEYENDAS FISCALES --}}
    <div class="legend-box uppercase">
        ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY
    </div>

    <div class="legend-box" style="margin-top: 5px;">
        Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y términos ofertados o convenidos.
    </div>

    <div class="legend-box" style="margin-top: 5px;">
        "Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea"
    </div>

    {{-- CÓDIGO QR --}}
    <div class="qr-container">
        @if(!empty($qrBase64))
            <img src="{{ $qrBase64 }}" alt="QR SIAT" width="130" height="130">
        @else
            @php
                $qrFallback = "https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=" . urlencode($qrUrlContent ?? 'https://siat.impuestos.gob.bo');
            @endphp
            <img src="{{ $qrFallback }}" alt="QR SIAT" width="130" height="130">
        @endif
    </div>

</body>
</html>
