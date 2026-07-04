<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Productos y servicios homologados por Impuestos Nacionales
        Schema::create('siat_productos_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_actividad');
            $table->string('codigo_producto');
            $table->string('descripcion');
            $table->timestamps();
        });

        // Leyendas de facturas homologadas por Impuestos Nacionales
        Schema::create('siat_leyendas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_actividad');
            $table->text('descripcion');
            $table->timestamps();
        });

        // Registro de eventos significativos (contingencias)
        Schema::create('siat_contingencias', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo_evento_sin'); // Código del evento de contingencia (ej. 1 = Corte de luz)
            $table->string('descripcion_evento');
            $table->string('cafc')->nullable(); // Código de Autorización de Facturas por Contingencia (si aplica)
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin')->nullable();
            $table->enum('estado', ['abierta', 'cerrada', 'procesada'])->default('abierta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siat_productos_servicios');
        Schema::dropIfExists('siat_leyendas');
        Schema::dropIfExists('siat_contingencias');
    }
};
