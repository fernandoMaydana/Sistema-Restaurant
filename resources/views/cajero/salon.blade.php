@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-3">

    <div class="row g-3">
        {{-- COLUMNA IZQUIERDA: GRILLA DE MESAS --}}
        <div class="col-lg-8 col-xl-9">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-3 px-3 pb-3">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="d-flex align-items-center gap-3 ps-1">
                            <h3 class="fw-bold mb-0 text-dark">🪑 Salón de Mesas</h3>
                            <span class="text-muted small"><i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}</span>
                        </div>
                        <div class="d-flex gap-2 ms-auto">
                            <a href="{{ route('cajero.pedido.llevar.crear') }}" class="btn btn-warning fw-bold text-white px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2 fs-6">
                                <i class="bi bi-bag-plus-fill fs-5"></i>
                                <span>🛍️ Pedido Para Llevar</span>
                            </a>
                            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2 fs-6" onclick="abrirModalReservas()">
                                <i class="bi bi-calendar-event-fill fs-5"></i>
                                <span>📅 Reservas</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4" id="salon-grid-container">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Sección superior: Pedidos Para Llevar Activos --}}
                    @php
                        $pedidosLlevarActivos = $mesas->where('es_para_llevar', true)->filter(fn($m) => $m->pedidos->isNotEmpty());
                    @endphp

                    @if($pedidosLlevarActivos->isNotEmpty())
                        <div class="mb-4 bg-light p-3 rounded-4 border-0">
                            <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                                <span class="fs-4">🛍️</span> Pedidos Para Llevar Activos
                                <span class="badge bg-warning text-dark rounded-pill">{{ $pedidosLlevarActivos->count() }}</span>
                            </h5>
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                                @foreach($pedidosLlevarActivos as $mesaLlevar)
                                    @php $pedidoLlevar = $mesaLlevar->pedidos->first(); @endphp
                                    <div class="col">
                                        <div class="card h-100 border-0 shadow-sm mesa-card cursor-pointer"
                                             onclick="mostrarDetalle('{{ $mesaLlevar->id }}')"
                                             style="border-radius: 14px; background-color: #fff9f2; border-left: 5px solid #ffb703 !important; transition: all 0.2s;">
                                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">LLEVAR #{{ $mesaLlevar->numero }}</h6>
                                                        <small class="text-muted" style="font-size: 0.75rem;">
                                                             <i class="bi bi-clock-history me-1"></i>Hace {{ $pedidoLlevar->created_at->diff(now())->format('%H:%I') }} h
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1 rounded-pill" style="font-size: 0.65rem;">Por Cobrar</span>
                                                </div>
                                                
                                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                                    <span class="text-muted small" style="font-size: 0.75rem;"><i class="bi bi-person-fill"></i> {{ $pedidoLlevar->mesero->name }}</span>
                                                    <span class="text-success fw-bold fs-6">Bs {{ number_format($pedidoLlevar->total, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <hr class="my-4 opacity-10">
                    @endif

                    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
                        @forelse($mesas->where('es_para_llevar', false) as $mesa)
                            @php
                                $pedidoActivo = $mesa->pedidos->first();
                                $libre = !$pedidoActivo;
                                $reservaHoy = $reservas->where('mesa_id', $mesa->id)->where('estado', 'pendiente')->first();
                            @endphp

                            <div class="col">
                                <div class="card h-100 border-0 text-center shadow-sm mesa-card" 
                                     onclick="mostrarDetalle('{{ $mesa->id }}')"
                                     style="cursor: pointer; border-radius: 12px; overflow: hidden; transition: all 0.2s; background-color: {{ $libre ? '#f8fff9' : '#fff9f9' }};">
                                     
                                    <div style="height: 5px; width: 100%; background: {{ $libre ? '#2ec4b6' : '#e63946' }};"></div>

                                    <div class="card-body p-3 d-flex flex-column align-items-center">
                                        <h4 class="fw-bold mb-1 text-dark">
                                            @if($mesa->es_para_llevar)
                                                🛍️ LLEVAR {{ $mesa->numero }}
                                            @else
                                                MESA {{ $mesa->numero }}
                                            @endif
                                        </h4>

                                        @if($reservaHoy)
                                            <div class="my-1">
                                                <span class="badge bg-primary text-white px-2 py-1 rounded-pill" style="font-size: 0.65rem;" title="Reservada para {{ $reservaHoy->cliente_nombre }}">
                                                    <i class="bi bi-calendar-check-fill me-1"></i>Res. {{ \Carbon\Carbon::parse($reservaHoy->hora)->format('H:i') }}
                                                </span>
                                            </div>
                                        @endif
                                        
                                        @if(!$libre)
                                            {{-- Información Operativa --}}
                                            <div class="mt-2 mb-1">
                                                <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.7rem;">
                                                    <i class="bi bi-clock me-1"></i>Hace {{ $pedidoActivo->created_at->diff(now())->format('%H:%I') }} h
                                                </span>
                                            </div>
                                            
                                            <div class="text-muted small mb-2" style="font-size: 0.8rem;">
                                                <i class="bi bi-person-fill"></i> {{ $pedidoActivo->mesero->name }}
                                            </div>

                                            <div class="text-success fw-bold h3 mb-3">
                                                Bs {{ number_format($pedidoActivo->total, 2) }}
                                            </div>

                                            {{-- Botones de impresión rápida --}}
                                            <div class="d-flex gap-2 mt-auto w-100 justify-content-center">
                                                <button type="button" 
                                                   class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center shadow-sm" 
                                                   style="width: 40px; height: 40px; border-radius: 10px;"
                                                   onclick="imprimirDirecto(event, '{{ route('cajero.api.imprimir.comanda', $pedidoActivo->id) }}')"
                                                   title="Imprimir Comanda (Cocina)">
                                                    <i class="bi bi-printer-fill"></i>
                                                </button>
                                                <button type="button"
                                                   class="btn btn-sm btn-outline-dark d-flex align-items-center justify-content-center shadow-sm" 
                                                   style="width: 40px; height: 40px; border-radius: 10px;"
                                                   onclick="imprimirDirecto(event, '{{ route('cajero.api.imprimir.cuenta', $pedidoActivo->id) }}')"
                                                   title="Imprimir Pre-cuenta">
                                                    <i class="bi bi-receipt"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="py-4 text-muted opacity-50">
                                                <i class="bi bi-check2-circle display-6 mb-2 d-block"></i>
                                                <span class="small fw-bold text-uppercase">Libre</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <p class="text-muted">No hay mesas configuradas.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: PANEL DE DETALLES --}}
        <div class="col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 min-vh-lg-80" id="panel-vacio" style="display: block;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center text-muted p-5">
                    <i class="bi bi-hand-index-thumb display-1 mb-3 opacity-25"></i>
                    <h5>Selecciona una mesa</h5>
                    <p class="small">Haz clic en cualquier mesa para ver los detalles y procesar acciones.</p>
                </div>
            </div>

            @foreach($mesas as $mesa)
                @php $pedidoActivo = $mesa->pedidos->first(); @endphp
                <div class="card border-0 shadow-sm rounded-4 h-100 detail-pane" id="detalle-{{ $mesa->id }}" style="display: none;">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-0">
                                @if($mesa->es_para_llevar)
                                    🛍️ Para Llevar #{{ $mesa->numero }}
                                @else
                                    Mesa {{ $mesa->numero }}
                                @endif
                            </h4>
                            <span class="badge {{ !$pedidoActivo ? 'bg-success' : 'bg-danger' }} rounded-pill" style="font-size: 0.7rem;">
                                {{ !$pedidoActivo ? 'Disponible' : 'Ocupada' }}
                            </span>
                        </div>
                        <button type="button" class="btn-close" onclick="cerrarDetalle()"></button>
                    </div>

                    <div class="card-body p-4 d-flex flex-column">
                        @if($pedidoActivo)
                            {{-- Info del pedido --}}
                            <div class="mb-3">
                                <small class="text-muted d-block">Mesero: <strong>{{ $pedidoActivo->mesero->name }}</strong></small>
                                <small class="text-muted d-block">Hace: <strong>{{ $pedidoActivo->updated_at->diffForHumans() }}</strong></small>
                            </div>

                            <hr class="my-3 opacity-10">

                            {{-- Lista de Items --}}
                            <div class="flex-grow-1 overflow-auto mb-4" style="max-height: 40vh;">
                                <h6 class="fw-bold text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Consumo Actual</h6>
                                <ul class="list-group list-group-flush gap-2">
                                    @foreach($pedidoActivo->detalles as $det)
                                        <li class="list-group-item d-flex align-items-center justify-content-between p-3 border rounded-3 mb-2 bg-light bg-opacity-25 consumo-item" style="border-color: #eef1f6 !important; border-radius: 12px !important;">
                                            <div class="d-flex align-items-center gap-3">
                                                {{-- Cantidad en Badge destacado --}}
                                                <div class="bg-primary-subtle text-primary border border-primary-subtle d-flex align-items-center justify-content-center fw-bold rounded-3" style="width: 42px; height: 42px; font-size: 1.1rem; flex-shrink: 0; min-width: 42px;">
                                                    {{ $det->cantidad }}
                                                </div>
                                                <div>
                                                    {{-- Nombre del Producto grande --}}
                                                    <div class="fw-bold text-dark mb-0" style="font-size: 1.05rem; line-height: 1.2;">
                                                        {{ $det->nombre_mostrar }}
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                                        <span class="text-muted small">Bs {{ number_format($det->precio_unitario, 2) }} c/u</span>
                                                        @if($det->estado_comanda === 'pendiente')
                                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-0.5 rounded-pill" style="font-size: 0.65rem;">
                                                                Pendiente
                                                            </span>
                                                        @else
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded-pill" style="font-size: 0.65rem;">
                                                                En Cocina
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Precio Subtotal --}}
                                            <div class="text-end ps-2">
                                                <span class="fw-bold text-dark fs-5">Bs {{ number_format($det->cantidad * $det->precio_unitario, 2) }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Botones de Impresión Térmica --}}
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <button type="button" onclick="imprimirDirecto(event, '{{ route('cajero.api.imprimir.comanda', $pedidoActivo->id) }}')" class="btn btn-outline-primary w-100 py-2 rounded-3" title="Imprimir Comanda Cocina">
                                        <i class="bi bi-printer me-1"></i> Comanda
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" onclick="imprimirDirecto(event, '{{ route('cajero.api.imprimir.cuenta', $pedidoActivo->id) }}')" class="btn btn-outline-dark w-100 py-2 rounded-3" title="Imprimir Pre-cuenta">
                                        <i class="bi bi-receipt me-1"></i> Pre-cuenta
                                    </button>
                                </div>
                            </div>

                            {{-- Botón de Cobro --}}
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 mb-0 fw-bold">TOTAL:</span>
                                    <span class="h4 mb-0 fw-bold text-success">Bs {{ number_format($pedidoActivo->total, 2) }}</span>
                                </div>
                                <div class="mb-2">
                                    <a href="{{ route('cajero.mesa', $mesa->id) }}" class="btn btn-outline-success w-100 py-2 fw-bold rounded-4 shadow-sm border-2">
                                        <i class="bi bi-pencil-square me-2"></i> ACTUALIZAR MESA
                                    </a>
                                </div>
                                <a href="{{ route('cajero.cobrar', $pedidoActivo->id) }}" class="btn btn-warning w-100 py-3 fw-bold text-white fs-5 rounded-4 shadow-sm">
                                    <i class="bi bi-credit-card me-2"></i> COBRAR
                                </a>
                                <form action="{{ route('cajero.pedido.anular', $pedidoActivo->id) }}" method="POST" class="mt-2 swal-confirm-form"
                                      data-swal-title="¿Anular Pedido?"
                                      data-swal-message="¿Estás seguro de ANULAR y BORRAR este pedido por completo? Esta acción devolverá los productos al inventario y liberará la mesa."
                                      data-swal-icon="warning"
                                      data-swal-confirm-text="Sí, anular pedido">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100 py-2 fw-bold rounded-4 shadow-sm">
                                        <i class="bi bi-trash3-fill me-2"></i> ANULAR PEDIDO
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-check2-circle text-success display-1 opacity-25"></i>
                                <p class="mt-3">Esta mesa está lista para nuevos clientes.</p>
                                <a href="{{ route('cajero.mesa', $mesa->id) }}" class="btn btn-outline-success w-100 py-3 fw-bold rounded-4 mt-3">
                                    <i class="bi bi-plus-circle me-2"></i> ATENDER MESA
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </div>
</div>

