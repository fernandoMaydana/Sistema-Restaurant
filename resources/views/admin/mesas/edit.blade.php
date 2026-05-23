@extends('layouts.admin')

@section('title', 'Editar Mesa')

@section('admin_content')
<div class="card shadow-sm col-md-6 offset-md-3">
    <div class="card-body">
        <form action="{{ route('admin.mesas.update', $mesa->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label>Número de Mesa</label>
                <input type="number" min="1" name="numero" class="form-control" required value="{{ old('numero', $mesa->numero) }}">
            </div>

            <div class="mb-3">
                <label>Capacidad (personas)</label>
                <input type="number" min="1" name="capacidad" class="form-control" required value="{{ old('capacidad', $mesa->capacidad) }}">
            </div>

            <div class="mb-3">
                <label>Estado Activo</label>
                <select name="estado" class="form-control" required>
                    <option value="libre" {{ old('estado', $mesa->estado) == 'libre' ? 'selected' : '' }}>Libre (Verificador en verde)</option>
                    <option value="ocupada" {{ old('estado', $mesa->estado) == 'ocupada' ? 'selected' : '' }}>Ocupada (Verificador en rojo)</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('admin.mesas.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
