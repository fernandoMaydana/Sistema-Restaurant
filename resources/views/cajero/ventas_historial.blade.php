@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- HEADER CON BOTÓN VOLVER Y TOTAL FILTRADO --}}
    <div class="row align-items-center mb-4 pb-2 border-bottom">
        <div class="col-md-7">
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('cajero.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Inicio
                </a>
            </div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-receipt me-2 text-primary"></i>
                Historial de Ventas
            </h2>
            <p class="text-muted small mb-0">Busca y consulta todas las ventas registradas con filtros por fecha, cliente o método de pago.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="bg-primary-subtle text-primary border border-primary-subtle px-4 py-3 rounded-4 d-inline-block shadow-sm">
                <span class="fs-6 fw-bold text-uppercase d-block mb-1">TOTAL VENTAS FILTRADAS:</span>
                <span class="fs-2 fw-black text-primary">Bs {{ number_format($totalFiltrado, 2) }}</span>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4">{{ session('success') }}</div>
    @endif

    {{-- CARD DE FILTROS DE BÚSQUEDA --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-dark text-white py-3 fw-bold rounded-top-4">
            <i class="bi bi-funnel me-2"></i>Filtros de Búsqueda
        </div>
        <div class="card-body p-4">
            <form action="{{ route('cajero.ventas.historial') }}" method="GET" class="row g-3">
                <div class="col-md-3 col-lg-2">
                    <label class="form-label fw-bold text-primary small mb-1">
                        <i class="bi bi-calendar-event me-1"></i>Día Específico
                    </label>
                    <input type="date" name="fecha_especifica" class="form-control border-primary" value="{{ request('fecha_especifica') }}">
                </div>

                <div class="col-md-3 col-lg-2">
                    <label class="form-label fw-bold small mb-1">Desde Fecha</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>

                <div class="col-md-3 col-lg-2">
                    <label class="form-label fw-bold small mb-1">Hasta Fecha</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>

                <div class="col-md-3 col-lg-3">
                    <label class="form-label fw-bold small mb-1">Cliente / NIT / CI</label>
                    <input type="text" name="cliente" class="form-control" placeholder="Nombre de cliente o documento" value="{{ request('cliente') }}">
                </div>

                <div class="col-md-3 col-lg-2">
                    <label class="form-label fw-bold small mb-1">Método de Pago</label>
                    <select name="metodo_pago" class="form-select">
                        <option value="">Todos los métodos</option>
                        <option value="efectivo" {{ request('metodo_pago') == 'efectivo' ? 'selected' : '' }}>💵 Efectivo</option>
                        <option value="tarjeta" {{ request('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>💳 Tarjeta</option>
                        <option value="qr" {{ request('metodo_pago') == 'qr' ? 'selected' : '' }}>📱 Pago QR</option>
                        <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>🏦 Transferencia</option>
                    </select>
                </div>

                <div class="col-md-3 col-lg-1">
                    <label class="form-label fw-bold small mb-1">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }}>Pagada</option>
                        <option value="anulada" {{ request('estado') == 'anulada' ? 'selected' : '' }}>Anulada</option>
                    </select>
                </div>

                <div class="col-12 text-end mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('cajero.ventas.historial') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                        <i class="bi bi-eraser me-1"></i> Limpiar Filtros
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold shadow-sm">
                        <i class="bi bi-search me-1"></i> Buscar Ventas
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLA DE RESULTADOS DE VENTAS --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <span class="fw-bold fs-5 text-dark"><i class="bi bi-list-stars me-2 text-primary"></i>Listado de Ventas</span>
            <span class="badge bg-secondary px-3 py-2 fs-6">Mostrando {{ $facturas->count() }} de {{ $facturas->total() }} registros</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">N° Venta</th>
                            <th>Fecha y Hora</th>
                            <th>Mesa</th>
                            <th>Cliente</th>
                            <th>Cajero</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($facturas as $factura)
                            <tr class="{{ $factura->estado === 'anulada' ? 'table-danger opacity-75' : '' }}">
                                <td class="ps-4 font-monospace fw-bold text-muted">
                                    #{{ str_pad($factura->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="fw-medium text-dark">
                                    {{ $factura->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    @if($factura->pedido?->mesa)
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-7">
                                            {{ $factura->pedido->mesa->es_para_llevar ? '🛍️ Llevar' : '🪑 Mesa ' . $factura->pedido->mesa->numero }}
                                        </span>
                                    @else
                                        <span class="text-muted small">Sin mesa</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark {{ $factura->estado === 'anulada' ? 'text-decoration-line-through text-muted' : '' }}">
                                            {{ $factura->cliente_nombre ?? 'Consumidor Final' }}
                                        </span>
                                        <small class="text-muted" style="font-size: 0.75rem;">CI/NIT: {{ $factura->cliente_nit_ci ?? '99001' }}</small>
                                    </div>
                                </td>
                                <td class="small fw-medium text-secondary">
                                    {{ $factura->cajero?->name ?? 'Sistema' }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary text-uppercase px-2 py-1 fs-7">
                                        {{ $factura->metodo_pago }}
                                    </span>
                                </td>
                                <td>
                                    @if($factura->estado === 'activa')
                                        <span class="badge bg-success px-3 py-1 fs-7"><i class="bi bi-check-circle me-1"></i>Pagada</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-1 fs-7"><i class="bi bi-x-circle me-1"></i>Anulada</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-black fs-5 {{ $factura->estado === 'activa' ? 'text-success' : 'text-decoration-line-through text-muted' }}">
                                        Bs {{ number_format($factura->monto_pagado, 2) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" onclick="verDetalleFactura({{ $factura->id }}, '{{ route('cajero.ventas.detalle', $factura->id, false) }}')" 
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" title="Ver Detalle de Consumo">
                                        <i class="bi bi-eye-fill me-1"></i>Detalle
                                    </button>

                                    @if($factura->estado === 'activa')
                                        <button type="button" onclick="imprimirFacturaDirecta(event, '{{ route('cajero.api.imprimir.factura', $factura->id, false) }}')" 
                                                class="btn btn-sm btn-outline-dark rounded-pill px-3" id="btn-imprimir-hist-{{ $factura->id }}" title="Imprimir Comprobante">
                                            <i class="bi bi-printer me-1"></i>Ticket
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
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-receipt-cutoff fs-1 d-block mb-3 opacity-50"></i>
                                    <h5 class="fw-bold">No se encontraron ventas</h5>
                                    <p class="small mb-0">Intenta modificar los filtros de búsqueda seleccionados arriba.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($facturas->hasPages())
            <div class="card-footer bg-light py-3 border-top">
                <div class="d-flex justify-content-center">
                    {{ $facturas->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

{{-- MODAL DETALLE DE FACTURA / VENTA --}}
<div class="modal fade" id="modalDetalleFactura" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Detalle de Venta #<span id="lbl-factura-id"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Info General --}}
                <div class="row g-3 mb-3 bg-light p-3 rounded-4 border" style="font-size: 0.9rem;">
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Mesa / Destino</small>
                        <span id="lbl-factura-mesa" class="fw-bold text-dark"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Fecha y Hora</small>
                        <span id="lbl-factura-fecha" class="fw-medium"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Cliente</small>
                        <span id="lbl-factura-cliente" class="fw-medium"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">CI / NIT</small>
                        <span id="lbl-factura-nit" class="fw-medium"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Método de Pago</small>
                        <span id="lbl-factura-metodo" class="badge bg-secondary"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Cajero Responsable</small>
                        <span id="lbl-factura-cajero" class="fw-medium"></span>
                    </div>
                </div>

                {{-- Tabla de Productos Consumidos --}}
                <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-list-ul me-1 text-primary"></i> Ítems Consumidos</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Cant.</th>
                                <th>Producto</th>
                                <th class="text-end">P. Unit</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-factura-detalles">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end text-uppercase fs-7" style="font-size: 0.75rem;">Total Venta:</th>
                                <th id="lbl-factura-total" class="text-end text-success fs-5 fw-bold"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary px-4 fw-semibold rounded-3" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function verDetalleFactura(facturaId, urlDetalle) {
        Swal.fire({
            title: 'Cargando detalle...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(urlDetalle)
            .then(r => r.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    document.getElementById('lbl-factura-id').textContent = data.factura.id;
                    document.getElementById('lbl-factura-mesa').textContent = 'Mesa ' + data.factura.mesa_numero;
                    document.getElementById('lbl-factura-fecha').textContent = data.factura.fecha;
                    document.getElementById('lbl-factura-cliente').textContent = data.factura.cliente_nombre;
                    document.getElementById('lbl-factura-nit').textContent = data.factura.cliente_nit_ci;
                    
                    const metodoBadge = document.getElementById('lbl-factura-metodo');
                    metodoBadge.textContent = data.factura.metodo_pago.toUpperCase();
                    
                    document.getElementById('lbl-factura-cajero').textContent = data.factura.cajero_nombre;
                    document.getElementById('lbl-factura-total').textContent = 'Bs ' + data.factura.monto_pagado.toFixed(2);

                    const tbody = document.getElementById('tbl-factura-detalles');
                    tbody.innerHTML = '';
                    data.detalles.forEach(d => {
                        let notasHtml = d.notas ? `<br><small class="text-warning">* Nota: ${d.notas}</small>` : '';
                        tbody.innerHTML += `
                            <tr>
                                <td>${d.cantidad}x</td>
                                <td>
                                    <strong>${d.producto_nombre}</strong>
                                    ${notasHtml}
                                </td>
                                <td class="text-end">Bs ${d.precio_unitario.toFixed(2)}</td>
                                <td class="text-end fw-bold">Bs ${d.subtotal.toFixed(2)}</td>
                            </tr>
                        `;
                    });

                    const modal = new bootstrap.Modal(document.getElementById('modalDetalleFactura'));
                    modal.show();
                } else {
                    Swal.fire('Error', 'No se pudo obtener el detalle de la venta.', 'error');
                }
            })
            .catch(e => {
                console.error("Error al obtener detalle de factura.", e);
                Swal.close();
                Swal.fire('Error', 'Hubo un error de red al intentar conectarse al servidor.', 'error');
            });
    }

    function imprimirFacturaDirecta(event, urlImpresion) {
        const btn = event.currentTarget;
        const originalHtml = btn.innerHTML;

        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>...';
        btn.disabled = true;

        fetch(urlImpresion, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) {
                btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Enviado';
                btn.classList.replace('btn-outline-dark', 'btn-success');
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.replace('btn-success', 'btn-outline-dark');
                    btn.disabled = false;
                }, 2000);
            } else {
                Swal.fire('Error de Impresión', data.message, 'error');
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        })
        .catch(e => {
            console.error(e);
            Swal.fire('Error de Red', 'No se pudo enviar la orden de impresión.', 'error');
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    }
</script>

<style>
.fw-black { font-weight: 900; }
.fs-7 { font-size: 0.8rem; }
</style>
@endsection
