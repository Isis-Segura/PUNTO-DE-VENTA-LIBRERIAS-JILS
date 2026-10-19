<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();

            // restrictOnDelete: no se puede borrar un producto si ya tiene ventas asociadas
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();

            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2); // precio del producto al momento de la venta
            $table->decimal('subtotal', 10, 2); // cantidad * precio_unitario

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
