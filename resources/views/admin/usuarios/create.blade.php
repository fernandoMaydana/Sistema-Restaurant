@extends('layouts.admin')

@section('title', 'Nuevo Usuario')

@section('admin_content')
<div class="card shadow-sm col-md-8 offset-md-2">
    <div class="card-body">
        <form action="{{ route('admin.usuarios.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nombre Completo</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label>Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label>Rol</label>
                <select name="role" class="form-control" required>
                    <option value="">-- Seleccionar --</option>
                    <option value="admin">Administrador</option>
                    <option value="cajero">Cajero</option>
                    <option value="mesero">Mesero</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required minlength="8">
            </div>
            <div class="mb-3">
                <label>Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
            </div>

            <div class="mb-4 mt-3 p-3 border rounded bg-light">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="transform: scale(1.3); margin-right: 10px;">
                    <label class="form-check-label fw-bold" for="is_active">Cuenta Activa (Habilitada para ingresar al sistema)</label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
