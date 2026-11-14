<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enlace de términos de un solo uso: "terminos_token" identifica el último enlace
        // enviado por correo y "terminos_enlace_usado_at" marca cuándo se gastó.
        Schema::table('users', function (Blueprint $table) {
            $table->string('terminos_token', 64)->nullable()->after('terminos_aceptados_at');
            $table->timestamp('terminos_enlace_usado_at')->nullable()->after('terminos_token');
        });

        // Solicitudes de un usuario para volver a ver los términos y condiciones.
        Schema::create('terms_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending | attended
            $table->foreignId('attended_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('attended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terms_requests');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['terminos_token', 'terminos_enlace_usado_at']);
        });
    }
};
