<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->decimal('iva', 10, 2)->default(0)->after('subtotal');
            $table->decimal('tasa_iva', 5, 2)->default(16)->after('iva');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['iva', 'tasa_iva']);
        });
    }
};
