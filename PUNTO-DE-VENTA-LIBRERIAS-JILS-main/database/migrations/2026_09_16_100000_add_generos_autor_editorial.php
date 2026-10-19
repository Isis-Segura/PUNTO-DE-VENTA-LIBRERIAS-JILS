<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('genero_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('genero_id')->constrained('generos')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['producto_id', 'genero_id']);
        });

        Schema::table('productos', function (Blueprint $table) {
            if (! Schema::hasColumn('productos', 'autor')) {
                $table->string('autor', 200)->nullable()->after('codigo');
            }
            if (! Schema::hasColumn('productos', 'editorial')) {
                $table->string('editorial', 200)->nullable()->after('autor');
            }
        });

        // Copiar nombres de categorías al catálogo de géneros (si existen)
        if (Schema::hasTable('categorias')) {
            $cats = DB::table('categorias')->get();
            foreach ($cats as $cat) {
                DB::table('generos')->insertOrIgnore([
                    'nombre' => $cat->nombre,
                    'descripcion' => $cat->descripcion ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Si había pivot categoria_producto, mapear a generos por nombre
        if (Schema::hasTable('categoria_producto')) {
            $rows = DB::table('categoria_producto')
                ->join('categorias', 'categorias.id', '=', 'categoria_producto.categoria_id')
                ->select('categoria_producto.producto_id', 'categorias.nombre')
                ->get();
            foreach ($rows as $row) {
                $generoId = DB::table('generos')->where('nombre', $row->nombre)->value('id');
                if ($generoId) {
                    DB::table('genero_producto')->insertOrIgnore([
                        'producto_id' => $row->producto_id,
                        'genero_id' => $generoId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            if (Schema::hasColumn('productos', 'editorial')) {
                $table->dropColumn('editorial');
            }
            if (Schema::hasColumn('productos', 'autor')) {
                $table->dropColumn('autor');
            }
        });
        Schema::dropIfExists('genero_producto');
        Schema::dropIfExists('generos');
    }
};
