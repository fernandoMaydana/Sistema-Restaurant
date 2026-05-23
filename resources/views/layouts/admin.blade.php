@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="min-height: calc(100vh - 56px);">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-bold' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.usuarios.index') }}">
                            <i class="bi bi-people me-2"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.mesas.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.mesas.index') }}">
                            <i class="bi bi-grid me-2"></i> Mesas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categorias.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.categorias.index') }}">
                            <i class="bi bi-tags me-2"></i> Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.productos.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.productos.index') }}">
                            <i class="bi bi-box me-2"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <span class="nav-link text-muted fw-bold text-uppercase px-3" style="font-size: 0.75rem;">Finanzas</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.ventas.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.ventas.index') }}">
                            <i class="bi bi-receipt me-2"></i> Historial de Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.cajas.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.cajas.index') }}">
                            <i class="bi bi-clock-history me-2"></i> Historial Cajas
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
