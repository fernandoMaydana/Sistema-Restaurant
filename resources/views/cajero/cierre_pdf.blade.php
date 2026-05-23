<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cierre #{{ $caja->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; margin: 0; }
        .subtitle { font-size: 14px; margin: 5px 0 0 0; color: #666; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 14px; font-weight: bold; background: #eee; padding: 5px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { padding: 8px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-danger { color: #dc3545; }
        .text-success { color: #198754; }
        .text-primary { color: #0d6efd; }
        .summary-box { background: #f8f9fa; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>

<div class="header">
    <h1 class="title">REPORTE DE CIERRE DE CAJA</h1>
    <p class="subtitle">Sesión #{{ $caja->id }} - Cajero: {{ $caja->user->name }}</p>
    <p style="margin-top: 5px; font-size: 11px;">
        Apertura: {{ \Carbon\Carbon::parse($caja->fecha_apertura)->format('d/m/Y H:i') }} | 
        Cierre: {{ \Carbon\Carbon::parse($caja->fecha_cierre ?? now())->format('d/m/Y H:i') }}
    </p>
</div>

<div class="section summary-box">
    <h2 class="section-title" style="background: none; border-bottom: 1px solid #ccc; padding-left: 0;">Resumen Financiero</h2>
    <table>
        <tr>
            <td width="50%">Monto Inicial:</td>
            <td width="50%" class="text-right font-bold">Bs {{ number_format($caja->monto_inicial, 2) }}</td>
        </tr>
        <tr>
            <td>Ventas Totales del Turno (+):</td>
            <td class="text-right font-bold text-success">Bs {{ number_format($totalRecaudado, 2) }}</td>
        </tr>
        <tr>
            <td>Gastos Registrados (-):</td>
            <td class="text-right font-bold text-danger">-Bs {{ number_format($totalGastos, 2) }}</td>
        </tr>
        <tr>
            <td style="border-top: 2px solid #333; padding-top: 10px;" class="font-bold">EFECTIVO ESTIMADO EN CAJA:</td>
            <td style="border-top: 2px solid #333; padding-top: 10px;" class="text-right font-bold text-primary" style="font-size: 16px;">
                Bs {{ number_format($caja->monto_final, 2) }}
            </td>
        </tr>
    </table>
</div>

<div class="section">
    <h2 class="section-title">Desglose de Ventas por Método de Pago</h2>
    <table>
        <tr>
            <td width="70%">Efectivo</td>
            <td class="text-right">Bs {{ number_format($ventasPorMetodo['efectivo'], 2) }}</td>
        </tr>
        <tr>
            <td>QR / Transferencia</td>
            <td class="text-right">Bs {{ number_format($ventasPorMetodo['qr'] + $ventasPorMetodo['transferencia'], 2) }}</td>
        </tr>
        <tr>
            <td>Tarjeta</td>
            <td class="text-right">Bs {{ number_format($ventasPorMetodo['tarjeta'], 2) }}</td>
        </tr>
        <tr>
            <td class="font-bold text-right">TOTAL:</td>
            <td class="font-bold text-right">Bs {{ number_format($totalRecaudado, 2) }}</td>
        </tr>
    </table>
</div>

@if($gastos->count() > 0)
<div class="section">
    <h2 class="section-title text-danger">Detalle de Gastos</h2>
    <table>
        <thead>
            <tr>
                <th>Descripción / Motivo</th>
                <th class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gastos as $gasto)
                <tr>
                    <td>{{ $gasto->descripcion }}</td>
                    <td class="text-right text-danger">-Bs {{ number_format($gasto->monto, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="section" style="page-break-before: auto;">
    <h2 class="section-title">Resumen de Productos Vendidos</h2>
    
    @foreach($resumen as $categoria => $productos)
        <h4 style="margin-bottom: 5px; color: #555;">{{ strtoupper($categoria) }}</h4>
        <table style="margin-top: 0;">
            <tbody>
                @foreach($productos as $nombre => $cantidad)
                    <tr>
                        <td width="80%">{{ $nombre }}</td>
                        <td width="20%" class="text-right font-bold">x{{ $cantidad }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</div>

<div style="text-align: center; margin-top: 40px; color: #777; font-size: 10px;">
    Documento generado por RESTO-SISTEMA el {{ now()->format('d/m/Y a las H:i') }}
</div>

</body>
</html>
