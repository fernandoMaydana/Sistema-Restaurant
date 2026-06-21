@extends('layouts.admin')

@section('title', 'Gestión de Combos y Promociones')

@section('actions')
    <a href="{{ route('admin.combos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo Combo / Promo</a>
@endsection

@section('admin_content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.combos.index') }}" method="GET" class="row g-3">
            <div class="col-md-10">
                <label class="form-label">Buscar Combo o Promoción</label>
                <input type="text" name="buscar" class="form-control" placeholder="Nombre o descripción..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($combos as $combo)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden combo-card" style="transition: transform 0.2s, box-shadow 0.2s;">
                <div class="position-absolute top-0 end-0 m-2 z-3">
                    <span class="badge {{ $combo->tipo === 'fijo' ? 'bg-primary' : 'bg-warning text-dark' }} px-3 py-2 rounded-pill shadow-sm">
                        {{ $combo->tipo === 'fijo' ? 'Precio Fijo' : 'Condicionado' }}
                    </span>
                </div>
                
                @if($combo->imagen)
                    <img src="{{ asset('storage/' . $combo->imagen) }}" class="card-img-top" alt="{{ $combo->nombre }}" style="height: 180px; object-fit: cover;">
                @else
                    <div class="bg-gradient-secondary card-img-top d-flex align-items-center justify-content-center text-muted" style="height: 180px; background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);">
                        <i class="bi bi-gift-fill text-secondary" style="font-size: 3rem;"></i>
                    </div>
                @endif

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-dark mb-1">{{ $combo->nombre }}</h5>
                    <p class="card-text text-muted small mb-3">{{ $combo->descripcion ?? 'Sin descripción' }}</p>
                    
                    <h6 class="fw-bold mb-2 text-secondary small text-uppercase">Productos Incluidos:</h6>
                    <ul class="list-group list-group-flush mb-4 small flex-grow-1">
                        @foreach($combo->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-1">
                                <span>
                                    <span class="badge bg-secondary me-1">{{ $item->cantidad }}x</span>
                                    {{ $item->producto->nombre ?? 'Producto Eliminado' }}
                                </span>
                                @if($item->es_gratuito)
                                    <span class="badge bg-success">Gratis</span>
                                @else
                                    <span class="text-muted">Bs {{ number_format(($item->producto->precio ?? 0), 2) }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                        <div>
                            <span class="text-muted small d-block">Precio Combo</span>
                            <span class="fs-4 fw-bold text-success">Bs {{ number_format($combo->precio_mostrar, 2) }}</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-activo" type="checkbox" role="switch" 
                                   data-id="{{ $combo->id }}" {{ $combo->activo ? 'checked' : '' }}>
                            <label class="form-check-label text-muted small">{{ $combo->activo ? 'Activo' : 'Inactivo' }}</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light border-0 d-flex justify-content-end gap-2 py-3">
                    <a href="{{ route('admin.combos.edit', $combo->id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <form action="{{ route('admin.combos.destroy', $combo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro de eliminar este combo?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center p-5 text-muted">
            <i class="bi bi-gift" style="font-size: 4rem;"></i>
            <p class="mt-3 fs-5">No se encontraron combos ni promociones registradas.</p>
            <a href="{{ route('admin.combos.create') }}" class="btn btn-primary mt-2">Crear el Primero</a>
        </div>
    @endforelse
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar el toggle dinámico de activo/inactivo
    const toggles = document.querySelectorAll('.toggle-activo');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const comboId = this.dataset.id;
            const label = this.nextElementSibling;
            
            fetch(`/admin/combos/${comboId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.activo) {
                        label.textContent = 'Activo';
                        this.checked = true;
                    } else {
                        label.textContent = 'Inactivo';
                        this.checked = false;
                    }
                } else {
                    alert('Error al actualizar estado');
                    this.checked = !this.checked; // revertir
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión');
                this.checked = !this.checked; // revertir
            });
        });
    });
});
</script>
<style>
.combo-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1) !important;
}
</style>
@endsection
