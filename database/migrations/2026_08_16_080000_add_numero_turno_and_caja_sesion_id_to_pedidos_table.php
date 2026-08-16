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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('caja_sesion_id')->nullable()->after('mesa_id')->constrained('caja_sesions')->nullOnDelete();
            $table->integer('numero_turno')->default(1)->after('caja_sesion_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['caja_sesion_id']);
            $table->dropColumn(['caja_sesion_id', 'numero_turno']);
        });
    }
};
