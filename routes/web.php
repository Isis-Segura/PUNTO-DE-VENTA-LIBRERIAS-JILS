<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\GeneroController;

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

// El dashboard general (con datos de TODAS las sucursales) es exclusivo del
// Administrador General.
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
});

// Módulo de Usuarios: el Administrador General administra a todos; el
// Gerente puede dar de alta/editar/eliminar cajeros de su propia sucursal
// (el propio controlador limita el alcance del Gerente).
Route::middleware(['auth', 'role:admin,gerente'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UsuarioController::class);
});

// Sucursales: el Administrador General las crea/edita/elimina.
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('sucursales', SucursalController::class)
        ->except(['show', 'index'])
        ->parameters(['sucursales' => 'sucursal']);
});

// Ver el listado de sucursales y el detalle de cada una (con su inventario):
// lo puede hacer el Administrador (todas) y el Gerente (solo la suya).
Route::middleware(['auth', 'role:admin,gerente'])->group(function () {
    Route::get('sucursales', [SucursalController::class, 'index'])->name('sucursales.index');
    Route::get('sucursales/{sucursal}', [SucursalController::class, 'show'])->name('sucursales.show');
});

// Categorías de productos: Admin y Gerente (compartidas entre sucursales).
// Antes CategoriaController estaba vacío y no tenía ninguna ruta registrada.
Route::middleware(['auth', 'role:admin,gerente'])->group(function () {
    Route::resource('categorias', CategoriaController::class);
});

// Productos: Admin (todas las sucursales) y Gerente (solo la suya).
Route::middleware(['auth', 'role:admin,gerente'])->group(function () {
    Route::resource('productos', ProductoController::class)->except(['show']);

    Route::put('inventario/{inventario}', [InventarioController::class, 'update'])->name('inventario.update');
});

// Ventas / Punto de venta: Admin, Gerente y Cajero
Route::middleware(['auth', 'role:admin,gerente,cajero'])->group(function () {
    Route::get('ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::get('ventas/pos', [VentaController::class, 'create'])->name('ventas.create');
    Route::get('ventas/buscar-productos', [VentaController::class, 'buscarProductos'])->name('ventas.buscar');
    Route::post('ventas', [VentaController::class, 'store'])->name('ventas.store');
    Route::get('ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');
    Route::get('ventas/{venta}/recibo-digital', [VentaController::class, 'reciboDigital'])->name('ventas.recibo-digital');
});
Route::post('ventas/simular-pago-tarjeta', [VentaController::class, 'simularPagoTarjeta'])
    ->name('ventas.simular-pago-tarjeta');

// Solo el Administrador puede borrar tickets del historial
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('ventas/{venta}', [VentaController::class, 'destroy'])->name('ventas.destroy');
});

// Módulo de Perfil: cambiar contraseña
Route::middleware('auth')->group(function () {
    Route::get('mi-cuenta/contrasena', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('mi-cuenta/contrasena', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('mi-cuenta/contrasena', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('mi-cuenta/contrasena', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('mi-cuenta/contrasena/ayuda', [ProfileController::class, 'requestPasswordHelp'])->name('profile.password.help');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('solicitudes-contrasena', [PasswordResetRequestController::class, 'index'])->name('password-requests.index');
    Route::post('solicitudes-contrasena/{passwordResetRequest}/atender', [PasswordResetRequestController::class, 'attend'])->name('password-requests.attend');
});

#Solicitudes de cambio de contraseña: solo el Administrador puede verlas y atenderlas
Route::middleware('auth')->group(function () {
    Route::get('mi-cuenta/contrasena', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('mi-cuenta/contrasena', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('mi-cuenta/contrasena/ayuda', [ProfileController::class, 'requestPasswordHelp'])->name('profile.password.help');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('solicitudes-contrasena', [PasswordResetRequestController::class, 'index'])->name('password-requests.index');
    Route::post('solicitudes-contrasena/{passwordResetRequest}/atender', [PasswordResetRequestController::class, 'attend'])->name('password-requests.attend');
});

// En el mismo grupo auth + role:admin,gerente que categorias:
Route::resource('generos', GeneroController::class);

Route::delete('admin/solicitudes-contrasena/{passwordResetRequest}', [PasswordResetRequestController::class, 'destroy'])
    ->name('admin.password-requests.destroy');