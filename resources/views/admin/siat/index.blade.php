@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Encabezado Principal --}}
    <div class="row align-items-center mb-4 pb-2 border-bottom">
        <div class="col-md-6">
            <span class="h2 fw-bold mb-0 text-gradient">
                <i class="bi bi-file-earmark-ruled me-2 text-primary"></i>Configuración SIAT Bolivia
            </span>
            <p class="text-muted mb-0">Administra las credenciales, sincronización de catálogos y emisión de facturas en línea.</p>
        </div>
        <div class="col-md-6 text-md-end mt-2 mt-md-0">
            <button id="btn-test-connection" class="btn btn-outline-primary fw-bold py-2 px-3 rounded-3 shadow-sm transition-all">
                <i class="bi bi-wifi me-2"></i>Probar Conexión SIN
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0 alert-dismissible fade show mb-4 rounded-3">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- COLUMNA IZQUIERDA: CONFIGURACION --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-gear-fill me-2"></i>Parámetros del Sistema</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.siat.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">NIT de la Empresa</label>
                                <input type="text" name="nit" class="form-control border-2 @error('nit') is-invalid @enderror" 
                                       value="{{ old('nit', $config->nit) }}" required>
                                @error('nit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Código de Actividad Económica</label>
                                <input type="text" name="actividad_economica" class="form-control border-2 @error('actividad_economica') is-invalid @enderror" 
                                       value="{{ old('actividad_economica', $config->actividad_economica) }}" required>
                                @error('actividad_economica') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-muted">Token Delegado (SIN)</label>
                                <textarea name="token_delegado" rows="4" class="form-control border-2 @error('token_delegado') is-invalid @enderror" required>{{ old('token_delegado', $config->token_delegado) }}</textarea>
                                @error('token_delegado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Ambiente</label>
                                <select name="ambiente" class="form-select border-2">
                                    <option value="piloto" {{ $config->ambiente == 'piloto' ? 'selected' : '' }}>🧪 Piloto (Pruebas)</option>
                                    <option value="produccion" {{ $config->ambiente == 'produccion' ? 'selected' : '' }}>🚀 Producción (Real)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Modalidad de Facturación</label>
                                <select name="modalidad" class="form-select border-2">
                                    <option value="computarizada" {{ $config->modalidad == 'computarizada' ? 'selected' : '' }}>💻 Computarizada en Línea</option>
                                    <option value="electronica" {{ $config->modalidad == 'electronica' ? 'selected' : '' }}>✍️ Electrónica en Línea (Firma Digital)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Código Sucursal</label>
                                <input type="number" name="codigo_sucursal" class="form-control border-2" 
                                       value="{{ old('codigo_sucursal', $config->codigo_sucursal) }}" min="0" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Código Punto de Venta</label>
                                <input type="number" name="codigo_punto_venta" class="form-control border-2" 
                                       value="{{ old('codigo_punto_venta', $config->codigo_punto_venta) }}" min="0" required>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="card bg-light border-0 rounded-3 p-3">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_enabled" id="is_enabled" 
                                               value="1" {{ $config->is_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-dark" for="is_enabled">
                                            🟢 Activar Facturación en Línea
                                        </label>
                                        <div class="form-text">Si está activo, al cobrar se emitirá la factura digital directamente al SIN.</div>
                                    </div>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="modo_prueba_sin_conexion" id="modo_prueba_sin_conexion" 
                                               value="1" {{ $config->modo_prueba_sin_conexion ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-dark" for="modo_prueba_sin_conexion">
                                            🧪 Modo Simulado (Pruebas sin Internet)
                                        </label>
                                        <div class="form-text">Permite probar todo el flujo de cobro simulando respuestas del SIN de forma instantánea. Recomendado para desarrollo.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-2 text-end">
                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow">
                                <i class="bi bi-save me-2"></i>Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: ESTADOS Y ACCIONES --}}
        <div class="col-lg-5">
            {{-- Tarjeta de Códigos SIN --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-shield-lock-fill me-2"></i>Códigos de Autorización</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-muted">Código CUIS:</span>
                            <button id="btn-renew-cuis" class="btn btn-sm btn-outline-dark fw-bold rounded-2">
                                <i class="bi bi-arrow-repeat me-1"></i>Renovar
                            </button>
                        </div>
                        <div class="p-2 border rounded bg-light font-monospace text-break mb-1 text-center" id="display-cuis" style="font-size: 0.85rem;">
                            {{ $config->cuis ?: 'No generado' }}
                        </div>
                        <small class="text-muted d-block text-end">Generado el: <span id="display-cuis-date">{{ $config->cuis_creado_el ? \Carbon\Carbon::parse($config->cuis_creado_el)->format('d/m/Y H:i') : 'Nunca' }}</span></small>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-muted">Código CUFD (Diario):</span>
                            <button id="btn-renew-cufd" class="btn btn-sm btn-outline-dark fw-bold rounded-2">
                                <i class="bi bi-arrow-repeat me-1"></i>Renovar
                            </button>
                        </div>
                        <div class="p-2 border rounded bg-light font-monospace text-break mb-1 text-center" id="display-cufd" style="font-size: 0.85rem; max-height: 80px; overflow-y: auto;">
                            {{ $config->cufd_codigo ?: 'No generado' }}
                        </div>
                        <small class="text-muted d-block text-end">Vence el: <span id="display-cufd-exp">{{ $config->cufd_expiracion ? \Carbon\Carbon::parse($config->cufd_expiracion)->format('d/m/Y H:i') : 'Nunca' }}</span></small>
                    </div>
                </div>
            </div>

            {{-- Tarjeta de Catálogos --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-gradient-success text-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-cloud-arrow-down-fill me-2"></i>Sincronización de Catálogos</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <span class="h3 fw-black text-primary d-block mb-1" id="count-productos">{{ $productosSinCount }}</span>
                                <small class="text-muted fw-semibold">Productos Homologados</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <span class="h3 fw-black text-success d-block mb-1" id="count-leyendas">{{ $leyendasCount }}</span>
                                <small class="text-muted fw-semibold">Leyendas de Factura</small>
                            </div>
                        </div>
                    </div>
                    
                    <button id="btn-sync-catalogos" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow">
                        <i class="bi bi-arrow-repeat me-2 fs-5"></i>Sincronizar Catálogos Ahora
                    </button>
                    <small class="text-muted d-block text-center mt-2">La sincronización debe realizarse por lo menos una vez al mes.</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODALES Y LOADER --}}
<div class="modal fade" id="loaderModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 bg-transparent text-center">
            <div class="spinner-border text-white" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Procesando...</span>
            </div>
            <p class="text-white mt-3 fw-bold fs-5" id="loaderText">Conectando con el SIN...</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loader = new bootstrap.Modal(document.getElementById('loaderModal'));
    const loaderText = document.getElementById('loaderText');

    function showLoader(text) {
        loaderText.textContent = text;
        loader.show();
    }

    function hideLoader() {
        loader.hide();
    }

    // 1. Probar Conexión
    document.getElementById('btn-test-connection').addEventListener('click', function() {
        showLoader('Probando comunicación SOAP...');
        fetch("{{ route('admin.siat.test-connection') }}")
            .then(res => res.json())
            .then(data => {
                hideLoader();
                if (data.success) {
                    alert('🟢 ' + data.message);
                } else {
                    alert('🔴 ' + data.message);
                }
            })
            .catch(err => {
                hideLoader();
                alert('🔴 Ocurrió un error al intentar probar la conexión.');
            });
    });

    // 2. Renovar CUIS
    document.getElementById('btn-renew-cuis').addEventListener('click', function() {
        if (!confirm('¿Desea solicitar un nuevo código CUIS a Impuestos Nacionales?')) return;
        showLoader('Renovando código CUIS...');
        fetch("{{ route('admin.siat.renew-cuis') }}", { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
            .then(res => res.json())
            .then(data => {
                hideLoader();
                if (data.success) {
                    document.getElementById('display-cuis').textContent = data.cuis;
                    document.getElementById('display-cuis-date').textContent = new Date().toLocaleString();
                    alert('🟢 ' + data.message);
                } else {
                    alert('🔴 ' + data.message);
                }
            })
            .catch(err => {
                hideLoader();
                alert('🔴 Error al intentar renovar el CUIS.');
            });
    });

    // 3. Renovar CUFD
    document.getElementById('btn-renew-cufd').addEventListener('click', function() {
        if (!confirm('¿Desea renovar el código CUFD de facturación diaria?')) return;
        showLoader('Renovando código CUFD...');
        fetch("{{ route('admin.siat.renew-cufd') }}", { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
            .then(res => res.json())
            .then(data => {
                hideLoader();
                if (data.success) {
                    document.getElementById('display-cufd').textContent = data.cufd;
                    document.getElementById('display-cufd-exp').textContent = new Date(Date.now() + 24*3600*1000).toLocaleString();
                    alert('🟢 ' + data.message);
                } else {
                    alert('🔴 ' + data.message);
                }
            })
            .catch(err => {
                hideLoader();
                alert('🔴 Error al intentar renovar el CUFD.');
            });
    });

    // 4. Sincronizar Catálogos
    document.getElementById('btn-sync-catalogos').addEventListener('click', function() {
        showLoader('Sincronizando catálogos SIN...');
        fetch("{{ route('admin.siat.sync-catalogos') }}", { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
            .then(res => res.json())
            .then(data => {
                hideLoader();
                if (data.success) {
                    // Recargar contadores simulados
                    document.getElementById('count-productos').textContent = "4";
                    document.getElementById('count-leyendas').textContent = "3";
                    alert('🟢 ' + data.message);
                } else {
                    alert('🔴 ' + data.message);
                }
            })
            .catch(err => {
                hideLoader();
                alert('🔴 Error de sincronización.');
            });
    });
});
</script>

<style>
.text-gradient {
    background: linear-gradient(90deg, #4361ee, #3a0ca3);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #4361ee, #3f37c9);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #2ec4b6, #0f9f90);
}
.transition-all {
    transition: all 0.25s ease-in-out;
}
.transition-all:hover {
    transform: translateY(-2px);
}
.fw-black {
    font-weight: 900;
}
</style>
@endsection
