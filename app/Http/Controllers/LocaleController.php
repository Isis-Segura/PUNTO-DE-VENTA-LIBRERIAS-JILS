<?php

namespace App\Http\Controllers;

class LocaleController extends Controller
{
    /**
     * Cambia el idioma activo y regresa a la página anterior.
     * Ej: GET /lang/en, GET /lang/es
     */
    public function switch(string $locale)
    {
        $disponibles = array_keys(config('idiomas.disponibles', []));

        if (in_array($locale, $disponibles, true)) {
            session(['locale' => $locale]);
        }

        return back();
    }
}
