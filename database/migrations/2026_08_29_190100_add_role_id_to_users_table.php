<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cada usuario pertenece a un rol (Administrador General, Gerente, Cajero)
            $table->foreignId('role_id')
                ->nullable()
                ->after('id')
                ->constrained('roles')
                ->nullOnDelete();

            // Cuando el usuario es Gerente o Cajero, queda ligado a una sucursal.
            // El Administrador General deja este campo en null (ve todas las sucursales).
            // NOTA: la columna sucursal_id se deja preparada desde ahora; la tabla
            // "sucursales" se agregará cuando desarrollen ese módulo. Por eso no se
            // define la llave foránea todavía, solo la columna.
            $table->unsignedBigInteger('sucursal_id')->nullable()->after('role_id');

            // Para poder desactivar un usuario sin borrarlo (ej. un cajero que ya no trabaja ahí)
            $table->boolean('activo')->default(true)->after('sucursal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn(['sucursal_id', 'activo']);
        });
    }
};
