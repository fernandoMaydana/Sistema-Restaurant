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
        Schema::table('productos', function (Blueprint $table) {
            $table->decimal('precio_3', 8, 2)->nullable()->after('precio_2_nombre');
            $table->string('precio_3_nombre', 50)->nullable()->after('precio_3');
            $table->decimal('costo', 8, 2)->default(0)->after('precio_3_nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['precio_3', 'precio_3_nombre', 'costo']);
        });
    }
};
