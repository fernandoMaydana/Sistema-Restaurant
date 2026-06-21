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
                <div class="fw-bold fs-5 lh-1">
                    @if($mesa->es_para_llevar)
                        🛍️ Llevar {{ $mesa->numero }}
                    @else
                        Mesa {{ $mesa->numero }}
                    @endif
                </div>
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
                <li class="nav-item flex-shrink-0">
                    <button type="button"
                            class="nav-link active px-3 py-2 fw-bold btn-outline-danger text-danger"
                            data-bs-toggle="pill"
                            data-bs-target="#cat-combos"
                            style="border-radius: 20px; font-size: 0.9rem; white-space: nowrap;">
                        🎁 Combos y Promos
                    </button>
                </li>
                @foreach($categorias as $i => $cat)
                    <li class="nav-item flex-shrink-0">
                        <button type="button"
                                class="nav-link px-3 py-2 fw-bold"
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
            <!-- Pestaña Combos -->
            <div class="tab-pane fade show active" id="cat-combos">
                <div class="d-flex flex-column gap-2 mb-4">
                    @foreach($combos as $combo)
                        <div class="card border shadow-sm" style="border-radius: 12px; overflow: hidden; border-left: 5px solid #dc3545 !important;">
                            <div class="d-flex align-items-center p-2">
                                <div class="flex-shrink-0 bg-light rounded d-flex align-items-center justify-content-center text-muted position-relative" 
                                     style="width: 70px; height: 70px; border: 1px dashed #ccc;">
                                    @if($combo->imagen)
                                        <img src="{{ asset('storage/' . $combo->imagen) }}" alt="{{ $combo->nombre }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                                    @else
                                        <i class="bi bi-gift text-danger" style="font-size: 1.5rem;"></i>
                                    @endif
                                    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; z-index: 10;">
                                        {{ $combo->tipo === 'fijo' ? 'Fijo' : 'Promo' }}
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold mb-1" style="font-size: 0.9rem; line-height: 1.2;">{{ $combo->nombre }}</div>
                                    <p class="text-muted small mb-1" style="font-size: 0.75rem; line-height: 1.1;">{{ $combo->descripcion }}</p>
                                    <div class="mb-2">
                                        @foreach($combo->items as $item)
                                            <span class="badge bg-secondary mb-1" style="font-size: 0.7rem; padding: 0.2em 0.4em;">
                                                {{ $item->cantidad }}x {{ $item->producto->nombre ?? 'N/A' }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-success fw-bold" style="font-size: 0.85rem;">
                                            Bs {{ number_format($combo->precio_mostrar, 2) }}
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm px-3 fw-bold" style="font-size: 0.8rem; border-radius: 6px;" onclick="agregarComboAlPedido({{ json_encode($combo) }})">
                                            <i class="bi bi-plus-lg"></i> Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if($combos->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-gift" style="font-size: 2.5rem;"></i>
                            <p class="mt-2" style="font-size: 0.85rem;">No hay combos activos disponibles.</p>
                        </div>
                    @endif
                </div>
            </div>

            @foreach($categorias as $i => $cat)
                <div class="tab-pane fade" id="cat-{{ $cat->id }}">
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
                <h5 class="offcanvas-title fw-bold mb-0">
                    @if($mesa->es_para_llevar)
                        🛍️ Detalle Llevar #{{ $mesa->numero }}
                    @else
                        🧾 Detalle Mesa {{ $mesa->numero }}
                    @endif
                </h5>
                <span class="text-muted" style="font-size: 0.85rem;">Pedido actual de la mesa</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0 overflow-auto">
            {{-- Items registrados en base de datos --}}
            <div id="db-items-container">
                @if($pedido && $pedido->detalles->count() > 0)
                    <ul class="list-group list-group-flush border-bottom">
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
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Items seleccionados en pantalla (por agregar) --}}
            <div class="p-3" id="new-items-offcanvas-section" style="display: none;">
                <small class="text-muted d-block mb-2 fw-bold text-uppercase" id="new-items-offcanvas-title">Productos por agregar</small>
                <div id="new-items-offcanvas-list">
                    {{-- Llenado vía JS --}}
                </div>
            </div>

            <div class="text-center py-5" id="empty-pedido-msg" style="display: {{ ($pedido && $pedido->detalles->count() > 0) ? 'none' : 'block' }};">
                <div class="mb-3">
                    <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                </div>
                <p class="text-muted fw-bold">No hay ítems seleccionados ni registrados aún.</p>
            </div>
        </div>
        
        <div class="offcanvas-footer p-3 border-top bg-light">
            <div class="d-flex justify-content-between align-items-center fw-bold fs-5 mb-3">
                <span id="label-total-offcanvas">Total Estimado:</span>
                <span class="text-success" id="total-offcanvas">Bs {{ number_format($pedido ? $pedido->total : 0, 2) }}</span>
            </div>
            
            @if($pedido)
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-outline-primary" id="btn-imprimir-precuenta" onclick="imprimirPreCuenta({{ $pedido->id }})">
                    <i class="bi bi-printer me-2"></i>Imprimir Pre-Cuenta
                </button>
            </div>
            @endif
        </div>
    </div>

