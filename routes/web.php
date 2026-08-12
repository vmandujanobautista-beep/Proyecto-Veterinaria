<?php

use App\Http\Controllers\Admin\AdminConfiguracionController;
use App\Http\Controllers\Admin\AdminReportesController;
use App\Http\Controllers\Admin\AdminUsuarioController;
use App\Http\Controllers\AuthModalController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

// ── Modals de Autenticación (sin middleware — gestionados desde la landing) ──
Route::post('/auth/modal/register', [AuthModalController::class, 'register'])->name('modal.register');
Route::post('/auth/modal/verify-email', [AuthModalController::class, 'verifyEmail'])->name('modal.verify-email');
Route::post('/auth/modal/reset-password', [AuthModalController::class, 'resetPassword'])->name('modal.reset-password');


Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clientes', ClienteController::class);

    // Rutas AJAX para el modal "Nuevo Cliente"
    Route::post('/clientes/modal-store', [ClienteController::class, 'storeModal'])->name('clientes.modal-store');
    Route::post('/clientes/{cliente}/mascotas-modal', [ClienteController::class, 'storeMascotaModal'])->name('clientes.mascotas-modal');

    Route::resource('mascotas', MascotaController::class);
    Route::resource('citas', CitaController::class);
    Route::post('/productos/{producto}/solicitar-reabastecimiento', [ProductoController::class, 'solicitarReabastecimiento'])
         ->name('productos.solicitar-reabastecimiento');
    Route::get('/api/productos/next-sku', [ProductoController::class, 'getNextSku'])->name('api.productos.next-sku');
    Route::resource('productos', ProductoController::class);
    // Rutas API de ventas (deben ir ANTES del resource para evitar conflictos)
    Route::get('/api/ventas/productos',                 [VentaController::class, 'getProductos'])->name('api.ventas.productos');
    Route::get('/api/ventas/{cliente}/mascotas',        [VentaController::class, 'getMascotasCliente'])->name('api.ventas.mascotas');
    Route::resource('ventas', VentaController::class);
    Route::post('/ventas/{venta}/cancelar',             [VentaController::class, 'cancelar'])->name('ventas.cancelar');

    Route::post('/citas/{cita}/confirmar',        [CitaController::class, 'confirmar'])->name('citas.confirmar');
    Route::post('/citas/{cita}/notificar',        [CitaController::class, 'notificar'])->name('citas.notificar');
    Route::post('/confirmaciones/{confirmacion}/reintentar', [CitaController::class, 'reintentarConfirmacion'])->name('confirmaciones.reintentar');
    Route::post('/citas/{cita}/cancelar',          [CitaController::class, 'cancelar'])->name('citas.cancelar');
    Route::post('/citas/{cita}/completar',         [CitaController::class, 'completar'])->name('citas.completar');
    Route::get('/api/clientes/{cliente}/mascotas', [CitaController::class, 'mascotasPorCliente'])->name('api.clientes.mascotas');
    Route::get('/api/clientes',                    [CitaController::class, 'listarClientes'])->name('api.clientes.listar');

    // ── RUTAS EXCLUSIVAS DE ADMINISTRADOR ────────────────────────────────────
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        // Usuarios y Roles
        Route::get('/usuarios',                        [AdminUsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios',                       [AdminUsuarioController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{user}',                 [AdminUsuarioController::class, 'update'])->name('usuarios.update');
        Route::post('/usuarios/{user}/toggle-activo',  [AdminUsuarioController::class, 'toggleActivo'])->name('usuarios.toggle-activo');
        Route::post('/usuarios/{user}/reset-password', [AdminUsuarioController::class, 'resetPassword'])->name('usuarios.reset-password');

        // Configuración
        Route::get('/configuracion',   [AdminConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::post('/configuracion',  [AdminConfiguracionController::class, 'update'])->name('configuracion.update');

        // Reportes
        Route::get('/reportes',        [AdminReportesController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/datos',  [AdminReportesController::class, 'datos'])->name('reportes.datos');
        Route::get('/reportes/pdf',    [AdminReportesController::class, 'pdf'])->name('reportes.pdf');
    });
});

Route::middleware('auth')->group(function () {
    // Modal de Perfil — actualización vía JSON (Alpine.js fetch)
    Route::post('/perfil/actualizar', [ProfileController::class, 'actualizarPerfil'])->name('perfil.actualizar');
});

require __DIR__.'/auth.php';
