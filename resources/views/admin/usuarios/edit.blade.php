@extends('layouts.admin')

@section('title', 'Editar Usuario: ' . $usuario->name)

@section('admin_content')
<div class="card shadow-sm col-md-8 offset-md-2">
    <div class="card-body">
        <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Nombre Completo</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name', $usuario->name) }}">
            </div>
            <div class="mb-3">
                <label>Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email', $usuario->email) }}">
            </div>
            <div class="mb-3">
                <label>Rol</label>
                <select name="role" class="form-control" required>
                    <option value="admin" {{ $usuario->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="cajero" {{ $usuario->role == 'cajero' ? 'selected' : '' }}>Cajero</option>
                    <option value="mesero" {{ $usuario->role == 'mesero' ? 'selected' : '' }}>Mesero</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Contraseña (Dejar en blanco para conservar actual)</label>
                <input type="password" name="password" class="form-control" minlength="8">
            </div>
            <div class="mb-3">
                <label>Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" minlength="8">
            </div>

            <div class="mb-4 mt-3 p-3 border rounded bg-light">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $usuario->is_active) ? 'checked' : '' }} style="transform: scale(1.3); margin-right: 10px;">
                    <label class="form-check-label fw-bold" for="is_active">Cuenta Activa (Habilitada para ingresar al sistema)</label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
