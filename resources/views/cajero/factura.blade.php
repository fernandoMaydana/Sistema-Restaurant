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
            <span class="text-muted">Total de la Cuenta:</span>
            <strong class="text-dark">Bs {{ number_format($factura->monto_pagado, 2) }}</strong>
        </div>
        <div class="d-flex justify-content-between mb-2 fs-6">
            <span class="text-muted">Efectivo Recibido ({{ ucfirst($factura->metodo_pago) }}):</span>
            <strong class="text-dark">Bs {{ number_format($factura->efectivo_recibido ?? $factura->monto_pagado, 2) }}</strong>
        </div>
        <hr class="my-2 opacity-10">
        <div class="d-flex justify-content-between align-items-center mt-2">
            <span class="fw-bold text-success fs-5">Cambio a Devolver:</span>
            <span class="fs-2 fw-black text-success">Bs {{ number_format(max(0, ($factura->efectivo_recibido ?? $factura->monto_pagado) - $factura->monto_pagado), 2) }}</span>
        </div>
    </div>

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
                alert("Error de Impresora:\n" + data.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(e => {
            console.error("Error de red.", e);
            alert("Error de red al intentar imprimir.");
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>

<style>
    .fw-black { font-weight: 900; }
</style>
@endsection
