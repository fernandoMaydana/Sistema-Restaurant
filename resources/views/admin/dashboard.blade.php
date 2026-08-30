@extends('layouts.admin')

@section('title', 'Dashboard Ejecutivo')

@section('admin_content')
<div class="row g-3 mb-4">
    {{-- Selector de Fecha --}}
    <div class="col-lg-4 col-md-12">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <label class="form-label fw-bold text-muted small text-uppercase mb-2">
                    <i class="bi bi-calendar-event me-1 text-primary"></i> Filtrar por Fecha
                </label>
                <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
                    <input type="date" name="fecha" class="form-control form-control-lg border-2 rounded-3" value="{{ $fecha }}">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold px-3 rounded-3 shadow-sm d-flex align-items-center gap-1">
                        <i class="bi bi-funnel-fill"></i>
                        <span>Filtrar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- KPI Ventas --}}
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 text-white overflow-hidden" 
             style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="card-body p-4 position-relative d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-white-50 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Ventas</span>
                        <h6 class="text-white opacity-75 small mb-0">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</h6>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="background: rgba(255, 255, 255, 0.2); width: 48px; height: 48px;">
                        <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h2 class="mb-0 fw-black display-6">Bs {{ number_format($totalVentas, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI Stock --}}
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 text-white position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-white-50 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Control de Inventario</span>
                        <h6 class="text-white opacity-75 small mb-0">Unidades registradas</h6>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="background: rgba(255, 255, 255, 0.15); width: 48px; height: 48px;">
                        <i class="bi bi-box-seam-fill fs-4"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex align-items-baseline gap-2">
                        <h2 class="mb-0 fw-black display-6">{{ $totalBebidasStock }}</h2>
                        <span class="small text-white-50">unidades</span>
                    </div>
                    @if($stockBebidasCritico > 0)
                        <div class="badge bg-warning text-dark mt-2 fw-bold px-3 py-2 rounded-pill">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $stockBebidasCritico }} con stock crítico
                        </div>
                    @else
                        <div class="badge bg-success text-white mt-2 fw-bold px-3 py-2 rounded-pill">
                            <i class="bi bi-check-circle-fill me-1"></i> Inventario en orden
                        </div>
                    @endif
                </div>
                <a href="{{ route('admin.stock.index') }}" class="stretched-link"></a>
            </div>
        </div>
    </div>
</div>

{{-- Tabla de Mesas Pagadas --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="bi bi-receipt-cutoff text-primary"></i>
            Mesas y Transacciones Pagadas ({{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }})
        </h5>
        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill">
            {{ $facturasHoy->count() }} registros
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Hora</th>
                        <th>Mesa / Pedido</th>
                        <th>Cajero / Usuario</th>
                        <th>Cliente</th>
                        <th>Total Pagado</th>
                        <th>Método de Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturasHoy as $factura)
                        <tr class="{{ $factura->estado === 'anulada' ? 'table-danger opacity-75' : '' }}">
                            <td class="ps-4 fw-bold text-muted">{{ $factura->created_at->format('H:i') }}</td>
                            <td>
                                <span class="fw-bold text-dark">
                                    @if(($factura->pedido->mesa->es_para_llevar ?? false))
                                        🛍️ Llevar #{{ $factura->pedido->mesa->numero ?? 'N/A' }}
                                    @else
                                        🪑 Mesa {{ $factura->pedido->mesa->numero ?? 'N/A' }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="small text-secondary">
                                    <i class="bi bi-person-circle me-1"></i>{{ $factura->cajero->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $factura->cliente_nombre ?? 'Consumidor Final' }}</td>
                            <td>
                                <span class="fw-black fs-6 {{ $factura->estado === 'anulada' ? 'text-decoration-line-through text-danger' : 'text-success' }}">
                                    Bs {{ number_format($factura->monto_pagado, 2) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $metodoBadge = [
                                        'efectivo' => ['bg' => 'bg-success-subtle text-success border border-success-subtle', 'icon' => 'bi-cash'],
                                        'tarjeta' => ['bg' => 'bg-primary-subtle text-primary border border-primary-subtle', 'icon' => 'bi-credit-card'],
                                        'qr' => ['bg' => 'bg-info-subtle text-info border border-info-subtle', 'icon' => 'bi-qr-code-scan'],
                                        'transferencia' => ['bg' => 'bg-purple-subtle text-purple border border-purple-subtle', 'icon' => 'bi-bank']
                                    ][$factura->metodo_pago] ?? ['bg' => 'bg-secondary text-white', 'icon' => 'bi-wallet2'];
                                @endphp

                                <span class="badge {{ $metodoBadge['bg'] }} px-3 py-2 rounded-pill fw-bold" style="font-size: 0.78rem;">
                                    <i class="bi {{ $metodoBadge['icon'] }} me-1"></i>{{ ucfirst($factura->metodo_pago) }}
                                </span>

                                @if($factura->estado === 'anulada')
                                    <span class="badge bg-danger text-white ms-1 px-2 py-1 rounded-pill" style="font-size: 0.7rem;">ANULADA</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                No se han registrado pagos para la fecha seleccionada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
