<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Idiomas disponibles en la aplicación
    |--------------------------------------------------------------------------
    |
    | El protocolo del proyecto exige, como mínimo, español e inglés. Para
    | agregar un idioma nuevo (ej. francés):
    |
    | 1. Corre: php artisan translations:generate fr
    |    (esto genera lang/fr.json traduciendo automáticamente los textos
    |    de la app mediante una API de traducción gratuita)
    | 2. Agrega la línea 'fr' => 'Français' aquí abajo.
    |
    | Con eso el idioma ya aparece en el selector de la interfaz.
    |
    */

    'disponibles' => [
        'es' => 'Español',
        'en' => 'English',
    ],

    #Johiel puntos
    #agregar tradución
    #php artisan optimize:clear
    #php artisan translations:generate en
];
