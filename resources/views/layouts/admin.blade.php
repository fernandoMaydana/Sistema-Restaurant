@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row flex-nowrap">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse px-0"
             style="height: calc(100vh - 56px); position: sticky; top: 56px; overflow-y: auto; overflow-x: hidden;">
            <div class="pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-bold' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 text-secondary me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.stock.index') ? 'active fw-bold' : '' }}" href="{{ route('admin.stock.index') }}">
                            <i class="bi bi-box-seam text-secondary me-2"></i> Control de Stock
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.usuarios.index') }}">
                            <i class="bi bi-people text-primary me-2"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.mesas.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.mesas.index') }}">
                            <i class="bi bi-grid text-success me-2"></i> Mesas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categorias.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.categorias.index') }}">
                            <i class="bi bi-tags text-warning me-2"></i> Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.productos.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.productos.index') }}">
                            <i class="bi bi-box text-info me-2"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.combos.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.combos.index') }}">
                            <i class="bi bi-gift text-danger me-2"></i> Combos y Promos
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <span class="nav-link text-muted fw-bold text-uppercase px-3" style="font-size: 0.75rem;">Finanzas</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.ventas.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.ventas.index') }}">
                            <i class="bi bi-receipt text-success me-2"></i> Historial de Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.cajas.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.cajas.index') }}">
                            <i class="bi bi-clock-history text-secondary me-2"></i> Historial Cajas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.graficos') ? 'active fw-bold' : '' }}" href="{{ route('admin.reportes.graficos') }}">
                            <i class="bi bi-bar-chart-line text-primary me-2"></i> Gráficos e Históricos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.rentabilidad') ? 'active fw-bold' : '' }}" href="{{ route('admin.reportes.rentabilidad') }}">
                            <i class="bi bi-cash-coin text-success me-2"></i> Rentabilidad y Utilidades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.productos_vendidos') ? 'active fw-bold' : '' }}" href="{{ route('admin.reportes.productos_vendidos') }}">
                            <i class="bi bi-trophy text-warning me-2"></i> Productos Más Vendidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.meseros') ? 'active fw-bold' : '' }}" href="{{ route('admin.reportes.meseros') }}">
                            <i class="bi bi-person-badge text-info me-2"></i> Rendimiento Meseros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.stock_critico') ? 'active fw-bold' : '' }}" href="{{ route('admin.reportes.stock_critico') }}">
                            <i class="bi bi-exclamation-triangle text-danger me-2"></i> Stock Crítico
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">@yield('title', 'Admin Panel')</h1>
                @yield('actions')
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('admin_content')
        </main>
    </div>
</div>
@endsection
