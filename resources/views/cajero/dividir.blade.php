@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    <div class="row align-items-center mb-4 pb-2 border-bottom">
        <div class="col-md-6">
            <a href="{{ route('cajero.salon') }}" class="btn btn-outline-secondary btn-sm me-3">
                <i class="bi bi-arrow-left"></i> Volver al Salón
            </a>
            <span class="h2 fw-bold mb-0">
                <i class="bi bi-scissors me-2 text-warning"></i>
                Dividir Cuenta — 
                @if($pedido->mesa->es_para_llevar)
                    🛍️ Llevar {{ $pedido->mesa->numero }}
                @else
                    Mesa {{ $pedido->mesa->numero }}
                @endif
            </span>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="bg-light border px-4 py-2 rounded-4 d-inline-block">
                <span class="fs-6 text-muted">Total de la Mesa:</span>
                <span class="fs-4 fw-bold ms-2 text-dark">Bs {{ number_format($pedido->total, 2) }}</span>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div>
    @endif

    <form action="{{ route('cajero.pedidos.dividir.procesar', $pedido->id) }}" method="POST" class="swal-confirm-form"
          data-swal-title="¿Procesar Pago Dividido?"
          data-swal-message="Se cobrará únicamente la parte seleccionada y se emitirá la factura correspondiente. El saldo restante quedará en la mesa."
          data-swal-icon="question"
          data-swal-confirm-text="Sí, cobrar parte dividida">
        @csrf

        <div class="row g-4">

            {{-- COLUMNA IZQUIERDA: SELECCIÓN DE ITEMS A PAGAR --}}
            <div class="col-lg-6 col-xl-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-dark text-white py-3 fw-bold">
                        <i class="bi bi-list-check me-2"></i>Selecciona los productos a cobrar en esta parte
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">Utiliza los botones <strong class="text-success">+</strong> y <strong class="text-danger">−</strong> para indicar la cantidad de cada plato que se va a pagar en esta transacción.</p>
                        
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Precio Unitario</th>
                                        <th class="text-center" style="width: 150px;">Cantidad a Pagar</th>
                                        <th class="text-end" style="width: 120px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedido->detalles as $index => $det)
                                        <tr id="row-det-{{ $det->id }}">
                                            <td>
                                                <div class="fw-bold text-dark fs-6">{{ $det->nombre_mostrar }}</div>
                                                <small class="text-muted">Cantidad total en mesa: {{ $det->cantidad }}</small>
                                                <input type="hidden" name="split_items[{{ $index }}][detalle_id]" value="{{ $det->id }}">
                                            </td>
                                            <td class="text-center fw-medium">Bs {{ number_format($det->precio_unitario, 2) }}</td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-danger p-0 rounded-circle" style="width: 28px; height: 28px;" onclick="modificarCantidad('{{ $det->id }}', -1, {{ $det->precio_unitario }})">−</button>
                                                    <input type="number" name="split_items[{{ $index }}][cantidad]" id="qty-{{ $det->id }}" value="0" 
                                                           class="form-control form-control-sm text-center fw-bold px-0 border-0 bg-transparent" style="width: 40px;" readonly>
                                                    <button type="button" class="btn btn-sm btn-outline-success p-0 rounded-circle" style="width: 28px; height: 28px;" onclick="modificarCantidad('{{ $det->id }}', 1, {{ $det->precio_unitario }}, {{ $det->cantidad }})">+</button>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold text-dark">
                                                Bs <span id="subtotal-{{ $det->id }}">0.00</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: FORMULARIO DE COBRO --}}
            <div class="col-lg-6 col-xl-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-primary text-white py-3 fw-bold">
                        <i class="bi bi-cash-coin me-2"></i>Detalle de Cobro
                    </div>
                    <div class="card-body p-4">
                        <div class="bg-primary-subtle text-primary p-3 rounded-4 mb-4 text-center">
                            <span class="fs-6 fw-bold text-uppercase d-block mb-1">TOTAL A COBRAR EN ESTA PARTE:</span>
                            <span class="fs-1 fw-black">Bs <span id="total-a-cobrar">0.00</span></span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre del Cliente</label>
                                <input type="text" name="cliente_nombre" class="form-control" placeholder="Consumidor Final" value="{{ old('cliente_nombre') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">NIT / CI</label>
                                <input type="text" name="cliente_nit_ci" class="form-control" placeholder="Opcional" value="{{ old('cliente_nit_ci') }}">
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label fw-bold mb-2">Método de Pago</label>
                                <div class="row g-2">
                                    @foreach(['efectivo' => '💵 Efectivo', 'tarjeta' => '💳 Tarjeta', 'qr' => '📱 Pago QR', 'transferencia' => '🏦 Transf.'] as $val => $label)
                                        <div class="col-6 col-md-3">
                                            <input type="radio" class="btn-check" name="metodo_pago" id="metodo_{{ $val }}" 
                                                   value="{{ $val }}" {{ old('metodo_pago', 'efectivo') == $val ? 'checked' : '' }} required>
                                            <label class="btn btn-outline-primary w-100 py-2 d-flex flex-column gap-1 fw-bold fs-7" for="metodo_{{ $val }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-7 mt-4">
                                <label class="form-label fw-bold text-primary">Efectivo Recibido</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white border-0 fs-5 fw-bold">Bs</span>
                                    <input type="number" step="0.01" min="0"
                                           name="monto_pagado" id="monto_pagado" class="form-control form-control-lg border-primary border-2 fs-2 fw-black"
                                           value="0.00" required oninput="calcCambio()" style="height: 60px;">
                                </div>
                            </div>

                            <div class="col-md-5 mt-4 d-flex flex-column justify-content-center">
                                <div class="bg-success-subtle p-3 rounded-4 border border-success-subtle text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-success fw-bold small d-block mb-1">CAMBIO A DEVOLVER:</span>
                                    <span id="cambio-display" class="text-success fw-black fs-3">Bs 0.00</span>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-3 fs-4 fw-black rounded-4 shadow" id="btn-submit" disabled>
                                    <i class="bi bi-check2-circle me-2"></i>
                                    PROCESAR COBRO PARCIAL
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    const itemsSelection = {};
    let totalCobrar = 0;

    function modificarCantidad(id, delta, precio, maxCant = 999) {
        const input = document.getElementById('qty-' + id);
        const subLabel = document.getElementById('subtotal-' + id);
        const row = document.getElementById('row-det-' + id);

        let cant = (parseInt(input.value) || 0) + delta;
        if (cant < 0) cant = 0;
        if (cant > maxCant) cant = maxCant;

        input.value = cant;
        itemsSelection[id] = cant;

        subLabel.innerText = (cant * precio).toFixed(2);

        if (cant > 0) {
            row.classList.add('table-primary-subtle');
        } else {
            row.classList.remove('table-primary-subtle');
        }

        recalcularTotal();
    }

    function recalcularTotal() {
        totalCobrar = 0;
        document.querySelectorAll('tbody tr').forEach(row => {
            const id = row.id.replace('row-det-', '');
            const cant = parseInt(document.getElementById('qty-' + id).value) || 0;
            const sub = parseFloat(document.getElementById('subtotal-' + id).innerText) || 0;
            totalCobrar += sub;
        });

        // Habilitar o deshabilitar botón de cobro
        const btnSubmit = document.getElementById('btn-submit');
        if (totalCobrar > 0) {
            btnSubmit.disabled = false;
        } else {
            btnSubmit.disabled = true;
        }

        document.getElementById('total-a-cobrar').innerText = totalCobrar.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        
        const inputMonto = document.getElementById('monto_pagado');
        inputMonto.min = totalCobrar.toFixed(2);
        if (parseFloat(inputMonto.value) < totalCobrar || inputMonto.value === '0.00') {
            inputMonto.value = totalCobrar.toFixed(2);
        }
        
        calcCambio();
    }

    function calcCambio() {
        const monto = parseFloat(document.getElementById('monto_pagado').value) || 0;
        const cambio = Math.max(0, monto - totalCobrar);
        document.getElementById('cambio-display').textContent = 'Bs ' + cambio.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
</script>

<style>
.fw-black { font-weight: 950; }
.fs-7 { font-size: 0.8rem; }
.table-primary-subtle {
    background-color: #eef1ff !important;
}
.btn-check:checked + .btn-outline-primary {
    background-color: var(--bs-primary);
    color: white;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.2);
    transform: translateY(-2px);
}
</style>
@endsection
