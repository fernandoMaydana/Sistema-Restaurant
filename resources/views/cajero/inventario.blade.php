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
                            <th class="py-3 text-uppercase text-muted fw-bold text-center" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 150px;">Stock Actual</th>
                            <th class="pe-4 py-3 text-uppercase text-muted fw-bold text-end" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 300px;">Agregar Stock</th>
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
                                <td class="pe-4 py-3 text-end">
                                    <form action="{{ route('cajero.inventario.agregar_stock', $producto->id) }}" method="POST" class="d-flex align-items-center justify-content-end gap-2 inline-stock-form">
                                        @csrf
                                        <div class="input-group input-group-sm" style="width: 110px;">
                                            <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 8px 0 0 8px;">+</span>
                                            <input type="number" name="cantidad" min="1" class="form-control border-start-0 text-center fw-bold form-control-sm" placeholder="Cant." required style="border-radius: 0 8px 8px 0;">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary px-3 py-2 fw-bold rounded-pill d-flex align-items-center gap-1 shadow-sm hover-scale">
                                            <i class="bi bi-plus-lg"></i> Agregar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
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
    .inline-stock-form input::-webkit-outer-spin-button,
    .inline-stock-form input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .inline-stock-form input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endsection
