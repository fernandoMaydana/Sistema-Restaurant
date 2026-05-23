@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-3 pb-5" style="max-width: 800px; margin: auto;">

    {{-- Header Fijo --}}
    <div class="d-flex justify-content-between align-items-center py-2 px-2 border-bottom mb-3 sticky-top bg-white" style="z-index:100; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route(Auth::user()->role . '.salon') }}" class="btn btn-sm btn-outline-secondary" style="border: none; font-size: 1.2rem;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="fw-bold fs-5 lh-1">Mesa {{ $mesa->numero }}</div>
                <span class="badge {{ $mesa->estado == 'libre' ? 'bg-success' : 'bg-danger' }} fw-normal" style="font-size: 0.7rem;">
                    {{ ucfirst($mesa->estado) }}
                </span>
            </div>
        </div>

        {{-- Botón Ver Mesa SIEMPRE VISIBLE --}}
        @php
            $cantidadItems = $pedido ? $pedido->detalles->count() : 0;
        @endphp
        <button class="btn btn-outline-dark btn-sm fw-bold position-relative" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#detallePedido" style="border-radius: 8px;">
            <i class="bi bi-receipt me-1"></i>Ver Mesa
            @if($cantidadItems > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                    {{ $cantidadItems }}
                </span>
            @endif
        </button>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-2">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- ═══════════════════════════════
         SELECCIÓN DE PRODUCTOS
    ═══════════════════════════════ --}}
    <form action="{{ route(Auth::user()->role . '.registrar', $mesa->id) }}" method="POST" id="form-pedido">
        @csrf

        {{-- Tabs por categoría --}}
        <div class="px-2">
            <ul class="nav nav-pills mb-3 flex-nowrap overflow-auto pb-2 custom-scrollbar" id="catTabs" style="gap: 8px;">
                @foreach($categorias as $i => $cat)
                    <li class="nav-item flex-shrink-0">
                        <button type="button"
                                class="nav-link {{ $i === 0 ? 'active' : '' }} px-3 py-2 fw-bold"
                                data-bs-toggle="pill"
                                data-bs-target="#cat-{{ $cat->id }}"
                                style="border-radius: 20px; font-size: 0.9rem; white-space: nowrap;">
                            {{ $cat->nombre }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <style>
            /* Ocultar scrollbar completamente para un look más limpio */
            .custom-scrollbar::-webkit-scrollbar {
                display: none;
            }
            .custom-scrollbar {
                scrollbar-width: none; /* Firefox */
                -ms-overflow-style: none; /* IE y Edge */
            }
        </style>

        <div class="tab-content px-2">
            @foreach($categorias as $i => $cat)
                <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="cat-{{ $cat->id }}">
                    <div class="d-flex flex-column gap-2 mb-4">
                        @foreach($cat->productos as $prod)
                            @php $key = 'p_' . $prod->id; @endphp
                            
                            {{-- Tarjeta de producto horizontal para móvil --}}
                            <div class="card border shadow-sm" style="border-radius: 12px; overflow: hidden; transition: border-color .15s" id="card-{{ $prod->id }}">
                                <div class="d-flex align-items-center p-2">
                                    {{-- Recuadro para Imagen del Producto --}}
                                    <div class="flex-shrink-0 bg-light rounded d-flex align-items-center justify-content-center text-muted position-relative" 
                                         style="width: 70px; height: 70px; border: 1px dashed #ccc;">
                                        @if($prod->imagen)
                                            <img src="{{ asset('storage/' . $prod->imagen) }}" alt="{{ $prod->nombre }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                                        @else
                                            <i class="bi bi-image" style="font-size: 1.5rem;"></i>
                                        @endif

                                        @if($prod->usa_inventario)
                                            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill {{ $prod->stock > 0 ? 'bg-primary' : 'bg-danger' }}" style="font-size: 0.65rem; z-index: 10;">
                                                {{ $prod->stock }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    {{-- Info y Controles --}}
                                    <div class="flex-grow-1 ms-3">
                                        <div class="fw-bold mb-1" style="font-size: 0.95rem; line-height: 1.2;">{{ $prod->nombre }}</div>
                                        
                                        {{-- Fila Precio 1 --}}
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="text-success fw-bold" style="font-size: 0.85rem;">
                                                Bs {{ number_format($prod->precio, 2) }}
                                            </div>
                                            @include('mesero.partials.counter', ['key' => 'p_' . $prod->id . '_1', 'prod' => $prod, 'precio' => $prod->precio])
                                        </div>

                                        {{-- Fila Precio 2 (Si existe) --}}
                                        @if($prod->precio_2)
                                            <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                                <div class="text-primary fw-bold" style="font-size: 0.85rem;">
                                                    Bs {{ number_format($prod->precio_2, 2) }} 
                                                    <small class="d-block text-muted fw-normal" style="font-size: 0.7rem;">{{ $prod->precio_2_nombre }}</small>
                                                </div>
                                                @include('mesero.partials.counter', ['key' => 'p_' . $prod->id . '_2', 'prod' => $prod, 'precio' => $prod->precio_2])
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Barra inferior fija para móviles --}}
        <div class="position-fixed bottom-0 start-0 end-0 bg-white border-top px-3 py-3 d-flex justify-content-between align-items-center" 
             style="z-index:200; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
            <div class="d-flex flex-column">
                <span class="text-muted" style="font-size: 0.8rem;">Seleccionados</span>
                <strong id="resumen-count" class="text-primary fs-5 lh-1">0 items</strong>
            </div>
            <button type="submit" class="btn btn-success px-4 py-2 fw-bold" id="btn-registrar" style="border-radius: 8px; font-size: 1rem;" disabled>
                <i class="bi bi-check2-circle me-1"></i>Registrar
            </button>
        </div>

    </form>

    {{-- ═══════════════════════════════
         OFFCANVAS: DETALLE DE LA MESA
    ═══════════════════════════════ --}}
    
    <div class="offcanvas offcanvas-bottom rounded-top-4" tabindex="-1" id="detallePedido" style="height: 80vh;">
        <div class="offcanvas-header border-bottom px-3 py-3">
            <div>
                <h5 class="offcanvas-title fw-bold mb-0">🧾 Detalle Mesa {{ $mesa->numero }}</h5>
                <span class="text-muted" style="font-size: 0.85rem;">Pedido actual registrado</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0 overflow-auto">
            @if($pedido && $pedido->detalles->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach($pedido->detalles as $det)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-3">
                            <div class="d-flex flex-column">
                                <span class="fw-bold" style="font-size: 0.95rem;">{{ $det->nombre_mostrar }}</span>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="text-muted" style="font-size: 0.85rem;">Bs {{ number_format($det->precio_unitario, 2) }}</span>
                                    @if($det->estado_comanda === 'pendiente')
                                        <span class="badge bg-warning text-dark" style="font-size:0.65rem">Pendiente</span>
                                    @else
                                        <span class="badge bg-success" style="font-size:0.65rem">En cocina</span>
                                    @endif
                                </div>
                            </div>
                                <div class="text-end d-flex flex-column align-items-end">
                                    <span class="badge bg-light text-dark border">x{{ $det->cantidad }}</span>
                                    <strong class="mt-1 text-success mb-1" style="font-size: 0.95rem;">
                                        Bs {{ number_format($det->cantidad * $det->precio_unitario, 2) }}
                                    </strong>
                                    @if(Auth::user()->role === 'cajero')
                                        <form action="{{ route('cajero.pedido.eliminar_item', $det->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este producto de la cuenta?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm p-0 d-flex align-items-center justify-content-center mt-1" style="width: 28px; height: 28px; border-radius: 6px;">
                                                <i class="bi bi-trash" style="font-size: 0.9rem;"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-muted fw-bold">No hay ítems registrados aún.</p>
                </div>
            @endif
        </div>
        @if($pedido)
        <div class="offcanvas-footer p-3 border-top bg-light">
            <div class="d-flex justify-content-between align-items-center fw-bold fs-5 mb-3">
                <span>Total Acumulado:</span>
                <span class="text-success">Bs {{ number_format($pedido->total, 2) }}</span>
            </div>
            
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-outline-primary" id="btn-imprimir-precuenta" onclick="imprimirPreCuenta({{ $pedido->id }})">
                    <i class="bi bi-printer me-2"></i>Imprimir Pre-Cuenta
                </button>
            </div>
        </div>
        @endif
    </div>

</div>

<script>
// Mapa de cantidades seleccionadas en este formulario
const seleccion = {};

function cambiarCant(key, prodId, delta) {
    const input = document.getElementById('qty-' + key);
    let val = (parseInt(input.value) || 0) + delta;
    if (val < 0) val = 0;
    input.value = val;
    sincronizar(key, prodId, val);
}

function actualizarDesdeInput(key, prodId) {
    const input = document.getElementById('qty-' + key);
    const val = Math.max(0, parseInt(input.value) || 0);
    input.value = val;
    sincronizar(key, prodId, val);
}

function sincronizar(key, prodId, val) {
    const hidQty = document.getElementById('hid-qty-' + key);
    const hidPid = document.getElementById('hid-pid-' + key);
    const hidPrc = document.getElementById('hid-prc-' + key);
    const card   = document.getElementById('card-' + prodId);

    hidQty.value = val;

    if (val > 0) {
        seleccion[key] = val;
        hidQty.disabled = false;
        hidPid.disabled = false;
        hidPrc.disabled = false;
        card.style.borderColor = '#0d6efd';
        card.style.backgroundColor = 'rgba(13, 110, 253, 0.05)';
    } else {
        delete seleccion[key];
        hidQty.disabled = true;
        hidPid.disabled = true;
        hidPrc.disabled = true;

        // Solo quitar el color si NO hay otra variante del mismo producto con cantidad > 0
        const otrasVariantes = Object.keys(seleccion).filter(k => k.startsWith('p_' + prodId + '_'));
        if (otrasVariantes.length === 0) {
            card.style.borderColor = '';
            card.style.backgroundColor = '';
        }
    }

    // Actualizar resumen
    const total = Object.values(seleccion).reduce((s, v) => s + v, 0);
    document.getElementById('resumen-count').textContent = total + (total === 1 ? ' item' : ' items');
    document.getElementById('btn-registrar').disabled = total === 0;
}

// Función para imprimir pre-cuenta desde el celular
function imprimirPreCuenta(pedidoId) {
    const btn = document.getElementById('btn-imprimir-precuenta');
    const textoOriginal = btn.innerHTML;
    
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Imprimiendo...';
    btn.disabled = true;

    fetch(`/mesero/api/imprimir/cuenta/${pedidoId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Mostrar pequeño feedback
            btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>¡Enviado!';
            btn.classList.replace('btn-outline-primary', 'btn-success');
            btn.classList.add('text-white');
            
            setTimeout(() => {
                btn.innerHTML = textoOriginal;
                btn.classList.replace('btn-success', 'btn-outline-primary');
                btn.classList.remove('text-white');
                btn.disabled = false;
            }, 3000);
        } else {
            alert(data.message || 'Error al imprimir');
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
        }
    })
    .catch(error => {
        alert('Error de conexión con la impresora');
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
    });
}
</script>
@endsection
