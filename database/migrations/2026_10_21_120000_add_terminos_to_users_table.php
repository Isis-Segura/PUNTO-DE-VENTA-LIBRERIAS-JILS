<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Fecha en la que el usuario aceptó los Términos y Condiciones en la página
            // a la que lleva el correo de bienvenida. NULL = todavía no los acepta.
            $table->timestamp('terminos_aceptados_at')->nullable()->after('activo');

            // Contraseña inicial asignada por el administrador, guardada CIFRADA
            // (cast "encrypted" del modelo, usa APP_KEY). La página de términos la
            // muestra una vez aceptados; se borra cuando el usuario cambia su contraseña.
            $table->text('credenciales_pendientes')->nullable()->after('terminos_aceptados_at');
        });

        // Los usuarios que ya existían antes de este cambio no deben quedar
        // como "pendientes de aceptar términos".
        DB::table('users')->update(['terminos_aceptados_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['terminos_aceptados_at', 'credenciales_pendientes']);
        });
    }
};
