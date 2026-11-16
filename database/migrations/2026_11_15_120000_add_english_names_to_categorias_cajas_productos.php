<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table): void {
            $table->string('nombre_en', 150)->nullable()->after('nombre');
            $table->string('descripcion_en', 500)->nullable()->after('descripcion');
        });

        Schema::table('cajas', function (Blueprint $table): void {
            $table->string('nombre_en', 150)->nullable()->after('nombre');
            $table->string('descripcion_en', 255)->nullable()->after('descripcion');
        });

        Schema::table('productos', function (Blueprint $table): void {
            $table->string('nombre_en', 150)->nullable()->after('nombre');
            $table->string('descripcion_en', 50)->nullable()->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table): void {
            $table->dropColumn(['nombre_en', 'descripcion_en']);
        });

        Schema::table('cajas', function (Blueprint $table): void {
            $table->dropColumn(['nombre_en', 'descripcion_en']);
        });

        Schema::table('productos', function (Blueprint $table): void {
            $table->dropColumn(['nombre_en', 'descripcion_en']);
        });
    }
};
