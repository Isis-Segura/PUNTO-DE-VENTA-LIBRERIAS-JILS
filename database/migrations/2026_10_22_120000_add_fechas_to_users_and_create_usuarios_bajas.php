<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Última vez que se puso inactivo / última vez que regresó (se reactivó).
            // La fecha de ingreso es terminos_aceptados_at (cuando aceptó y quedó activo).
            $table->timestamp('desactivado_at')->nullable()->after('terminos_aceptados_at');
            $table->timestamp('reactivado_at')->nullable()->after('desactivado_at');
        });

        // La migración anterior marcó a TODOS los usuarios existentes con la misma fecha
        // (el momento en que se corrió). Para esos usuarios la fecha de ingreso real es
        // la de su creación. Solo se corrige si esa fecha la comparten 2 o más usuarios
        // (así no se tocan las aceptaciones reales hechas desde la página de términos).
        $fechaMasiva = DB::table('users')->orderBy('id')->value('terminos_aceptados_at');

        if ($fechaMasiva && DB::table('users')->where('terminos_aceptados_at', $fechaMasiva)->count() >= 2) {
            DB::table('users')
                ->where('terminos_aceptados_at', $fechaMasiva)
                ->whereNotNull('created_at')
                ->update(['terminos_aceptados_at' => DB::raw('created_at')]);
        }

        // Historial de usuarios eliminados de forma permanente. Al borrarse de "users"
        // desaparecen de la lista, así que aquí se conserva su "salida definitiva".
        Schema::create('usuarios_bajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // id que tenía (ya no existe en users)
            $table->string('name');
            $table->string('email');
            $table->string('rol_slug', 50)->nullable();
            $table->string('rol_nombre', 100)->nullable();
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->string('sucursal_nombre', 150)->nullable();
            $table->timestamp('ingreso_at')->nullable();
            $table->timestamp('desactivado_at')->nullable();
            $table->timestamp('reactivado_at')->nullable();
            $table->timestamp('eliminado_at')->nullable();
            $table->string('eliminado_por', 150)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios_bajas');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['desactivado_at', 'reactivado_at']);
        });
    }
};