<script>
    function mostrarDetalle(mesaId) {
        // Ocultar placeholder vacío
        document.getElementById('panel-vacio').style.display = 'none';
        
        // Ocultar todos los páneles de detalles
        const panes = document.querySelectorAll('.detail-pane');
        panes.forEach(p => p.style.display = 'none');

        // Quitar selección visual de tarjetas
        const cards = document.querySelectorAll('.mesa-card');
        cards.forEach(c => c.style.transform = 'scale(1)');
        cards.forEach(c => c.style.boxShadow = 'var(--bs-box-shadow-sm)');

        // Mostrar el panel seleccionado
        const target = document.getElementById('detalle-' + mesaId);
        if (target) {
            target.style.display = 'block';
            // Efecto visual en la tarjeta seleccionada (opcional)
            event.currentTarget.style.transform = 'scale(1.05)';
            event.currentTarget.style.boxShadow = '0 .5rem 1rem rgba(0,0,0,.15)';
        }
    }

    function cerrarDetalle() {
        const panes = document.querySelectorAll('.detail-pane');
        panes.forEach(p => p.style.display = 'none');
        document.getElementById('panel-vacio').style.display = 'block';
        
        const cards = document.querySelectorAll('.mesa-card');
        cards.forEach(c => c.style.transform = 'scale(1)');
    }

    // Polling para actualizaciones en tiempo real
    let currentSignature = null;
    
    function checkSalonStatus() {
        fetch('{{ route('cajero.salon.status') }}')
            .then(response => response.json())
            .then(data => {
                if (currentSignature === null) {
                    currentSignature = data.signature; // Primera carga
                } else if (currentSignature !== data.signature) {
                    // Si la firma cambió, hubo modificaciones. Recargamos la página instantáneamente.
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error al verificar estado del salón:', error));
    }

    // Verificar cada 10 segundos
    setInterval(checkSalonStatus, 10000);
    // Primera verificación al cargar
    setTimeout(checkSalonStatus, 1000);

    // Función para imprimir directo sin abrir ventana
    function imprimirDirecto(event, url) {
        event.preventDefault();
        event.stopPropagation();
        
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.classList.add('disabled');
        btn.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) {
                // Éxito, se podría usar un Toast, por ahora solo console.log o alert sutil
                console.log(data.message);
                // Si la impresora cortó el papel, mostramos un feedback verde rápido
                const originalBg = btn.style.backgroundColor;
                btn.classList.remove('btn-outline-primary', 'btn-outline-dark');
                btn.classList.add('btn-success', 'text-white');
                setTimeout(() => {
                    btn.classList.remove('btn-success', 'text-white');
                    if (url.includes('comanda')) btn.classList.add('btn-outline-primary');
                    else btn.classList.add('btn-outline-dark');
                }, 1500);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Impresora',
                    text: data.message,
                    confirmButtonColor: '#e63946'
                });
            }
        })
        .catch(e => {
            Swal.fire({
                icon: 'error',
                title: 'Error de Red',
                text: 'Hubo un error de red al intentar comunicarse con la ticketera.',
                confirmButtonColor: '#e63946'
            });
            console.error(e);
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('disabled');
            btn.disabled = false;
        });
    }

