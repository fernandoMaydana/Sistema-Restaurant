@extends('layouts.admin')

@section('title', 'Alerta de Inventario y Stock Crítico')

@section('admin_content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 bg-white">
            <div class="card-body p-3">
                <form action="{{ route('admin.reportes.stock_critico') }}" method="GET" class="row g-3 align-items-end justify-content-between">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Filtrar por Categoría</label>
                        <select name="categoria_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ $categoria_id == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="text-muted small">Mostrando solo productos con <strong>control de inventario activo</strong></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card shadow-sm border-0 border-start border-4 border-primary h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Productos Monitoreados</div>
                        <h2 class="mb-0 fw-bold text-primary">{{ number_format($totalMonitoreados) }} <span class="fs-6 fw-normal text-muted">ítems</span></h2>
                    </div>
                    <div class="ms-3 bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-box-seam-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card shadow-sm border-0 border-start border-4 border-danger h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Productos Agotados</div>
                        <h2 class="mb-0 fw-bold text-danger">{{ number_format($totalAgotados) }} <span class="fs-6 fw-normal text-muted">ítems</span></h2>
                    </div>
                    <div class="ms-3 bg-danger bg-opacity-10 text-danger rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-slash-circle-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-warning h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">En Stock Crítico (1-5)</div>
                        <h2 class="mb-0 fw-bold text-warning">{{ number_format($totalCriticos) }} <span class="fs-6 fw-normal text-muted">ítems</span></h2>
                    </div>
                    <div class="ms-3 bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Alert Table -->
<div class="card shadow-sm border-0 bg-white mb-4">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="card-title fw-bold mb-0 text-dark">
            <i class="bi bi-bell-fill text-danger me-2 animate-pulse"></i>Listado de Niveles de Stock
        </h5>
        <span class="text-muted small">Estado actual y reposición de inventario</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 80px;">Imagen</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th style="min-width: 180px;">Nivel de Stock</th>
                        <th class="text-center">Stock</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $item)
                        @php
                            $status = 'normal';
                            $percentage = 100;
                            if ($item->stock == 0) {
                                $status = 'out';
                                $percentage = 0;
                            } elseif ($item->stock <= 5) {
                                $status = 'critical';
                                // Calcular porcentaje relativo para gráfico (de 1 a 5, donde 5 es 100% de la barra crítica)
                                $percentage = ($item->stock / 5) * 100;
                            }
                        @endphp
                        <tr class="{{ $status == 'out' ? 'table-danger bg-opacity-25' : ($status == 'critical' ? 'table-warning bg-opacity-25' : '') }}">
                            <td class="ps-4">
                                @if($item->imagen)
                                    <img src="{{ asset('storage/' . $item->imagen) }}" alt="{{ $item->nombre }}" style="width: 42px; height: 42px; object-fit: cover; border-radius: 4px;" class="shadow-xs">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 42px; height: 42px; border: 1px dashed #ccc;">
                                        <i class="bi bi-image" style="font-size: 0.85rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->nombre }}</div>
                                @if($item->precio_nombre)
                                    <small class="text-muted">{{ $item->precio_nombre }}: Bs {{ number_format($item->precio, 2) }}</small>
                                @else
                                    <small class="text-muted">Bs {{ number_format($item->precio, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->categoria->nombre ?? 'Sin Categoría' }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        @if($status == 'out')
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        @elseif($status == 'critical')
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        @else
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-bold">
                                @if($status == 'out')
                                    <span class="badge bg-danger rounded-pill px-3">0</span>
                                @elseif($status == 'critical')
                                    <span class="badge bg-warning text-dark rounded-pill px-3">{{ $item->stock }}</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3">{{ $item->stock }}</span>
                                @endif
                            </td>
                            <td>
                                @if($status == 'out')
                                    <span class="badge bg-danger text-uppercase fw-bold"><i class="bi bi-x-circle-fill me-1"></i> Agotado</span>
                                @elseif($status == 'critical')
                                    <span class="badge bg-warning text-dark text-uppercase fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Stock Crítico</span>
                                @else
                                    <span class="badge bg-success text-uppercase fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Saludable</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.productos.edit', $item->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                    <i class="bi bi-pencil-fill me-1" style="font-size: 0.75rem;"></i> Editar Ficha
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 mb-2 d-block text-black-50"></i>
                                No se encontraron productos monitoreados en esta categoría.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .animate-pulse {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
</style>
@endsection
