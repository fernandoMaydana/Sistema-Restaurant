@extends('layouts.admin')

@section('title', 'Historial de Ventas')

@section('admin_content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title text-white-50 text-uppercase fw-bold mb-1">Total Filtrado</h6>
                <h2 class="mb-0 fw-bold">Bs {{ number_format($total_filtrado, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-funnel me-1"></i> Filtros de Búsqueda
    </div>
    <div class="card-body">
        <form action="{{ route('admin.ventas.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Desde</label>
                <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Cajero</label>
                <select name="cajero_id" class="form-select">
                    <option value="">Todos los cajeros</option>
                    @foreach($cajeros as $cajero)
                        <option value="{{ $cajero->id }}" {{ request('cajero_id') == $cajero->id ? 'selected' : '' }}>{{ $cajero->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Método de Pago</label>
                <select name="metodo_pago" class="form-select">
                    <option value="">Todos</option>
                    <option value="efectivo" {{ request('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="tarjeta" {{ request('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                    <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    <option value="qr" {{ request('metodo_pago') == 'qr' ? 'selected' : '' }}>QR</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
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
                        <th>ID Venta</th>
                        <th>Fecha y Hora</th>
                        <th>Mesa</th>
                        <th>Cajero</th>
                        <th>Cliente</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas as $factura)
                        <tr>
                            <td>#{{ str_pad($factura->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $factura->created_at->format('d/m/Y H:i') }}</td>
                            <td>Mesa {{ $factura->pedido->mesa->numero ?? 'N/A' }}</td>
                            <td>{{ $factura->cajero->name ?? 'N/A' }}</td>
                            <td>
                                {{ $factura->cliente_nombre ?? 'Consumidor Final' }}
                                @if($factura->cliente_documento)
                                    <br><small class="text-muted">{{ $factura->cliente_documento }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($factura->metodo_pago) }}</span>
                            </td>
                            <td>
                                @if($factura->estado == 'activa')
                                    <span class="badge bg-success">Pagada</span>
                                @else
                                    <span class="badge bg-danger">Anulada</span>
                                @endif
                            </td>
                            <td class="fw-bold text-success">Bs {{ number_format($factura->monto_pagado, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                                No se encontraron ventas con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($facturas->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $facturas->links() }}
        </div>
    @endif
</div>
@endsection
