@extends('layouts.admin')

@section('title', 'Carta de Productos')

@section('actions')
    <a href="{{ route('admin.productos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo Producto</a>
@endsection

@section('admin_content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.productos.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Categoría</label>
                <select name="categoria_id" class="form-select">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Buscar Producto</label>
                <input type="text" name="buscar" class="form-control" placeholder="Nombre del producto..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Imagen</th>
                        <th>Categoría</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Inventario</th>
                        <th>Disponibilidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                        <tr>
                            <td>
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px; border: 1px dashed #ccc;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                            <td class="fw-bold">{{ $producto->nombre }}</td>
                            <td>Bs {{ number_format($producto->precio, 2) }}</td>
                            <td>
                                @if($producto->usa_inventario)
                                    @if($producto->stock > 10)
                                        <span class="badge bg-success">{{ $producto->stock }} ud.</span>
                                    @elseif($producto->stock > 0)
                                        <span class="badge bg-warning text-dark">{{ $producto->stock }} ud.</span>
                                    @else
                                        <span class="badge bg-danger">Agotado (0)</span>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($producto->disponible)
                                    <span class="badge bg-success">Disponible</span>
                                @else
                                    <span class="badge bg-danger">Agotado</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form action="{{ route('admin.productos.destroy', $producto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro de eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($productos->isEmpty())
            <div class="text-center p-4 text-muted">
                No hay productos en la carta.
            </div>
        @endif
    </div>
</div>
@endsection
