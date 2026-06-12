@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-center align-items-center mb-4 position-relative">
        <h2 class="fw-bold fs-3 mb-0 text-center">🪑 Salón de Mesas</h2>
    </div>

    {{-- Leyenda --}}
    <div class="d-flex gap-3 mb-4 flex-wrap">
        <span class="d-flex align-items-center gap-1"><span style="width:14px;height:14px;background:#28a745;border-radius:50%;display:inline-block;"></span> Libre</span>
        <span class="d-flex align-items-center gap-1"><span style="width:14px;height:14px;background:#dc3545;border-radius:50%;display:inline-block;"></span> Ocupada</span>
    </div>

    {{-- Toast de pedido registrado --}}
    @if(session('pedido_registrado'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
            <div id="toastPedido" class="toast align-items-center text-white bg-success border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body fw-bold fs-6">
                        {{ session('pedido_registrado') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const el = document.getElementById('toastPedido');
                if (el) el.classList.remove('show');
            }, 4000);
        </script>
    @endif

    {{-- Cuadrícula de mesas responsiva para móvil --}}
    <div class="row g-3 px-1">
        @forelse($mesas as $mesa)
            @php
                $pedidoActivo = $mesa->pedidos->first();
                $libre = !$pedidoActivo;
            @endphp

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 text-center shadow-sm"
                     style="border-radius: 16px; overflow: hidden; background-color: {{ $libre ? '#f8fff9' : '#fff5f6' }};">
                     
                    {{-- Borde superior de color --}}
                    <div style="height: 6px; width: 100%; background: {{ $libre ? '#28a745' : '#dc3545' }};"></div>

                    <div class="card-body p-3 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge {{ $libre ? 'bg-success' : 'bg-danger' }} rounded-pill" style="font-size: 0.7rem;">
                                {{ $libre ? 'Libre' : 'Ocupada' }}
                            </span>
                            <small class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-people-fill"></i> {{ $mesa->capacidad }}</small>
                        </div>
                        
                        <h4 class="fw-bold mb-0 text-dark">
                            @if($mesa->es_para_llevar)
                                🛍️ Llevar {{ $mesa->numero }}
                            @else
                                Mesa {{ $mesa->numero }}
                            @endif
                        </h4>
                        
                        @if(!$libre)
                            <div class="mt-2 text-success fw-bold">
                                Bs {{ number_format($pedidoActivo->total, 2) }}
                            </div>
                        @else
                            <div class="mt-2 text-muted" style="font-size: 0.85rem;">
                                Disponible
                            </div>
                        @endif

                        <div class="mt-auto pt-3">
                            @if(!$libre)
                                <button type="button" class="btn w-100 fw-bold" data-bs-toggle="offcanvas" data-bs-target="#mesa-offcanvas-{{ $mesa->id }}" style="background-color: #ffebee; color: #c62828; border-radius: 10px; font-size: 0.9rem;">
                                    <i class="bi bi-receipt me-1"></i> Ver Mesa
                                </button>
                            @else
                                <a href="{{ route('mesero.mesa', $mesa->id) }}" class="btn w-100 fw-bold" style="background-color: #e8f5e9; color: #2e7d32; border-radius: 10px; font-size: 0.9rem;">
                                    <i class="bi bi-plus-circle me-1"></i> Atender Mesa
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    No hay mesas registradas. El administrador debe crearlas primero.
                </div>
            </div>
        @endforelse
    </div>

    {{-- ═══════════════════════════════
         OFFCANVAS PARA MESAS OCUPADAS
    ═══════════════════════════════ --}}
    @foreach($mesas as $mesa)
        @php $pedidoActivo = $mesa->pedidos->first(); @endphp
        @if($pedidoActivo)
            <div class="offcanvas offcanvas-bottom rounded-top-4" tabindex="-1" id="mesa-offcanvas-{{ $mesa->id }}" style="height: 80vh;">
                <div class="offcanvas-header border-bottom px-3 py-3 bg-light rounded-top-4">
                    <div>
                        <h5 class="offcanvas-title fw-bold mb-0">
                            @if($mesa->es_para_llevar)
                                🛍️ Detalle Llevar #{{ $mesa->numero }}
                            @else
                                🧾 Detalle Mesa {{ $mesa->numero }}
                            @endif
                        </h5>
                        <span class="text-muted" style="font-size: 0.85rem;">Pedido {{ $pedidoActivo->estado === 'cuenta_solicitada' ? '(Cuenta Solicitada)' : 'Abierto' }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body p-0 overflow-auto">
                    @if($pedidoActivo->detalles->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($pedidoActivo->detalles as $det)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-3">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $det->nombre_mostrar }}</span>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="text-muted" style="font-size: 0.85rem;">Bs {{ number_format($det->precio_unitario, 2) }}</span>
                                            @if($det->estado_comanda === 'pendiente')
                                                <span class="badge bg-warning text-dark" style="font-size:0.65rem">Pendiente</span>
                                            @else
                                                <span class="badge bg-success" style="font-size:0.65rem">En cocina</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-end d-flex flex-column align-items-end">
                                        <span class="badge bg-light text-dark border">x{{ $det->cantidad }}</span>
                                        <strong class="mt-1 text-success" style="font-size: 0.95rem;">
                                            Bs {{ number_format($det->cantidad * $det->precio_unitario, 2) }}
                                        </strong>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <p class="text-muted fw-bold">No hay ítems registrados aún.</p>
                        </div>
                    @endif
                </div>
                
                {{-- FOOTER: Total y Botón Agregar Producto --}}
                <div class="offcanvas-footer border-top bg-white px-3 py-3">
                    <div class="d-flex justify-content-between align-items-center fw-bold fs-5 mb-3">
                        <span class="text-dark">Total Acumulado:</span>
                        <span class="text-success">Bs {{ number_format($pedidoActivo->total, 2) }}</span>
                    </div>

                    @if($pedidoActivo->estado === 'abierto')
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('mesero.mesa', $mesa->id) }}" class="btn btn-primary w-100 fw-bold py-2 rounded-3" style="font-size: 1.05rem;">
                                <i class="bi bi-plus-circle me-2"></i>Agregar Productos
                            </a>
                            <button type="button" class="btn btn-outline-primary w-100 fw-bold py-2 rounded-3 mb-2" id="btn-imprimir-comanda-{{ $pedidoActivo->id }}" onclick="imprimirComanda({{ $pedidoActivo->id }})" style="font-size: 1.05rem;">
                                <i class="bi bi-printer-fill me-2"></i>Imprimir Comanda
                            </button>
                            <button type="button" class="btn btn-outline-info w-100 fw-bold py-2 rounded-3" id="btn-imprimir-precuenta-{{ $pedidoActivo->id }}" onclick="imprimirPreCuenta({{ $pedidoActivo->id }})" style="font-size: 1.05rem;">
                                <i class="bi bi-receipt me-2"></i>Imprimir Pre-Cuenta
                            </button>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-2">
                            <div class="alert alert-warning mb-0 text-center fw-bold">
                                <i class="bi bi-clock-history me-1"></i> Esperando cobro en caja
                            </div>
                            <button type="button" class="btn btn-outline-info w-100 fw-bold py-2 rounded-3" id="btn-imprimir-precuenta-{{ $pedidoActivo->id }}" onclick="imprimirPreCuenta({{ $pedidoActivo->id }})" style="font-size: 1.05rem;">
                                <i class="bi bi-printer me-2"></i>Reimprimir Pre-Cuenta
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endforeach

</div>

<script>
// Función para imprimir comanda desde el celular
function imprimirComanda(pedidoId) {
    const btn = document.getElementById('btn-imprimir-comanda-' + pedidoId);
    const textoOriginal = btn.innerHTML;
    
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
    btn.disabled = true;

    fetch(`/mesero/api/imprimir/comanda/${pedidoId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>¡Éxito!';
            btn.classList.replace('btn-outline-primary', 'btn-success');
            btn.classList.add('text-white');
            
            setTimeout(() => {
                btn.innerHTML = textoOriginal;
                btn.classList.replace('btn-success', 'btn-outline-primary');
                btn.classList.remove('text-white');
                btn.disabled = false;
            }, 3000);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error de Impresora',
                text: data.message || 'Error al imprimir',
                confirmButtonColor: '#e63946'
            });
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error de Red',
            text: 'Error de conexión con la impresora',
            confirmButtonColor: '#e63946'
        });
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
    });
}

// Función para imprimir pre-cuenta desde el celular (vista Salón)
function imprimirPreCuenta(pedidoId) {
    const btn = document.getElementById('btn-imprimir-precuenta-' + pedidoId);
    const textoOriginal = btn.innerHTML;
    
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Imprimiendo...';
    btn.disabled = true;
 
    fetch(`/mesero/api/imprimir/cuenta/${pedidoId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>¡Enviado!';
            btn.classList.replace('btn-outline-info', 'btn-success');
            btn.classList.add('text-white');
            
            setTimeout(() => {
                btn.innerHTML = textoOriginal;
                btn.classList.replace('btn-success', 'btn-outline-info');
                btn.classList.remove('text-white');
                btn.disabled = false;
            }, 3000);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error de Impresora',
                text: data.message || 'Error al imprimir',
                confirmButtonColor: '#e63946'
            });
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error de Red',
            text: 'Error de conexión con la impresora',
            confirmButtonColor: '#e63946'
        });
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
    });
}
</script>
@endsection
