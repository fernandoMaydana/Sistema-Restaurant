@extends('layouts.admin')

@section('title', 'Historial de Cajas')

@section('admin_content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-funnel me-1 text-primary"></i> Filtros de Búsqueda
    </div>
    <div class="card-body">
        <form action="{{ route('admin.cajas.index') }}" method="GET" class="row g-3">
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Desde</label>
                <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-primary"><i class="bi bi-calendar-event me-1"></i>Día Específico</label>
                <input type="date" name="fecha_especifica" class="form-control border-primary" value="{{ request('fecha_especifica') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Cajero</label>
                <select name="cajero_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($cajeros as $cajero)
                        <option value="{{ $cajero->id }}" {{ request('cajero_id') == $cajero->id ? 'selected' : '' }}>{{ $cajero->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="abierta" {{ request('estado') == 'abierta' ? 'selected' : '' }}>Abierta</option>
                    <option value="cerrada" {{ request('estado') == 'cerrada' ? 'selected' : '' }}>Cerrada</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Buscar</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Cajero</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th>Monto Inicial</th>
                        <th>Efectivo Final</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cajas as $caja)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $caja->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($caja->fecha_apertura)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($caja->fecha_cierre)
                                    {{ \Carbon\Carbon::parse($caja->fecha_cierre)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">En progreso</span>
                                @endif
                            </td>
                            <td>Bs {{ number_format($caja->monto_inicial, 2) }}</td>
                            <td class="fw-bold text-success">Bs {{ number_format($caja->monto_final ?? 0, 2) }}</td>
                            <td>
                                @if($caja->estado === 'abierta')
                                    <span class="badge bg-primary">Abierta</span>
                                @else
                                    <span class="badge bg-secondary">Cerrada</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" onclick="verDetalleCaja({{ $caja->id }}, '{{ route('admin.cajas.detalle', $caja->id, false) }}')" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" title="Ver Detalle en Pantalla">
                                    <i class="bi bi-eye-fill"></i> DETALLE
                                </button>
                                @if($caja->estado === 'abierta')
                                    <form action="{{ route('admin.cajas.cerrar_forzado', $caja->id) }}" method="POST" class="d-inline" onsubmit="return confirmarCierreForzado(event, this);">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 me-1" title="Cerrar esta caja de forma administrativa">
                                            <i class="bi bi-x-circle me-1"></i> Cerrar Caja
                                        </button>
                                    </form>
                                @endif
                                @if($caja->estado === 'cerrada')
                                    <a href="{{ route('admin.cajas.pdf', $caja->id) }}" class="btn btn-sm btn-danger text-white rounded-pill px-3 me-1" title="Descargar PDF">
                                        <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                    </a>
                                    <button type="button" onclick="imprimirCierreAntiguo(event, '{{ route('admin.cajas.imprimir', $caja->id) }}')" class="btn btn-sm btn-dark rounded-pill px-3" title="Imprimir Ticket">
                                        <i class="bi bi-printer-fill"></i> TICKET
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No hay registros de cajas todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($cajas->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $cajas->links() }}
    </div>
    @endif
</div>

<script>
    function imprimirCierreAntiguo(event, url) {
        event.preventDefault();
        const btn = event.currentTarget;
        const originalHtml = btn.innerHTML;
        
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
                btn.innerHTML = '<i class="bi bi-check"></i> OK';
                btn.classList.replace('btn-dark', 'btn-success');
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.replace('btn-success', 'btn-dark');
                    btn.disabled = false;
                }, 2000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Impresora',
                    text: data.message || 'Error al imprimir',
                    confirmButtonColor: '#e63946'
                });
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        })
        .catch(e => {
            console.error("Error de red.", e);
            Swal.fire({
                icon: 'error',
                title: 'Error de Red',
                text: 'Error de conexión al intentar imprimir.',
                confirmButtonColor: '#e63946'
            });
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    }

    function verDetalleCaja(cajaId, urlDetalle) {
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
                    document.getElementById('lbl-caja-id').textContent = data.caja.id;
                    document.getElementById('lbl-caja-cajero').textContent = data.caja.cajero_nombre;
                    document.getElementById('lbl-caja-apertura').textContent = data.caja.fecha_apertura;
                    document.getElementById('lbl-caja-cierre').textContent = data.caja.fecha_cierre;
                    
                    const statusBadge = document.getElementById('lbl-caja-estado');
                    statusBadge.textContent = data.caja.estado.toUpperCase();
                    if (data.caja.estado === 'abierta') {
                        statusBadge.className = 'badge bg-primary text-white';
                    } else {
                        statusBadge.className = 'badge bg-secondary text-white';
                    }

                    document.getElementById('lbl-caja-monto-inicial').textContent = 'Bs ' + data.caja.monto_inicial.toFixed(2);
                    document.getElementById('lbl-caja-monto-ventas').textContent = 'Bs ' + data.total_ventas.toFixed(2);
                    document.getElementById('lbl-caja-monto-gastos').textContent = 'Bs ' + data.total_gastos.toFixed(2);

                    document.getElementById('lbl-caja-pago-efectivo').textContent = 'Bs ' + data.ventas_por_metodo.efectivo.toFixed(2);
                    document.getElementById('lbl-caja-pago-qr').textContent = 'Bs ' + data.ventas_por_metodo.qr.toFixed(2);
                    document.getElementById('lbl-caja-pago-tarjeta').textContent = 'Bs ' + data.ventas_por_metodo.tarjeta.toFixed(2);
                    document.getElementById('lbl-caja-pago-transferencia').textContent = 'Bs ' + data.ventas_por_metodo.transferencia.toFixed(2);

                    document.getElementById('lbl-caja-monto-final').textContent = 'Bs ' + data.caja.monto_final.toFixed(2);

                    // Gastos Table
                    const tbodyGastos = document.getElementById('tbl-caja-gastos');
                    tbodyGastos.innerHTML = '';
                    if (data.gastos.length > 0) {
                        data.gastos.forEach(g => {
                            tbodyGastos.innerHTML += `
                                <tr>
                                    <td class="ps-3">
                                        <strong>${g.descripcion}</strong><br>
                                        <small class="text-muted">${g.hora}</small>
                                    </td>
                                    <td class="text-end text-danger fw-bold pe-3">-Bs ${g.monto.toFixed(2)}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tbodyGastos.innerHTML = '<tr><td colspan="2" class="text-center py-3 text-muted">No se registraron gastos.</td></tr>';
                    }

                    // Products Table
                    const tbodyProds = document.getElementById('tbl-caja-productos');
                    tbodyProds.innerHTML = '';
                    if (data.resumen_productos.length > 0) {
                        data.resumen_productos.forEach(p => {
                            tbodyProds.innerHTML += `
                                <tr>
                                    <td class="ps-3">
                                        <strong>${p.nombre}</strong><br>
                                        <small class="badge bg-light text-dark border">${p.categoria}</small>
                                    </td>
                                    <td class="text-end fw-bold pe-3">x${p.cantidad}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tbodyProds.innerHTML = '<tr><td colspan="2" class="text-center py-3 text-muted">No se registraron ventas de productos.</td></tr>';
                    }

                    const modal = new bootstrap.Modal(document.getElementById('modalDetalleCaja'));
                    modal.show();
                } else {
                    Swal.fire('Error', data.message || 'No se pudo obtener el detalle de la caja.', 'error');
                }
            })
            .catch(e => {
                console.error("Error al obtener detalle de caja.", e);
                Swal.close();
                Swal.fire('Error', 'Hubo un error de red al intentar conectarse al servidor.', 'error');
            });
    }
