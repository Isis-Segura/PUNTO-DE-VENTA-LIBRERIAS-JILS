<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
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

// Sucursales: solo el Administrador General las crea/edita/elimina
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('sucursales', SucursalController::class)->except(['show']);
});

// Productos e Inventario: Admin (todas las sucursales) y Gerente (solo la suya)
Route::middleware(['auth', 'role:admin,gerente'])->group(function () {
    Route::resource('productos', ProductoController::class)->except(['show']);

    Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::put('inventario/{inventario}', [InventarioController::class, 'update'])->name('inventario.update');
});

// Ventas / Punto de venta: Admin, Gerente y Cajero (cada quien limitado a su sucursal)
Route::middleware(['auth', 'role:admin,gerente,cajero'])->group(function () {
    Route::get('ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::get('ventas/pos', [VentaController::class, 'create'])->name('ventas.create');
    Route::get('ventas/buscar-productos', [VentaController::class, 'buscarProductos'])->name('ventas.buscar');
    Route::post('ventas', [VentaController::class, 'store'])->name('ventas.store');
    Route::get('ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');
    Route::get('ventas/{venta}/recibo-digital', [VentaController::class, 'reciboDigital'])->name('ventas.recibo-digital');
});