@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('cajero.salon') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 fw-semibold shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver al Salón
            </a>
            <h2 class="mb-0 fw-bold">
                Actualizar 
                @if($mesa->es_para_llevar)
                    <i class="bi bi-bag-fill text-primary me-1"></i> Llevar {{ $mesa->numero }}
                @else
                    Mesa {{ $mesa->numero }}
                @endif
            </h2>
            <span class="badge {{ $mesa->estado == 'libre' ? 'bg-success' : 'bg-danger' }} fs-6 rounded-pill px-3">
                {{ ucfirst($mesa->estado) }}
            </span>
        </div>
    </div>

    <form action="{{ route('cajero.mesa.actualizar', $mesa->id) }}" method="POST" id="form-actualizar">
        @csrf
        <div class="row">
            
            {{-- PANEL IZQUIERDO: SELECCIÓN DE PRODUCTOS NUEVOS --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4 rounded-4">
                    <div class="card-header bg-white py-3 border-0 d-flex flex-wrap align-items-center justify-content-between gap-2 rounded-top-4">
                        <ul class="nav nav-pills category-chips-wrapper flex-grow-1 border-0 mb-0" id="catTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link category-chip active rounded-pill px-3 py-2 fw-bold" id="tab-combos" data-bs-toggle="pill" data-bs-target="#cat-combos" role="tab" aria-controls="cat-combos" aria-selected="true">
                                    <i class="bi bi-gift me-1"></i> Combos y Promos
                                </button>
                            </li>
                            @foreach($categorias as $i => $cat)
                                <li class="nav-item" role="presentation">
                                    <button type="button" class="nav-link category-chip rounded-pill px-3 py-2 fw-bold" id="tab-cat-{{ $cat->id }}" data-bs-toggle="pill" data-bs-target="#cat-{{ $cat->id }}" role="tab" aria-controls="cat-{{ $cat->id }}" aria-selected="false">
                                        {{ $cat->nombre }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="input-group input-group-sm ms-auto" style="width: 220px;">
                            <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="input-buscar-menu" class="form-control border-start-0 rounded-end-pill py-2" placeholder="Buscar producto..." onkeyup="filtrarProductosMenu()">
                        </div>
                    </div>
                    <div class="card-body bg-light">
                        <div class="tab-content">
                            <!-- Pestaña Combos -->
                            <div class="tab-pane fade show active" id="cat-combos">
                                <div class="row row-cols-1 row-cols-xl-2 g-4">
                                    @foreach($combos as $combo)
                                        <div class="col">
                                            <div class="card h-100 border shadow-sm" style="border-radius: 12px; border-left: 5px solid #dc3545 !important;">
                                                <div class="card-body p-4 d-flex align-items-center">
                                                                                    <div class="position-relative" style="width: 100px; height: 100px; flex-shrink: 0;">
                                                        <div class="product-zoom-container shadow-xs w-100 h-100" style="border-radius: 14px;">
                                                            @if($combo->imagen)
                                                                 <img src="{{ asset('storage/' . $combo->imagen) }}" alt="{{ $combo->nombre }}" class="product-zoom-img" onclick="mostrarLightbox(this)">
                                                            @else
                                                                 <div class="product-placeholder-gradient w-100 h-100" style="border-radius: 12px; background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);">
                                                                     <i class="bi bi-gift-fill" style="font-size: 2.2rem; color: #d32f2f;"></i>
                                                                 </div>
                                                            @endif
                                                        </div>
                                                        <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger" style="font-size: 0.75rem; z-index: 10; padding: 0.35em 0.65em;">
                                                            {{ $combo->tipo === 'fijo' ? 'Fijo' : 'Promo' }}
                                                        </span>
                                                    </div>
                                                    <div class="ms-4 flex-grow-1">
                                                        <div class="fw-bold fs-5 mb-1 text-dark">{{ $combo->nombre }}</div>
                                                        <p class="text-muted small mb-2" style="font-size: 0.8rem; line-height: 1.2;">{{ $combo->descripcion }}</p>
                                                        
                                                        <div class="mb-3">
                                                            @foreach($combo->items as $item)
                                                                <span class="badge bg-secondary mb-1" style="font-size: 0.75rem;">
                                                                    {{ $item->cantidad }}x {{ $item->producto->nombre ?? 'N/A' }}
                                                                    @if($item->es_gratuito)
                                                                        <span class="text-warning fw-bold">(Gratis)</span>
                                                                    @endif
                                                                </span>
                                                            @endforeach
                                                        </div>

                                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                                            <span class="text-success fw-bold fs-5">Bs {{ number_format($combo->precio_mostrar, 2) }}</span>
                                                            <button type="button" class="btn btn-danger btn-sm px-3 fw-bold" onclick="agregarComboAlPedido({{ json_encode($combo) }})">
                                                                <i class="bi bi-plus-lg"></i> Agregar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($combos->isEmpty())
                                        <div class="col-12 text-center py-5 text-muted">
                                            <i class="bi bi-gift" style="font-size: 3rem;"></i>
                                            <p class="mt-2">No hay combos activos disponibles.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @foreach($categorias as $i => $cat)
                                <div class="tab-pane fade" id="cat-{{ $cat->id }}">
                                    <div class="row row-cols-1 row-cols-xl-2 g-4">
                                        @foreach($cat->productos as $prod)
                                            <div class="col">
                                                <div class="card h-100 border shadow-sm item-card" id="card-prod-{{ $prod->id }}">
                                                    <div class="card-body p-4 d-flex align-items-center">
                                                        <div class="position-relative" style="width: 120px; height: 120px; flex-shrink: 0;">
                                                            <div class="product-zoom-container shadow-sm w-100 h-100" style="border-radius: 16px;">
                                                                @if($prod->imagen)
                                                                     <img src="{{ asset('storage/' . $prod->imagen) }}" alt="{{ $prod->nombre }}" class="product-zoom-img" onclick="mostrarLightbox(this)">
                                                                @else
                                                                     <div class="product-placeholder-gradient w-100 h-100" style="border-radius: 14px;">
                                                                         <i class="bi bi-egg-fried" style="font-size: 2.8rem;"></i>
                                                                     </div>
                                                                @endif
                                                            </div>
    
                                                            @if($prod->usa_inventario)
                                                                <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill {{ $prod->stock > 0 ? 'bg-primary' : 'bg-danger' }}" style="font-size: 0.85rem; z-index: 10; padding: 0.35em 0.65em;">
                                                                    {{ $prod->stock }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="ms-4 flex-grow-1">
                                                            <div class="fw-bold fs-4 mb-3 text-dark">{{ $prod->nombre }}</div>
                                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                                <span class="text-success fw-bold fs-4">Bs {{ number_format($prod->precio, 2) }}</span>
                                                                @include('cajero.partials.pos_counter', ['key' => 'p_' . $prod->id . '_1', 'prod' => $prod, 'precio' => $prod->precio, 'tipo' => 'nuevo'])
                                                            </div>
                                                            @if($prod->precio_2)
                                                                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-light">
                                                                    <span class="text-primary fw-bold fs-5">
                                                                        Bs {{ number_format($prod->precio_2, 2) }} 
                                                                        <small class="text-muted">({{ $prod->precio_2_nombre }})</small>
                                                                    </span>
                                                                    @include('cajero.partials.pos_counter', ['key' => 'p_' . $prod->id . '_2', 'prod' => $prod, 'precio' => $prod->precio_2, 'tipo' => 'nuevo'])
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- PANEL DERECHO: TICKET DE LA MESA --}}
            <div class="col-md-4">
                <div class="card shadow border-0 position-sticky rounded-4 overflow-hidden" style="top: 20px;">
                    <div class="card-header bg-white border-bottom py-3 px-3.5 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-receipt-cutoff fs-4 text-primary"></i>
                            <h5 class="mb-0 fw-bold fs-5 text-dark">Comanda Mesa {{ $mesa->numero }}</h5>
                        </div>
                        {{-- Acciones Rápidas del Ticket --}}
                        <div class="d-flex align-items-center gap-1">
                            @if($pedido && $pedido->detalles->count() > 0)
                                <button type="button" onclick="imprimirDirecto(event, '{{ route('cajero.api.imprimir.cuenta', $pedido->id) }}')" class="btn btn-sm btn-light border py-1.5 px-2.5 rounded-3 text-secondary" title="Imprimir Pre-cuenta Rápida">
                                    <i class="bi bi-printer fs-6"></i>
                                </button>
                            @endif
                            <button type="button" onclick="vaciarSeleccionNueva()" class="btn btn-sm btn-light border py-1.5 px-2.5 rounded-3 text-danger" title="Vaciar selección nueva">
                                <i class="bi bi-trash3 fs-6"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0 overflow-auto" style="max-height: 58vh;">
                        
                        @if($pedido && $pedido->detalles->count() > 0)
                            <div class="p-3 border-bottom">
                                <small class="text-uppercase fw-bold text-muted d-block mb-2.5" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    <i class="bi bi-clock-history me-1 text-secondary"></i> Registrados Anteriormente en Cocina
                                </small>
                                @foreach($pedido->detalles as $det)
                                    <div class="d-flex justify-content-between align-items-center mb-2.5 p-2.5 rounded-3 bg-light existing-item-row" id="row-det-{{ $det->id }}">
                                        <div style="flex: 1;">
                                            {{-- Nombre del Producto Grande --}}
                                            <div class="fw-bold text-dark fs-5 mb-1" style="line-height: 1.2;">{{ $det->nombre_mostrar }}</div>
                                            <div class="d-flex align-items-center gap-2">
                                                <small class="text-muted" style="font-size: 0.8rem;">Bs {{ number_format($det->precio_unitario, 2) }} c/u</small>
                                                @if($det->estado_comanda === 'pendiente')
                                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-0.5 rounded-pill" style="font-size: 0.65rem;">
                                                        <i class="bi bi-clock me-1"></i>Pendiente
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded-pill" style="font-size: 0.65rem;">
                                                        <i class="bi bi-check-circle me-1"></i>En Cocina
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-1.5 bg-white border rounded-pill px-2.5 py-1 shadow-2xs ms-2">
                                            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-black text-decoration-none" style="width: 22px; height: 22px; font-size: 1.2rem; line-height: 1;" onclick="modificarExistente('{{ $det->id }}', -1)">−</button>
                                            <input type="number" name="detalles[{{ $det->id }}][cantidad]" id="det-qty-{{ $det->id }}" value="{{ $det->cantidad }}" 
                                                   class="form-control form-control-sm text-center fw-black px-0 border-0 bg-transparent" style="width: 32px; font-size: 1rem;" readonly>
                                            <button type="button" class="btn btn-sm btn-link text-success p-0 fw-black text-decoration-none" style="width: 22px; height: 22px; font-size: 1.2rem; line-height: 1;" onclick="modificarExistente('{{ $det->id }}', 1)">+</button>
                                        </div>
                                        <div class="text-end fw-bold ms-3" style="min-width: 75px; font-size: 1rem;">
                                            Bs <span id="det-subtotal-{{ $det->id }}">{{ number_format($det->cantidad * $det->precio_unitario, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- SECCIÓN DE ITEMS QUE SE ESTÁN AGREGANDO --}}
                        <div id="new-items-container" class="p-3">
                            <small class="text-uppercase fw-bold text-primary d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <i class="bi bi-plus-circle-dotted me-1"></i> Nuevos Ítems por Agregar
                            </small>
                            <div id="new-items-list">
                                {{-- Se llena vía JS --}}
                                <div class="text-center text-muted py-4 small opacity-75" id="no-new-items">
                                    <i class="bi bi-plus-circle display-6 d-block mb-2 opacity-50"></i>
                                    Haz clic en los platos para añadirlos al pedido.
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer p-4 border-top bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted fw-bold text-uppercase small" style="font-size: 0.8rem; letter-spacing: 0.5px;">TOTAL ESTIMADO:</span>
                            <span class="h2 mb-0 fw-black text-success" id="total-general">Bs 0.00</span>
                        </div>
                        {{-- Desglose del conteo cuantitativo sin emojis --}}
                        <small id="resumen-items-conteo" class="text-muted d-block small mb-3 text-end" style="font-size: 0.78rem;">
                            0 productos en pedido
                        </small>

                        @if($mesa->es_para_llevar)
                            <div class="d-flex flex-column gap-2">
                                <button type="submit" name="opcion_pago" value="cobrar_ahora" class="btn btn-warning btn-lg fw-black text-white shadow-sm py-3 rounded-4 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                                    <i class="bi bi-credit-card-2-front-fill fs-5"></i>
                                    <span>PAGAR EN ESTE MOMENTO (COBRAR AHORA)</span>
                                </button>
                                <button type="submit" name="opcion_pago" value="recoger_despues" class="btn btn-outline-primary btn-lg fw-bold shadow-sm py-2.5 rounded-4 d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-clock-history fs-5"></i>
                                    <span>PAGAR AL RECOGER DESPUÉS</span>
                                </button>
                            </div>
                        @else
                            <button type="submit" class="btn btn-success btn-lg w-100 fw-black shadow py-3 rounded-4 d-flex align-items-center justify-content-center gap-2" id="btn-submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 1.15rem; letter-spacing: 0.5px;">
                                <i class="bi bi-send-check-fill fs-5"></i>
                                <span>ENVIAR A COCINA Y GUARDAR</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    const seleccion = {}; // Para nuevos items
    const existentes = {}; // Para items ya registrados

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

    // Inicializar totales
    document.addEventListener('DOMContentLoaded', () => {
        @if($pedido)
            @foreach($pedido->detalles as $det)
                existentes['{{ $det->id }}'] = {
                    precio: {{ $det->precio_unitario }},
                    cant: {{ $det->cantidad }}
                };
            @endforeach
        @endif
        calcularTotalGeneral();
    });

    function modificarExistente(id, delta) {
        const input = document.getElementById('det-qty-' + id);
        const subLabel = document.getElementById('det-subtotal-' + id);
        const row = document.getElementById('row-det-' + id);

        let cant = (parseInt(input.value) || 0) + delta;
        if (cant < 0) cant = 0;

        input.value = cant;
        existentes[id].cant = cant;

        subLabel.innerText = (cant * existentes[id].precio).toLocaleString('en-US', { minimumFractionDigits: 2 });

        if (cant === 0) {
            row.style.opacity = '0.5';
            row.style.backgroundColor = '#fff5f5';
        } else {
            row.style.opacity = '1';
            row.style.backgroundColor = 'transparent';
        }

        calcularTotalGeneral();
    }

    function cambiarCantNuevo(key, prodId, delta, nombre, precio) {
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
        sincronizarNuevo(key, prodId, val, nombre, precio);
    }

    function sincronizarNuevo(key, prodId, val, nombre, precio) {
        const hidQty = document.getElementById('hid-qty-' + key);
        const hidPid = document.getElementById('hid-pid-' + key);
        const hidPrc = document.getElementById('hid-prc-' + key);
        const hidNota = document.getElementById('hid-nota-' + key);
        const card   = document.getElementById('card-prod-' + prodId);

        if (val > 0) {
            const prevNotas = seleccion[key] ? seleccion[key].notas : '';
            seleccion[key] = { nombre, precio, val, prodId, key, notas: prevNotas };
            hidQty.value = val;
            hidQty.disabled = false;
            hidPid.disabled = false;
            hidPrc.disabled = false;
            hidNota.disabled = false;
            if(card) card.classList.add('product-card-active', 'border-primary');
        } else {
            delete seleccion[key];
            hidQty.disabled = true;
            hidPid.disabled = true;
            hidPrc.disabled = true;
            hidNota.disabled = true;
            hidNota.value = '';
            
            // Si no hay otras variantes de este mismo producto
            const otras = Object.keys(seleccion).filter(k => k.startsWith('p_' + prodId + '_'));
            if (otras.length === 0 && card) card.classList.remove('product-card-active', 'border-primary', 'bg-primary-subtle');
        }

        renderNewItems();
        calcularTotalGeneral();
    }

    function actualizarNotaNuevo(key, notaText) {
        if (seleccion[key]) {
            seleccion[key].notas = notaText;
            const hidNota = document.getElementById('hid-nota-' + key);
            if (hidNota) {
                hidNota.value = notaText;
            }
        }
    }

    function toggleInputNota(key) {
        const container = document.getElementById('container-nota-' + key);
        if (container) {
            container.classList.toggle('d-none');
            if (!container.classList.contains('d-none')) {
                const input = document.getElementById('input-nota-' + key);
                if (input) input.focus();
            }
        }
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
        const form = document.getElementById('form-actualizar');

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
        renderNewItems();
        calcularTotalGeneral();
        
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
        renderNewItems();
        calcularTotalGeneral();
    }

    function renderNewItems() {
        const container = document.getElementById('new-items-list');
        const emptyMsg = document.getElementById('no-new-items');
        
        container.innerHTML = '';
        const items = Object.values(seleccion);

        if (items.length === 0) {
            container.appendChild(emptyMsg);
            return;
        }

        let renderedCombos = {};

        items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'd-flex flex-column mb-3 animate__animated animate__fadeIn border-bottom pb-2';
            
            let deleteBtnHtml = '';
            if (item.isCombo) {
                if (!renderedCombos[item.comboKey]) {
                    renderedCombos[item.comboKey] = true;
                    deleteBtnHtml = `
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2 p-1 bg-danger-subtle rounded border border-danger-subtle">
                            <span class="small fw-bold text-danger"><i class="bi bi-gift-fill me-1"></i>${item.comboNombre}</span>
                            <button type="button" class="btn btn-sm btn-danger py-0 px-2 fw-bold" style="font-size: 0.7rem;" onclick="quitarCombo('${item.comboKey}')">
                                <i class="bi bi-trash-fill"></i> Quitar Combo
                            </button>
                        </div>
                    `;
                }
            }

            div.innerHTML = `
                ${deleteBtnHtml}
                <div class="d-flex justify-content-between align-items-center">
                    <div style="flex: 1;">
                        {{-- Nombre del Producto Grande --}}
                        <div class="fw-bold text-dark fs-5" style="line-height: 1.2;">${item.nombre}</div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="text-muted small" style="font-size: 0.8rem;">Bs ${item.precio.toFixed(2)} c/u</span>
                            ${!item.isCombo ? `
                                <button type="button" class="btn btn-sm btn-link p-0 text-primary text-decoration-none border-0" style="font-size: 0.76rem;" onclick="editarPrecioNuevo('${item.key}')" title="Editar precio unitario">
                                    <i class="bi bi-pencil-fill me-0.5" style="font-size: 0.7rem;"></i> Editar precio
                                </button>
                            ` : ''}
                        </div>
                    </div>
                    <div class="fw-bold text-end ms-3">
                        <span class="badge ${item.isCombo ? 'bg-danger' : 'bg-primary'} rounded-pill px-3 py-1 fs-6">x${item.val}</span>
                        <div class="fs-6 fw-black text-dark mt-1">Bs ${(item.val * item.precio).toFixed(2)}</div>
                    </div>
                </div>
                ${!item.isCombo ? `
                <div class="w-100 mt-1">
                    <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none text-muted small d-inline-flex align-items-center gap-1" style="font-size: 0.74rem;" onclick="toggleInputNota('${item.key}')">
                        <i class="bi bi-plus-circle text-primary"></i> ${item.notas ? '<span class="text-primary fw-semibold">Especificación: ' + item.notas + '</span>' : '+ Poner especificación'}
                    </button>
                    <div id="container-nota-${item.key}" class="${item.notas ? '' : 'd-none'} mt-1">
                        <input type="text" id="input-nota-${item.key}" placeholder="Escribe especificación (ej. Sin cebolla, extra salsa)..." 
                               class="form-control form-control-sm border mt-1 rounded-3" 
                               style="font-size: 0.78rem;" 
                               value="${item.notas || ''}" 
                               oninput="actualizarNotaNuevo('${item.key}', this.value)">
                    </div>
                </div>` : ''}
            `;
            container.appendChild(div);
        });
    }

    function editarPrecioNuevo(key) {
        if (!seleccion[key]) return;
        const item = seleccion[key];
        
        Swal.fire({
            title: 'Editar Precio Unitario',
            html: `<p class="text-muted small mb-2">Modificar precio unitario para <strong>${item.nombre}</strong></p>`,
            input: 'number',
            inputValue: item.precio,
            inputAttributes: {
                step: '0.50',
                min: '0'
            },
            showCancelButton: true,
            confirmButtonText: 'Guardar Precio',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed && result.value !== null) {
                const nuevoPrecio = parseFloat(result.value);
                if (!isNaN(nuevoPrecio) && nuevoPrecio >= 0) {
                    seleccion[key].precio = nuevoPrecio;
                    const hidPrc = document.getElementById('hid-prc-' + key);
                    if (hidPrc) hidPrc.value = nuevoPrecio;
                    renderNewItems();
                    calcularTotalGeneral();
                }
            }
        });
    }

    function calcularTotalGeneral() {
        let total = 0;
        let cantExistentes = 0;
        let cantNuevos = 0;
        
        // Sumar existentes
        Object.values(existentes).forEach(item => {
            total += item.cant * item.precio;
            cantExistentes += item.cant;
        });

        // Sumar nuevos
        Object.values(seleccion).forEach(item => {
            total += item.val * item.precio;
            cantNuevos += item.val;
        });

        document.getElementById('total-general').innerText = 'Bs ' + total.toLocaleString('en-US', { minimumFractionDigits: 2 });
        
        const resumenEl = document.getElementById('resumen-items-conteo');
        if (resumenEl) {
            let msg = '';
            if (cantExistentes > 0 && cantNuevos > 0) {
                msg = `${cantExistentes} registrados + ${cantNuevos} nuevos por enviar`;
            } else if (cantExistentes > 0) {
                msg = `${cantExistentes} registrados previamente`;
            } else if (cantNuevos > 0) {
                msg = `${cantNuevos} nuevos por enviar a cocina`;
            } else {
                msg = `0 productos en pedido`;
            }
            resumenEl.innerText = msg;
        }
    }

    function vaciarSeleccionNueva() {
        if (Object.keys(seleccion).length === 0) return;
        
        Swal.fire({
            title: '¿Vaciar selección nueva?',
            text: 'Se desmarcarán todos los productos nuevos agregados en esta sesión.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, vaciar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Object.keys(seleccion).forEach(key => {
                    const prodId = seleccion[key].prodId;
                    const input = document.getElementById('qty-' + key);
                    if (input) input.value = 0;
                    sincronizarNuevo(key, prodId, 0, '', 0);
                });
            }
        });
    }

    function filtrarProductosMenu() {
        const query = (document.getElementById('input-buscar-menu').value || '').toLowerCase().trim();
        const activeTab = document.querySelector('.tab-pane.active');
        if (!activeTab) return;

        const productCards = activeTab.querySelectorAll('.col');
        productCards.forEach(col => {
            const text = col.textContent.toLowerCase();
            if (text.includes(query)) {
                col.style.display = '';
            } else {
                col.style.display = 'none';
            }
        });
    }

    // Atajos de Teclado (F2, Ctrl+Enter, Esc)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F2') {
            e.preventDefault();
            const search = document.getElementById('input-buscar-menu');
            if (search) {
                search.focus();
                search.select();
            }
        }
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            const form = document.getElementById('form-actualizar');
            if (form) form.submit();
        }
        if (e.key === 'Escape' && !document.querySelector('.swal2-container')) {
            window.location.href = "{{ route('cajero.salon') }}";
        }
    });

    // Listener para eventos nativos de Bootstrap 5 en pestañas
    document.addEventListener('DOMContentLoaded', function() {
        const tabTriggerList = document.querySelectorAll('#catTabs button[data-bs-toggle="pill"]');
        tabTriggerList.forEach(tabTriggerEl => {
            tabTriggerEl.addEventListener('shown.bs.tab', function () {
                filtrarProductosMenu();
            });
        });
    });
</script>

<style>
    .nav-pills .nav-link { border-radius: 20px; padding: 0.5rem 1.25rem; }
    .item-card { cursor: pointer; border-radius: 18px !important; overflow: hidden; transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.2s ease; }
    .item-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important; }
    .bg-primary-subtle { background-color: #eef2ff !important; }
</style>
@endsection