</div>

<script>
// Mapa de cantidades seleccionadas en este formulario
const seleccion = {};
const pedidoTotalDb = {{ $pedido ? $pedido->total : 0 }};

const stockDeProductos = {
    @foreach($categorias as $cat)
        @foreach($cat->productos as $p)
            '{{ $p->id }}': {
                usa_inventario: {{ $p->usa_inventario ? 'true' : 'false' }},
                stock: {{ $p->stock ?? 0 }}
            },
        @endforeach
    @endforeach
};

function cambiarCant(key, prodId, delta, nombre, precio) {
    const input = document.getElementById('qty-' + key);
    let val = (parseInt(input.value) || 0) + delta;
    if (val < 0) val = 0;
    
    const productData = stockDeProductos[prodId];
    if (productData && productData.usa_inventario && delta > 0) {
        let totalPedidoActualmente = 0;
        document.querySelectorAll(`[id^="qty-p_${prodId}_"]`).forEach(inp => {
            if (inp.id !== 'qty-' + key) {
                totalPedidoActualmente += parseInt(inp.value) || 0;
            }
        });
        if (totalPedidoActualmente + val > productData.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Agotado',
                text: `No hay suficiente stock disponible para "${nombre}". Stock máximo: ${productData.stock}`,
                confirmButtonColor: '#ffc107'
            });
            return;
        }
    }

    input.value = val;
    sincronizar(key, prodId, val, nombre, precio);
}

function actualizarDesdeInput(key, prodId, nombre, precio) {
    const input = document.getElementById('qty-' + key);
    const val = Math.max(0, parseInt(input.value) || 0);

    const productData = stockDeProductos[prodId];
    if (productData && productData.usa_inventario && val > 0) {
        let totalPedidoActualmente = 0;
        document.querySelectorAll(`[id^="qty-p_${prodId}_"]`).forEach(inp => {
            if (inp.id !== 'qty-' + key) {
                totalPedidoActualmente += parseInt(inp.value) || 0;
            }
        });
        if (totalPedidoActualmente + val > productData.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Agotado',
                text: `No puedes agregar esa cantidad. Stock disponible para "${nombre}": ${productData.stock}`,
                confirmButtonColor: '#ffc107'
            });
            input.value = 0;
            sincronizar(key, prodId, 0, nombre, precio);
            return;
        }
    }

    input.value = val;
    sincronizar(key, prodId, val, nombre, precio);
}

function sincronizar(key, prodId, val, nombre, precio) {
    const hidQty = document.getElementById('hid-qty-' + key);
    const hidPid = document.getElementById('hid-pid-' + key);
    const hidPrc = document.getElementById('hid-prc-' + key);
    const hidNota = document.getElementById('hid-nota-' + key);
    const card   = document.getElementById('card-' + prodId);

    hidQty.value = val;

    if (val > 0) {
        const prevNotas = seleccion[key] ? seleccion[key].notas : '';
        seleccion[key] = { val, nombre, precio, key, notas: prevNotas };
        hidQty.disabled = false;
        hidPid.disabled = false;
        hidPrc.disabled = false;
        hidNota.disabled = false;
        card.style.borderColor = '#0d6efd';
        card.style.backgroundColor = 'rgba(13, 110, 253, 0.05)';
    } else {
        delete seleccion[key];
        hidQty.disabled = true;
        hidPid.disabled = true;
        hidPrc.disabled = true;
        hidNota.disabled = true;
        hidNota.value = '';

        // Solo quitar el color si NO hay otra variante del mismo producto con cantidad > 0
        const otrasVariantes = Object.keys(seleccion).filter(k => k.startsWith('p_' + prodId + '_'));
        if (otrasVariantes.length === 0) {
            card.style.borderColor = '';
            card.style.backgroundColor = '';
        }
    }

    // Actualizar resumen de barra inferior
    const totalCantidad = Object.values(seleccion).reduce((s, item) => s + item.val, 0);
    document.getElementById('resumen-count').textContent = totalCantidad + (totalCantidad === 1 ? ' item' : ' items');
    document.getElementById('btn-registrar').disabled = totalCantidad === 0;

    // Actualizar offcanvas
    renderNewItemsOffcanvas();
}

