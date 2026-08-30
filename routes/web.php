<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// El registro público queda desactivado: los usuarios (Gerentes, Cajeros) los crea
// el Administrador General desde el módulo de Usuarios, no se auto-registran.
Auth::routes(['register' => false]);

// Cambiar el idioma de la interfaz (ej. /lang/en, /lang/es)
Route::get('/lang/{locale}', [App\Http\Controllers\LocaleController::class, 'switch'])->name('lang.switch');

#Johiel puntos
#Rutas para el panel de administración y gerention
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Todo lo del módulo de administración solo lo puede usar el Administrador General
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('usuarios', UsuarioController::class);
});