</script>

{{-- Modal Detalle de Caja --}}
<div class="modal fade" id="modalDetalleCaja" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-safe2 me-2"></i>Detalle de Sesión de Caja #<span id="lbl-caja-id"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Info General -->
                <div class="row mb-4">
                    <div class="col-md-3 col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Cajero</small>
                        <span id="lbl-caja-cajero" class="fw-bold"></span>
                    </div>
                    <div class="col-md-3 col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Apertura</small>
                        <span id="lbl-caja-apertura" class="small"></span>
                    </div>
                    <div class="col-md-3 col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Cierre</small>
                        <span id="lbl-caja-cierre" class="small"></span>
                    </div>
                    <div class="col-md-3 col-6">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Estado</small>
                        <span id="lbl-caja-estado" class="badge"></span>
                    </div>
                </div>

                <hr class="opacity-10">

                <!-- Totales Financieros -->
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-cash-stack me-2 text-success"></i>Resumen Financiero</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded border border-light-subtle">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem;">Monto Inicial</small>
                            <h5 id="lbl-caja-monto-inicial" class="fw-bold mb-0 mt-1"></h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded border border-light-subtle">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem;">Ventas Totales</small>
                            <h5 id="lbl-caja-monto-ventas" class="fw-bold text-success mb-0 mt-1"></h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded border border-light-subtle">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem;">Gastos Totales</small>
                            <h5 id="lbl-caja-monto-gastos" class="fw-bold text-danger mb-0 mt-1"></h5>
                        </div>
                    </div>
                </div>

                <!-- Métodos de Pago y Efectivo Final -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border border-light-subtle">
                            <div class="card-header bg-white py-2 fw-bold" style="font-size: 0.85rem;">
                                Ventas por Método de Pago
                            </div>
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted small">Efectivo:</span>
                                    <span id="lbl-caja-pago-efectivo" class="fw-medium small"></span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted small">QR:</span>
                                    <span id="lbl-caja-pago-qr" class="fw-medium small"></span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted small">Tarjeta:</span>
                                    <span id="lbl-caja-pago-tarjeta" class="fw-medium small"></span>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Transferencia:</span>
                                    <span id="lbl-caja-pago-transferencia" class="fw-medium small"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success-subtle border-0 h-100 d-flex flex-column justify-content-center align-items-center p-3 text-center">
                            <small class="text-success-emphasis text-uppercase fw-bold" style="font-size: 0.75rem;">Efectivo Final Estimado</small>
                            <h3 id="lbl-caja-monto-final" class="fw-bold text-success-emphasis mb-0 mt-2"></h3>
                            <small class="text-muted mt-1" style="font-size: 0.7rem;">(Inicial + Ventas Efectivo - Gastos)</small>
                        </div>
                    </div>
                </div>

                <!-- Detalle de Gastos y Productos -->
                <div class="row">
                    <!-- Tabla Gastos -->
                    <div class="col-md-6 mb-3">
                        <div class="card border border-light-subtle h-100">
                            <div class="card-header bg-white py-2 fw-bold" style="font-size: 0.85rem;">
                                <i class="bi bi-wallet2 text-danger me-1"></i> Gastos del Turno
                            </div>
                            <div class="card-body p-0 overflow-auto" style="max-height: 200px;">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">Detalle</th>
                                            <th class="text-end pe-3">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl-caja-gastos">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla Productos Vendidos -->
                    <div class="col-md-6 mb-3">
                        <div class="card border border-light-subtle h-100">
                            <div class="card-header bg-white py-2 fw-bold" style="font-size: 0.85rem;">
                                <i class="bi bi-box-seam text-primary me-1"></i> Resumen de Ventas (Productos)
                            </div>
                            <div class="card-body p-0 overflow-auto" style="max-height: 200px;">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">Producto</th>
                                            <th class="text-end pe-3">Cant.</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl-caja-productos">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmarCierreForzado(event, form) {
        event.preventDefault();
        Swal.fire({
            title: '¿Cerrar esta sesión de caja?',
            html: '<p class="text-secondary mb-0">Se calcularán automáticamente las ventas acumuladas para pasar la sesión a <strong>CERRADA</strong> y habilitar la descarga de su PDF y Ticket.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-x-circle-fill me-1"></i> Sí, Cerrar Caja',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'rounded-4 border-0 shadow-lg',
                confirmButton: 'rounded-pill px-4 py-2 fw-bold',
                cancelButton: 'rounded-pill px-4 py-2 fw-semibold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }
</script>
@endsection
