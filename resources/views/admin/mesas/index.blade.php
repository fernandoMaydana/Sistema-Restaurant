@extends('layouts.admin')

@section('title', 'Mesas del Salón')

@section('actions')
    <a href="{{ route('admin.mesas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nueva Mesa</a>
@endsection

@section('admin_content')
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Número de Mesa</th>
                        <th>Capacidad</th>
                        <th>Estado Actual</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mesas as $mesa)
                        <tr>
                            <td>{{ $mesa->id }}</td>
                            <td class="fw-bold">Mesa {{ $mesa->numero }}</td>
                            <td><i class="bi bi-people-fill"></i> {{ $mesa->capacidad }} personas</td>
                            <td>
                                @if($mesa->estado == 'libre')
                                    <span class="badge bg-success">Libre</span>
                                @else
                                    <span class="badge bg-danger">Ocupada</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.mesas.edit', $mesa->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form action="{{ route('admin.mesas.destroy', $mesa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro de eliminar esta mesa?');">
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
        @if($mesas->isEmpty())
            <div class="text-center p-4 text-muted">
                No hay mesas registradas.
            </div>
        @endif
    </div>
</div>
@endsection
