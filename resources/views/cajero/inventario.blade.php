@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    {{-- Encabezado de Página --}}
    <div class="d-flex justify-content-between align-items-center py-3 border-bottom mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="bi bi-box-seam me-2 text-primary"></i>
                Control de Inventario
            </h1>
            <span class="text-muted">Gestione y actualice el stock de los productos rápidamente.</span>
        </div>
        <div>
            <a href="{{ route('cajero.dashboard') }}" class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-3 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Volver al Panel
            </a>
        </div>
    </div>

    {{-- Alertas de Éxito o Error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 12px;">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 12px;">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Banner informativo Consumo Personal --}}
    <div class="alert border-0 mb-4 d-flex align-items-center gap-3" style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); border-radius: 14px; box-shadow: 0 2px 12px rgba(255,152,0,0.10);">
        <div style="background: #ff9800; border-radius: 50%; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="bi bi-people-fill text-white fs-5"></i>
        </div>
        <div>
            <div class="fw-bold text-dark" style="font-size: 0.95rem;">Consumo del Personal</div>
            <div class="text-muted small">Use el botón <span class="badge" style="background:#ff9800; color:white;"><i class="bi bi-people me-1"></i>Personal</span> para registrar productos consumidos por el equipo y descontarlos automáticamente del stock.</div>
        </div>
    </div>

    {{-- Card de Filtros --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form action="{{ route('cajero.inventario') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted">Filtrar por Categoría</label>
                    <select name="categoria_id" class="form-select border-light-subtle bg-light-subtle" style="border-radius: 10px; padding: 10px;">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted">Buscar Producto</label>
                    <div class="input-group">
                        <span class="input-group-text border-light-subtle bg-light-subtle" style="border-radius: 10px 0 0 10px;"><i class="bi bi-search"></i></span>
                        <input type="text" name="buscar" class="form-control border-light-subtle bg-light-subtle" placeholder="Escriba el nombre del producto..." value="{{ request('buscar') }}" style="border-radius: 0 10px 10px 0; padding: 10px;">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm" style="border-radius: 10px; padding: 11px;">
                        <i class="bi bi-funnel me-1"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Productos --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light py-3 border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 80px;">Imagen</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Categoría</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Producto</th>
                            <th class="py-3 text-uppercase text-muted fw-bold text-center" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 140px;">Stock Actual</th>
                            <th class="py-3 text-uppercase text-muted fw-bold text-end" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 230px;">Agregar Stock</th>
                            <th class="pe-4 py-3 text-uppercase text-muted fw-bold text-end" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 160px;">Consumo Personal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr class="product-row">
                                <td class="ps-4 py-3">
                                    @if($producto->imagen)
                                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="product-img shadow-sm">
                                    @else
                                        <div class="product-img-placeholder d-flex align-items-center justify-content-center text-muted border">
                                            <i class="bi bi-image" style="font-size: 1.2rem;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium">
                                        {{ $producto->categoria->nombre ?? 'Sin Categoría' }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <div class="fw-bold text-dark fs-6">{{ $producto->nombre }}</div>
                                    @if(!$producto->disponible)
                                        <span class="text-danger small fw-medium"><i class="bi bi-exclamation-circle me-1"></i>No Disponible en Carta</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    @if($producto->stock > 10)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6 rounded-pill fw-bold">
                                            {{ $producto->stock }} ud.
                                        </span>
                                    @elseif($producto->stock > 0)
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 fs-6 rounded-pill fw-bold">
                                            {{ $producto->stock }} ud.
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fs-6 rounded-pill fw-bold">
                                            Agotado (0)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-end">
                                    <form action="{{ route('cajero.inventario.agregar_stock', $producto->id) }}" method="POST" class="d-flex align-items-center justify-content-end gap-2 inline-stock-form">
                                        @csrf
                                        <div class="input-group input-group-sm" style="width: 100px;">
                                            <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 8px 0 0 8px;">+</span>
                                            <input type="number" name="cantidad" min="1" class="form-control border-start-0 text-center fw-bold form-control-sm" placeholder="Cant." required style="border-radius: 0 8px 8px 0;">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary px-3 py-2 fw-bold rounded-pill d-flex align-items-center gap-1 shadow-sm hover-scale">
                                            <i class="bi bi-plus-lg"></i> Agregar
                                        </button>
                                    </form>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    {{-- Botón que abre el modal de Consumo Personal --}}
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-warning fw-bold rounded-pill d-flex align-items-center gap-1 shadow-sm hover-scale ms-auto btn-consumo"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalConsumoPersonal"
                                        data-producto-id="{{ $producto->id }}"
                                        data-producto-nombre="{{ $producto->nombre }}"
                                        data-producto-stock="{{ $producto->stock }}"
                                    >
                                        <i class="bi bi-people-fill"></i> Personal
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="my-3 text-center">
                                        <i class="bi bi-box-seam text-secondary opacity-50" style="font-size: 3rem;"></i>
                                        <p class="mt-3 fs-5 fw-semibold text-dark">No se encontraron productos con inventario activo</p>
                                        <p class="text-muted small">Intente cambiar los filtros o busque otro producto.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================== --}}
{{-- MODAL: Consumo del Personal                                  --}}
{{-- =========================================================== --}}
<div class="modal fade" id="modalConsumoPersonal" tabindex="-1" aria-labelledby="modalConsumoPersonalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">

            {{-- Header --}}
            <div class="modal-header border-0 px-4 pt-4 pb-2" style="background: linear-gradient(135deg, #ff9800, #f57c00);">
                <div class="d-flex align-items-center gap-3">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-people-fill text-white fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="modalConsumoPersonalLabel">Consumo del Personal</h5>
                        <p class="text-white-50 mb-0 small">Descontar stock por uso interno</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body px-4 py-4">

                {{-- Producto seleccionado --}}
                <div class="mb-4 p-3 rounded-3" style="background: #fff8e1; border: 1.5px solid #ffe0b2;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-box-seam text-warning fs-5"></i>
                        <div>
                            <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Producto seleccionado</div>
                            <div class="fw-bold text-dark fs-6" id="modalNombreProducto">-</div>
                        </div>
                        <div class="ms-auto text-end">
                            <div class="text-muted small">Stock disponible</div>
                            <div class="fw-bold" id="modalStockProducto" style="color: #f57c00;">- ud.</div>
                        </div>
                    </div>
                </div>

                <form id="formConsumoPersonal" method="POST" action="">
                    @csrf

                    {{-- Cantidad --}}
                    <div class="mb-3">
                        <label for="consumoCantidad" class="form-label fw-semibold text-dark">
                            <i class="bi bi-123 me-1 text-warning"></i>Cantidad a descontar
                        </label>
                        <div class="input-group" style="border-radius: 12px; overflow: hidden;">
                            <button type="button" class="btn btn-outline-secondary px-3" id="btnMenos" onclick="ajustarCantidad(-1)">
                                <i class="bi bi-dash-lg"></i>
                            </button>
                            <input type="number"
                                   id="consumoCantidad"
                                   name="cantidad"
                                   class="form-control text-center fw-bold fs-5 border-start-0 border-end-0"
                                   value="1" min="1" required
                                   style="max-width: 100px;">
                            <button type="button" class="btn btn-outline-secondary px-3" id="btnMas" onclick="ajustarCantidad(1)">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <div class="form-text text-muted small mt-1">
                            <i class="bi bi-info-circle me-1"></i>El stock se reducirá automáticamente.
                        </div>
                    </div>

                    {{-- Descripción / Motivo --}}
                    <div class="mb-4">
                        <label for="consumoDescripcion" class="form-label fw-semibold text-dark">
                            <i class="bi bi-chat-text me-1 text-warning"></i>Motivo / Personal <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <input type="text"
                               id="consumoDescripcion"
                               name="descripcion"
                               class="form-control"
                               placeholder="Ej: Turno noche – Juan P."
                               maxlength="255"
                               style="border-radius: 10px;">
                        <div class="form-text text-muted small mt-1">Puede anotar el nombre del personal o el turno.</div>
                    </div>

                    {{-- Botones --}}
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary fw-semibold flex-fill" data-bs-dismiss="modal" style="border-radius: 10px;">
                            Cancelar
                        </button>
                        <button type="submit" class="btn fw-bold flex-fill text-white" style="background: linear-gradient(135deg, #ff9800, #f57c00); border: none; border-radius: 10px;">
                            <i class="bi bi-people-fill me-2"></i>Registrar Consumo
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
    .product-row {
        transition: background-color 0.2s ease-in-out;
    }
    .product-row:hover {
        background-color: rgba(67, 97, 238, 0.02) !important;
    }
    .product-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #fff;
    }
    .product-img-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background-color: #f8f9fa;
        border-style: dashed !important;
    }
    .bg-light {
        background-color: #f1f3f9 !important;
    }
    .hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-scale:hover {
        transform: scale(1.04);
        box-shadow: 0 4px 8px rgba(67, 97, 238, 0.15) !important;
    }
    .btn-consumo {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: linear-gradient(135deg, #ff9800, #f57c00);
        border: none;
        color: white;
    }
    .btn-consumo:hover {
        transform: scale(1.04);
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.35) !important;
        color: white;
    }
    .inline-stock-form input::-webkit-outer-spin-button,
    .inline-stock-form input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .inline-stock-form input[type=number] {
        -moz-appearance: textfield;
    }
    #consumoCantidad {
        border-left: 1px solid #dee2e6 !important;
        border-right: 1px solid #dee2e6 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalConsumoPersonal');

    modal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        const productoId    = btn.getAttribute('data-producto-id');
        const productoNombre = btn.getAttribute('data-producto-nombre');
        const productoStock = btn.getAttribute('data-producto-stock');

        // Actualizar datos en el modal
        document.getElementById('modalNombreProducto').textContent = productoNombre;
        document.getElementById('modalStockProducto').textContent  = productoStock + ' ud.';

        // Actualizar action del form
        const baseUrl = "{{ url('/cajero/inventario/consumo-personal') }}";
        document.getElementById('formConsumoPersonal').action = baseUrl + '/' + productoId;

        // Resetear campos
        document.getElementById('consumoCantidad').value    = 1;
        document.getElementById('consumoCantidad').max      = productoStock;
        document.getElementById('consumoDescripcion').value = '';
    });
});

function ajustarCantidad(delta) {
    const input = document.getElementById('consumoCantidad');
    const val   = parseInt(input.value) || 1;
    const max   = parseInt(input.max)   || 9999;
    const nuevo = Math.max(1, Math.min(max, val + delta));
    input.value = nuevo;
}
</script>
@endsection