function actualizarNota(key, notaText) {
    if (seleccion[key]) {
        seleccion[key].notas = notaText;
        const hidNota = document.getElementById('hid-nota-' + key);
        if (hidNota) {
            hidNota.value = notaText;
        }
    }
}

function renderNewItemsOffcanvas() {
    const container = document.getElementById('new-items-offcanvas-list');
    const section = document.getElementById('new-items-offcanvas-section');
    const emptyMsg = document.getElementById('empty-pedido-msg');
    
    container.innerHTML = '';
    const items = Object.values(seleccion);

    let dbItemsCount = {{ $pedido ? $pedido->detalles->count() : 0 }};

    if (items.length === 0) {
        section.style.display = 'none';
        if (dbItemsCount === 0) {
            emptyMsg.style.display = 'block';
        } else {
            emptyMsg.style.display = 'none';
        }
        actualizarTotalOffcanvas();
        return;
    }

    section.style.display = 'block';
    emptyMsg.style.display = 'none';

    let renderedCombos = {};

    items.forEach(item => {
        const li = document.createElement('div');
        li.className = 'd-flex flex-column py-2 px-3 border-bottom bg-light-subtle';
        
        let deleteBtnHtml = '';
        if (item.isCombo) {
            if (!renderedCombos[item.comboKey]) {
                renderedCombos[item.comboKey] = true;
                deleteBtnHtml = `
                    <div class="d-flex justify-content-between align-items-center w-100 mb-2 p-1 bg-danger-subtle rounded border border-danger-subtle">
                        <span class="small fw-bold text-danger"><i class="bi bi-gift-fill me-1"></i>${item.comboNombre}</span>
                        <button type="button" class="btn btn-sm btn-danger py-0 px-2 fw-bold" style="font-size: 0.7rem;" onclick="quitarCombo('${item.comboKey}')">
                            <i class="bi bi-trash-fill"></i> Quitar
                        </button>
                    </div>
                `;
            }
        }

        li.innerHTML = `
            ${deleteBtnHtml}
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column">
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">${item.nombre}</span>
                    <span class="text-primary" style="font-size: 0.8rem;">${item.isCombo ? '+ Combo' : '+ Por agregar'} (Bs ${item.precio.toFixed(2)})</span>
                </div>
                <div class="text-end d-flex flex-column align-items-end">
                    <span class="badge ${item.isCombo ? 'bg-danger' : 'bg-primary'}">x${item.val}</span>
                    <strong class="mt-1 ${item.isCombo ? 'text-danger' : 'text-primary'}" style="font-size: 0.9rem;">
                        Bs ${(item.val * item.precio).toFixed(2)}
                    </strong>
                </div>
            </div>
            ${!item.isCombo ? `
            <div class="w-100 mt-1">
                <input type="text" placeholder="Especificaciones (ej. Sin cebolla)..." 
                       class="form-control form-control-sm border-1 mt-1" 
                       style="font-size: 0.8rem;" 
                       value="${item.notas || ''}" 
                       oninput="actualizarNota('${item.key}', this.value)">
            </div>` : ''}
        `;
        container.appendChild(li);
    });

    actualizarTotalOffcanvas();
}

function actualizarTotalOffcanvas() {
    let totalNuevos = 0;
    Object.values(seleccion).forEach(item => {
        totalNuevos += item.val * item.precio;
    });

    const totalGeneral = pedidoTotalDb + totalNuevos;
    document.getElementById('total-offcanvas').innerText = 'Bs ' + totalGeneral.toLocaleString('en-US', { minimumFractionDigits: 2 });
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
            Swal.fire({
                icon: 'error',
                title: 'Error de Impresora',
                text: data.message || 'Error al imprimir',
                confirmButtonColor: '#e63946'
            });
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error de Red',
            text: 'Error de conexión con la impresora',
            confirmButtonColor: '#e63946'
        });
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
    });
}

let comboInstanceCounter = 0;

