@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0" style="border-radius: 1.5rem;">
                <div class="card-body p-5 text-center">
                    <div class="mb-4 text-danger" style="font-size: 4rem;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Cierre de Caja</h2>
                    <p class="text-muted mb-4">Estás a punto de cerrar la sesión de caja del día de hoy. Una vez cerrada, no podrás registrar más ventas en esta sesión.</p>
                    
                    <div class="bg-light p-4 rounded-4 mb-4 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Monto Inicial:</span>
                            <span class="fw-bold fs-5">Bs {{ number_format($caja->monto_inicial, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ventas del Turno (+):</span>
                            <span class="fw-bold fs-5 text-success">Bs {{ number_format($totalVentas, 2) }}</span>
                        </div>
                        
                        {{-- Desglose por método de pago --}}
                        <div class="ps-3 border-start border-2 border-success-subtle mb-3">
                            <div class="d-flex justify-content-between mb-1 small text-muted">
                                <span>💵 Efectivo:</span>
                                <span class="fw-semibold">Bs {{ number_format($ventasPorMetodo['efectivo'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 small text-muted">
                                <span>📱 Pago QR:</span>
                                <span class="fw-semibold">Bs {{ number_format($ventasPorMetodo['qr'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 small text-muted">
                                <span>💳 Tarjeta:</span>
                                <span class="fw-semibold">Bs {{ number_format($ventasPorMetodo['tarjeta'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 small text-muted">
                                <span>🏦 Transferencia:</span>
                                <span class="fw-semibold">Bs {{ number_format($ventasPorMetodo['transferencia'], 2) }}</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Gastos Registrados (-):</span>
                            <span class="fw-bold fs-5 text-danger">-Bs {{ number_format($totalGastos, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold h5">Balance Total:</span>
                            <span class="fw-bold h5 text-primary">Bs {{ number_format(($caja->monto_inicial + $totalVentas) - $totalGastos, 2) }}</span>
                        </div>
                        <p class="text-muted mt-3 mb-0" style="font-size: 0.8rem; line-height: 1.3;">
                            <i class="bi bi-info-circle me-1"></i> El reporte de cierre detallará esta información y registrará el saldo final de caja.
                        </p>
                    </div>

                    <form action="{{ route('cajero.cierre.confirmar') }}" method="POST">
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg fw-bold py-3 rounded-3 shadow-sm">
                                <i class="bi bi-check-circle me-2"></i>CONFIRMAR CIERRE Y VER REPORTE
                            </button>
                            <a href="{{ route('cajero.dashboard') }}" class="btn btn-link text-secondary text-decoration-none py-2">
                                Regresar al panel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
