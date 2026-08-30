@extends('layouts.admin')

@section('title', 'Gestión de Usuarios y Permisos')

@section('actions')
    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-person-plus-fill fs-5"></i>
        <span>Nuevo Usuario</span>
    </a>
@endsection

@section('admin_content')
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="bi bi-people text-primary"></i>
            Usuarios Registrados
        </h5>
        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill">
            {{ $usuarios->count() }} usuarios
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol de Acceso</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $user)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $user->name }}</span>
                                        @if(auth()->id() === $user->id)
                                            <span class="badge bg-info-subtle text-info border border-info-subtle" style="font-size: 0.65rem;">Sesión Actual</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-secondary small">{{ $user->email }}</span></td>
                            <td>
                                @php
                                    $roleBadge = [
                                        'admin' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                        'cajero' => 'bg-primary-subtle text-primary border border-primary-subtle',
                                        'mesero' => 'bg-success-subtle text-success border border-success-subtle'
                                    ][$user->role] ?? 'bg-secondary text-white';
                                @endphp
                                <span class="badge {{ $roleBadge }} px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                    <i class="bi bi-shield-lock me-1"></i>{{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i>Activo
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border px-3 py-1 rounded-pill" style="font-size: 0.75rem;">
                                        <i class="bi bi-dash-circle me-1"></i>Inactivo
                                    </span>
                                @endif
                            </td>
                            <td><span class="text-muted small">{{ $user->created_at->format('d/m/Y') }}</span></td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 fw-bold">
                                        <i class="bi bi-pencil-square me-1"></i>Editar
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro de eliminar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-3 fw-bold">
                                                <i class="bi bi-trash3-fill me-1"></i>Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
