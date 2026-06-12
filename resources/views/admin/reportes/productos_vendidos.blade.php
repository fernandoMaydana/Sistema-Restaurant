@extends('layouts.admin')

@section('title', 'Productos Más Vendidos')

@section('admin_content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <form action="{{ route('admin.reportes.productos_vendidos') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Fecha Desde</label>
                        <input type="date" name="fecha_desde" class="form-control" value="{{ $fecha_desde }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Fecha Hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ $fecha_hasta }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-primary"><i class="bi bi-calendar-event me-1"></i>Día Específico</label>
                        <input type="date" name="fecha_especifica" class="form-control border-primary" value="{{ $fecha_especifica }}">
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter-right me-1"></i> Filtrar Reporte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="row mb-4">
    <div class="col-md-6 mb-3 mb-md-0">
        <div class="card shadow-sm border-0 border-start border-4 border-warning h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Unidades Vendidas</div>
                        <h2 class="mb-0 fw-bold text-dark">{{ number_format($totalCantidad) }} <span class="fs-6 fw-normal text-muted">uds.</span></h2>
                    </div>
                    <div class="ms-3 bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 border-start border-4 border-success h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Ingresos Generados</div>
                        <h2 class="mb-0 fw-bold text-success">Bs {{ number_format($totalRecaudado, 2) }}</h2>
                    </div>
                    <div class="ms-3 bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-cash-coin fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Column -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100 bg-white">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title fw-bold mb-0 text-dark">
                    <i class="bi bi-bar-chart-line text-warning me-2"></i>Top 10 Productos Estrella
                </h5>
                <span class="text-muted small">Representación por unidades vendidas</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                @if($topProductos->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-pie-chart fs-1 mb-2 d-block text-black-50"></i>
                        No hay suficientes datos para graficar.
                    </div>
                @else
                    <div style="position: relative; height:320px; width:100%;">
                        <canvas id="chartTopProductos"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Table Column -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100 bg-white">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title fw-bold mb-0 text-dark">
                    <i class="bi bi-list-ol text-warning me-2"></i>Ranking de Productos
                </h5>
                <span class="text-muted small">Detalle completo de ventas por producto</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-3" style="width: 60px;">Puesto</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end pe-3">Ingresos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productos as $index => $item)
                                <tr>
                                    <td class="ps-3 text-center">
                                        @if($index == 0)
                                            <span class="badge bg-warning text-dark rounded-circle px-2 py-1"><i class="bi bi-trophy-fill"></i></span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary rounded-circle px-2 py-1">2</span>
                                        @elseif($index == 2)
                                            <span class="badge bg-light text-dark border rounded-circle px-2 py-1">3</span>
                                        @else
                                            <span class="text-muted fw-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->producto && $item->producto->imagen)
                                                <img src="{{ asset('storage/' . $item->producto->imagen) }}" alt="{{ $item->producto->nombre }}" style="width: 38px; height: 38px; object-fit: cover; border-radius: 4px;" class="me-2 shadow-xs">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted me-2" style="width: 38px; height: 38px; border: 1px dashed #ccc;">
                                                    <i class="bi bi-image" style="font-size: 0.8rem;"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $item->nombre_mostrar }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $item->producto->categoria->nombre ?? 'Sin Categoría' }}</span>
                                    </td>
                                    <td class="text-center fw-bold">{{ number_format($item->total_cantidad) }}</td>
                                    <td class="text-end pe-3 fw-bold text-success">Bs {{ number_format($item->total_recaudado, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-box-seam fs-1 mb-2 d-block text-black-50"></i>
                                        No se registran productos vendidos en este periodo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@if(!$topProductos->isEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('chartTopProductos').getContext('2d');
        
        const labels = [
            @foreach($topProductos as $item)
                "{!! addslashes($item->nombre_mostrar) !!}",
            @endforeach
        ];
        
        const data = [
            @foreach($topProductos as $item)
                {{ $item->total_cantidad }},
            @endforeach
        ];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: data,
                    backgroundColor: 'rgba(255, 193, 7, 0.75)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1.5,
                    borderRadius: 4,
                    barPercentage: 0.65
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // Convert to horizontal bar chart
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.parsed.x} unidades`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f1f1'
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
