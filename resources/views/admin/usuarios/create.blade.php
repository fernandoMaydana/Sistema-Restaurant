@extends('layouts.admin')

@section('title', 'Nuevo Usuario')

@section('admin_content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-person-plus-fill text-primary me-2"></i>Nuevo Usuario
                </h3>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-1"></i> Volver al Listado
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    {{-- Banner de Creación Rápida --}}
                    <div class="alert alert-primary border-0 rounded-3 shadow-xs mb-4 d-flex align-items-center gap-3">
                        <i class="bi bi-magic fs-3 text-primary"></i>
                        <div class="small">
                            <strong>⚡ Creación Rápida e Inteligente:</strong> Solo ingresa el <strong>Nombre Completo</strong> y el <strong>Rol</strong>. El correo electrónico y la contraseña se autogenerarán automáticamente si los dejas en blanco.
                        </div>
                    </div>

                    <form action="{{ route('admin.usuarios.store') }}" method="POST" id="form-crear-usuario">
                        @csrf

                        {{-- Nombre Completo --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="input-name" class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" placeholder="Ej. Carlos Mamani" required value="{{ old('name') }}" onkeyup="generarSugerencias()">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Rol --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rol en el Sistema <span class="text-danger">*</span></label>
                            <select name="role" class="form-select form-select-lg rounded-3 @error('role') is-invalid @enderror" required>
                                <option value="">-- Seleccionar Rol --</option>
                                <option value="cajero" {{ old('role') == 'cajero' ? 'selected' : '' }}>⚡ Cajero (Manejo de Cajas, Salón y Facturación)</option>
                                <option value="mesero" {{ old('role') == 'mesero' ? 'selected' : '' }}>🍽️ Mesero (Toma de Pedidos y Comandas)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>👑 Administrador (Acceso Total al Sistema)</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Correo Electrónico (Opcional) --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label fw-bold">Correo Electrónico</label>
                                <span class="badge bg-light text-secondary border">Opcional - Se autogenera</span>
                            </div>
                            <input type="email" name="email" id="input-email" class="form-control rounded-3 @error('email') is-invalid @enderror" placeholder="Ej. carlos.mamani@restaurante.com" value="{{ old('email') }}">
                            <div class="form-text text-primary small mt-1" id="help-email" style="display: none;">
                                <i class="bi bi-lightbulb-fill me-1"></i>Sugerido: <span id="sugerencia-email-txt" class="fw-bold"></span>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Contraseña (Opcional) --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label fw-bold">Contraseña</label>
                                    <span class="badge bg-light text-secondary border">Opcional</span>
                                </div>
                                <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="Por defecto: 12345678" minlength="8">
                                <div class="form-text text-muted small">Por defecto se asigna: <code>12345678</code></div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Repetir contraseña" minlength="8">
                            </div>
                        </div>

                        {{-- Estado Activo --}}
                        <div class="p-3 border rounded-4 bg-light mb-4">
                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="transform: scale(1.3); cursor: pointer;">
                                <label class="form-check-label fw-bold text-dark mb-0 ms-2" for="is_active" style="cursor: pointer;">
                                    Cuenta Activa (Habilitada para ingresar inmediatamente)
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold px-4 flex-grow-1 shadow-sm">
                                <i class="bi bi-check-circle-fill me-2"></i>GUARDAR USUARIO
                            </button>
                            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-light btn-lg rounded-3 border px-4">
                                Cancelar
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function generarSugerencias() {
        const nameInput = document.getElementById('input-name');
        const emailInput = document.getElementById('input-email');
        const helpEmail = document.getElementById('help-email');
        const sugerenciaTxt = document.getElementById('sugerencia-email-txt');

        if (!nameInput || !emailInput) return;

        const val = nameInput.value.trim().toLowerCase();
        if (val.length > 2) {
            // Generar slug del nombre
            const slug = val.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z0-9]/g, ".").replace(/\.+/g, ".").replace(/^\.|\.$/g, "");
            const sugerido = `${slug}@restaurante.com`;
            sugerenciaTxt.innerText = sugerido;
            helpEmail.style.display = 'block';

            if (emailInput.value.trim() === '') {
                emailInput.placeholder = sugerido;
            }
        } else {
            helpEmail.style.display = 'none';
        }
    }
</script>
@endsection
