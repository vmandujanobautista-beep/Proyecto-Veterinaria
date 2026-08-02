<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with(['cliente:id,nombre,apellido', 'mascota:id,nombre', 'user:id,name', 'ventaProductos.producto:id,nombre,precio'])
                      ->select(['id', 'cliente_id', 'mascota_id', 'user_id', 'metodo_pago', 'estado', 'total', 'created_at'])
                      ->latest();

        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->whereHas('cliente', fn ($c) =>
                    $c->where('nombre',   'like', "%{$buscar}%")
                      ->orWhere('apellido','like', "%{$buscar}%")
                )->orWhereHas('mascota', fn ($m) =>
                    $m->where('nombre', 'like', "%{$buscar}%")
                );
            });
        }

        if ($metodo = $request->input('metodo_pago')) {
            $query->where('metodo_pago', $metodo);
        }

        if ($fecha = $request->input('fecha')) {
            $query->whereDate('created_at', $fecha);
        }

        $ventas = $query->paginate(10)->withQueryString();

        // KPIs del encabezado — agrupados para reducir queries
        $hoy = now()->toDateString();

        $kpisHoy = Venta::selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as sum')
                        ->whereDate('created_at', $hoy)
                        ->first();

        $kpisMes = Venta::selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as sum')
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->first();

        $totalHoy     = $kpisHoy->sum  ?? 0;
        $ventasHoy    = $kpisHoy->count ?? 0;
        $totalMes     = $kpisMes->sum  ?? 0;
        $ventasMes    = $kpisMes->count ?? 0;
        $totalGeneral = Venta::sum('total');
        $totalVentas  = Venta::count();

        return view('ventas.index', compact(
            'ventas',
            'totalHoy', 'ventasHoy',
            'totalMes', 'ventasMes',
            'totalGeneral', 'totalVentas'
        ));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $mascotas = Mascota::with('cliente')->orderBy('nombre')->get();
        $productos = Producto::where('stock', '>', 0)->orderBy('nombre')->get();
        return view('ventas.create', compact('clientes', 'mascotas', 'productos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id'                    => ['required', 'exists:clientes,id'],
            'mascota_id'                    => ['nullable', 'exists:mascotas,id'],
            'metodo_pago'                   => ['required', 'string', 'max:100'],
            'estado'                        => ['nullable', 'string', 'in:pendiente,completada,cancelada'],
            'total'                         => ['required', 'numeric', 'min:0'],
            'productos'                     => ['required', 'array', 'min:1'],
            'productos.*.producto_id'       => ['required', 'exists:productos,id'],
            'productos.*.cantidad'          => ['required', 'integer', 'min:1'],
            'productos.*.precio_unitario'   => ['required', 'numeric', 'min:0'],
        ]);

        DB::beginTransaction();

        try {
            $venta = Venta::create([
                'cliente_id'  => $data['cliente_id'],
                'mascota_id'  => $data['mascota_id'] ?? null,
                'user_id'     => Auth::id(),
                'metodo_pago' => $data['metodo_pago'],
                'estado'      => $data['estado'] ?? 'completada',
                'total'       => $data['total'],
            ]);

            foreach ($data['productos'] as $item) {
                $producto = Producto::findOrFail($item['producto_id']);

                if ($producto->stock < $item['cantidad']) {
                    throw new \RuntimeException("Stock insuficiente para: {$producto->nombre}.");
                }

                $subtotal = $item['precio_unitario'] * $item['cantidad'];

                $venta->ventaProductos()->create([
                    'producto_id'     => $producto->id,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal'        => $subtotal,
                ]);

                $producto->decrement('stock', $item['cantidad']);
            }

            DB::commit();

            return redirect()->route('ventas.show', $venta)
                             ->with('success', 'Venta registrada correctamente.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'mascota', 'user', 'ventaProductos.producto']);
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        // Las ventas no se editan — redirigir a show
        return redirect()->route('ventas.show', $venta);
    }

    public function update(Request $request, Venta $venta)
    {
        $data = $request->validate([
            'estado'      => ['required', 'string', 'in:pendiente,completada,cancelada'],
            'metodo_pago' => ['required', 'string', 'max:100'],
        ]);

        $venta->update($data);

        return redirect()->route('ventas.show', $venta)
                         ->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();

        return redirect()->route('ventas.index')
                         ->with('success', 'Venta eliminada correctamente.');
    }
}
