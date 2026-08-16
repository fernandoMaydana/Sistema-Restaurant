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
        Schema::table('caja_sesions', function (Blueprint $table) {
            $table->decimal('monto_real', 10, 2)->nullable()->after('monto_final');
            $table->decimal('diferencia', 10, 2)->default(0)->after('monto_real');
            $table->text('observaciones')->nullable()->after('diferencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caja_sesions', function (Blueprint $table) {
            $table->dropColumn(['monto_real', 'diferencia', 'observaciones']);
        });
    }
};
