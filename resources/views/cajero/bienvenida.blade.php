@extends('layouts.app')

@section('content')

{{-- Fondo minimalista --}}
<div style="min-height: calc(100vh - 56px); background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); display: flex; align-items: center; justify-content: center;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5">
                
                <div class="fade-in-up text-center p-5 card border-0 shadow-lg" style="border-radius: 2rem; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                    
                    {{-- Ícono animado --}}
                    <div class="mb-4 text-primary" style="font-size: 5rem; animation: pulse-light 2s infinite;">
                        <i class="bi bi-cash-register"></i>
                    </div>

                    {{-- Saludo --}}
                    <h1 class="fw-bold mb-1 text-dark" style="font-size: 2rem;">
                        ¡Hola, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-muted mb-4">
                        Preparando la caja para la jornada del <br>
                        <strong>{{ now()->locale('es')->isoFormat('dddd D [de] MMMM') }}</strong>
                    </p>

                    <div class="my-4" style="width: 50px; height: 4px; background: #4361ee; margin: 0 auto; border-radius: 10px;"></div>

                    {{-- Formulario de Apertura --}}
                    <form action="{{ route('cajero.abrir_caja') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4 text-start">
                            <label for="monto_inicial" class="form-label fw-bold text-center w-100 mb-3" style="font-size: 0.9rem; color: #4a5568;">
                                MONTO INICIAL EN EFECTIVO
                            </label>
                            <div class="input-group input-group-lg shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                <span class="input-group-text border-0 bg-light text-muted" style="font-size: 1.25rem;">Bs</span>
                                <input type="number" 
                                       name="monto_inicial" 
                                       id="monto_inicial" 
                                       step="0.01" 
                                       class="form-control border-0 bg-light fw-bold text-dark p-3" 
                                       placeholder="0.00" 
                                       required 
                                       autofocus
                                       style="font-size: 1.5rem;">
                            </div>
                            @error('monto_inicial')
                                <div class="text-danger small mt-2 px-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" 
                                class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm" 
                                style="border-radius: 15px; font-size: 1.1rem; transition: all 0.3s;">
                            <i class="bi bi-play-fill me-2"></i>INICIAR CAJA
                        </button>
                    </form>

                    <p class="mt-4 text-muted small">
                        Ingresa el monto con el que inicias para llevar un control exacto al final del turno.
                    </p>

                </div>

            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse-light {
    0%, 100% { transform: scale(1); filter: drop-shadow(0 0 0 rgba(67, 97, 238, 0)); }
    50%       { transform: scale(1.05); filter: drop-shadow(0 0 15px rgba(67, 97, 238, 0.2)); }
}

button[type="submit"]:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3) !important;
}
</style>

@endsection
