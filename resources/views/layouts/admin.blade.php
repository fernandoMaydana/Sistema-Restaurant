@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row flex-nowrap">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse px-0 border-end shadow-sm"
             style="height: calc(100vh - 56px); position: sticky; top: 56px; overflow-y: auto; overflow-x: hidden;">
            <div class="pt-2 pb-5">
                <ul class="nav flex-column gap-1 px-2">
                    {{-- SECCIÓN OPERACIONES --}}
                    <li class="nav-item">
                        <div class="sidebar-heading">Operaciones</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.stock.index') ? 'active' : '' }}" href="{{ route('admin.stock.index') }}">
                            <i class="bi bi-box-seam me-2"></i> Control de Stock
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.mesas.*') ? 'active' : '' }}" href="{{ route('admin.mesas.index') }}">
                            <i class="bi bi-grid me-2"></i> Mesas
                        </a>
                    </li>

                    {{-- SECCIÓN CATÁLOGOS --}}
                    <li class="nav-item">
                        <div class="sidebar-heading">Catálogos</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}" href="{{ route('admin.productos.index') }}">
                            <i class="bi bi-box me-2"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}" href="{{ route('admin.categorias.index') }}">
                            <i class="bi bi-tags me-2"></i> Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.combos.*') ? 'active' : '' }}" href="{{ route('admin.combos.index') }}">
                            <i class="bi bi-gift me-2"></i> Combos y Promos
                        </a>
                    </li>

                    {{-- SECCIÓN GESTIÓN --}}
                    <li class="nav-item">
                        <div class="sidebar-heading">Gestión</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}" href="{{ route('admin.usuarios.index') }}">
                            <i class="bi bi-people me-2"></i> Usuarios y Roles
                        </a>
                    </li>

                    {{-- SECCIÓN FINANZAS Y REPORTES --}}
                    <li class="nav-item">
                        <div class="sidebar-heading">Finanzas & Reportes</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.ventas.*') ? 'active' : '' }}" href="{{ route('admin.ventas.index') }}">
                            <i class="bi bi-receipt me-2"></i> Historial de Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.cajas.*') ? 'active' : '' }}" href="{{ route('admin.cajas.index') }}">
                            <i class="bi bi-clock-history me-2"></i> Historial de Cajas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.graficos') ? 'active' : '' }}" href="{{ route('admin.reportes.graficos') }}">
                            <i class="bi bi-bar-chart-line me-2"></i> Gráficos e Históricos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.rentabilidad') ? 'active' : '' }}" href="{{ route('admin.reportes.rentabilidad') }}">
                            <i class="bi bi-cash-coin me-2"></i> Rentabilidad y Utilidad
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.productos_vendidos') ? 'active' : '' }}" href="{{ route('admin.reportes.productos_vendidos') }}">
                            <i class="bi bi-trophy me-2"></i> Productos Vendidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.meseros') ? 'active' : '' }}" href="{{ route('admin.reportes.meseros') }}">
                            <i class="bi bi-person-badge me-2"></i> Rendimiento Meseros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.stock_critico') ? 'active' : '' }}" href="{{ route('admin.reportes.stock_critico') }}">
                            <i class="bi bi-exclamation-triangle me-2"></i> Stock Crítico
                        </a>
                    </li>

                    {{-- SECCIÓN CONFIGURACIÓN --}}
                    <li class="nav-item">
                        <div class="sidebar-heading">Configuración</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.siat.*') ? 'active' : '' }}" href="{{ route('admin.siat.index') }}">
                            <i class="bi bi-file-earmark-ruled me-2"></i> Facturación SIAT
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
