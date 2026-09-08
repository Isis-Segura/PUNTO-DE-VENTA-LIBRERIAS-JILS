<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnDelete();

            // El cajero (o quien haya realizado la venta) que la registró.
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('metodo_pago_id')->constrained('metodos_pago')->restrictOnDelete();

            $table->string('folio', 30)->unique(); // número de ticket, ej. V-000001
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);

            $table->timestamps(); // created_at = fecha y hora de la venta
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
