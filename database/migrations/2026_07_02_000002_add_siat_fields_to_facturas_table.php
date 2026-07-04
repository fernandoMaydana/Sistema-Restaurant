<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->string('cuf', 150)->nullable()->unique();
            $table->text('cufd_codigo')->nullable();
            $table->bigInteger('numero_factura_siat')->nullable();
            $table->enum('estado_siat', ['no_siat', 'pendiente', 'enviada', 'rechazada', 'anulada_siat'])->default('no_siat');
            $table->string('codigo_recepcion')->nullable();
            $table->text('leyenda_sin')->nullable();
            $table->string('xml_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn([
                'cuf',
                'cufd_codigo',
                'numero_factura_siat',
                'estado_siat',
                'codigo_recepcion',
                'leyenda_sin',
                'xml_path'
            ]);
        });
    }
};
