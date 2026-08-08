<?php

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
    Route::resource('productos', ProductoController::class);
    // Rutas API de ventas (deben ir ANTES del resource para evitar conflictos)
    Route::get('/api/ventas/productos',                 [VentaController::class, 'getProductos'])->name('api.ventas.productos');
    Route::get('/api/ventas/{cliente}/mascotas',        [VentaController::class, 'getMascotasCliente'])->name('api.ventas.mascotas');
    Route::resource('ventas', VentaController::class);
    Route::post('/ventas/{venta}/cancelar',             [VentaController::class, 'cancelar'])->name('ventas.cancelar');

    Route::post('/citas/{cita}/confirmar',        [CitaController::class, 'confirmar'])->name('citas.confirmar');
    Route::post('/citas/{cita}/confirmar-whatsapp',[CitaController::class, 'confirmarWhatsapp'])->name('citas.confirmar-whatsapp');
    Route::post('/citas/{cita}/cancelar',          [CitaController::class, 'cancelar'])->name('citas.cancelar');
    Route::post('/citas/{cita}/completar',         [CitaController::class, 'completar'])->name('citas.completar');
    Route::post('/citas/{cita}/enviar-email',      [CitaController::class, 'enviarEmail'])->name('citas.enviar-email');
    Route::post('/citas/{cita}/enviar-whatsapp',   [CitaController::class, 'enviarWhatsapp'])->name('citas.enviar-whatsapp');
    Route::get('/api/clientes/{cliente}/mascotas', [CitaController::class, 'mascotasPorCliente'])->name('api.clientes.mascotas');
    Route::get('/api/clientes',                    [CitaController::class, 'listarClientes'])->name('api.clientes.listar');
});

Route::middleware('auth')->group(function () {
    // Las rutas de la página de perfil anterior se eliminan porque ahora usamos el Modal.

    // Modal de Perfil — actualización vía JSON (Alpine.js fetch)
    Route::post('/perfil/actualizar', [ProfileController::class, 'actualizarPerfil'])->name('perfil.actualizar');
});

require __DIR__.'/auth.php';



