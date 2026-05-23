@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    <div class="row align-items-center mb-4 pb-2 border-bottom">
        <div class="col-md-6">
            <a href="{{ route('cajero.salon') }}" class="btn btn-outline-secondary btn-sm me-3">
                <i class="bi bi-arrow-left"></i> Volver al Salón
            </a>
            <span class="h2 fw-bold mb-0">
                <i class="bi bi-credit-card me-2 text-primary"></i>
                Procesar Pago — Mesa {{ $pedido->mesa->numero }}
            </span>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="bg-primary-subtle text-primary px-4 py-2 rounded-4 d-inline-block">
                <span class="fs-5 fw-bold">TOTAL A COBRAR:</span>
                <span class="fs-2 fw-black ms-2">Bs {{ number_format($pedido->total, 2) }}</span>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div>
    @endif

    <div class="row g-4">

        {{-- COLUMNA IZQUIERDA: RESUMEN --}}
        <div class="col-lg-4 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header bg-dark text-white py-3 fw-bold">
                    <i class="bi bi-list-check me-2"></i>Resumen de Consumo
                </div>
                <div class="card-body p-0">
                    <div class="overflow-auto" style="max-height: 500px;">
                        <ul class="list-group list-group-flush">
                            @foreach($pedido->detalles as $det)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                    <div>
                                        <div class="fw-bold">{{ $det->nombre_mostrar }}</div>
                                        <small class="text-muted">Bs {{ number_format($det->precio_unitario, 2) }} x {{ $det->cantidad }}</small>
                                    </div>
                                    <span class="fw-bold text-dark fs-5">Bs {{ number_format($det->cantidad * $det->precio_unitario, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card-footer bg-light p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h4 mb-0 fw-bold">SUBTOTAL:</span>
                        <span class="h3 mb-0 fw-bold text-primary">Bs {{ number_format($pedido->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: FORMULARIO --}}
        <div class="col-lg-8 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-primary text-white py-3 fw-bold">
                    <i class="bi bi-cash-coin me-2"></i>Finalizar Transacción
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('cajero.cobrar.pagar', $pedido->id) }}" method="POST">
                        @csrf

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fs-6 fw-bold">Nombre del Cliente</label>
                                <input type="text" name="cliente_nombre" class="form-control form-control-lg border-2"
                                       placeholder="Consumidor Final"
                                       value="{{ old('cliente_nombre') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-6 fw-bold">NIT / CI / Documento</label>
                                <input type="text" name="cliente_nit_ci" class="form-control form-control-lg border-2"
                                       placeholder="Opcional"
                                       value="{{ old('cliente_nit_ci') }}">
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label fs-5 fw-bold mb-3">Método de Pago</label>
                                <div class="row g-3">
                                    @foreach(['efectivo' => '💵 Efectivo', 'tarjeta' => '💳 Tarjeta', 'qr' => '📱 Pago QR', 'transferencia' => '🏦 Transf.'] as $val => $label)
                                        <div class="col-6 col-md-3">
                                            <input type="radio" class="btn-check" name="metodo_pago" id="metodo_{{ $val }}" 
                                                   value="{{ $val }}" {{ old('metodo_pago', 'efectivo') == $val ? 'checked' : '' }} required>
                                            <label class="btn btn-outline-primary w-100 py-3 d-flex flex-column gap-2 fw-bold" for="metodo_{{ $val }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-7 mt-5">
                                <label class="form-label fs-4 fw-bold text-primary">Efectivo Recibido</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white border-0 fs-3 fw-bold">$</span>
                                    <input type="number" step="0.01" min="{{ $pedido->total }}"
                                           name="monto_pagado" id="monto_pagado" class="form-control form-control-lg border-primary border-2 fs-1 fw-black"
                                           value="{{ old('monto_pagado', number_format($pedido->total, 2, '.', '')) }}"
                                           required oninput="calcCambio()" style="height: 100px;">
                                </div>
                            </div>

                            <div class="col-md-5 mt-5 d-flex flex-column justify-content-center">
                                <div class="bg-success-subtle p-4 rounded-4 border border-success-subtle text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-success fw-bold fs-5 d-block mb-1">CAMBIO A DEVOLVER:</span>
                                    <span id="cambio-display" class="text-success fw-black fs-1">Bs 0.00</span>
                                </div>
                            </div>

                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-primary w-100 py-4 fs-3 fw-black rounded-4 shadow"
                                        onclick="return confirm('¿Confirmar pago y liberar Mesa {{ $pedido->mesa->numero }}?')">
                                    <i class="bi bi-check2-circle me-3"></i>
                                    PROCESAR COBRO · LIBERAR MESA
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const total = {{ $pedido->total }};

function calcCambio() {
    const monto = parseFloat(document.getElementById('monto_pagado').value) || 0;
    const cambio = Math.max(0, monto - total);
    document.getElementById('cambio-display').textContent = '$' + cambio.toFixed(2);
}

// Calcular al cargar
calcCambio();
</script>

<style>
.fw-black { font-weight: 900; }
.btn-check:checked + .btn-outline-primary {
    background-color: var(--bs-primary);
    color: white;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    transform: translateY(-3px);
}
</style>
@endsection
