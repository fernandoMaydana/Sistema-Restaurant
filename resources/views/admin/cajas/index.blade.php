@extends('layouts.admin')

@section('title', 'Historial de Cajas')

@section('admin_content')
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
                alert("Error de Impresora:\n" + data.message);
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        })
        .catch(e => {
            console.error("Error de red.", e);
            alert("Error de conexión al intentar imprimir.");
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    }
</script>
@endsection
