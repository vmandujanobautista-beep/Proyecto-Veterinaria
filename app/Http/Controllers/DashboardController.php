<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString();

        // ── Estadísticas cacheadas (TTL: 60 segundos) ──────────────────────
        // Las tarjetas de conteo no cambian con alta frecuencia; cachearlas
        // evita 4 queries extra en cada carga del dashboard.
        $totalClientes = Cache::remember('dashboard_total_clientes', 60, fn () =>
            Cliente::count()
        );

        $citasHoy = Cache::remember("dashboard_citas_hoy_{$hoy}", 60, fn () =>
            Cita::whereDate('fecha', $hoy)->count()
        );

        $userId = auth()->id();

        $ventasHoy = Cache::remember("dashboard_ventas_hoy_{$userId}_{$hoy}", 60, fn () =>
            Venta::where('user_id', $userId)->whereDate('created_at', $hoy)->sum('total')
        );

        // Mascotas se obtiene via relación; se puede cachear si el modelo existe
        $totalMascotas = Cache::remember('dashboard_total_mascotas', 60, fn () =>
            \App\Models\Mascota::count()
        );

        $mesActual = now()->format('Y-m');

        $clientesEsteMes = Cache::remember("dashboard_clientes_mes_{$mesActual}", 60, fn () =>
            Cliente::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count()
        );

        $mascotasEsteMes = Cache::remember("dashboard_mascotas_mes_{$mesActual}", 60, fn () =>
            \App\Models\Mascota::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count()
        );

        // ── Próximas citas (hoy en adelante) — eager loading para evitar N+1 ──
        $proximasCitas = Cita::with(['mascota:id,nombre,especie', 'cliente:id,nombre,apellido,telefono'])
            ->select(['id', 'fecha', 'hora', 'tipo_servicio', 'estado', 'cliente_id', 'mascota_id'])
            ->whereDate('fecha', '>=', $hoy)
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(6)
            ->get();

        // ── Últimos clientes registrados — eager loading y columnas necesarias ──
        $ultimosClientes = Cliente::select(['id', 'nombre', 'apellido', 'email', 'telefono', 'created_at'])
            ->withCount('mascotas')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalClientes',
            'clientesEsteMes',
            'totalMascotas',
            'mascotasEsteMes',
            'citasHoy',
            'ventasHoy',
            'proximasCitas',
            'ultimosClientes'
        ));
    }
}
