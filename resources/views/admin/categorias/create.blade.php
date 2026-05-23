@extends('layouts.admin')

@section('title', 'Nueva Categoría')

@section('admin_content')
<div class="card shadow-sm col-md-6 offset-md-3">
    <div class="card-body">
        <form action="{{ route('admin.categorias.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nombre de la Categoría</label>
                <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}" placeholder="Ej: Bebidas, Postres, etc.">
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
