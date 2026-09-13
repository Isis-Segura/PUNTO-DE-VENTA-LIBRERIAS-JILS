<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('direccion', 255)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('contacto', 150)->nullable(); // información de contacto adicional (email, encargado, etc.)
            $table->boolean('activa')->default(true); // "Estado" que pide el protocolo

            // Gerente responsable de la sucursal. Es opcional al crearla porque
            // el Admin puede crear la sucursal primero y asignarle gerente después.
            $table->foreignId('gerente_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sucursales');
    }
};
