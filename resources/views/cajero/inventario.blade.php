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

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .custom-scrollbar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .btn-categoria {
            transition: background-color 0.25s ease, color 0.25s ease, transform 0.15s ease;
        }
        .btn-categoria:hover:not(.active) {
            background-color: #f1f3f9;
            color: #212529 !important;
            transform: translateY(-1px);
        }
        .btn-categoria.active {
            background-color: #4361ee !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.25);
        }
    </style>

    {{-- Card de Filtros dinámicos --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <!-- Fila de Categorías superiores tipo Pestañas -->
            <div class="mb-4">
                <label class="form-label fw-bold text-muted mb-2"><i class="bi bi-tags-fill me-1"></i> Filtrar por Categoría</label>
                <ul class="nav nav-pills flex-nowrap overflow-auto pb-2 custom-scrollbar" id="categoriaTabs" style="gap: 8px;">
                    <li class="nav-item">
                        <button type="button" class="nav-link active fw-bold px-4 py-2 btn-categoria" data-categoria-id="todos" style="border-radius: 20px;">
                            📂 Todos los Productos
                        </button>
                    </li>
                    @foreach($categorias as $cat)
                        <li class="nav-item">
                            <button type="button" class="nav-link fw-bold px-4 py-2 btn-categoria text-secondary" data-categoria-id="{{ $cat->id }}" style="border-radius: 20px;">
                                {{ $cat->nombre }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Buscador en tiempo real -->
            <div>
                <label class="form-label fw-bold text-muted mb-2"><i class="bi bi-search me-1"></i> Buscar Producto</label>
                <div class="input-group">
                    <span class="input-group-text border-light-subtle bg-light-subtle" style="border-radius: 12px 0 0 12px; padding: 12px;"><i class="bi bi-search"></i></span>
                    <input type="text" id="buscadorInput" class="form-control border-light-subtle bg-light-subtle" placeholder="Escriba el nombre del producto para buscar instantáneamente..." style="border-radius: 0 12px 12px 0; padding: 12px;">
                </div>
            </div>
        </div>
    </div>

    {{-- Grid de Tarjetas de Inventario --}}
    <div class="row" id="productosGrid">
        @foreach($productos as $producto)
            <div class="col-md-6 col-lg-3 mb-4 producto-tarjeta-col" data-categoria-id="{{ $producto->categoria_id }}" data-nombre="{{ strtolower($producto->nombre) }}">
                <div class="card h-100 border-0 shadow-sm overflow-hidden product-card" style="border-radius: 16px;">
                    <!-- Badge de Categoría flotante -->
                    <div class="position-absolute top-0 start-0 m-3 z-3">
                        <span class="badge bg-dark bg-opacity-75 text-white px-3 py-2 rounded-pill shadow-sm" style="backdrop-filter: blur(4px); font-size: 0.75rem;">
                            {{ $producto->categoria->nombre ?? 'Sin Categoría' }}
                        </span>
                    </div>

                    <!-- Badge de disponibilidad en carta flotante -->
                    <div class="position-absolute top-0 end-0 m-3 z-3">
                        @if(!$producto->disponible)
                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.75rem;">
                                No en Carta
                            </span>
                        @endif
                    </div>
                    
                    <!-- Imagen -->
                    @if($producto->imagen)
                        <div class="product-zoom-container w-100" style="height: 150px; border-radius: 0; border: none; box-shadow: none;">
                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="product-zoom-img card-img-top w-100 h-100" style="object-fit: cover; cursor: zoom-in;" onclick="mostrarLightbox(this)">
                        </div>
                    @else
                        <div class="product-placeholder-gradient card-img-top d-flex align-items-center justify-content-center text-muted w-100" style="height: 150px; border-radius: 0; border: none;">
                            <i class="bi bi-egg-fried" style="font-size: 3.5rem;"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column p-4">
                        <h5 class="card-title fw-bold text-dark mb-2" style="font-size: 1.15rem;">{{ $producto->nombre }}</h5>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                            <div>
                                <span class="text-muted small d-block mb-1">Precio</span>
                                <span class="fs-5 fw-bold text-secondary">Bs {{ number_format($producto->precio, 2) }}</span>
                            </div>
                            
                            <div class="text-end">
                                <span class="text-muted small d-block mb-1">Stock Actual</span>
                                @if($producto->stock > 10)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold fs-6">
                                        {{ $producto->stock }} ud.
                                    </span>
                                @elseif($producto->stock > 0)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 rounded-pill fw-bold fs-6">
                                        {{ $producto->stock }} ud.
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-bold fs-6">
                                        Agotado
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Formulario de Reabastecimiento de Stock -->
                        <div class="border-top pt-3 mt-auto">
                            <form action="{{ route('cajero.inventario.agregar_stock', $producto->id) }}" method="POST" class="inline-stock-form mb-0">
                                @csrf
                                <label class="form-label small fw-bold text-muted mb-2"><i class="bi bi-plus-circle me-1"></i>Reabastecer stock:</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-light-subtle text-muted" style="border-radius: 12px 0 0 12px; padding: 10px;">+</span>
                                    <input type="number" name="cantidad" min="1" class="form-control border-light-subtle bg-light text-center fw-bold" placeholder="Cant..." required style="padding: 10px;">
                                    <button type="submit" class="btn btn-primary px-3 fw-bold shadow-sm d-flex align-items-center gap-1 hover-scale" style="border-radius: 0 12px 12px 0;">
                                        <i class="bi bi-plus-lg"></i> Agregar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center p-5 text-muted bg-white border-0 shadow-sm mb-4" id="empty-products-msg" style="display: {{ $productos->isEmpty() ? 'block' : 'none' }}; border-radius: 16px;">
        <div class="mb-3">
            <i class="bi bi-box-seam" style="font-size: 3.5rem; opacity: 0.5;"></i>
        </div>
        <p class="fs-5 fw-semibold text-dark">No se encontraron productos con inventario activo</p>
        <p class="small text-muted">Intente cambiar los filtros o busque otro producto.</p>
    </div>
</div>

<style>
    .product-card {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesCategoria = document.querySelectorAll('.btn-categoria');
    const buscadorInput = document.getElementById('buscadorInput');
    const tarjetasProductos = document.querySelectorAll('.producto-tarjeta-col');
    const emptyMessage = document.getElementById('empty-products-msg');
    
    let categoriaSeleccionada = 'todos';
    let textoBusqueda = '';

    // Manejar clics de categorías
    botonesCategoria.forEach(btn => {
        btn.addEventListener('click', function () {
            // Remover activo de todos los botones
            botonesCategoria.forEach(b => {
                b.classList.remove('active', 'btn-primary');
                b.classList.add('text-secondary');
            });
            
            // Activar botón clickeado
            this.classList.add('active', 'btn-primary');
            this.classList.remove('text-secondary');
            
            categoriaSeleccionada = this.getAttribute('data-categoria-id');
            filtrarProductos();
        });
    });

    // Manejar escritura en el buscador
    buscadorInput.addEventListener('input', function () {
        textoBusqueda = this.value.toLowerCase().trim();
        filtrarProductos();
    });

    function filtrarProductos() {
        let visibles = 0;
        
        tarjetasProductos.forEach(tarjeta => {
            const catId = tarjeta.getAttribute('data-categoria-id');
            const nombre = tarjeta.getAttribute('data-nombre');
            
            const coincideCategoria = (categoriaSeleccionada === 'todos' || catId === categoriaSeleccionada);
            const coincideBusqueda = (nombre.includes(textoBusqueda));
            
            if (coincideCategoria && coincideBusqueda) {
                tarjeta.style.display = '';
                visibles++;
            } else {
                tarjeta.style.display = 'none';
            }
        });

        // Mostrar / Ocultar mensaje de vacío
        if (visibles === 0) {
            emptyMessage.style.display = 'block';
        } else {
            emptyMessage.style.display = 'none';
        }
    }
});
</script>
@endsection
