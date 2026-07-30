<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\Producto;
use App\Models\Venta;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString();

        // Tarjetas de estadísticas
        $totalClientes = Cliente::count();
        $totalMascotas = Mascota::count();
        $citasHoy      = Cita::whereDate('fecha', $hoy)->count();
        $ventasHoy     = Venta::whereDate('created_at', $hoy)->sum('total');

        // Próximas citas (hoy en adelante)
        $proximasCitas = Cita::with(['mascota', 'cliente'])
            ->whereDate('fecha', '>=', $hoy)
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(6)
            ->get();

        // Últimos clientes registrados
        $ultimosClientes = Cliente::withCount('mascotas')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalClientes',
            'totalMascotas',
            'citasHoy',
            'ventasHoy',
            'proximasCitas',
            'ultimosClientes'
        ));
    }
}
