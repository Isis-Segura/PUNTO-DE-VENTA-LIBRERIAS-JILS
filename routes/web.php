<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
#Johiel puntos
#Rutas para el panel de administración y gerention
Route::get('/home', [App\Http\Controllers\AdminController::class, 'index'])->name('home')->middleware('auth');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');