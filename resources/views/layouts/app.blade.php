<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistema Restaurante') }}</title>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts (Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* Mini ajustes adicionales inline por si Vite no compiló aún */
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
    <div id="app">

        {{-- ─── NAVBAR (Ocultar en Login) ────────────────────────────────────────── --}}
        @if(!Route::is('login'))
        <nav class="navbar navbar-expand-md">
            <div class="container-fluid px-3">

                {{-- Brand --}}
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="bi bi-fork-knife"></i>
                    Restaurante
                </a>

                <button class="navbar-toggler" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navMain">
                    <i class="bi bi-list" style="font-size: 1.4rem; color: #4a5568;"></i>
                </button>

                <div class="collapse navbar-collapse" id="navMain">
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(Auth::user()->role === 'cajero')
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('cajero.dashboard') ? 'active fw-bold text-primary' : '' }}" href="{{ route('cajero.dashboard') }}">
                                        <i class="bi bi-clock-history me-1"></i>Inicio
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('cajero.salon') ? 'active fw-bold text-primary' : '' }}" href="{{ route('cajero.salon') }}">
                                        <i class="bi bi-grid-3x3-gap me-1"></i>Salón de Mesas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('cajero.inventario') ? 'active fw-bold text-primary' : '' }}" href="{{ route('cajero.inventario') }}">
                                        <i class="bi bi-box-seam me-1"></i>Inventario
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center gap-1">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesión
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                                   href="#" role="button" data-bs-toggle="dropdown">
                                    <span class="d-flex align-items-center justify-content-center rounded-circle"
                                          style="width:30px;height:30px;background:#eef1ff;font-size:0.85rem;color:#4361ee;font-weight:700;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                    <span style="font-size:0.875rem;font-weight:500;color:#374151;">
                                        {{ Auth::user()->name }}
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item text-danger"
                                       href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @endif

        {{-- ─── MAIN CONTENT ────────────────────────────────────────────── --}}
        <main class="py-4">
            @yield('content')
        </main>

    </div>
    
    @yield('scripts')
</body>
</html>
