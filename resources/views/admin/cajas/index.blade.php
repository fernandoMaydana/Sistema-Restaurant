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
</script>
@endsection
