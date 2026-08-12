<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Http\Request;

class AdminReportesController extends Controller
{
    public function index(Request $request)
    {
        // Periodo seleccionado (por defecto: este mes)
        $desde = $request->input('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->input('hasta', now()->toDateString());

        return view('admin.reportes.index', compact('desde', 'hasta'));
    }

    public function datos(Request $request)
    {
        $desde = $request->input('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->input('hasta', now()->toDateString());

        $datos = $this->getDatosReporte($desde, $hasta);

        return response()->json($datos);
    }

    public function pdf(Request $request)
    {
        $desde = $request->input('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->input('hasta', now()->toDateString());

        $datos = $this->getDatosReporte($desde, $hasta);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reportes.pdf', $datos);
        return $pdf->download('reporte-supervision-'.$desde.'-'.$hasta.'.pdf');
    }

    private function getDatosReporte($desde, $hasta)
    {
        // Ventas
        $ventasTotales     = Venta::whereBetween('created_at', [$desde, $hasta . ' 23:59:59'])
            ->where('estado', '!=', 'cancelada')
            ->sum('total');

        $ventasCount       = Venta::whereBetween('created_at', [$desde, $hasta . ' 23:59:59'])->count();

        $ventas            = Venta::with(['cliente', 'user'])
            ->whereBetween('created_at', [$desde, $hasta . ' 23:59:59'])
            ->latest()
            ->get()
            ->map(fn ($v) => [
                'id'       => $v->id,
                'cliente'  => $v->cliente ? $v->cliente->nombre . ' ' . ($v->cliente->apellido_paterno ?? $v->cliente->apellido) : '—',
                'usuario'  => $v->user ? $v->user->name : '—',
                'total'    => $v->total,
                'estado'   => $v->estado,
                'fecha'    => $v->created_at->format('d/m/Y H:i'),
            ]);

        // Citas
        $citasPorEstado    = Cita::whereBetween('fecha', [$desde, $hasta])
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        // Productos bajos
        $productosBajos    = Producto::where('stock', '<', 10)
            ->select(['id', 'nombre', 'codigo', 'stock', 'categoria'])
            ->orderBy('stock')
            ->get();

        // JOIN directo — elimina el whereHas + carga de modelos Venta intermedios
        $productosVendidos = \App\Models\VentaProducto::join('ventas', 'venta_productos.venta_id', '=', 'ventas.id')
            ->join('productos', 'venta_productos.producto_id', '=', 'productos.id')
            ->whereBetween('ventas.created_at', [$desde, $hasta . ' 23:59:59'])
            ->selectRaw('productos.nombre, SUM(venta_productos.cantidad) as total_vendido, SUM(venta_productos.subtotal) as total_ingresos')
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get()
            ->map(fn ($vp) => [
                'nombre'         => $vp->nombre,
                'total_vendido'  => $vp->total_vendido,
                'total_ingresos' => $vp->total_ingresos,
            ]);

        // Clientes y mascotas registrados en el periodo
        $clientesNuevos    = Cliente::whereBetween('created_at', [$desde, $hasta . ' 23:59:59'])->count();
        $mascotasNuevas    = Mascota::whereBetween('created_at', [$desde, $hasta . ' 23:59:59'])->count();

        // Actividad de usuarios
        $actividadUsuarios = User::select(['id', 'name', 'email', 'role', 'last_login_at', 'activo'])
            ->withCount([
                'ventas as ventas_periodo' => fn ($q) => $q->whereBetween('created_at', [$desde, $hasta . ' 23:59:59']),
                'citas as citas_periodo'   => fn ($q) => $q->whereBetween('fecha', [$desde, $hasta]),
            ])
            ->get();

        // Ventas por día del periodo (para gráfico de líneas del Dashboard)
        $ventasPorDia = Venta::whereBetween('created_at', [$desde, $hasta . ' 23:59:59'])
            ->where('estado', '!=', 'cancelada')
            ->selectRaw('DATE(created_at) as fecha, SUM(total) as total, COUNT(*) as cantidad')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->map(fn ($v) => [
                'fecha'    => $v->fecha,
                'total'    => (float) $v->total,
                'cantidad' => (int)   $v->cantidad,
            ]);

        // Clientes y mascotas de los últimos 6 meses (para gráfico de crecimiento)
        $clientesPorMes = Cliente::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $mascotasPorMes = Mascota::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Periodo anterior (misma duración) para variación de tarjetas
        $diasPeriodo            = \Carbon\Carbon::parse($desde)->diffInDays(\Carbon\Carbon::parse($hasta)) + 1;
        $antesDesde             = \Carbon\Carbon::parse($desde)->subDays($diasPeriodo)->toDateString();
        $antesHasta             = \Carbon\Carbon::parse($desde)->subDay()->toDateString();
        $ventasTotalesAnterior  = Venta::whereBetween('created_at', [$antesDesde, $antesHasta . ' 23:59:59'])->where('estado', '!=', 'cancelada')->sum('total');
        $clientesNuevosAnterior = Cliente::whereBetween('created_at', [$antesDesde, $antesHasta . ' 23:59:59'])->count();
        $mascotasNuevasAnterior = Mascota::whereBetween('created_at', [$antesDesde, $antesHasta . ' 23:59:59'])->count();
        $citasPeriodoActual     = Cita::whereBetween('fecha', [$desde, $hasta])->count();
        $citasPeriodoAnterior   = Cita::whereBetween('fecha', [$antesDesde, $antesHasta])->count();

        // Totales históricos (para las tarjetas de Clientes y Mascotas)
        $totalClientesGlobal  = Cliente::count();
        $totalMascotasGlobal  = Mascota::count();

        return compact(
            'ventasTotales',
            'ventasCount',
            'ventas',
            'citasPorEstado',
            'productosBajos',
            'productosVendidos',
            'clientesNuevos',
            'mascotasNuevas',
            'actividadUsuarios',
            'ventasPorDia',
            'clientesPorMes',
            'mascotasPorMes',
            'ventasTotalesAnterior',
            'clientesNuevosAnterior',
            'mascotasNuevasAnterior',
            'citasPeriodoActual',
            'citasPeriodoAnterior',
            'totalClientesGlobal',
            'totalMascotasGlobal',
            'desde',
            'hasta'
        );
    }
}
