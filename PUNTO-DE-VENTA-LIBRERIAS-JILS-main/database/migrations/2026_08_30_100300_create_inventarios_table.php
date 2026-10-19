<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();

            // Un producto tiene un solo registro de inventario (relación 1 a 1).
            // Se separa de "productos" porque el protocolo pide "Inventario"
            // como elemento independiente en la base de datos.
            $table->foreignId('producto_id')->unique()->constrained('productos')->cascadeOnDelete();

            $table->unsignedInteger('cantidad')->default(0);
            $table->unsignedInteger('stock_minimo')->default(5); // para la alerta de "bajo inventario"

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
