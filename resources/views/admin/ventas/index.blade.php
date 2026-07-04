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
            <div class="col-md-2">
                <label class="form-label small fw-bold">Desde</label>
                <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-primary"><i class="bi bi-calendar-event me-1"></i>Día Específico</label>
                <input type="date" name="fecha_especifica" class="form-control border-primary" value="{{ request('fecha_especifica') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Cajero</label>
                <select name="cajero_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($cajeros as $cajero)
                        <option value="{{ $cajero->id }}" {{ request('cajero_id') == $cajero->id ? 'selected' : '' }}>{{ $cajero->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Método</label>
                <select name="metodo_pago" class="form-select">
                    <option value="">Todos</option>
                    <option value="efectivo" {{ request('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="tarjeta" {{ request('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                    <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    <option value="qr" {{ request('metodo_pago') == 'qr' ? 'selected' : '' }}>QR</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Buscar</button>
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
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas as $factura)
                        <tr class="{{ $factura->estado === 'anulada' ? 'table-danger opacity-75' : '' }}">
                            <td>#{{ str_pad($factura->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $factura->created_at->format('d/m/Y H:i') }}</td>
                            <td>Mesa {{ $factura->pedido?->mesa?->numero ?? 'N/A' }}</td>
                            <td>{{ $factura->cajero?->name ?? 'N/A' }}</td>
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
                            <td class="fw-bold {{ $factura->estado === 'activa' ? 'text-success' : 'text-decoration-line-through text-muted' }}">Bs {{ number_format($factura->monto_pagado, 2) }}</td>
                            <td class="text-end pe-4">
                                <button type="button" onclick="verDetalleFactura({{ $factura->id }}, '{{ route('admin.ventas.detalle', $factura->id, false) }}')" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Ver Detalle">
                                    <i class="bi bi-eye-fill"></i> Detalle
                                </button>
                            </td>
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
</script>

{{-- Modal Detalle de Factura --}}
<div class="modal fade" id="modalDetalleFactura" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2"></i>Detalle de Venta #<span id="lbl-factura-id"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Info General -->
                <div class="row g-2 mb-3" style="font-size: 0.9rem;">
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Mesa</small>
                        <span id="lbl-factura-mesa" class="fw-bold text-dark"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Fecha y Hora</small>
                        <span id="lbl-factura-fecha"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Cliente</small>
                        <span id="lbl-factura-cliente" class="fw-medium"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">CI/NIT</small>
                        <span id="lbl-factura-nit"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Método de Pago</small>
                        <span id="lbl-factura-metodo" class="badge bg-secondary"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Cajero</small>
                        <span id="lbl-factura-cajero"></span>
                    </div>
                </div>

                <hr class="opacity-10">

                <!-- Tabla de Productos Consumidos -->
                <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-list-ul me-1 text-primary"></i> Detalle de Consumo</h6>
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
                                <th colspan="3" class="text-end text-uppercase" style="font-size: 0.75rem;">Total Cobrado:</th>
                                <th id="lbl-factura-total" class="text-end text-success fs-5 fw-bold"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection
