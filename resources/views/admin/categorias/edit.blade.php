@extends('layouts.admin')

@section('title', 'Editar Categoría')

@section('admin_content')
<div class="card shadow-sm col-md-6 offset-md-3">
    <div class="card-body">
        <form action="{{ route('admin.categorias.update', $categoria->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Nombre de la Categoría</label>
                <input type="text" name="nombre" class="form-control" required value="{{ old('nombre', $categoria->nombre) }}">
            </div>
            
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
