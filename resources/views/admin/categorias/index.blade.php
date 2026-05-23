@extends('layouts.admin')

@section('title', 'Categorías del Menú')

@section('actions')
    <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nueva Categoría</a>
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
                        <th>Creado en</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->id }}</td>
                            <td class="fw-bold">{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.categorias.edit', $categoria->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form action="{{ route('admin.categorias.destroy', $categoria->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro de eliminar esta categoría?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($categorias->isEmpty())
            <div class="text-center p-4 text-muted">
                No hay categorías registradas.
            </div>
        @endif
    </div>
</div>
@endsection
