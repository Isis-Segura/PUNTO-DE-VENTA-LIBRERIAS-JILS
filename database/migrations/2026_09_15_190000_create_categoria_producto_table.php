<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categoria_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['producto_id', 'categoria_id']);
        });

        // Migrar la categoría única actual (si existe) al pivot
        if (Schema::hasColumn('productos', 'categoria_id')) {
            $rows = DB::table('productos')->whereNotNull('categoria_id')->get(['id', 'categoria_id']);
            foreach ($rows as $row) {
                DB::table('categoria_producto')->insertOrIgnore([
                    'producto_id' => $row->id,
                    'categoria_id' => $row->categoria_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria_producto');
    }
};
