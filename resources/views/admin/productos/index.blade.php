@extends('layouts.admin')

@section('title', 'Carta de Productos')

@section('actions')
    <a href="{{ route('admin.productos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo Producto</a>
@endsection

@section('admin_content')
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

<div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
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
<<div class="row" id="productosGrid">
    @foreach($productos as $producto)
        <div class="col-md-6 col-lg-3 mb-4 producto-tarjeta-col" data-categoria-id="{{ $producto->categoria_id }}" data-nombre="{{ strtolower($producto->nombre) }}">
            <div class="card h-100 border-0 shadow-sm overflow-hidden product-card" style="border-radius: 16px;">
                <!-- Badge de Categoría flotante -->
                <div class="position-absolute top-0 start-0 m-3 z-3">
                    <span class="badge bg-dark bg-opacity-75 text-white px-3 py-2 rounded-pill shadow-sm" style="backdrop-filter: blur(4px); font-size: 0.75rem;">
                        {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                    </span>
                </div>

                <!-- Badge de disponibilidad o agotado -->
                <div class="position-absolute top-0 end-0 m-3 z-3">
                    @if($producto->disponible)
                        <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.75rem;">
                            Disponible
                        </span>
                    @else
                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.75rem;">
                            Agotado
                        </span>
                    @endif
                </div>
                
                <!-- Imagen en la cabecera -->
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
                    <h5 class="card-title fw-bold text-dark mb-3" style="font-size: 1.15rem;">{{ $producto->nombre }}</h5>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 mt-auto">
                        <div>
                            <span class="text-muted small d-block mb-1">Precio</span>
                            <span class="fs-4 fw-bold text-success">Bs {{ number_format($producto->precio, 2) }}</span>
                        </div>
                        
                        <div class="text-end">
                            <span class="text-muted small d-block mb-1">Inventario</span>
                            @if($producto->usa_inventario)
                                @if($producto->stock > 10)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold">
                                        {{ $producto->stock }} ud.
                                    </span>
                                @elseif($producto->stock > 0)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1 rounded-pill fw-bold">
                                        {{ $producto->stock }} ud.
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill fw-bold">
                                        Agotado
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill fw-bold">
                                    Ilimitado
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between gap-2 border-top pt-3">
                        <a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn btn-outline-primary rounded-pill px-3 flex-grow-1">
                            <i class="bi bi-pencil-fill me-1"></i> Editar
                        </a>
                        <form action="{{ route('admin.productos.destroy', $producto->id) }}" method="POST" class="swal-confirm-form flex-grow-1 mb-0" 
                              data-swal-title="¿Eliminar producto?" 
                              data-swal-message="Esta acción no se puede deshacer y el producto ya no se mostrará en la carta."
                              data-swal-icon="warning"
                              data-swal-confirm-text="Sí, eliminar"
                              data-swal-cancel-text="Cancelar">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-3 w-100">
                                <i class="bi bi-trash-fill me-1"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="text-center p-5 text-muted bg-white shadow-sm border-0 mb-4" id="empty-products-msg" style="display: {{ $productos->isEmpty() ? 'block' : 'none' }}; border-radius: 16px;">
    <div class="mb-3">
        <i class="bi bi-box-seam" style="font-size: 3.5rem; opacity: 0.5;"></i>
    </div>
    <p class="fs-5 fw-semibold text-dark">No se encontraron productos</p>
    <p class="small text-muted">Intenta cambiar de categoría o buscar otro producto.</p>
</div>
@endsection

@section('scripts')
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