</script>

{{-- MODAL DE GESTIÓN DE RESERVAS --}}
<div class="modal fade" id="modalReservas" tabindex="-1" aria-labelledby="modalReservasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-primary text-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold" id="modalReservasLabel"><i class="bi bi-calendar-check-fill me-2"></i> Gestión de Reservas - Hoy</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                {{-- Formulario para Nueva Reserva (Colapsable) --}}
                <div class="mb-4">
                    <button class="btn btn-outline-primary fw-bold w-100 py-2 d-flex align-items-center justify-content-center gap-2" 
                            type="button" data-bs-toggle="collapse" data-bs-target="#collapseFormReserva" 
                            aria-expanded="false" aria-controls="collapseFormReserva">
                        <i class="bi bi-calendar-plus-fill"></i>
                        <span>Registrar Nueva Reserva</span>
                    </button>
                    
                    <div class="collapse mt-3" id="collapseFormReserva">
                        <div class="card card-body border-0 bg-light p-3" style="border-radius: 14px;">
                            <form id="formNuevaReserva">
                                @csrf
                                <input type="hidden" name="fecha" value="{{ date('Y-m-d') }}">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Nombre del Cliente *</label>
                                        <input type="text" class="form-control" name="cliente_nombre" required placeholder="Ej. Juan Pérez">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Teléfono / Celular</label>
                                        <input type="text" class="form-control" name="cliente_telefono" placeholder="Ej. 78945612">
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <label class="form-label small fw-bold text-muted">Hora de Reserva *</label>
                                        <input type="time" class="form-control" name="hora" required>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <label class="form-label small fw-bold text-muted">Cantidad Personas *</label>
                                        <input type="number" class="form-control" name="cantidad_personas" min="1" required placeholder="Ej. 4">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold text-muted">Mesa Asignada</label>
                                        <select class="form-select" name="mesa_id">
                                            <option value="">Ninguna (Espera)</option>
                                            @foreach($mesas->where('es_para_llevar', false) as $m)
                                                <option value="{{ $m->id }}">Mesa {{ $m->numero }} (Cap: {{ $m->capacidad }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-muted">Notas / Requerimientos</label>
                                        <textarea class="form-control" name="notas" rows="2" placeholder="Ej. Mesa cerca de la ventana, traer pastel de cumple, etc."></textarea>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-success fw-bold px-4 py-2 rounded-3 shadow-sm">
                                            <i class="bi bi-check-circle me-1"></i> Guardar Reserva
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <hr class="opacity-10 my-4">

                {{-- Tabla de Reservas del Día --}}
                <div class="table-responsive">
                    <table class="table align-middle table-hover border-0">
                        <thead class="table-light text-muted small fw-bold">
                            <tr>
                                <th>CLIENTE</th>
                                <th>HORA</th>
                                <th>PERS.</th>
                                <th>MESA</th>
                                <th>ESTADO</th>
                                <th class="text-end">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody id="tablaReservasBody">
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Cargando reservas...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Inicializar Bootstrap Modal
    let bootstrapModalReservas = null;

    function abrirModalReservas() {
        if (!bootstrapModalReservas) {
            bootstrapModalReservas = new bootstrap.Modal(document.getElementById('modalReservas'));
        }
        bootstrapModalReservas.show();
        cargarReservas();
    }

    // Cargar Reservas AJAX
    function cargarReservas() {
        const body = document.getElementById('tablaReservasBody');
        
        fetch('{{ route('cajero.reservas.index') }}')
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    body.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-x display-6 d-block mb-2 opacity-50"></i>
                                No hay reservas registradas para hoy.
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                body.innerHTML = '';
                data.forEach(res => {
                    let estadoBadge = '';
                    let btnAcciones = '';
                    
                    if (res.estado === 'pendiente') {
                        estadoBadge = `<span class="badge bg-warning text-dark px-2.5 py-1.5 rounded-pill fw-bold">Pendiente</span>`;
                        btnAcciones = `
                            <button class="btn btn-sm btn-success fw-bold me-1" onclick="asistirReserva(${res.id})" title="Marcar como Asistido / Sentar">
                                <i class="bi bi-check-lg"></i> Asistir
                            </button>
                            <button class="btn btn-sm btn-outline-danger fw-bold me-1" onclick="cancelarReserva(${res.id})" title="Cancelar Reserva">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </button>
                        `;
                    } else if (res.estado === 'asistida') {
                        estadoBadge = `<span class="badge bg-success text-white px-2.5 py-1.5 rounded-pill fw-bold">Asistido</span>`;
                    } else {
                        estadoBadge = `<span class="badge bg-secondary text-white px-2.5 py-1.5 rounded-pill fw-bold">Cancelado</span>`;
                    }
                    
                    // Botón de eliminar siempre visible al final
                    btnAcciones += `
                        <button class="btn btn-sm btn-light text-danger fw-bold" onclick="eliminarReserva(${res.id})" title="Eliminar Registro">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    
                    let mesaNombre = res.mesa_id ? `Mesa ${res.mesa.numero}` : `<span class="text-muted small">Sin asignar</span>`;
                    let telefono = res.cliente_telefono ? `<br><small class="text-muted"><i class="bi bi-telephone"></i> ${res.cliente_telefono}</small>` : '';
                    let notas = res.notas ? `<br><small class="text-info font-monospace" style="font-size:0.75rem;">* ${res.notas}</small>` : '';

                    let formattedHora = res.hora.substring(0, 5);
                    
                    body.innerHTML += `
                        <tr>
                            <td>
                                <strong class="text-dark">${res.cliente_nombre}</strong>
                                ${telefono}
                                ${notas}
                            </td>
                            <td class="fw-bold text-primary">${formattedHora}</td>
                            <td><span class="badge bg-light text-dark border px-2 py-1">${res.cantidad_personas} pers.</span></td>
                            <td class="fw-bold">${mesaNombre}</td>
                            <td>${estadoBadge}</td>
                            <td class="text-end" style="white-space: nowrap;">${btnAcciones}</td>
                        </tr>
                    `;
                });
            })
            .catch(err => {
                console.error(err);
                body.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4 text-danger">
                            Hubo un error al intentar cargar las reservas.
                        </td>
                    </tr>
                `;
            });
    }

    // Enviar Formulario de Nueva Reserva
    document.getElementById('formNuevaReserva').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route('cajero.reservas.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                document.getElementById('formNuevaReserva').reset();
                
                // Colapsar formulario
                const collapse = bootstrap.Collapse.getInstance(document.getElementById('collapseFormReserva'));
                if (collapse) collapse.hide();
                
                // Recargar lista y salón
                cargarReservas();
                setTimeout(() => window.location.reload(), 1500);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonColor: '#e63946'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error de conexión al guardar la reserva.',
                confirmButtonColor: '#e63946'
            });
        });
    });

    // Marcar como Asistido
    function asistirReserva(id) {
        Swal.fire({
            title: '¿Confirmar asistencia?',
            text: "Se marcará al cliente como presente en el local.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/cajero/reservas/${id}/asistir`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Asistencia registrada',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        cargarReservas();
                        
                        if (data.redirect_to) {
                            setTimeout(() => window.location.href = data.redirect_to, 1200);
                        } else {
                            setTimeout(() => window.location.reload(), 1500);
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo registrar la asistencia.',
                        confirmButtonColor: '#e63946'
                    });
                });
            }
        });
    }

    // Cancelar Reserva
    function cancelarReserva(id) {
        Swal.fire({
            title: '¿Cancelar esta reserva?',
            text: "El estado pasará a cancelado y liberará el espacio de la mesa.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e63946',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, cancelar reserva',
            cancelButtonText: 'No, conservar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/cajero/reservas/${id}/cancelar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Reserva Cancelada',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        cargarReservas();
                        setTimeout(() => window.location.reload(), 1200);
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cancelar la reserva.',
                        confirmButtonColor: '#e63946'
                    });
                });
            }
        });
    }

    // Eliminar Registro de Reserva
    function eliminarReserva(id) {
        Swal.fire({
            title: '¿Eliminar registro?',
            text: "Esta acción borrará la reserva definitivamente de la base de datos.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/cajero/reservas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Registro Eliminado',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        cargarReservas();
                        setTimeout(() => window.location.reload(), 1200);
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo borrar el registro.',
                        confirmButtonColor: '#e63946'
                    });
                });
            }
        });
    }
</script>

<style>
.mesa-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
}
.consumo-item {
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.consumo-item:hover {
    background-color: #f8f9ff !important;
    border-color: #4361ee !important;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.04);
}
@media (min-width: 992px) {
    .min-vh-lg-80 {
        min-height: 80vh;
    }
}
</style>
@endsection
