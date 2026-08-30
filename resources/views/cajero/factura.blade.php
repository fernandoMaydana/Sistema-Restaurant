@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        <h2 class="fw-bold mt-2 mb-1">¡Pago Procesado Exitosamente!</h2>
        <p class="text-muted fs-6 mb-0">La mesa ha sido liberada y la factura generada.</p>
    </div>

    <div class="row g-4 justify-content-center align-items-start">
        {{-- COLUMNA IZQUIERDA: RESUMEN DE PAGO & SIAT --}}
        <div class="col-lg-6 col-xl-5">
            <div class="card border-0 shadow-sm p-4 rounded-4 mb-4 bg-white border border-success-subtle">
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
                <div class="card border-0 shadow-sm p-4 rounded-4 bg-white" style="border-top: 5px solid #4361ee !important;">
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
        </div>

        {{-- COLUMNA DERECHA: BOTONES DE ACCIÓN --}}
        <div class="col-lg-6 col-xl-5">
            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white border border-primary-subtle">
                <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i> Acciones Rápidas</h5>

                <div class="d-flex flex-column gap-3">
                    <button onclick="imprimirFacturaDirecta()" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm rounded-4" id="btn-imprimir-factura">
                        <i class="bi bi-printer me-2"></i> IMPRIMIR FACTURA (TICKET)
                    </button>

                    <a href="{{ route('cajero.factura.pdf', $factura->id) }}" target="_blank" class="btn btn-outline-danger btn-lg fw-bold py-3 shadow-sm rounded-4">
                        <i class="bi bi-file-earmark-pdf-fill me-2"></i> VER / DESCARGAR PDF
                    </a>

                    <button type="button" onclick="abrirModalWhatsapp()" class="btn btn-success btn-lg fw-bold py-3 shadow-sm rounded-4">
                        <i class="bi bi-whatsapp me-2"></i> ENVIAR POR WHATSAPP
                    </button>
                    
                    @php
                        $pendienteId = $pedidoPendienteId ?? session('pedido_pendiente_id');
                    @endphp

                    @if($pendienteId)
                        <a href="{{ route('cajero.cobrar', $pendienteId) }}" class="btn btn-warning btn-lg fw-bold py-3 shadow-sm rounded-4 text-dark shadow-sm">
                            <i class="bi bi-scissors me-2"></i> CONTINUAR COBRANDO MESA
                        </a>
                    @endif

                    <a href="{{ route('cajero.salon') }}" class="btn btn-outline-secondary btn-lg fw-bold py-3 shadow-sm rounded-4">
                        <i class="bi bi-grid-3x3-gap me-2"></i> VOLVER AL SALÓN
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL WHATSAPP --}}
<div class="modal fade" id="modalWhatsapp" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-whatsapp me-2"></i>Enviar Factura por WhatsApp</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Número de WhatsApp del Cliente:</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted fw-bold">+591</span>
                        <input type="text" id="ws-telefono" class="form-control form-control-lg" placeholder="Ej: 71234567" maxlength="12">
                    </div>
                    <small class="text-muted d-block mt-2">Se abrirá WhatsApp para enviar el resumen de la factura y el enlace al documento PDF.</small>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" onclick="enviarFacturaWhatsapp()" class="btn btn-success fw-bold rounded-3">
                    <i class="bi bi-send me-1"></i> Abrir WhatsApp
                </button>
            </div>
        </div>
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

    function abrirModalWhatsapp() {
        const modal = new bootstrap.Modal(document.getElementById('modalWhatsapp'));
        modal.show();
    }

    async function enviarFacturaWhatsapp() {
        let telefono = document.getElementById('ws-telefono').value.trim();
        if (!telefono) {
            Swal.fire({
                icon: 'warning',
                title: 'Número Requerido',
                text: 'Por favor ingrese el número de teléfono del cliente.',
                confirmButtonColor: '#198754'
            });
            return;
        }
        
        telefono = telefono.replace(/[^0-9]/g, '');
        if (!telefono.startsWith('591') && telefono.length === 8) {
            telefono = '591' + telefono;
        }

        const cliente = "{{ addslashes($factura->cliente_nombre ?? 'Estimado cliente') }}";
        const total = "{{ number_format($factura->monto_pagado, 2) }}";
        const facturaNum = "{{ $factura->numero_factura_siat ?? str_pad($factura->id, 6, '0', STR_PAD_LEFT) }}";
        const pdfUrl = "{{ route('cajero.factura.pdf', $factura->id) }}";
        const fileName = "Factura_{{ $factura->id }}.pdf";

        let texto = `Hola *${cliente}*, ¡gracias por tu preferencia! 🍽️\n\n`;
        texto += `📄 *Factura N°:* ${facturaNum}\n`;
        texto += `💰 *Monto Total:* Bs ${total}\n`;
        texto += `🗓️ *Fecha:* {{ $factura->created_at->format('d/m/Y H:i') }}\n\n`;
        texto += `Adjunto tu factura oficial en formato PDF.\n\n`;
        texto += `También puedes verla en este enlace:\n${pdfUrl}\n\n`;
        texto += `¡Que tengas un excelente día! 😊`;

        // 1. Intentar envío nativo del archivo PDF (Dispositivos móviles / Navegadores compatibles)
        if (navigator.share) {
            try {
                const response = await fetch(pdfUrl);
                const blob = await response.blob();
                const file = new File([blob], fileName, { type: 'application/pdf' });

                if (navigator.canShare && navigator.canShare({ files: [file] })) {
                    await navigator.share({
                        title: `Factura N° ${facturaNum}`,
                        text: texto,
                        files: [file]
                    });

                    const modalEl = document.getElementById('modalWhatsapp');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if(modal) modal.hide();
                    return;
                }
            } catch (e) {
                console.log("No se pudo usar Web Share API, usando fallback de WhatsApp Web.", e);
            }
        }

        // 2. Fallback WhatsApp Web (PC): Abrir chat + Descargar PDF automáticamente para arrastrar
        const url = `https://api.whatsapp.com/send?phone=${telefono}&text=${encodeURIComponent(texto)}`;
        window.open(url, '_blank');

        // Descargar PDF para adjuntar manualmente si se desea
        const a = document.createElement('a');
        a.href = pdfUrl;
        a.download = fileName;
        a.target = '_blank';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        const modalEl = document.getElementById('modalWhatsapp');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if(modal) modal.hide();
    }
</script>

<style>
    .fw-black { font-weight: 900; }
</style>
@endsection
