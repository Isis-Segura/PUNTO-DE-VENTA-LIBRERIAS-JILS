<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generos', function (Blueprint $table): void {
            $table->string('nombre_en', 150)->nullable()->after('nombre');
            $table->text('descripcion_en')->nullable()->after('descripcion');
        });

        $translations = [
            'Politíco' => ['Political', null],
            'Acción' => ['Action', 'Lots of adrenaline'],
            'Fantasías' => ['Fantasy', 'Magic and supernatural things'],
            'Comedia y Aventura' => [
                'Comedy and Adventure',
                'The plot revolves around chaotic and humorous situations.',
            ],
            'Romance' => [
                'Romance',
                'Novels centered on romantic relationships, often divided into historical, paranormal, and contemporary subgenres.',
            ],
            'Misterio y Crimen' => [
                'Mystery and Crime',
                'Includes noir, thriller, suspense, and detective fiction.',
            ],
        ];

        foreach ($translations as $spanish => [$english, $description]) {
            DB::table('generos')
                ->where('nombre', $spanish)
                ->update([
                    'nombre_en' => $english,
                    'descripcion_en' => $description,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('generos', function (Blueprint $table): void {
            $table->dropColumn(['nombre_en', 'descripcion_en']);
        });
    }
};