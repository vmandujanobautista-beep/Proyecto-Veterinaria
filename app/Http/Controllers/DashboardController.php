<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString();

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

        $totalMascotas = Cache::remember('dashboard_total_mascotas', 60, fn () =>
            Mascota::count()
        );

        $mesActual = now()->format('Y-m');

        $clientesEsteMes = Cache::remember("dashboard_clientes_mes_{$mesActual}", 60, fn () =>
            Cliente::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count()
        );

        $mascotasEsteMes = Cache::remember("dashboard_mascotas_mes_{$mesActual}", 60, fn () =>
            Mascota::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count()
        );

        $proximasCitas = Cita::with(['mascota:id,nombre,especie', 'cliente:id,nombre,apellido,telefono'])
            ->select(['id', 'fecha', 'hora', 'tipo_servicio', 'estado', 'cliente_id', 'mascota_id'])
            ->whereDate('fecha', '>=', $hoy)
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(6)
            ->get();

        $ultimosClientes = Cliente::select(['id', 'nombre', 'apellido', 'email', 'telefono', 'created_at'])
            ->withCount('mascotas')
            ->latest()
            ->limit(5)
            ->get();

        // Estadisticas adicionales para el Administrador
        $adminStats = null;
        if (auth()->user()->isAdmin()) {
            $adminStats = [
                'ventas_mes' => Cache::remember("admin_ventas_mes_{$mesActual}", 60, fn () =>
                    Venta::whereYear('created_at', now()->year)
                        ->whereMonth('created_at', now()->month)
                        ->where('estado', '!=', 'cancelada')
                        ->sum('total')
                ),
                'citas_canceladas' => Cache::remember("admin_citas_canceladas_{$mesActual}", 60, fn () =>
                    Cita::whereYear('created_at', now()->year)
                        ->whereMonth('created_at', now()->month)
                        ->where('estado', 'cancelada')
                        ->count()
                ),
                'productos_bajo_stock' => Cache::remember('admin_productos_bajo_stock', 60, fn () =>
                    Producto::where('stock', '<=', 5)->count()
                ),
                'total_usuarios' => Cache::remember('admin_total_usuarios', 60, fn () =>
                    User::count()
                ),
                'usuarios_activos' => Cache::remember('admin_usuarios_activos', 60, fn () =>
                    User::where('activo', true)->count()
                ),
                'productos_bajo' => Producto::where('stock', '<=', 5)
                    ->select(['id', 'nombre', 'stock', 'categoria'])
                    ->orderBy('stock')
                    ->limit(5)
                    ->get(),
                'actividad_reciente' => User::select(['id', 'name', 'role', 'last_login_at', 'activo'])
                    ->orderByDesc('last_login_at')
                    ->limit(5)
                    ->get(),
            ];
        }

        return view('dashboard', compact(
            'totalClientes',
            'clientesEsteMes',
            'totalMascotas',
            'mascotasEsteMes',
            'citasHoy',
            'ventasHoy',
            'proximasCitas',
            'ultimosClientes',
            'adminStats'
        ));
    }
}
