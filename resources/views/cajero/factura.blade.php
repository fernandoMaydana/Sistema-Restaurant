@extends('layouts.app')

@section('content')
<div class="container py-5 d-flex flex-column align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="text-center mb-5">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
        <h2 class="fw-bold mt-3">¡Pago Procesado Exitosamente!</h2>
        <p class="text-muted fs-5">La mesa ha sido liberada y la factura generada.</p>
    </div>

    <div class="card border-0 shadow-sm p-4 rounded-4 mb-4 bg-white border border-success-subtle w-100" style="max-width: 400px;">
        <div class="mb-3 text-center">
            <span class="text-muted small text-uppercase fw-bold">
                <i class="bi bi-receipt-cutoff me-1 text-success"></i> Resumen de Pago
            </span>
        </div>
        <div class="d-flex justify-content-between mb-2 fs-6">
            <span class="text-muted">Total a Pagar:</span>
            <strong class="text-dark">Bs {{ number_format($factura->monto_pagado, 2) }}</strong>
        </div>
        <div class="d-flex justify-content-between mb-2 fs-6">
            <span class="text-muted">Monto Recibido ({{ ucfirst($factura->metodo_pago) }}):</span>
            <strong class="text-dark">Bs {{ number_format($factura->efectivo_recibido ?? $factura->monto_pagado, 2) }}</strong>
        </div>
        <hr class="my-2 opacity-10">
        <div class="d-flex justify-content-between align-items-center mt-2">
            <span class="fw-bold text-success fs-5">Cambio a Devolver:</span>
            <span class="fs-2 fw-black text-success">Bs {{ number_format(max(0, ($factura->efectivo_recibido ?? $factura->monto_pagado) - $factura->monto_pagado), 2) }}</span>
        </div>
    </div>

    @if($factura->cuf)
        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4 bg-white w-100" style="max-width: 400px; border-top: 5px solid #4361ee !important;">
            <div class="mb-3 text-center">
                <span class="badge bg-primary text-uppercase px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-1"></i> SIAT Factura en Línea
                </span>
            </div>
            <div class="mb-2">
                <small class="text-muted d-block">Número de Factura SIN:</small>
                <strong class="text-dark">{{ $factura->numero_factura_siat }}</strong>
            </div>
            <div class="mb-2">
                <small class="text-muted d-block">Código Único de Factura (CUF):</small>
                <div class="p-2 border rounded bg-light font-monospace text-break" style="font-size: 0.75rem;">
                    {{ $factura->cuf }}
                </div>
            </div>
            <div class="mb-2">
                <small class="text-muted d-block">Estado SIAT:</small>
                @if($factura->estado_siat === 'pendiente')
                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle-fill me-1"></i> Contingencia Offline</span>
                @else
                    <span class="badge bg-success"><i class="bi bi-check-all me-1"></i> Validada</span>
                @endif
            </div>
            <div class="text-center mt-3 p-2 bg-light rounded-3">
                <small class="text-muted d-block mb-2">Código QR de Verificación:</small>
                @php
                    $nit = \Illuminate\Support\Facades\DB::table('siat_configs')->value('nit') ?? '1020304050';
                    $qrUrl = "https://siat.impuestos.gob.bo/consulta/QR?nit={$nit}&cuf={$factura->cuf}&numero={$factura->numero_factura_siat}&t=1";
                @endphp
                <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl={{ urlencode($qrUrl) }}&choe=UTF-8" alt="QR SIN" class="img-fluid border p-1 bg-white" style="width: 130px; height: 130px;">
            </div>
            <div class="mt-3 text-center border-top pt-2">
                <small class="text-muted font-italic" style="font-size: 0.8rem;">{{ $factura->leyenda_sin }}</small>
            </div>
        </div>
    @endif

    <div class="d-flex flex-column gap-3 w-100" style="max-width: 400px;">
        <button onclick="imprimirFacturaDirecta()" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm rounded-4" id="btn-imprimir-factura">
            <i class="bi bi-printer me-2"></i> IMPRIMIR FACTURA
        </button>
        
        <a href="{{ route('cajero.salon') }}" class="btn btn-outline-secondary btn-lg fw-bold py-3 shadow-sm rounded-4">
            <i class="bi bi-grid-3x3-gap me-2"></i> VOLVER AL SALÓN
        </a>
    </div>
</div>

<script>
    function imprimirFacturaDirecta() {
        const btn = document.getElementById('btn-imprimir-factura');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Imprimiendo...';
        btn.disabled = true;

        fetch('{{ route('cajero.api.imprimir.factura', $factura->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) {
                btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>¡Enviado!';
                btn.classList.replace('btn-primary', 'btn-success');
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.replace('btn-success', 'btn-primary');
                    btn.disabled = false;
                }, 2000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Impresora',
                    text: data.message,
                    confirmButtonColor: '#e63946'
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(e => {
            console.error("Error de red.", e);
            Swal.fire({
                icon: 'error',
                title: 'Error de Red',
                text: 'Hubo un error de red al intentar comunicarse con la ticketera.',
                confirmButtonColor: '#e63946'
            });
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>

<style>
    .fw-black { font-weight: 900; }
</style>
@endsection
