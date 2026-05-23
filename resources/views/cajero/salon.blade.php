@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-3">

    <div class="row g-3">
        {{-- COLUMNA IZQUIERDA: GRILLA DE MESAS --}}
        <div class="col-lg-8 col-xl-9">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <div class="row w-100 align-items-center">
                    <div class="col-4">
                        {{-- Espacio vacío para balancear el centro --}}
                    </div>
                    <div class="col-4 text-center">
                        <span class="fw-bold fs-3">🪑 Salón de Mesas</span>
                    </div>
                    <div class="col-4 text-end">
                        <span class="text-muted small">{{ Auth::user()->name }}</span>
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

                    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
                        @forelse($mesas as $mesa)
                            @php
                                $pedidoActivo = $mesa->pedidos->first();
                                $libre = !$pedidoActivo;
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
                                <ul class="list-group list-group-flush">
                                    @foreach($pedidoActivo->detalles as $det)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0 border-bottom">
                                            <div>
                                                <div class="fw-bold" style="font-size: 0.9rem;">{{ $det->nombre_mostrar }}</div>
                                                <small class="text-muted">Bs {{ number_format($det->precio_unitario, 2) }} x {{ $det->cantidad }}</small>
                                            </div>
                                            <span class="fw-bold text-dark">Bs {{ number_format($det->cantidad * $det->precio_unitario, 2) }}</span>
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
                                <a href="{{ route('cajero.mesa', $mesa->id) }}" class="btn btn-link w-100 text-decoration-none mt-2 small">
                                    <i class="bi bi-plus-circle"></i> Agregar más productos
                                </a>
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
                alert("❌ Error de Impresora:\n" + data.message + "\n\nAsegúrate de que la impresora esté compartida en Windows como '" + '{{ env('PRINTER_NAME', 'EPSON_TM') }}' + "'.");
            }
        })
        .catch(e => {
            alert("Error de red al intentar imprimir.");
            console.error(e);
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('disabled');
            btn.disabled = false;
        });
    }

</script>

<style>
.mesa-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
}
@media (min-width: 992px) {
    .min-vh-lg-80 {
        min-height: 80vh;
    }
}
</style>
@endsection
