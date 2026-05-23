@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-success">
        <h1>Panel de Administración - Restaurante</h1>
        <p>Bienvenido, {{ Auth::user()->name }}. Aqui podras ver el menu y tomar pedidos</p>
    </div>
</div>
@endsection