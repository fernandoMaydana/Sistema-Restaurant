<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistema Restaurante') }}</title>

    <!-- Aplicación Web Progresiva (PWA Standalone Mode) -->
    <meta name="theme-color" content="#10b981">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Restaurante">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon-192.png') }}">

    <!-- Script de Inicialización de Modo Oscuro Sin Parpadeo -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('app-theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();

        function applyTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('app-theme', theme);
            const icons = document.querySelectorAll('.theme-toggle-icon');
            icons.forEach(icon => {
                if (theme === 'dark') {
                    icon.className = 'bi bi-sun-fill theme-toggle-icon text-warning';
                } else {
                    icon.className = 'bi bi-moon-stars-fill theme-toggle-icon text-dark';
                }
            });
        }

        function toggleTheme() {
            const currentTheme = localStorage.getItem('app-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('app-theme') || 'light';
            applyTheme(savedTheme);
        });
    </script>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts (Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Mini ajustes adicionales inline por si Vite no compiló aún */
        body { font-family: 'Inter', sans-serif; }

        /* Ocultar flechas (spinners) en inputs de número */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* --- ESTILOS LIGHTBOX GLOBAL Y MINIATURAS --- */
        .lightbox-overlay {
            display: none;
            position: fixed;
            z-index: 10000; /* Mayor que navbar o modals de Bootstrap */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            justify-content: center;
            align-items: center;
            cursor: zoom-out;
            opacity: 0;
            transition: opacity 0.25s ease;
        }
        .lightbox-overlay.show {
            display: flex;
            opacity: 1;
        }
        .lightbox-content {
            margin: auto;
            display: block;
            max-width: 85%;
            max-height: 80vh;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.5);
            transform: scale(0.9);
            transition: transform 0.25s ease;
            border: 4px solid rgba(255,255,255,0.1);
        }
        .lightbox-overlay.show .lightbox-content {
            transform: scale(1);
        }
        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: 0.2s;
            cursor: pointer;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        .lightbox-close:hover {
            color: #ddd;
            transform: scale(1.1);
        }
        .lightbox-caption {
            position: absolute;
            bottom: 40px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            background: rgba(0,0,0,0.7);
            padding: 8px 24px;
            border-radius: 30px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.15);
            max-width: 80%;
        }

        /* Miniaturas de Producto con zoom en Hover */
        .product-zoom-container {
            overflow: hidden;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            border: 2px solid #fff;
            cursor: zoom-in;
            display: inline-block;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease;
        }
        .product-zoom-container:hover {
            transform: scale(1.08);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .product-zoom-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Placeholders con Degradado Premium */
        .product-placeholder-gradient {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            color: #e65100;
            border: 1.5px dashed rgba(230, 81, 0, 0.3) !important;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.02);
        }
        .product-placeholder-gradient:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(230, 81, 0, 0.08);
        }
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
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('cajero.cajas.historial') ? 'active fw-bold text-primary' : '' }}" href="{{ route('cajero.cajas.historial') }}">
                                        <i class="bi bi-safe me-1"></i>Historial Cajas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('cajero.ventas.historial') ? 'active fw-bold text-primary' : '' }}" href="{{ route('cajero.ventas.historial') }}">
                                        <i class="bi bi-receipt me-1"></i>Historial Ventas
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
                            <li class="nav-item me-1">
                                <button type="button" class="btn btn-light border btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center shadow-2xs" onclick="toggleTheme()" title="Cambiar Modo Oscuro / Claro" style="width: 34px; height: 34px;">
                                    <i class="bi bi-moon-stars-fill theme-toggle-icon text-warning"></i>
                                </button>
                            </li>

                            <li class="nav-item me-2">
                                <a class="btn btn-light border btn-sm text-dark fw-bold rounded-pill px-3 d-flex align-items-center gap-1 shadow-sm" href="{{ route('ayuda.index') }}" title="Manual e Instrucciones del Sistema">
                                    <i class="bi bi-book-half text-primary"></i> Manual / Ayuda
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                                   href="#" role="button" data-bs-toggle="dropdown">
                                    <span class="d-flex align-items-center justify-content-center rounded-circle"
                                          style="width:30px;height:30px;background:#eef1ff;font-size:0.85rem;color:#4361ee;font-weight:700;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                    <span style="font-size:0.875rem;font-weight:500;">
                                        {{ Auth::user()->name }}
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('perfil.edit') }}">
                                        <i class="bi bi-person me-2 text-primary"></i>Mi Perfil
                                    </a>
                                    <button class="dropdown-item d-flex align-items-center gap-2" onclick="toggleTheme()">
                                        <i class="bi bi-moon-stars-fill theme-toggle-icon text-warning me-1"></i>Modo Oscuro / Claro
                                    </button>
                                    <a class="dropdown-item" href="{{ route('ayuda.index') }}">
                                        <i class="bi bi-book me-2 text-warning"></i>Manual e Instrucciones
                                    </a>
                                    <div class="dropdown-divider"></div>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Interceptar formularios con la clase 'swal-confirm-form'
            document.body.addEventListener('submit', function (e) {
                const form = e.target;
                if (form.classList.contains('swal-confirm-form')) {
                    e.preventDefault();
                    
                    const message = form.getAttribute('data-swal-message') || '¿Estás seguro de realizar esta acción?';
                    const title = form.getAttribute('data-swal-title') || '¿Confirmar acción?';
                    const icon = form.getAttribute('data-swal-icon') || 'warning';
                    const confirmButtonText = form.getAttribute('data-swal-confirm-text') || 'Sí, confirmar';
                    const cancelButtonText = form.getAttribute('data-swal-cancel-text') || 'No, cancelar';
                    const confirmColor = icon === 'danger' || icon === 'error' ? '#dc3545' : '#4361ee';

                    Swal.fire({
                        title: title,
                        text: message,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: confirmColor,
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: confirmButtonText,
                        cancelButtonText: cancelButtonText,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.classList.remove('swal-confirm-form');
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>

    @yield('scripts')

    {{-- Componente Lightbox Global para Imágenes de Productos --}}
    <div id="global-lightbox" class="lightbox-overlay" onclick="cerrarLightbox()">
        <span class="lightbox-close">&times;</span>
        <img class="lightbox-content" id="lightbox-img" alt="Zoom Producto">
        <div id="lightbox-caption" class="lightbox-caption"></div>
    </div>

    <script>
        function mostrarLightbox(element) {
            const src = element.getAttribute('data-lightbox-src') || element.src;
            const caption = element.getAttribute('data-lightbox-caption') || element.alt;
            const lightbox = document.getElementById('global-lightbox');
            const img = document.getElementById('lightbox-img');
            const cap = document.getElementById('lightbox-caption');
            
            img.src = src;
            cap.textContent = caption;
            lightbox.style.display = 'flex';
            
            // Forzar reflow para animación
            setTimeout(() => {
                lightbox.classList.add('show');
            }, 10);
        }

        function cerrarLightbox() {
            const lightbox = document.getElementById('global-lightbox');
            lightbox.classList.remove('show');
            setTimeout(() => {
                lightbox.style.display = 'none';
            }, 250);
        }

        // Evitar el menú contextual del navegador al mantener pulsado imágenes, botones o enlaces (sensación APK nativa)
        document.addEventListener('contextmenu', function(e) {
            if (e.target.tagName === 'IMG' || e.target.tagName === 'BUTTON' || e.target.closest('.btn') || e.target.closest('a')) {
                e.preventDefault();
            }
        });

        // Función para cambiar a Modo Pantalla Completa en Móvil (sin bordes de navegador)
        function toggleFullScreen() {
            if (!document.fullscreenElement && !document.webkitFullscreenElement) {
                const el = document.documentElement;
                if (el.requestFullscreen) {
                    el.requestFullscreen();
                } else if (el.webkitRequestFullscreen) {
                    el.webkitRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }
        }

        // Registro de Service Worker para PWA (Habilita instalación en móvil sin bordes de navegador)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register("{{ asset('sw.js') }}", { scope: "{{ asset('./') }}" })
                    .then(function(reg) {
                        console.log('PWA ServiceWorker activo:', reg.scope);
                    })
                    .catch(function(err) {
                        console.log('PWA ServiceWorker error:', err);
                    });
            });
        }
    </script>
</body>
</html>
