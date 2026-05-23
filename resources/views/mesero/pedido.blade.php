@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center py-3 border-bottom mb-4">
        <div>
            <a href="{{ route('mesero.salon') }}" class="btn btn-sm btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Salón
            </a>
            <span class="h4 fw-bold">
                <i class="bi bi-grid-1x2-fill me-1 text-primary"></i>
                Mesa {{ $mesa->numero }}
            </span>
            <span class="badge bg-secondary ms-2">{{ $mesa->capacidad }} personas</span>
        </div>
        @if($pedido)
            <span class="badge fs-6
                @if($pedido->estado === 'abierto') bg-danger
                @elseif($pedido->estado === 'cuenta_solicitada') bg-warning text-dark
                @endif">
                @if($pedido->estado === 'abierto') Ocupada
                @elseif($pedido->estado === 'cuenta_solicitada') Cuenta Solicitada
                @endif
            </span>
        @else
            <span class="badge bg-success fs-6">Libre</span>
        @endif
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- CASO 1: Mesa LIBRE → Botón para abrir pedido --}}
    @if(!$pedido)
        <div class="text-center py-5">
            <i class="bi bi-cup-hot display-1 text-muted"></i>
            <h4 class="mt-3 text-muted">Esta mesa está libre</h4>
            <p class="text-muted">¿Deseas abrir un pedido para esta mesa?</p>
            <form action="{{ route('mesero.abrir', $mesa->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-plus-circle me-2"></i>Abrir Pedido
                </button>
            </form>
        </div>

    {{-- CASO 2: Mesa OCUPADA o con CUENTA SOLICITADA --}}
    @else
        <div class="row g-4">

            {{-- Panel derecho: Resumen de la comanda actual --}}
            <div class="col-md-5 order-md-2">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-receipt me-2"></i>Comanda Actual</span>
                        <span class="badge bg-light text-dark">Pedido #{{ $pedido->id }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($pedido->detalles->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($pedido->detalles as $detalle)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-semibold">{{ $detalle->producto->nombre }}</span>
                                            <small class="text-muted d-block">{{ $detalle->producto->categoria->nombre ?? '' }}</small>
                                            @if($detalle->estado_comanda === 'pendiente')
                                                <span class="badge bg-warning text-dark" style="font-size:0.7rem">Pendiente de impresión</span>
                                            @else
                                                <span class="badge bg-success" style="font-size:0.7rem">Enviado a cocina</span>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-secondary rounded-pill">x{{ $detalle->cantidad }}</span>
                                            <div class="mt-1 fw-bold">Bs {{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-basket2 fs-3"></i>
                                <p class="mt-2 mb-0">Sin productos aún.</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold fs-5">Total:</span>
                            <span class="fw-bold fs-4 text-success">Bs {{ number_format($pedido->total, 2) }}</span>
                        </div>

                        @if($pedido->estado === 'abierto' && $pedido->detalles->count() > 0)
                            <form action="{{ route('mesero.cuenta', $pedido->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-warning w-100" onclick="return confirm('¿Solicitar la cuenta al cajero?')">
                                    <i class="bi bi-cash-coin me-2"></i>Solicitar la Cuenta
                                </button>
                            </form>
                        @elseif($pedido->estado === 'cuenta_solicitada')
                            <div class="alert alert-warning mb-0 text-center">
                                <i class="bi bi-hourglass-split me-2"></i>
                                Cuenta solicitada. El cajero la está procesando.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Panel izquierdo: Agregar productos (solo si pedido está abierto) --}}
            @if($pedido->estado === 'abierto')
            <div class="col-md-7 order-md-1">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-plus-circle me-2"></i>Agregar Productos
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mesero.agregar', $pedido->id) }}" method="POST" id="form-agregar">
                            @csrf

                            {{-- Tabs por categoría --}}
                            <ul class="nav nav-tabs mb-3" id="categoriasTabs" role="tablist">
                                @foreach($categorias as $i => $categoria)
                                    @if($categoria->productos->count() > 0)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $i === 0 ? 'active' : '' }}"
                                                    id="tab-{{ $categoria->id }}"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#cat-{{ $categoria->id }}"
                                                    type="button" role="tab">
                                                {{ $categoria->nombre }}
                                            </button>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>

                            <div class="tab-content" id="categoriasContent">
                                @foreach($categorias as $i => $categoria)
                                    @if($categoria->productos->count() > 0)
                                        <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}"
                                             id="cat-{{ $categoria->id }}" role="tabpanel">
                                            <div class="row g-2">
                                                @foreach($categoria->productos as $j => $producto)
                                                    @php $idx = $categoria->id . '_' . $producto->id; @endphp
                                                    <div class="col-12">
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text bg-light flex-grow-1 text-start" style="min-width:0">
                                                                <span class="text-truncate fw-semibold">{{ $producto->nombre }}</span>
                                                                <span class="ms-auto text-muted">Bs {{ number_format($producto->precio, 2) }}</span>
                                                            </span>
                                                            <input type="hidden"
                                                                   name="items[{{ $idx }}][producto_id]"
                                                                   value="{{ $producto->id }}">
                                                            <button type="button" class="btn btn-outline-secondary btn-qty-minus" data-target="qty-{{ $idx }}">−</button>
                                                            <input type="number"
                                                                   id="qty-{{ $idx }}"
                                                                   name="items[{{ $idx }}][cantidad]"
                                                                   value="0"
                                                                   min="0"
                                                                   class="form-control text-center qty-input"
                                                                   style="max-width:60px">
                                                            <button type="button" class="btn btn-outline-primary btn-qty-plus" data-target="qty-{{ $idx }}">+</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-success w-100" id="btn-enviar" disabled>
                                    <i class="bi bi-send me-2"></i>Enviar a Comanda
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

        </div>{{-- end row --}}
    @endif

</div>

<script>
// Botones +/- de cantidad
document.querySelectorAll('.btn-qty-plus').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.target);
        input.value = parseInt(input.value) + 1;
        checkHasItems();
    });
});
document.querySelectorAll('.btn-qty-minus').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.target);
        if (parseInt(input.value) > 0) input.value = parseInt(input.value) - 1;
        checkHasItems();
    });
});
document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('input', checkHasItems);
});

function checkHasItems() {
    const hasItems = [...document.querySelectorAll('.qty-input')].some(i => parseInt(i.value) > 0);
    document.getElementById('btn-enviar').disabled = !hasItems;
}

// Antes del submit: elimina campos con cantidad 0 para no enviar vacíos
document.getElementById('form-agregar')?.addEventListener('submit', function(e) {
    document.querySelectorAll('.qty-input').forEach(input => {
        if (parseInt(input.value) <= 0) {
            input.removeAttribute('name');
            // También eliminar el hidden del producto_id
            const idx = input.id.replace('qty-', '');
            const hidden = document.querySelector(`input[name="items[${idx}][producto_id]"]`);
            if (hidden) hidden.removeAttribute('name');
        }
    });
});
</script>
@endsection
