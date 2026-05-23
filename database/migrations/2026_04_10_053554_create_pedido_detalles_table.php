<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos');
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 8, 2);
            $table->enum('estado_comanda', ['pendiente', 'impreso'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_detalles');
    }
};