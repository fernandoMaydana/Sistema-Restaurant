@extends('layouts.admin')

@section('title', 'Nueva Mesa')

@section('admin_content')
<div class="card shadow-sm col-md-6 offset-md-3">
    <div class="card-body">
        <form action="{{ route('admin.mesas.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label>Número de Mesa</label>
                <input type="number" min="1" name="numero" class="form-control" required value="{{ old('numero') }}" placeholder="Ej: 1, 2, 3...">
            </div>

            <div class="mb-3">
                <label>Capacidad (personas)</label>
                <input type="number" min="1" name="capacidad" class="form-control" required value="{{ old('capacidad') }}">
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('admin.mesas.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
