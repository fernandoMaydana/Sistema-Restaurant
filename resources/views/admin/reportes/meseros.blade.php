@extends('layouts.admin')

@section('title', 'Rendimiento de Meseros')

@section('admin_content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <form action="{{ route('admin.reportes.meseros') }}" method="GET" class="row g-3 align-items-end">
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
        <div class="card shadow-sm border-0 border-start border-4 border-info h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Pedidos Servidos</div>
                        <h2 class="mb-0 fw-bold text-dark">{{ number_format($totalPedidos) }} <span class="fs-6 fw-normal text-muted">pedidos</span></h2>
                    </div>
                    <div class="ms-3 bg-info bg-opacity-10 text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-journal-check fs-4"></i>
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
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Facturado por Meseros</div>
                        <h2 class="mb-0 fw-bold text-success">Bs {{ number_format($totalVentas, 2) }}</h2>
                    </div>
                    <div class="ms-3 bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Column -->
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0 h-100 bg-white">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title fw-bold mb-0 text-dark">
                    <i class="bi bi-pie-chart text-info me-2"></i>Distribución de Ventas
                </h5>
                <span class="text-muted small">Participación de facturación por mesero</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                @if($meseros->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-person-x fs-1 mb-2 d-block text-black-50"></i>
                        No hay suficientes datos para graficar.
                    </div>
                @else
                    <div style="position: relative; height:280px; width:100%;">
                        <canvas id="chartMeseros"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Table Column -->
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0 h-100 bg-white">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title fw-bold mb-0 text-dark">
                    <i class="bi bi-award text-info me-2"></i>Rendimiento Detallado
                </h5>
                <span class="text-muted small">Métricas de eficiencia en el salón</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 60px;">Puesto</th>
                                <th>Mesero</th>
                                <th class="text-center">Pedidos</th>
                                <th class="text-end">Ticket Promedio</th>
                                <th class="text-end pe-3">Total Ventas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($meseros as $index => $item)
                                <tr>
                                    <td class="ps-3 text-center">
                                        @if($index == 0)
                                            <span class="badge bg-warning text-dark rounded-circle px-2 py-1"><i class="bi bi-star-fill"></i></span>
                                        @else
                                            <span class="text-muted fw-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="d-flex align-items-center justify-content-center rounded-circle me-2 text-white fw-bold shadow-sm"
                                                  style="width: 32px; height: 32px; background: linear-gradient(135deg, #0dcaf0, #0099ff); font-size: 0.85rem;">
                                                {{ strtoupper(substr($item->mesero->name ?? 'M', 0, 1)) }}
                                            </span>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $item->mesero->name ?? 'Mesero Eliminado' }}</div>
                                                <small class="text-muted" style="font-size: 0.75rem;">Rol: Mesero</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold">{{ number_format($item->total_pedidos) }}</td>
                                    <td class="text-end fw-semibold text-secondary">
                                        Bs {{ number_format($item->total_pedidos > 0 ? $item->total_ventas / $item->total_pedidos : 0, 2) }}
                                    </td>
                                    <td class="text-end pe-3 fw-bold text-success">Bs {{ number_format($item->total_ventas, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-person-x fs-1 mb-2 d-block text-black-50"></i>
                                        No se registran pedidos cobrados de meseros en este periodo.
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
@if(!$meseros->isEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('chartMeseros').getContext('2d');
        
        const labels = [
            @foreach($meseros as $item)
                "{!! addslashes($item->mesero->name ?? 'Mesero Eliminado') !!}",
            @endforeach
        ];
        
        const data = [
            @foreach($meseros as $item)
                {{ $item->total_ventas }},
            @endforeach
        ];

        // Paleta de colores atractiva HSL
        const colors = [
            '#0dcaf0', '#0d6efd', '#6f42c1', '#d63384', '#fd7e14', '#ffc107', '#198754', '#20c997'
        ];

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, data.length),
                    hoverOffset: 8,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return ` Bs ${value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endif
@endsection
