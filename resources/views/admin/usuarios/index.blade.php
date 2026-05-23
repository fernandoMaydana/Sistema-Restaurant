@extends('layouts.admin')

@section('title', 'Usuarios Registrados')

@section('actions')
    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo Usuario</a>
@endsection

@section('admin_content')
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Creado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge @if($user->role == 'admin') bg-danger @elseif($user->role == 'cajero') bg-primary @else bg-success @endif">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro de eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
