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
    Route::resource('mascotas', MascotaController::class);
    Route::resource('citas', CitaController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('ventas', VentaController::class);

    Route::post('/citas/{cita}/confirmar', [CitaController::class, 'confirmar'])->name('citas.confirmar');
    Route::post('/citas/{cita}/confirmar-whatsapp', [CitaController::class, 'confirmarWhatsapp'])->name('citas.confirmar-whatsapp');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



