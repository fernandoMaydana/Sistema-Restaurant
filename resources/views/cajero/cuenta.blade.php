@extends('layouts.app')

@section('content')
{{-- Botones de control visibles solo en pantalla --}}
<div class="container py-3 d-print-none text-center">
    <a href="{{ route('cajero.salon') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Volver al Salón
    </a>
</div>

{{-- ════════════════════════════════════════════════════════════════
     VISTA ÚNICA: FORMATO TICKET TÉRMICO (PANTALLA Y PAPEL)
     ════════════════════════════════════════════════════════════════ --}}
<div class="ticket-container">
    <div class="thermal-ticket">
        <div class="text-center font-monospace">
            <h4 style="margin: 0; font-size: 12pt;">RESTAURANTE</h4>
            <p style="font-size: 10pt; margin-bottom: 5px; opacity: 0.8;">DETALLE DE CONSUMO</p>
            <h2 style="margin: 5px 0; font-size: 24pt; font-weight: bold;">MESA {{ $pedido->mesa->numero }}</h2>
            
            <p style="font-size: 10pt; line-height: 1.2; margin: 10px 0;">
                --------------------------------<br>
                Mesero: {{ strtoupper($pedido->mesero->name) }}<br>
                Fecha: {{ now()->format('d/m/Y H:i') }}<br>
                --------------------------------
            </p>

            <div style="text-align: left; font-size: 11pt;">
                @foreach($pedido->detalles as $det)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>{{ $det->cantidad }} x {{ strtoupper(substr($det->nombre_mostrar, 0, 18)) }}</span>
                        <span>Bs {{ number_format($det->cantidad * $det->precio_unitario, 2) }}</span>
                    </div>
                @endforeach
                
                <div style="margin-top: 10px; border-top: 1px dashed #000; padding-top: 8px; display: flex; justify-content: space-between; font-weight: bold; font-size: 15pt;">
                    <span>TOTAL:</span>
                    <span>Bs {{ number_format($pedido->total, 2) }}</span>
                </div>
            </div>

            <p style="margin-top: 25px; font-size: 10pt; border-top: 1px dashed #eee; padding-top: 10px;">
                ¡GRACIAS POR SU VISITA!<br>
                #{{ $pedido->id }} - PRE-CUENTA<br>
                *** RESTO-SISTEMA ***
            </p>
        </div>
    </div>
</div>

<style>
/* Estilo base para pantalla */
.ticket-container {
    display: flex;
    justify-content: center;
    padding: 20px;
}

.thermal-ticket {
    background: white;
    width: 80mm;
    padding: 15px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    font-family: 'Courier New', Courier, monospace;
    color: #000;
}

@media print {
    /* Configuración de la página para impresora térmica de 80mm */
    @page {
        margin: 0; 
        size: 80mm auto; 
    }

    /* Ocultar todo lo que no sea el ticket */
    header, nav, .navbar, .d-print-none, footer, .sidebar { 
        display: none !important; 
    }
    
    /* Reset total de contenedores */
    html, body, #app, main { 
        background: white !important; 
        margin: 0 !important; 
        padding: 0 !important; 
        width: 80mm !important;
    }

    .ticket-container {
        padding: 0 !important;
        margin: 0 !important;
        width: 80mm !important;
        display: block !important;
    }

    .thermal-ticket {
        width: 100% !important; 
        max-width: 80mm !important;
        box-sizing: border-box !important;
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 5mm !important; /* Margen seguro de 5mm a cada lado para evitar cortes físicos de la impresora */
        border: none !important;
    }
}
</style>

<script>
    // Impresión automática si viene con el parámetro print=auto
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'auto') {
            window.print();
        }
    });
</script>
@endsection
