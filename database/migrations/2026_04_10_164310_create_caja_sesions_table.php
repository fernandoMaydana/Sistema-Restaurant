<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('caja_sesions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('monto_inicial', 10, 2);
            $table->decimal('monto_final', 10, 2)->nullable();
            $table->decimal('total_ventas', 10, 2)->default(0);
            $table->timestamp('fecha_apertura')->useCurrent();
            $table->timestamp('fecha_cierre')->nullable();
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_sesions');
    }
};
