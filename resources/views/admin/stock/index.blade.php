@extends('layouts.admin')

@section('title', 'Kardex y Control de Stock')

@section('actions')
<button type="button" class="btn btn-success fw-bold shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalRegistrarCompra">
    <i class="bi bi-plus-circle-fill"></i> Registrar Compra
</button>
@endsection

@section('admin_content')

{{-- Alertas de Éxito o Error --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Filtros --}}
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-3">
                <form action="{{ route('admin.stock.index') }}" method="GET" class="row g-3 align-items-end justify-content-between">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Seleccionar Mes</label>
                        <select name="mes" class="form-select border-light-subtle bg-light-subtle" style="border-radius: 10px; padding: 10px;">
                            @foreach($mesesFiltro as $key => $val)
                                <option value="{{ $key }}" {{ $mes == $key ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted">Filtrar por Producto</label>
                        <select name="producto_id" class="form-select border-light-subtle bg-light-subtle" style="border-radius: 10px; padding: 10px;">
                            <option value="">Todos los Productos (Bebidas / Consolidado)</option>
                            @foreach($productosList as $prod)
                                <option value="{{ $prod->id }}" {{ $productoId == $prod->id ? 'selected' : '' }}>
                                    {{ $prod->nombre }} ({{ $prod->categoria->nombre ?? 'Sin Categoria' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm" style="border-radius: 10px; padding: 11px;">
                            <i class="bi bi-funnel me-1"></i> Filtrar Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- KPI Cards --}}
<div class="row mb-4">
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="card shadow-sm border-0 border-start border-4 border-secondary h-100 bg-white" style="border-radius: 12px;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Stock Inicial</div>
                        <h2 class="mb-0 fw-bold text-secondary">{{ $totalStockInicial }} <span class="fs-6 fw-normal text-muted">ud.</span></h2>
                    </div>
                    <div class="ms-3 bg-secondary bg-opacity-10 text-secondary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-box fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="card shadow-sm border-0 border-start border-4 border-success h-100 bg-white" style="border-radius: 12px;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Compras</div>
                        <h2 class="mb-0 fw-bold text-success">+{{ $totalCompras }} <span class="fs-6 fw-normal text-muted">ud.</span></h2>
                    </div>
                    <div class="ms-3 bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-plus-lg fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="card shadow-sm border-0 border-start border-4 border-danger h-100 bg-white" style="border-radius: 12px;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Ventas </div>
                        <h2 class="mb-0 fw-bold text-danger">-{{ $totalVentas }} <span class="fs-6 fw-normal text-muted">ud.</span></h2>
                    </div>
                    <div class="ms-3 bg-danger bg-opacity-10 text-danger rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-cart-dash fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-start border-4 border-primary h-100 bg-white" style="border-radius: 12px;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Stock Final / Actual</div>
                        <h2 class="mb-0 fw-bold text-primary">{{ $totalStockFinal }} <span class="fs-6 fw-normal text-muted">ud.</span></h2>
                    </div>
                    <div class="ms-3 bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabla Kardex Diario --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="card-title fw-bold mb-0 text-dark">
            <i class="bi bi-layout-three-columns text-primary me-2"></i>Matriz de Movimiento Diario
        </h5>
        <span class="text-muted small">Desglose horizontal por producto. El stock inicial de un día corresponde al stock del día anterior. Deslice horizontalmente para ver todos los días.</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light">
                    <!-- Fila 1: Fechas -->
                    <tr>
                        <th rowspan="2" class="sticky-col ps-4 py-3 align-middle fw-bold border-end bg-light" style="width: 220px; min-width: 220px; left: 0; position: sticky; z-index: 10;">
                            Producto
                        </th>
                        @foreach($activeDates as $dateInfo)
                            <th colspan="4" class="text-center py-2 fw-bold border-end bg-secondary-subtle" style="font-size: 0.9rem;">
                                <i class="bi bi-calendar3 me-1 text-primary opacity-75"></i>{{ $dateInfo['formatted'] }}
                            </th>
                        @endforeach
                    </tr>
                    <!-- Fila 2: Sub-columnas -->
                    <tr>
                        @foreach($activeDates as $dateInfo)
                            <th class="text-center small py-2 fw-semibold text-muted border-end" style="min-width: 85px; font-size: 0.8rem;">Stock In.</th>
                            <th class="text-center small py-2 fw-semibold text-success border-end" style="min-width: 85px; font-size: 0.8rem;">Compras</th>
                            <th class="text-center small py-2 fw-semibold text-danger border-end" style="min-width: 85px; font-size: 0.8rem;">Ventas</th>
                            <th class="text-center small py-2 fw-semibold border-end" style="min-width: 90px; font-size: 0.8rem; color: #f57c00;">Personal</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($matrix as $row)
                        <tr class="product-row">
                            <td class="sticky-col ps-4 py-3 fw-bold text-dark border-end bg-white" style="left: 0; position: sticky; z-index: 2;">
                                {{ $row['producto']->nombre }}
                            </td>
                            @foreach($activeDates as $dateInfo)
                                @php
                                    $dString = $dateInfo['date_str'];
                                    $dayData = $row['history'][$dString] ?? ['stock_inicial' => 0, 'compras' => 0, 'ventas' => 0, 'consumo_personal' => 0];
                                @endphp
                                <td class="text-center fw-semibold text-muted border-end">
                                    {{ $dayData['stock_inicial'] }}
                                </td>
                                <td class="text-center border-end bg-success-subtle bg-opacity-10">
                                    @if($dayData['compras'] > 0)
                                        <span class="text-success fw-bold">+{{ $dayData['compras'] }}</span>
                                    @else
                                        <span class="text-muted opacity-50">-</span>
                                    @endif
                                </td>
                                <td class="text-center border-end bg-danger-subtle bg-opacity-10">
                                    @if($dayData['ventas'] > 0)
                                        <span class="text-danger fw-bold">-{{ $dayData['ventas'] }}</span>
                                    @else
                                        <span class="text-muted opacity-50">-</span>
                                    @endif
                                </td>
                                <td class="text-center border-end" style="background: rgba(245, 124, 0, 0.04);">
                                    @if(($dayData['consumo_personal'] ?? 0) > 0)
                                        <span class="fw-bold" style="color: #f57c00;">-{{ $dayData['consumo_personal'] }}</span>
                                    @else
                                        <span class="text-muted opacity-50">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 1 + count($activeDates) * 4 }}" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 d-block mb-2 text-black-50"></i>
                                No se encontraron productos de stock habilitados en las categorías especificadas para este mes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .sticky-col {
        position: sticky !important;
        left: 0;
        z-index: 5;
        box-shadow: 3px 0 6px -2px rgba(0,0,0,0.12);
    }
    thead th.sticky-col {
        z-index: 10 !important;
    }
    .product-row:hover .sticky-col {
        background-color: #f8f9fa !important;
    }
    .table-responsive {
        max-height: 650px;
    }
    /* Estilizar scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 10px;
        width: 10px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f8f9fa;
        border-radius: 5px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 5px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

{{-- MODAL REGISTRAR COMPRA --}}
<div class="modal fade" id="modalRegistrarCompra" tabindex="-1" aria-labelledby="modalRegistrarCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 18px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalRegistrarCompraLabel">
                    <i class="bi bi-plus-circle text-success me-2"></i>Registrar Compra de Bebidas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.stock.registrar_compra') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Producto</label>
                        <select name="producto_id" class="form-select" required style="border-radius: 8px; padding: 10px;">
                            <option value="">Seleccione un producto...</option>
                            @foreach($productosList as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->nombre }} (Stock: {{ $prod->stock }} ud.)</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Solo se listan las bebidas habilitadas para control de inventario.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Cantidad a Agregar</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-bag-plus-fill text-success"></i></span>
                            <input type="number" name="cantidad" min="1" step="1" class="form-control fw-bold" placeholder="Ej: 24" required style="border-radius: 0 8px 8px 0; padding: 10px;">
                        </div>
                        <small class="text-muted">Esta cantidad se sumará directamente al stock actual del producto.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary fw-bold px-4" data-bs-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm" style="border-radius: 8px;">Guardar Compra</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