function agregarComboAlPedido(combo) {
    // 1. Verificar stock para cada producto en el combo antes de agregarlo
    for (const item of combo.items) {
        const prod = item.producto;
        if (!prod) continue;
        
        const productData = stockDeProductos[prod.id];
        if (productData && productData.usa_inventario) {
            // Calcular cuánto se ha pedido de este producto en total (normales + combos)
            let totalPedido = 0;
            Object.values(seleccion).forEach(selItem => {
                if (selItem.prodId == prod.id) {
                    totalPedido += selItem.val;
                }
            });
            
            if (totalPedido + item.cantidad > productData.stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Insuficiente',
                    text: `No hay suficiente stock para "${prod.nombre}". Requerido: ${item.cantidad}, Disponible: ${productData.stock} (ya tienes ${totalPedido} en el ticket).`,
                    confirmButtonColor: '#e63946'
                });
                return; // Detener toda la adición si falta stock de algún item del combo
            }
        }
    }

    // 2. Calcular precios distribuidos si es combo Fijo
    let preciosItems = {};
    
    if (combo.tipo === 'fijo') {
        // Suma de precios normales de items no gratuitos
        let totalNormal = 0;
        combo.items.forEach(item => {
            if (!item.es_gratuito && item.producto) {
                totalNormal += parseFloat(item.producto.precio) * item.cantidad;
            }
        });

        // Ratio de descuento
        const comboPrecio = parseFloat(combo.precio_total);
        const ratio = totalNormal > 0 ? (comboPrecio / totalNormal) : 0;

        combo.items.forEach(item => {
            if (item.es_gratuito) {
                preciosItems[item.id] = 0;
            } else if (item.producto) {
                preciosItems[item.id] = parseFloat(item.producto.precio) * ratio;
            }
        });
    } else {
        // Combo condicionado (los gratis a Bs 0, los otros a su precio normal)
        combo.items.forEach(item => {
            if (item.es_gratuito) {
                preciosItems[item.id] = 0;
            } else if (item.producto) {
                preciosItems[item.id] = parseFloat(item.producto.precio);
            }
        });
    }

    // 3. Agregar los productos al pedido con un identificador único por combo
    const comboKey = 'c_' + combo.id + '_' + Date.now() + '_' + (comboInstanceCounter++);
    
    // Agregar inputs ocultos al form
    const form = document.getElementById('form-pedido');

    combo.items.forEach(item => {
        const prod = item.producto;
        if (!prod) return;
        
        const itemKey = `${comboKey}_${prod.id}`;
        const unitPrice = preciosItems[item.id];
        
        // Crear inputs ocultos dinámicamente en el formulario para este item del combo
        const htmlInputs = `
            <div id="inputs-${itemKey}">
                <input type="hidden" name="items[${itemKey}][producto_id]" id="hid-pid-${itemKey}" value="${prod.id}">
                <input type="hidden" name="items[${itemKey}][cantidad]" id="hid-qty-${itemKey}" value="${item.cantidad}">
                <input type="hidden" name="items[${itemKey}][precio_seleccionado]" id="hid-prc-${itemKey}" value="${unitPrice.toFixed(2)}">
                <input type="hidden" name="items[${itemKey}][notas]" id="hid-nota-${itemKey}" value="Combo: ${combo.nombre}${item.es_gratuito ? ' (Gratis)' : ''}">
            </div>
        `;
        form.insertAdjacentHTML('beforeend', htmlInputs);

        // Guardar en la estructura frontend `seleccion` para renderizar y sumar
        seleccion[itemKey] = {
            nombre: `${prod.nombre} (Combo: ${combo.nombre})`,
            precio: unitPrice,
            val: item.cantidad,
            prodId: prod.id,
            key: itemKey,
            notas: `Combo: ${combo.nombre}${item.es_gratuito ? ' (Gratis)' : ''}`,
            isCombo: true,
            comboKey: comboKey,
            comboNombre: combo.nombre
        };
    });

    // 4. Renderizar y calcular totales
    renderNewItemsOffcanvas();
    actualizarTotalOffcanvas();
    
    // Actualizar resumen de barra inferior
    const totalCantidad = Object.values(seleccion).reduce((s, item) => s + item.val, 0);
    document.getElementById('resumen-count').textContent = totalCantidad + (totalCantidad === 1 ? ' item' : ' items');
    document.getElementById('btn-registrar').disabled = totalCantidad === 0;

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: `Se agregó el combo "${combo.nombre}"`,
        showConfirmButton: false,
        timer: 2000
    });
}

function quitarCombo(comboKey) {
    // 1. Eliminar de seleccion todos los items que tengan el mismo comboKey
    Object.keys(seleccion).forEach(key => {
        if (key.startsWith(comboKey)) {
            delete seleccion[key];
            
            // 2. Eliminar del DOM los inputs ocultos
            const inputsDiv = document.getElementById('inputs-' + key);
            if (inputsDiv) inputsDiv.remove();
        }
    });

    // 3. Renderizar y recalcular
    renderNewItemsOffcanvas();
    actualizarTotalOffcanvas();
    
    // Actualizar resumen de barra inferior
    const totalCantidad = Object.values(seleccion).reduce((s, item) => s + item.val, 0);
    document.getElementById('resumen-count').textContent = totalCantidad + (totalCantidad === 1 ? ' item' : ' items');
    document.getElementById('btn-registrar').disabled = totalCantidad === 0;
}
</script>
@endsection
