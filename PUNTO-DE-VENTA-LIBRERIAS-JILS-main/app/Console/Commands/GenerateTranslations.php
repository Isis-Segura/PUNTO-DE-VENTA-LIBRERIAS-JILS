<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class GenerateTranslations extends Command
{
    /**
     * php artisan translations:generate en
     * php artisan translations:generate fr
     *
     * Escanea todas las vistas .blade.php buscando textos dentro de __('...')
     * (el texto en español, ya que es el idioma en que están escritos los
     * archivos) y genera/actualiza lang/{locale}.json traduciendo cada frase
     * con la API gratuita de MyMemory. Así, para agregar un nuevo idioma a la
     * aplicación (además de español e inglés) basta con correr este comando
     * con el código de idioma deseado (fr, pt, de, it, etc.).
     */
    protected $signature = 'translations:generate
                            {locale : Código del idioma destino, ej. en, fr, pt}
                            {--source=es : Idioma en el que están escritos los textos originales}';

    protected $description = 'Genera el archivo lang/{locale}.json traduciendo automáticamente los textos de la app con una API de traducción';

    public function handle(): int
    {
        $locale = $this->argument('locale');
        $source = $this->option('source');

        $this->info("Buscando textos a traducir de '{$source}' hacia '{$locale}'...");

        $strings = $this->collectStrings();
        $strings = array_unique(array_merge($strings, $this->collectMenuLabels()));

        if (empty($strings)) {
            $this->warn('No se encontraron textos __(...) en las vistas.');
            return self::SUCCESS;
        }

        $langPath = lang_path("{$locale}.json");
        $existing = File::exists($langPath)
            ? json_decode(File::get($langPath), true) ?? []
            : [];

        $pendientes = array_values(array_diff($strings, array_keys($existing)));

        if (empty($pendientes)) {
            $this->info('No hay textos nuevos por traducir. Todo está actualizado.');
            return self::SUCCESS;
        }

        $this->info(count($pendientes).' textos nuevos por traducir...');
        $bar = $this->output->createProgressBar(count($pendientes));

        foreach ($pendientes as $texto) {
            $existing[$texto] = $this->traducir($texto, $source, $locale);
            $bar->advance();

            // Pausa breve para respetar el límite de la API gratuita
            usleep(300_000);
        }

        $bar->finish();
        $this->newLine();

        ksort($existing);
        File::put($langPath, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("Listo. Archivo actualizado: lang/{$locale}.json");

        return self::SUCCESS;
    }

    /**
     * Busca en todas las vistas blade (y controladores) los textos usados
     * dentro de __('...') o __("...") y regresa la lista sin duplicados.
     */
    private function collectStrings(): array
    {
        $strings = [];

        $rutas = [
            resource_path('views'),
            app_path(),
        ];

        // No escanear las vistas publicadas de AdminLTE: ese paquete ya trae
        // sus propias traducciones en es/en (lang/vendor/adminlte/...), y sus
        // __('adminlte::...') son claves con namespace, no texto literal.
        $excluir = resource_path('views/vendor');

        foreach ($rutas as $ruta) {
            if (! File::exists($ruta)) {
                continue;
            }

            $archivos = File::allFiles($ruta);

            foreach ($archivos as $archivo) {
                if (! in_array($archivo->getExtension(), ['php'])) {
                    continue;
                }

                if (str_starts_with($archivo->getPathname(), $excluir)) {
                    continue;
                }

                $contenido = File::get($archivo->getPathname());

                // Captura __('texto') y __("texto")
                preg_match_all("/__\(\s*'((?:[^'\\\\]|\\\\.)*)'\s*\)/", $contenido, $m1);
                preg_match_all('/__\(\s*"((?:[^"\\\\]|\\\\.)*)"\s*\)/', $contenido, $m2);

                foreach (array_merge($m1[1], $m2[1]) as $texto) {
                    $texto = stripslashes($texto);

                    // Ignorar claves con namespace (ej. 'adminlte::adminlte.login_message')
                    if ($texto === '' || str_contains($texto, '::')) {
                        continue;
                    }

                    $strings[$texto] = true;
                }
            }
        }

        return array_keys($strings);
    }

    /**
     * Recorre el menú del sidebar y del navbar (config/adminlte.php) y extrae
     * todos los textos ('text', 'header', 'label') para traducirlos también.
     * Así, cuando agreguen un módulo nuevo al menú (Sucursales, Productos,
     * Ventas, etc.), basta con volver a correr este comando para que su
     * nombre en el menú también quede traducido.
     */
    private function collectMenuLabels(): array
    {
        $labels = [];

        $this->extraerDeListaDeItems(config('adminlte.menu', []), $labels);
        $this->extraerDeListaDeItems(config('adminlte.usermenu.menu', []), $labels);

        return array_keys($labels);
    }

    /**
     * Recibe una lista de items de menú (array secuencial) y guarda en
     * $labels los textos encontrados. Entra recursivamente a 'submenu'
     * para cubrir dropdowns y sidebar treeview.
     */
    private function extraerDeListaDeItems($lista, array &$labels): void
    {
        if (! is_array($lista)) {
            return;
        }

        foreach ($lista as $item) {
            // Un header suelto del sidebar puede venir como string plano, ej. 'MENU'
            if (is_string($item)) {
                if ($item !== '') {
                    $labels[$item] = true;
                }
                continue;
            }

            if (! is_array($item)) {
                continue;
            }

            foreach (['text', 'header', 'label'] as $campo) {
                if (isset($item[$campo]) && is_string($item[$campo]) && $item[$campo] !== '' && ! str_contains($item[$campo], '::')) {
                    $labels[$item[$campo]] = true;
                }
            }

            // Submenús anidados (dropdowns del navbar, treeview del sidebar)
            if (isset($item['submenu']) && is_array($item['submenu'])) {
                $this->extraerDeListaDeItems($item['submenu'], $labels);
            }
        }
    }

    /**
     * Traduce un texto usando la API pública y gratuita de MyMemory.
     * No requiere API key. Si la API falla, se deja el texto original
     * para no romper la interfaz.
     */
    private function traducir(string $texto, string $origen, string $destino): string
    {
        try {
            $response = Http::timeout(10)
                ->withOptions(['verify' => false]) // WAMP/XAMPP en Windows no trae cacert.pem configurado por defecto
                ->get('https://api.mymemory.translated.net/get', [
                    'q' => $texto,
                    'langpair' => "{$origen}|{$destino}",
                ]);

            if ($response->successful()) {
                $traducido = $response->json('responseData.translatedText');

                if (! empty($traducido)) {
                    return $traducido;
                }
            }
        } catch (\Throwable $e) {
            $this->warn("No se pudo traducir: \"{$texto}\" ({$e->getMessage()})");
        }

        // Si algo falla, se deja el texto en el idioma original como respaldo
        return $texto;
    }
}
