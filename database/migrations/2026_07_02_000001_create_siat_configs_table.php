<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siat_configs', function (Blueprint $table) {
            $table->id();
            $table->string('nit')->nullable();
            $table->text('token_delegado')->nullable();
            $table->enum('ambiente', ['piloto', 'produccion'])->default('piloto');
            $table->enum('modalidad', ['computarizada', 'electronica'])->default('computarizada');
            $table->integer('codigo_sucursal')->default(0);
            $table->integer('codigo_punto_venta')->default(0);
            $table->string('actividad_economica')->nullable();
            $table->string('cuis')->nullable();
            $table->timestamp('cuis_creado_el')->nullable();
            $table->text('cufd_codigo')->nullable();
            $table->string('cufd_codigo_control')->nullable();
            $table->timestamp('cufd_expiracion')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->boolean('modo_prueba_sin_conexion')->default(true); // Permite correr simulaciones localmente
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siat_configs');
    }
};
