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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('cliente_nombre');
            $table->string('cliente_telefono')->nullable();
            $table->date('fecha');
            $table->time('hora');
            $table->integer('cantidad_personas');
            $table->foreignId('mesa_id')->nullable()->constrained('mesas')->onDelete('set null');
            $table->text('notes')->nullable(); // Guardar notas (soportar notas/notes)
            $table->text('notas')->nullable();
            $table->enum('estado', ['pendiente', 'asistida', 'cancelada'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
