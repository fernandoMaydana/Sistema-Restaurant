@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <div class="login-card fade-in-up">

        {{-- Logo / Icono --}}
        <div class="login-logo">
            <i class="bi bi-fork-knife"></i>
        </div>

        <h1>Bienvenido</h1>
        <p class="login-subtitle">Ingresa tus credenciales para continuar</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input id="email" type="email"
                       class="form-control login-input @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}"
                       required autocomplete="email" autofocus
                       placeholder="esempio@correo.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" type="password"
                       class="form-control login-input @error('password') is-invalid @enderror"
                       name="password" required autocomplete="current-password"
                       placeholder="••••••••">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember me --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember"
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-muted" for="remember"
                           style="font-size:0.875rem;">
                        Mantener sesión
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-primary"
                       style="font-size:0.875rem; text-decoration:none;">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-login-submit">
                Iniciar Sesión
            </button>

        </form>

    </div>
</div>
@endsection