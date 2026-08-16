@extends('layouts.app')

@section('content')
<div class="container py-3 d-print-none" style="max-width: 600px;">
    <div class="text-center mb-4">
        <div class="badge bg-success p-2 px-3 rounded-pill mb-2">CAJA CERRADA EXITOSAMENTE</div>
        <h2 class="fw-bold">Reporte de Cierre</h2>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Cajero:</span>
                <span class="fw-bold">{{ $caja->user->name }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Fecha Apertura:</span>
                <span>{{ \Carbon\Carbon::parse($caja->fecha_apertura)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="text-muted">Fecha Cierre:</span>
                <span>{{ \Carbon\Carbon::parse($caja->fecha_cierre)->format('d/m/Y H:i') }}</span>
            </div>

            <hr>

            <div class="row text-center my-4">
                <div class="col-6 border-end">
                    <div class="text-muted small text-uppercase">Monto Inicial</div>
                    <div class="h4 fw-bold mb-0">Bs {{ number_format($caja->monto_inicial, 2) }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small text-uppercase">Ventas Totales</div>
                    <div class="h4 fw-bold mb-0 text-success">Bs {{ number_format($totalRecaudado, 2) }}</div>
                </div>
            </div>

            <div class="bg-success text-white p-3 rounded-4 text-center mb-4 shadow-sm">
                <div class="text-uppercase small opacity-75 fw-bold">1. Monto Total Vendido del Día</div>
                <div class="h2 fw-bold mb-0">Bs {{ number_format($totalRecaudado, 2) }}</div>
            </div>

            {{-- Operación de Caja --}}
            <div class="card bg-light border-0 rounded-4 mb-4">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">
                        <i class="bi bi-calculator me-2 text-primary"></i>2. Operación Balance de Caja
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-plus-circle-fill text-success me-2"></i>Base Inicial en Caja:</span>
                        <span class="fw-bold">Bs {{ number_format($caja->monto_inicial, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-plus-circle-fill text-success me-2"></i>Ventas en Efectivo:</span>
                        <span class="fw-bold text-success">+Bs {{ number_format($ventasPorMetodo['efectivo'], 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-plus-circle-fill text-info me-2"></i>Ventas QR / Transferencia:</span>
                        <span class="fw-bold text-info">+Bs {{ number_format($ventasPorMetodo['qr'] + $ventasPorMetodo['transferencia'], 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-plus-circle-fill text-info me-2"></i>Ventas Tarjeta:</span>
                        <span class="fw-bold text-info">+Bs {{ number_format($ventasPorMetodo['tarjeta'], 2) }}</span>
                    </div>
                    
                    @if($gastos->count() > 0)
                        <hr class="my-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-danger fw-bold"><i class="bi bi-dash-circle-fill me-2"></i>Gastos (-)</span>
                            <span class="fw-bold text-danger">-Bs {{ number_format($totalGastos, 2) }}</span>
                        </div>
                        <div class="ps-3 mb-2">
                            @foreach($gastos as $gasto)
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>• {{ $gasto->descripcion }}</span>
                                    <span>-Bs {{ number_format($gasto->monto, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Totales Finales --}}
            <div class="border rounded-4 p-3 mb-4 bg-white shadow-sm">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-wallet2 me-2 text-primary"></i>3. Totales y Balances Finales</h6>
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-primary bg-opacity-10 rounded-3">
                    <span class="fw-bold text-primary"><i class="bi bi-cash-stack me-2"></i>Efectivo en Caja:</span>
                    <span class="h5 fw-bold mb-0 text-primary">Bs {{ number_format($caja->monto_final, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-info bg-opacity-10 rounded-3">
                    <span class="fw-bold text-info"><i class="bi bi-bank me-2"></i>Total Bancos / Digital:</span>
                    <span class="h5 fw-bold mb-0 text-info">Bs {{ number_format($ventasPorMetodo['qr'] + $ventasPorMetodo['transferencia'] + $ventasPorMetodo['tarjeta'], 2) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2 bg-dark text-white rounded-3">
                    <span class="fw-bold"><i class="bi bi-currency-dollar me-2"></i>TOTAL DINERO DEL DÍA:</span>
                    <span class="h5 fw-bold mb-0 text-warning">Bs {{ number_format($caja->monto_final + $ventasPorMetodo['qr'] + $ventasPorMetodo['transferencia'] + $ventasPorMetodo['tarjeta'], 2) }}</span>
                </div>
            </div>

            <h5 class="fw-bold mb-3 mt-4"><i class="bi bi-list-check me-2"></i>Resumen de Productos</h5>
            @foreach($resumen as $categoria => $productos)
                <div class="mb-3">
                    <h6 class="text-primary fw-bold text-uppercase border-bottom pb-1" style="font-size: 0.8rem;">{{ $categoria }}</h6>
                    <ul class="list-unstyled mb-0">
                        @foreach($productos as $nombre => $cantidad)
                            <li class="d-flex justify-content-between py-1">
                                <span>{{ $nombre }}</span>
                                <span class="fw-bold">x{{ $cantidad }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
        <div class="card-footer bg-white border-0 p-4">
            <button id="btn-imprimir-cierre" onclick="imprimirCierreDirecto(event, '{{ route('cajero.api.imprimir.cierre', $caja->id) }}')" class="btn btn-dark w-100 py-3 fw-bold rounded-3 mb-2">
                <i class="bi bi-printer-fill me-2"></i>IMPRIMIR TICKET TÉRMICO
            </button>
            <a href="{{ route('cajero.cierre.pdf', $caja->id) }}" class="btn btn-danger w-100 py-3 fw-bold rounded-3 mb-2">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>DESCARGAR REPORTE EN PDF
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mt-2 py-2 border-0">
                Salir al inicio
            </a>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     DISEÑO OPTIMIZADO PARA IMPRESORA TÉRMICA (TIKETERA)
     ════════════════════════════════════════════════════════════════ --}}
<div class="d-none d-print-block thermal-ticket">
    <div class="text-center font-monospace">
        <h4 style="margin-bottom: 5px;">REPORTE DE CIERRE</h4>
        <p style="font-size: 10pt; line-height: 1.2;">
            RESTO-SISTEMA<br>
            --------------------------------<br>
            Cajero: {{ $caja->user->name }}<br>
            Fecha: {{ \Carbon\Carbon::parse($caja->fecha_cierre)->format('d/m/Y H:i') }}<br>
            ================================
        </p>

        <div style="text-align: left; font-size: 11pt;">
            <div style="font-weight: bold; font-size: 12pt; display: flex; justify-content: space-between; border-bottom: 1px solid #000; padding-bottom: 3px;">
                <span>1. TOTAL VENDIDO DÍA:</span>
                <span>Bs {{ number_format($totalRecaudado, 2) }}</span>
            </div>
            
            <p style="margin: 5px 0 2px 0; font-weight: bold; font-size: 9pt;">2. OPERACIÓN DE CAJA:</p>
            <div style="font-size: 9.5pt; padding-left: 5px;">
                <div style="display: flex; justify-content: space-between;">
                    <span>(+) Base Inicial:</span>
                    <span>Bs {{ number_format($caja->monto_inicial, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>(+) Ventas Efectivo:</span>
                    <span>+Bs {{ number_format($ventasPorMetodo['efectivo'], 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>(+) Ventas QR/Trans:</span>
                    <span>+Bs {{ number_format($ventasPorMetodo['qr'] + $ventasPorMetodo['transferencia'], 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>(+) Ventas Tarjeta:</span>
                    <span>+Bs {{ number_format($ventasPorMetodo['tarjeta'], 2) }}</span>
                </div>
                
                @if($gastos->count() > 0)
                    <div style="margin-top: 3px; border-top: 1px dotted #000; padding-top: 3px;">
                        <div style="font-weight: bold; font-size: 9pt;">(-) GASTOS REGISTRADOS:</div>
                        @foreach($gastos as $gasto)
                            <div style="display: flex; justify-content: space-between; font-size: 8.5pt; color: #333;">
                                <span>• {{ substr($gasto->descripcion, 0, 18) }}</span>
                                <span>-Bs {{ number_format($gasto->monto, 2) }}</span>
                            </div>
                        @endforeach
                        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 9pt;">
                            <span>TOTAL GASTOS:</span>
                            <span>-Bs {{ number_format($totalGastos, 2) }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <div style="margin-top: 8px; border-top: 1px dashed #000; padding-top: 5px;">
                <p style="margin: 0 0 3px 0; font-weight: bold; font-size: 9pt;">3. TOTALES Y BALANCES:</p>
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 11pt; background: #eee; padding: 2px;">
                    <span>💵 EFECTIVO CAJA:</span>
                    <span>Bs {{ number_format($caja->monto_final, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 10pt; margin-top: 2px;">
                    <span>💳 TOTAL DIGITAL:</span>
                    <span>Bs {{ number_format($ventasPorMetodo['qr'] + $ventasPorMetodo['transferencia'] + $ventasPorMetodo['tarjeta'], 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 11pt; border-top: 1px double #000; margin-top: 3px; padding-top: 2px;">
                    <span>💰 TOTAL DINERO DÍA:</span>
                    <span>Bs {{ number_format($caja->monto_final + $ventasPorMetodo['qr'] + $ventasPorMetodo['transferencia'] + $ventasPorMetodo['tarjeta'], 2) }}</span>
                </div>
            </div>
        </div>

        <p style="margin-top: 15px; text-align: left; font-weight: bold; text-decoration: underline;">
            RESUMEN DE PRODUCTOS:
        </p>
        
        @foreach($resumen as $categoria => $productos)
            <div style="text-align: left; margin-bottom: 10px;">
                <span style="font-size: 9pt; font-weight: bold;">* {{ strtoupper($categoria) }} *</span>
                @foreach($productos as $nombre => $cantidad)
                    <div style="display: flex; justify-content: space-between; font-size: 10pt;">
                        <span>{{ substr($nombre, 0, 20) }}</span>
                        <span>x{{ $cantidad }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach

        <p style="margin-top: 20px; border-top: 1px dashed #000; padding-top: 10px;">
            Gracias por su jornada.<br>
            #{{ $caja->id }}
        </p>
    </div>
</div>

<style>
@media print {
    /* Ocultar todo el layout de Bootstrap */
    header, nav, .navbar, .d-print-none, footer { display: none !important; }
    
    body { 
        margin: 0; 
        padding: 0; 
        background-color: #fff;
    }

    /* Estilos específicos para el ticket térmico */
    .thermal-ticket {
        width: 80mm; /* El ancho estándar de tiqueteras grandes */
        max-width: 80mm;
        margin: 0 auto;
        padding: 5px;
        font-family: 'Courier New', Courier, monospace;
        color: #000;
    }

    /* Si es impresora de 58mm podrías ajustar acá */
    /* @page { size: 80mm 200mm; margin: 0; } */
}
</style>

<script>
    function imprimirCierreDirecto(event, url) {
        event.preventDefault();
        const btn = document.getElementById('btn-imprimir-cierre');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Imprimiendo...';
        btn.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) {
                btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>¡Enviado a impresora!';
                btn.classList.replace('btn-dark', 'btn-success');
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.replace('btn-success', 'btn-dark');
                    btn.disabled = false;
                }, 3000);
            } else {
                alert("Error de Impresora:\n" + data.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(e => {
            console.error("Error de red.", e);
            alert("Error de conexión al intentar imprimir.");
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>
@endsection
