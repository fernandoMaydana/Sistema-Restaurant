@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center py-3 border-bottom mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-cash-register me-2 text-primary"></i>
                Panel de Caja
            </h1>
            <span class="text-muted">Cajero: <strong>{{ Auth::user()->name }}</strong></span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('cajero.salon') }}" class="btn btn-primary fw-bold px-4 py-2 shadow-sm rounded-3">
                <i class="bi bi-grid-3x3-gap me-2"></i>VER SALÓN
            </a>
            @if($caja)
                <a href="{{ route('cajero.cierre') }}" class="btn btn-outline-danger fw-bold px-4 py-2 shadow-sm rounded-3">
                    <i class="bi bi-door-closed me-2"></i>CERRAR CAJA
                </a>
            @else
                <a href="{{ route('cajero.bienvenida') }}" class="btn btn-success fw-bold px-4 py-2 shadow-sm rounded-3">
                    <i class="bi bi-cash-register me-2"></i>ABRIR CAJA
                </a>
            @endif
        </div>
    </div>

    {{-- Resumen de Hoy --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold opacity-75" style="font-size: 0.7rem;">Generado hoy</h6>
                    <h2 class="fw-bold mb-0">Bs {{ number_format($totalVentasHoy, 2) }}</h2>
                    <small class="opacity-75">Total de ventas cobradas</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold opacity-75" style="font-size: 0.7rem;">Ventas realizadas</h6>
                    <h2 class="fw-bold mb-0">{{ $facturasHoy->count() }}</h2>
                    <small class="opacity-75">Facturas emitidas hoy</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold opacity-75" style="font-size: 0.7rem;">Gastos del turno</h6>
                    <h2 class="fw-bold mb-0">Bs {{ number_format($totalGastosHoy, 2) }}</h2>
                    <small class="opacity-75">Dinero retirado de caja</small>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-4">
             <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-wallet2 me-2"></i>Gastos del Turno</h5>
                    @if($caja)
                        <button class="btn btn-danger btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalGasto">
                            <i class="bi bi-plus-circle me-1"></i>Registrar Gasto
                        </button>
                    @else
                        <button class="btn btn-secondary btn-sm rounded-pill px-3" disabled title="Debe abrir caja primero para registrar gastos">
                            <i class="bi bi-plus-circle me-1"></i>Registrar Gasto
                        </button>
                    @endif
                </div>
                <div class="card-body p-0 overflow-auto" style="max-height: 300px;">
                    <ul class="list-group list-group-flush">
                        @forelse($gastosHoy as $gasto)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-3">
                                <div>
                                    <span class="fw-bold text-dark">{{ $gasto->descripcion }}</span><br>
                                    <small class="text-muted">{{ $gasto->created_at->format('H:i') }}</small>
                                </div>
                                <span class="text-danger fw-bold">-Bs {{ number_format($gasto->monto, 2) }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center py-5 text-muted">No hay gastos registrados en este turno.</li>
                        @endforelse
                    </ul>
                </div>
             </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         SECCIÓN 3: HISTORIAL DE VENTAS HOY
    ══════════════════════════════════════ --}}
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2"></i>Historial de Ventas - Hoy</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Hora</th>
                                    <th>Mesa</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Método</th>
                                    <th class="text-end pe-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($facturasHoy as $factura)
                                    <tr class="{{ $factura->estado === 'anulada' ? 'table-danger opacity-75' : '' }}">
                                        <td class="ps-3 text-muted">{{ $factura->created_at->format('H:i') }}</td>
                                        <td class="fw-bold">Mesa {{ $factura->pedido->mesa->numero ?? 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark {{ $factura->estado === 'anulada' ? 'text-decoration-line-through text-muted' : '' }}">{{ $factura->cliente_nombre ?? 'Consumidor Final' }}</span>
                                                <small class="text-muted" style="font-size: 0.7rem;">CI/NIT: {{ $factura->cliente_nit_ci ?? 'S/N' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($factura->estado === 'anulada')
                                                <span class="badge bg-danger py-2 px-3 fs-6 text-decoration-line-through">Bs {{ number_format($factura->monto_pagado, 2) }}</span>
                                                <span class="badge bg-dark py-1 px-2 ms-1 text-uppercase" style="font-size: 0.7rem;">ANULADA</span>
                                            @else
                                                <span class="badge bg-success py-2 px-3 fs-6">Bs {{ number_format($factura->monto_pagado, 2) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary text-uppercase">{{ $factura->metodo_pago }}</span>
                                        </td>
                                        <td class="text-end pe-3">
                                            @if($factura->estado === 'activa')
                                                <form action="{{ route('cajero.factura.anular', $factura->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas ANULAR esta venta? Se descontará de la caja y reportes de hoy.')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 me-1">
                                                        <i class="bi bi-x-circle me-1"></i>Anular Venta
                                                    </button>
                                                </form>
                                                <button type="button" onclick="imprimirFacturaDirecta(event, '{{ route('cajero.api.imprimir.factura', $factura->id) }}')" class="btn btn-sm btn-outline-dark rounded-pill px-3" id="btn-imprimir-hist-{{ $factura->id }}">
                                                    <i class="bi bi-printer me-1"></i>Imprimir Ticket
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" disabled>
                                                    <i class="bi bi-x-circle-fill me-1"></i>Anulada
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Aún no se han registrado ventas hoy.</td>
                                    </tr>
                                @endforelse
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function imprimirFacturaDirecta(event, url) {
        event.preventDefault();
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        btn.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) {
                btn.innerHTML = '<i class="bi bi-check-circle"></i>';
                btn.classList.replace('btn-outline-dark', 'btn-success');
                btn.classList.add('text-white');
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.replace('btn-success', 'btn-outline-dark');
                    btn.classList.remove('text-white');
                    btn.disabled = false;
                }, 2000);
            } else {
                alert("Error de Impresora:\n" + data.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(e => {
            console.error("Error de red.", e);
            alert("Error de conexión al intentar imprimir.");
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>

{{-- Modal Registrar Gasto --}}
<div class="modal fade" id="modalGasto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">Registrar Gasto de Caja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('cajero.gasto.registrar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Monto del Gasto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">$</span>
                            <input type="number" step="0.01" min="0.01" name="monto" class="form-control form-control-lg" required placeholder="0.00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción / Motivo</label>
                        <input type="text" name="descripcion" class="form-control" required placeholder="Ej: Pago a proveedor de pan, compra de hielo...">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4 fw-bold">Guardar Gasto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection