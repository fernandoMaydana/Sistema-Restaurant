@extends('layouts.admin')

@section('title', 'Rentabilidad y Utilidades')

@section('admin_content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <form action="{{ route('admin.reportes.rentabilidad') }}" method="GET" class="row g-3 align-items-end justify-content-between">
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Desde</label>
                        <input type="date" name="fecha_desde" class="form-control" value="{{ $fecha_desde }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ $fecha_hasta }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-primary"><i class="bi bi-calendar-event me-1"></i>Día Específico</label>
                        <input type="date" name="fecha_especifica" class="form-control border-primary" value="{{ $fecha_especifica }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Categoría</label>
                        <select name="categoria_id" class="form-select">
                            <option value="">Todas las Categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ $categoria_id == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="card shadow-sm border-0 border-start border-4 border-success h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Ventas Brutas</div>
                        <h2 class="mb-0 fw-bold text-success">Bs {{ number_format($totalVentas, 2) }}</h2>
                    </div>
                    <div class="ms-3 bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-arrow-up-right fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="card shadow-sm border-0 border-start border-4 border-danger h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Costo de Insumos</div>
                        <h2 class="mb-0 fw-bold text-danger">Bs {{ number_format($totalCosto, 2) }}</h2>
                    </div>
                    <div class="ms-3 bg-danger bg-opacity-10 text-danger rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-cart-x fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="card shadow-sm border-0 border-start border-4 border-primary h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Ganancia / Utilidad</div>
                        <h2 class="mb-0 fw-bold text-primary">Bs {{ number_format($totalUtilidad, 2) }}</h2>
                    </div>
                    <div class="ms-3 bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-start border-4 border-warning h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Margen de Retorno</div>
                        <h2 class="mb-0 fw-bold text-warning">
                            {{ $totalVentas > 0 ? number_format(($totalUtilidad / $totalVentas) * 100, 1) : 0 }}%
                        </h2>
                    </div>
                    <div class="ms-3 bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-percent fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Tabla de Productos -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card shadow-sm border-0 bg-white h-100">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title fw-bold mb-0 text-dark">
                    <i class="bi bi-list-stars text-primary me-2"></i>Análisis de Rentabilidad por Producto
                </h5>
                <span class="text-muted small">Detalle del costo contra ingresos y margen de utilidad</span>
            </div>
            <div class="card-body p-0 overflow-auto" style="max-height: 480px;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Producto</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Costo Unit.</th>
                                <th class="text-end">Ingresos</th>
                                <th class="text-end">Costo Total</th>
                                <th class="text-end">Utilidad</th>
                                <th class="text-end pe-4">Margen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reporte as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $item['nombre_mostrar'] }}</div>
                                        <small class="text-muted">{{ $item['producto']->categoria->nombre ?? 'Sin Categoria' }}</small>
                                    </td>
                                    <td class="text-center fw-medium">{{ $item['cantidad'] }}</td>
                                    <td class="text-end text-muted">Bs {{ number_format($item['costo'], 2) }}</td>
                                    <td class="text-end fw-semibold">Bs {{ number_format($item['ingresos'], 2) }}</td>
                                    <td class="text-end text-danger">Bs {{ number_format($item['costo_total'], 2) }}</td>
                                    <td class="text-end text-success fw-bold">Bs {{ number_format($item['utilidad'], 2) }}</td>
                                    <td class="text-end pe-4 fw-bold text-warning">
                                        {{ $item['ingresos'] > 0 ? number_format(($item['utilidad'] / $item['ingresos']) * 100, 0) : 0 }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-file-earmark-bar-graph fs-1 d-block mb-2 text-black-50"></i>
                                        No hay registros de ventas para el periodo seleccionado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico ApexCharts -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 bg-white h-100">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title fw-bold mb-0 text-dark">
                    <i class="bi bi-pie-chart text-success me-2"></i>Estructura de Retorno
                </h5>
                <span class="text-muted small">Costo total vs Utilidad total</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                @if($totalVentas == 0)
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-pie-chart-fill fs-1 mb-2 d-block text-black-50"></i>
                        Esperando datos...
                    </div>
                @else
                    <div style="position: relative; height: 320px; width: 100%;">
                        <div id="chartUtilidades" style="height: 320px; width: 100%;"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($totalVentas > 0)
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            series: [{{ $totalCosto }}, {{ $totalUtilidad }}],
            chart: {
                type: 'donut',
                height: 320
            },
            labels: ['Costo Insumos', 'Ganancia Neta'],
            colors: ['#e63946', '#4361ee'],
            stroke: { show: false },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Ingreso Neto',
                                fontSize: '14px',
                                color: '#6c757d',
                                formatter: function(w) {
                                    const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return 'Bs ' + total.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '12px'
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return 'Bs ' + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#chartUtilidades"), options);
        chart.render();
    });
</script>
@endif
@endsection
