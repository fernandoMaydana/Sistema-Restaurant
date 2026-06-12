@extends('layouts.admin')

@section('title', 'Gráficos e Históricos de Ventas')

@section('admin_content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <form action="{{ route('admin.reportes.graficos') }}" method="GET" class="row g-3 align-items-end justify-content-between">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Seleccionar Año de Reporte</label>
                        <select name="anio" class="form-select" onchange="this.form.submit()">
                            @foreach($aniosDisponibles as $a)
                                <option value="{{ $a }}" {{ $anio == $a ? 'selected' : '' }}>Año {{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="text-muted small">Datos consolidados del año <strong>{{ $anio }}</strong></span>
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
                        <div class="text-muted small fw-bold text-uppercase mb-1">Ventas Totales Anuales</div>
                        <h2 class="mb-0 fw-bold text-primary">Bs {{ number_format($totalAnual, 2) }}</h2>
                    </div>
                    <div class="ms-3 bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card shadow-sm border-0 border-start border-4 border-info h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Transacciones del Año</div>
                        <h2 class="mb-0 fw-bold text-info">{{ number_format($totalTransaccionesAnual) }} <span class="fs-6 fw-normal text-muted">ventas</span></h2>
                    </div>
                    <div class="ms-3 bg-info bg-opacity-10 text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-receipt-cutoff fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-success h-100 bg-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Ticket Promedio General</div>
                        <h2 class="mb-0 fw-bold text-success">
                            Bs {{ number_format($totalTransaccionesAnual > 0 ? $totalAnual / $totalTransaccionesAnual : 0, 2) }}
                        </h2>
                    </div>
                    <div class="ms-3 bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-calculator fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Line Chart (Monthly Sales) -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card shadow-sm border-0 bg-white">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title fw-bold mb-0 text-dark">
                    <i class="bi bi-graph-up-arrow text-primary me-2"></i>Evolución Mensual de Ventas
                </h5>
                <span class="text-muted small">Tendencia de ingresos y volumen en el año {{ $anio }}</span>
            </div>
            <div class="card-body">
                <div id="chartVentasMensuales" style="height: 320px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart (Payment Methods) -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 bg-white h-100">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title fw-bold mb-0 text-dark">
                    <i class="bi bi-wallet-fill text-success me-2"></i>Métodos de Pago
                </h5>
                <span class="text-muted small">Distribución de ingresos en el año</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                @if($metodosPago->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-credit-card-2-back fs-1 mb-2 d-block text-black-50"></i>
                        Sin datos de transacciones.
                    </div>
                @else
                    <div id="chartMetodosPago" style="height: 260px; width: 100%;"></div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Monthly Details Table -->
<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="card-title fw-bold mb-0 text-dark">
            <i class="bi bi-calendar-range text-dark me-2"></i>Detalle Mensual Agregado
        </h5>
        <span class="text-muted small">Resumen contable mes a mes</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Mes</th>
                        <th class="text-center">Transacciones Realizadas</th>
                        <th class="text-end">Ticket Promedio</th>
                        <th class="text-end pe-4">Ingresos Totales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datosMensuales as $mes)
                        <tr class="{{ $mes['ventas'] > 0 ? '' : 'text-muted bg-light bg-opacity-25' }}">
                            <td class="ps-4 fw-semibold">{{ $mes['nombre'] }}</td>
                            <td class="text-center">
                                @if($mes['transacciones'] > 0)
                                    <span class="badge bg-light text-dark border">{{ $mes['transacciones'] }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end text-secondary fw-medium">
                                @if($mes['transacciones'] > 0)
                                    Bs {{ number_format($mes['ventas'] / $mes['transacciones'], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end pe-4 fw-bold {{ $mes['ventas'] > 0 ? 'text-success' : 'text-muted' }}">
                                Bs {{ number_format($mes['ventas'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const mesesNombres = [
            @foreach($datosMensuales as $m)
                "{{ $m['nombre'] }}",
            @endforeach
        ];
        
        const ventasValores = [
            @foreach($datosMensuales as $m)
                {{ $m['ventas'] }},
            @endforeach
        ];

        const transaccionesValores = [
            @foreach($datosMensuales as $m)
                {{ $m['transacciones'] }},
            @endforeach
        ];

        // --- 1. GRÁFICO DE EVOLUCIÓN MENSUAL (APEXCHARTS MIXTO) ---
        var optionsVentas = {
            series: [{
                name: 'Ingresos (Bs)',
                type: 'area',
                data: ventasValores
            }, {
                name: 'Transacciones',
                type: 'column',
                data: transaccionesValores
            }],
            chart: {
                height: 320,
                type: 'line',
                toolbar: { show: false }
            },
            stroke: {
                width: [4, 0],
                curve: 'smooth'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: [0.35, 0.65],
                    opacityTo: [0.1, 0.65],
                    stops: [0, 90, 100]
                }
            },
            colors: ['#4361ee', '#06d6a0'],
            labels: mesesNombres,
            markers: {
                size: [5, 0],
                colors: ['#4361ee'],
                strokeWidth: 2,
                strokeColors: '#ffffff'
            },
            yaxis: [{
                title: {
                    text: 'Ingresos (Bs)',
                    style: { color: '#4361ee', fontWeight: 'bold' }
                },
                labels: {
                    formatter: function(val) {
                        return 'Bs ' + val.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    }
                }
            }, {
                opposite: true,
                title: {
                    text: 'Cantidad de Transacciones',
                    style: { color: '#06d6a0', fontWeight: 'bold' }
                },
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0);
                    }
                }
            }],
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y, { seriesIndex }) {
                        if (typeof y !== "undefined") {
                            if (seriesIndex === 0) {
                                return 'Bs ' + y.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            }
                            return y.toFixed(0) + " ventas";
                        }
                        return y;
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1'
            }
        };
        var chartVentas = new ApexCharts(document.querySelector("#chartVentasMensuales"), optionsVentas);
        chartVentas.render();

        // --- 2. GRÁFICO DE MÉTODOS DE PAGO (APEXCHARTS DONUT) ---
        @if(!$metodosPago->isEmpty())
        const metodosLabels = [
            @foreach($metodosPago as $metodo)
                "{{ ucfirst($metodo->metodo_pago) }}",
            @endforeach
        ];
        
        const metodosValores = [
            @foreach($metodosPago as $metodo)
                {{ $metodo->total }},
            @endforeach
        ];

        const metodosColores = {
            'efectivo': '#198754',
            'qr': '#ffc107',
            'tarjeta': '#0d6efd',
            'transferencia': '#6f42c1'
        };

        const backgroundColors = [
            @foreach($metodosPago as $metodo)
                metodosColores["{{ strtolower($metodo->metodo_pago) }}"] || '#6c757d',
            @endforeach
        ];

        var optionsMetodos = {
            series: metodosValores,
            chart: {
                type: 'donut',
                height: 260
            },
            labels: metodosLabels,
            colors: backgroundColors,
            stroke: { show: false },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Ventas',
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
                fontSize: '11px',
                markers: { width: 8, height: 8 }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return 'Bs ' + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        };
        var chartMetodos = new ApexCharts(document.querySelector("#chartMetodosPago"), optionsMetodos);
        chartMetodos.render();
        @endif
    });
</script>
@endsection
