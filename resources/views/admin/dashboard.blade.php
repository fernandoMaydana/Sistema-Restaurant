@extends('layouts.admin')

@section('title', 'Dashboard')

@section('admin_content')
<div class="row mb-4">
    <div class="col-md-8 mb-3 mb-md-0">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex align-items-center gap-2 bg-white p-2 rounded shadow-sm">
            <label class="fw-bold ms-2 text-muted text-nowrap">Ver datos del:</label>
            <input type="date" name="fecha" class="form-control" value="{{ $fecha }}">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filtrar</button>
        </form>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success h-100 shadow-sm">
            <div class="card-body py-2 px-3 d-flex flex-column justify-content-center">
                <div class="text-white-50 small fw-bold text-uppercase mb-1">Ventas ({{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }})</div>
                <h3 class="mb-0 fw-bold">Bs {{ number_format($totalVentas, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold">
        Mesas Pagadas el {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Hora</th>
                        <th>Mesa</th>
                        <th>Cajero</th>
                        <th>Cliente</th>
                        <th>Total Pagado</th>
                        <th>Método</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturasHoy as $factura)
                        <tr>
                            <td>{{ $factura->created_at->format('H:i') }}</td>
                            <td>Mesa {{ $factura->pedido->mesa->numero ?? 'N/A' }}</td>
                            <td>{{ $factura->cajero->name ?? 'N/A' }}</td>
                            <td>{{ $factura->cliente_nombre ?? 'Consumidor Final' }}</td>
                            <td class="fw-bold">Bs {{ number_format($factura->monto_pagado, 2) }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($factura->metodo_pago) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-3 text-muted">No se han registrado pagos el día de hoy.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
