<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos');
            $table->foreignId('cajero_id')->constrained('users');
            $table->string('cliente_nombre')->nullable();
            $table->string('cliente_nit_ci')->nullable();
            $table->decimal('monto_pagado', 8, 2);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'qr', 'transferencia']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};