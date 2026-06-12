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
            @if($pedido->mesa->es_para_llevar)
                <h2 style="font-size: 24pt; margin: 5px 0; font-weight: bold; background: #000; color: #fff; padding: 5px;">* PARA LLEVAR *</h2>
                <h1 style="font-size: 36pt; margin: 5px 0; font-weight: bold;">PEDIDO {{ $pedido->mesa->numero }}</h1>
            @else
                <h1 style="font-size: 32pt; margin: 5px 0; font-weight: bold;">MESA {{ $pedido->mesa->numero }}</h1>
            @endif
            
            <p style="font-size: 8pt; line-height: 1.2; margin: 5px 0; opacity: 0.85;">
                ------------------------<br>
                Mesero: {{ strtoupper($pedido->mesero->name) }}<br>
                Fecha: {{ now()->format('d/m/Y H:i') }}<br>
                ------------------------
            </p>

            <div style="text-align: left; font-size: 11pt;">
                @forelse($pedido->detalles as $det)
                    <div style="margin-bottom: 6px; border-bottom: 1px dashed #eee; padding-bottom: 4px;">
                        <div style="display: flex; align-items: flex-start; font-size: 12pt;">
                            <span style="font-weight: 900; margin-right: 12px; min-width: 22px;">{{ $det->cantidad }}</span>
                            <span style="font-weight: bold; flex-grow: 1;">{{ strtoupper($det->nombre_mostrar) }}</span>
                        </div>
                        @if($det->notas)
                            <div style="font-size: 9.5pt; color: #333; font-style: italic; margin-top: 2px; font-weight: bold; padding-left: 34px;">
                                * NOTA: {{ strtoupper($det->notas) }}
                            </div>
                        @endif
                    </div>
                @empty
                    <p style="text-align: center; color: #666;">SIN ÍTEMS PENDIENTES</p>
                @endforelse
            </div>

            <p style="margin-top: 15px; border-top: 1px dashed #000; padding-top: 5px; font-size: 8pt;">
                #{{ $pedido->id }} - COPIA COCINA<br>
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
    width: 72mm;
    padding: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    font-family: 'Courier New', Courier, monospace;
    color: #000;
}

@media print {
    @page {
        margin: 0; 
        /* Eliminamos el size para evitar que el navegador asuma alturas raras y centre verticalmente */
    }

    /* Ocultar todo lo que no sea el ticket */
    header, nav, .navbar, .d-print-none, footer, .sidebar { 
        display: none !important; 
    }
    
    /* Reset total para evitar espacios en blanco (gasto de papel) */
    html, body, #app, main { 
        background: white !important; 
        margin: 0 !important; 
        padding: 0 !important; 
        width: 100% !important;
        height: auto !important; /* Forzar altura automática para que no centre el ticket en hojas largas */
        min-height: 0 !important;
    }

    .ticket-container {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        display: block !important;
    }

    .thermal-ticket {
        width: 65mm !important; /* Reducimos a 65mm para garantizar que nunca se corte el lado derecho */
        max-width: 65mm !important;
        box-sizing: border-box !important;
        box-shadow: none !important;
        margin: 0 !important; 
        padding: 0 2mm !important; 
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
