<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            // Cada producto pertenece a una sucursal (cada sucursal administra
            // su propio catálogo de forma independiente, según el protocolo).
            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnDelete();

            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();

            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->string('codigo', 60)->nullable(); // SKU / código de barras (opcional)
            $table->decimal('precio', 10, 2);
            $table->string('imagen')->nullable(); // ruta de la imagen del producto
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
