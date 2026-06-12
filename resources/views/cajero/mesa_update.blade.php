@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('cajero.salon') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Salón
            </a>
            <h2 class="mb-0 fw-bold">
                Actualizar 
                @if($mesa->es_para_llevar)
                    🛍️ Llevar {{ $mesa->numero }}
                @else
                    Mesa {{ $mesa->numero }}
                @endif
            </h2>
            <span class="badge {{ $mesa->estado == 'libre' ? 'bg-success' : 'bg-danger' }} fs-6">
                {{ ucfirst($mesa->estado) }}
            </span>
        </div>
    </div>

    <form action="{{ route('cajero.mesa.actualizar', $mesa->id) }}" method="POST" id="form-actualizar">
        @csrf
        <div class="row">
            
            {{-- PANEL IZQUIERDO: SELECCIÓN DE PRODUCTOS NUEVOS --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <ul class="nav nav-pills card-header-pills overflow-auto flex-nowrap pb-2" id="catTabs" style="gap: 8px;">
                            @foreach($categorias as $i => $cat)
                                <li class="nav-item">
                                    <button type="button" class="nav-link {{ $i === 0 ? 'active' : '' }} fw-bold" data-bs-toggle="pill" data-bs-target="#cat-{{ $cat->id }}">
                                        {{ $cat->nombre }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body bg-light">
                        <div class="tab-content">
                            @foreach($categorias as $i => $cat)
                                <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="cat-{{ $cat->id }}">
                                    <div class="row row-cols-1 row-cols-xl-2 g-4">
                                        @foreach($cat->productos as $prod)
                                            <div class="col">
                                                <div class="card h-100 border shadow-sm item-card" id="card-prod-{{ $prod->id }}">
                                                    <div class="card-body p-4 d-flex align-items-center">
                                                        <div class="bg-white rounded border d-flex align-items-center justify-content-center text-muted position-relative" style="width: 120px; height: 120px; flex-shrink: 0;">
                                                            @if($prod->imagen)
                                                                 <img src="{{ asset('storage/' . $prod->imagen) }}" alt="{{ $prod->nombre }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                                            @else
                                                                 <i class="bi bi-image fs-1"></i>
                                                            @endif

                                                            @if($prod->usa_inventario)
                                                                <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill {{ $prod->stock > 0 ? 'bg-primary' : 'bg-danger' }}" style="font-size: 0.75rem; z-index: 10;">
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
                <div class="card shadow border-0 position-sticky" style="top: 20px;">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Detalle de la Cuenta</h5>
                    </div>
                    <div class="card-body p-0 overflow-auto" style="max-height: 60vh;">
                        
                        @if($pedido && $pedido->detalles->count() > 0)
                            <div class="p-3 border-bottom bg-light">
                                <small class="text-muted d-block mb-2">PRODUCTOS REGISTRADOS</small>
                                @foreach($pedido->detalles as $det)
                                    <div class="d-flex justify-content-between align-items-center mb-3 existing-item-row" id="row-det-{{ $det->id }}">
                                        <div style="flex: 1;">
                                            <div class="fw-bold" style="font-size: 0.9rem;">{{ $det->nombre_mostrar }}</div>
                                            <small class="text-muted">Bs {{ number_format($det->precio_unitario, 2) }}</small>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger p-0 rounded-circle" style="width: 24px; height: 24px;" onclick="modificarExistente('{{ $det->id }}', -1)">−</button>
                                            <input type="number" name="detalles[{{ $det->id }}][cantidad]" id="det-qty-{{ $det->id }}" value="{{ $det->cantidad }}" 
                                                   class="form-control form-control-sm text-center fw-bold px-0 border-0 bg-transparent" style="width: 30px;" readonly>
                                            <button type="button" class="btn btn-sm btn-outline-success p-0 rounded-circle" style="width: 24px; height: 24px;" onclick="modificarExistente('{{ $det->id }}', 1)">+</button>
                                        </div>
                                        <div class="text-end fw-bold ms-3" style="min-width: 70px;">
                                            Bs <span id="det-subtotal-{{ $det->id }}">{{ number_format($det->cantidad * $det->precio_unitario, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- SECCIÓN DE ITEMS QUE SE ESTÁN AGREGANDO --}}
                        <div id="new-items-container" class="p-3">
                            <small class="text-muted d-block mb-2">POR AGREGAR</small>
                            <div id="new-items-list">
                                {{-- Se llena vía JS --}}
                                <div class="text-center text-muted py-3 small" id="no-new-items">No has seleccionado nada nuevo.</div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-white p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0 fw-bold text-dark">Total estimado:</h4>
                            <h2 class="mb-0 fw-bold text-success" id="total-general">Bs 0.00</h2>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm py-3" id="btn-submit">
                            <i class="bi bi-check-circle-fill me-2"></i> ACTUALIZAR MESA
                        </button>
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
            if(card) card.classList.add('border-primary', 'bg-primary-subtle');
        } else {
            delete seleccion[key];
            hidQty.disabled = true;
            hidPid.disabled = true;
            hidPrc.disabled = true;
            hidNota.disabled = true;
            hidNota.value = '';
            
            // Si no hay otras variantes de este mismo producto
            const otras = Object.keys(seleccion).filter(k => k.startsWith('p_' + prodId + '_'));
            if (otras.length === 0 && card) card.classList.remove('border-primary', 'bg-primary-subtle');
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

    function renderNewItems() {
        const container = document.getElementById('new-items-list');
        const emptyMsg = document.getElementById('no-new-items');
        
        container.innerHTML = '';
        const items = Object.values(seleccion);

        if (items.length === 0) {
            container.appendChild(emptyMsg);
            return;
        }

        items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'd-flex flex-column mb-3 animate__animated animate__fadeIn border-bottom pb-2';
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div style="flex: 1;">
                        <div class="fw-bold" style="font-size: 0.85rem;">${item.nombre}</div>
                        <small class="text-primary">+ Nuevo</small>
                    </div>
                    <div class="fw-bold text-end ms-3">
                        <span class="badge bg-primary">x${item.val}</span>
                        <div class="small">Bs ${(item.val * item.precio).toFixed(2)}</div>
                    </div>
                </div>
                <div class="w-100 mt-1">
                    <input type="text" placeholder="Especificaciones (ej. Sin cebolla)..." 
                           class="form-control form-control-sm border-1 mt-1" 
                           style="font-size: 0.75rem;" 
                           value="${item.notas || ''}" 
                           oninput="actualizarNotaNuevo('${item.key}', this.value)">
                </div>
            `;
            container.appendChild(div);
        });
    }

    function calcularTotalGeneral() {
        let total = 0;
        
        // Sumar existentes
        Object.values(existentes).forEach(item => {
            total += item.cant * item.precio;
        });

        // Sumar nuevos
        Object.values(seleccion).forEach(item => {
            total += item.val * item.precio;
        });

        document.getElementById('total-general').innerText = 'Bs ' + total.toLocaleString('en-US', { minimumFractionDigits: 2 });
    }
</script>

<style>
    .nav-pills .nav-link { border-radius: 20px; padding: 0.5rem 1.25rem; }
    .item-card { cursor: pointer; transition: all 0.2s; }
    .item-card:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important; }
    .bg-primary-subtle { background-color: #e7f1ff !important; }
</style>
@endsection
