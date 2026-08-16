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

    {{-- Resumen de Hoy: Cobrado vs Estimado en Mesas --}}
    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 mb-4">
        {{-- Card 1: Cobrado Real en Caja --}}
        <div class="col">
            <div class="card border-0 shadow-sm bg-success text-white rounded-4 h-100">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase fw-bold opacity-75" style="font-size: 0.7rem; letter-spacing: 0.5px;">Dinero en Caja (Cobrado)</span>
                        <i class="bi bi-cash-coin fs-4 opacity-75"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">Bs {{ number_format($totalVentasHoy, 2) }}</h2>
                        <small class="opacity-75" style="font-size: 0.78rem;">
                            <i class="bi bi-check-all me-1"></i>{{ $facturasHoy->count() }} ventas cobradas
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Estimado Por Cobrar en Mesas --}}
        <div class="col">
            <div class="card border-0 shadow-sm text-dark rounded-4 h-100" style="background-color: #fff3cd; border-left: 5px solid #ffc107 !important;">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase fw-bold text-warning-emphasis" style="font-size: 0.7rem; letter-spacing: 0.5px;">Por Cobrar (En Mesas)</span>
                        <i class="bi bi-clock-history fs-4 text-warning-emphasis"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0 text-dark">Bs {{ number_format($totalEstimadoMesasHoy, 2) }}</h2>
                        <small class="text-muted" style="font-size: 0.78rem;">
                            <i class="bi bi-grid-3x3-gap-fill me-1 text-warning"></i>{{ $cantMesasActivas }} mesas/pedidos activos
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Total Proyectado --}}
        <div class="col">
            <div class="card border-0 shadow-sm bg-primary text-white rounded-4 h-100">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase fw-bold opacity-75" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total Proyectado Hoy</span>
                        <i class="bi bi-graph-up-arrow fs-4 opacity-75"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">Bs {{ number_format($totalVentasHoy + $totalEstimadoMesasHoy, 2) }}</h2>
                        <small class="opacity-75" style="font-size: 0.78rem;">Cobrado + Consumo en Mesas</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Gastos del Turno --}}
        <div class="col">
            <div class="card border-0 shadow-sm bg-danger text-white rounded-4 h-100">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase fw-bold opacity-75" style="font-size: 0.7rem; letter-spacing: 0.5px;">Gastos del Turno</span>
                        <i class="bi bi-box-arrow-right fs-4 opacity-75"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">Bs {{ number_format($totalGastosHoy, 2) }}</h2>
                        <small class="opacity-75" style="font-size: 0.78rem;">Dinero retirado de caja</small>
                    </div>
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

    {{-- WIDGET DE ESTADO DE LA IMPRESORA --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4" id="printer-status-widget" style="background-color: #f8f9fa;">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white p-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="bi bi-printer text-muted fs-4" id="printer-icon"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Estado de Ticketera Fïsica</h6>
                    <span id="printer-status-text" class="text-muted small">
                        <span class="spinner-border spinner-border-sm text-secondary me-1" role="status"></span>
                        Verificando conexión...
                    </span>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold" onclick="verificarTicketera()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Recomprobar
                </button>
            </div>
        </div>
    </div>

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
        <div class="col-md-6 mb-4">
             <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-people-fill me-2 text-warning"></i>Consumo del Personal</h5>
                    @if($caja)
                        <button class="btn btn-warning btn-sm text-white fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalConsumo">
                            <i class="bi bi-plus-circle me-1"></i>Registrar Consumo
                        </button>
                    @else
                        <button class="btn btn-secondary btn-sm rounded-pill px-3" disabled title="Debe abrir caja primero para registrar consumos de personal">
                            <i class="bi bi-plus-circle me-1"></i>Registrar Consumo
                        </button>
                    @endif
                </div>
                <div class="card-body p-0 overflow-auto" style="max-height: 300px;">
                    <ul class="list-group list-group-flush">
                        @forelse($consumosHoy as $consumo)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-3">
                                <div>
                                    <span class="fw-bold text-dark">{{ $consumo->producto->nombre ?? 'Producto Eliminado' }}</span>
                                    <span class="badge bg-light text-dark border ms-1">x{{ $consumo->cantidad }}</span>
                                    @if($consumo->descripcion)
                                        <br><small class="text-muted">{{ $consumo->descripcion }}</small>
                                    @endif
                                    <br><small class="text-muted" style="font-size: 0.7rem;">{{ $consumo->created_at->format('H:i') }}</small>
                                </div>
                                <span class="text-warning fw-bold">-{{ $consumo->cantidad }} ud.</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center py-5 text-muted">No hay consumos de personal registrados hoy.</li>
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
                                        <td class="fw-bold">Mesa {{ $factura->pedido?->mesa?->numero ?? 'N/A' }}</td>
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
                                            <button type="button" onclick="verDetalleFactura({{ $factura->id }}, '{{ route('cajero.ventas.detalle', $factura->id, false) }}')" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" title="Ver Detalle">
                                                <i class="bi bi-eye-fill me-1"></i>Detalle
                                            </button>
                                            @if($factura->estado === 'activa')
                                                <button type="button" onclick="imprimirFacturaDirecta(event, '{{ route('cajero.api.imprimir.factura', $factura->id, false) }}')" class="btn btn-sm btn-outline-dark rounded-pill px-3" id="btn-imprimir-hist-{{ $factura->id }}">
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
    function verificarTicketera() {
        const textSpan = document.getElementById('printer-status-text');
        const icon = document.getElementById('printer-icon');
        
        textSpan.innerHTML = '<span class="spinner-border spinner-border-sm text-secondary me-1" role="status"></span>Verificando conexión...';
        icon.className = 'bi bi-printer text-muted fs-4';
        
        fetch('{{ route('cajero.api.check-printer', [], false) }}')
        .then(r => r.json())
        .then(data => {
            if (data.connected) {
                textSpan.innerHTML = `<span class="text-success fw-bold">🟢 Conectada</span> (Impresora: ${data.printer_name})`;
                icon.className = 'bi bi-printer-fill text-success fs-4';
            } else {
                textSpan.innerHTML = `<span class="text-danger fw-bold">🔴 Error de Conexión</span> (Impresora: ${data.printer_name}). Asegúrate de que esté encendida, conectada y compartida.`;
                icon.className = 'bi bi-printer-fill text-danger fs-4';
            }
        })
        .catch(e => {
            textSpan.innerHTML = '<span class="text-warning fw-bold">⚠️ No se pudo comprobar</span> (Error de red)';
            icon.className = 'bi bi-printer text-warning fs-4';
        });
    }

    // Comprobar al cargar
    document.addEventListener('DOMContentLoaded', verificarTicketera);

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
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Impresora',
                    text: data.message,
                    confirmButtonColor: '#e63946'
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(e => {
            console.error("Error de red.", e);
            Swal.fire({
                icon: 'error',
                title: 'Error de Conexión',
                text: 'Hubo un error de red al intentar comunicarse con el servidor.',
                confirmButtonColor: '#e63946'
            });
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    // Configuración para el modal de consumo
    document.addEventListener('DOMContentLoaded', function () {
        const selectProd = document.getElementById('consumo_producto_id');
        const inputCant = document.getElementById('dashboardConsumoCantidad');
        const stockLabel = document.getElementById('stock_disponible_label');

        if (selectProd) {
            selectProd.addEventListener('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const stock = parseInt(selectedOpt.getAttribute('data-stock')) || 0;
                
                inputCant.max = stock;
                stockLabel.textContent = 'Stock disponible: ' + stock + ' ud.';
                stockLabel.classList.remove('d-none');
                
                if (parseInt(inputCant.value) > stock) {
                    inputCant.value = stock;
                }
            });
        }
    });

    window.ajustarDashboardCantidad = function(delta) {
        const input = document.getElementById('dashboardConsumoCantidad');
        const val   = parseInt(input.value) || 1;
        const max   = parseInt(input.max)   || 9999;
        const nuevo = Math.max(1, Math.min(max, val + delta));
        input.value = nuevo;
    };
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
                            <span class="input-group-text bg-light">Bs</span>
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

{{-- Modal Registrar Consumo Personal --}}
<div class="modal fade" id="modalConsumo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title fw-bold">Registrar Consumo del Personal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('cajero.consumo_personal.registrar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Producto consumido</label>
                        <select name="producto_id" id="consumo_producto_id" class="form-select" required>
                            <option value="" disabled selected>Seleccione un producto...</option>
                            @foreach($productosInventario as $prod)
                                <option value="{{ $prod->id }}" data-stock="{{ $prod->stock }}">{{ $prod->nombre }} (Stock: {{ $prod->stock }} ud.)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Cantidad a descontar</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="input-group" style="max-width: 180px;">
                                <button type="button" class="btn btn-outline-secondary" onclick="ajustarDashboardCantidad(-1)">
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                                <input type="number"
                                       id="dashboardConsumoCantidad"
                                       name="cantidad"
                                       class="form-control text-center fw-bold fs-5"
                                       value="1" min="1" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="ajustarDashboardCantidad(1)">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                            <span id="stock_disponible_label" class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 fs-6 rounded-pill fw-bold d-none"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Motivo / Personal <span class="text-muted fw-normal">(opcional)</span></label>
                        <input type="text" name="descripcion" id="dashboardConsumoDescripcion" class="form-control" placeholder="Ej: Turno noche - Juan P., compra interna...">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-white px-4 fw-bold">Guardar Consumo</button>
                </div>
            </form>
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