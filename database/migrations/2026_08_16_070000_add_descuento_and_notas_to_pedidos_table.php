<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            if (!Schema::hasColumn('pedidos', 'descuento')) {
                $table->decimal('descuento', 8, 2)->default(0)->after('total');
            }
            if (!Schema::hasColumn('pedidos', 'notas')) {
                $table->string('notas', 255)->nullable()->after('descuento');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            if (Schema::hasColumn('pedidos', 'descuento')) {
                $table->dropColumn('descuento');
            }
            if (Schema::hasColumn('pedidos', 'notas')) {
                $table->dropColumn('notas');
            }
        });
    }
};
