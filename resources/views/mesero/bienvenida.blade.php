@extends('layouts.app')

@section('content')

{{-- Fondo claro y estético --}}
<div style="min-height: calc(100vh - 56px); background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%); display: flex; align-items: center; justify-content: center;">

    <div class="text-center px-4" style="max-width: 550px;">

        {{-- Ícono animado --}}
        <div class="mb-4" style="font-size: 5rem; animation: pulse 2s infinite;">
            🍽️
        </div>

        {{-- Saludo --}}
        <h1 class="fw-bold mb-1 text-dark" style="font-size: 2.2rem;">
            ¡Bienvenido, {{ Auth::user()->name }}!
        </h1>
        <p class="mb-1 text-secondary" style="font-size: 1.1rem;">
            Turno del {{ now()->locale('es')->isoFormat('dddd D [de] MMMM') }}
        </p>
        <p class="text-muted" style="font-size: 0.95rem;">
            {{ now()->format('H:i') }} hs
        </p>

        {{-- Separador --}}
        <div class="my-4" style="width: 60px; height: 4px; background: #0d6efd; margin: 0 auto; border-radius: 10px;"></div>

        {{-- Botón principal --}}
        <a href="{{ route('mesero.salon') }}"
           class="btn btn-lg px-5 py-3 fw-bold"
           style="background: #0d6efd; color: white; border-radius: 50px; font-size: 1.2rem;
                  box-shadow: 0 8px 25px rgba(13,110,253,0.3); transition: all 0.25s;
                  text-decoration: none;"
           onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 14px 35px rgba(13,110,253,0.45)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(13,110,253,0.3)'">
            ▶&nbsp;&nbsp; Empezar Día
        </a>

        <p class="mt-4 text-muted" style="font-size: 0.85rem;">
            Haz clic para ver el estado del salón y las mesas disponibles.
        </p>

    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50%       { transform: scale(1.1); }
}
</style>

@endsection
