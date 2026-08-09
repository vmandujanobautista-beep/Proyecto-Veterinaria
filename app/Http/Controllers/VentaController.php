<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\VentaProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\VentaCreada;
use App\Events\VentaEliminada;

class VentaController extends Controller
{
    /* ───────────────────────────── HISTORIAL ───────────────────────────── */

    public function index(Request $request)
    {
        $query = Venta::with([
                'cliente:id,nombre,apellido,apellido_paterno',
                'mascota:id,nombre',
                'user:id,name',
                'ventaProductos.producto:id,nombre,precio',
            ])
            ->where('user_id', auth()->id())
            ->select(['id', 'cliente_id', 'mascota_id', 'user_id', 'metodo_pago', 'estado', 'total', 'created_at'])
            ->latest();

        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->whereHas('cliente', fn ($c) =>
                    $c->where('nombre',   'like', "%{$buscar}%")
                      ->orWhere('apellido','like', "%{$buscar}%")
                      ->orWhere('apellido_paterno','like', "%{$buscar}%")
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

        // KPIs del encabezado
        $hoy     = now()->toDateString();
        $kpisHoy = Venta::selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as sum')
                        ->where('user_id', auth()->id())
                        ->whereDate('created_at', $hoy)->first();
        $kpisMes = Venta::selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as sum')
                        ->where('user_id', auth()->id())
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)->first();

        $totalHoy     = $kpisHoy->sum   ?? 0;
        $ventasHoy    = $kpisHoy->count ?? 0;
        $totalMes     = $kpisMes->sum   ?? 0;
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

    /* ─────────────────────────────── POS ─────────────────────────────── */

    public function create()
    {
        $clientes = Cliente::select('id', 'nombre', 'apellido', 'apellido_paterno')
                           ->orderBy('nombre')->get();

        $productos = Producto::orderBy('nombre')
                             ->paginate(15);

        return view('ventas.create', compact('clientes', 'productos'));
    }

    /* ─────────────────────────── GUARDAR VENTA ─────────────────────────── */

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id'                    => ['nullable', 'exists:clientes,id'],
            'mascota_id'                    => ['nullable', 'exists:mascotas,id'],
            'metodo_pago'                   => ['required', 'string', 'max:100'],
            'total'                         => ['required', 'numeric', 'min:0'],
            'productos'                     => ['required', 'array', 'min:1'],
            'productos.*.producto_id'       => ['required', 'exists:productos,id'],
            'productos.*.cantidad'          => ['required', 'integer', 'min:1'],
            'productos.*.precio_unitario'   => ['required', 'numeric', 'min:0'],
        ]);

        DB::beginTransaction();

        try {
            $venta = Venta::create([
                'cliente_id'  => $data['cliente_id'] ?? null,
                'mascota_id'  => $data['mascota_id'] ?? null,
                'user_id'     => Auth::id(),
                'metodo_pago' => $data['metodo_pago'],
                'estado'      => 'pagada',
                'total'       => $data['total'],
            ]);

            foreach ($data['productos'] as $item) {
                $producto = Producto::lockForUpdate()->findOrFail($item['producto_id']);

                if ($producto->stock < $item['cantidad']) {
                    throw new \RuntimeException("Stock insuficiente para: {$producto->nombre}.");
                }

                $venta->ventaProductos()->create([
                    'producto_id'     => $producto->id,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal'        => $item['precio_unitario'] * $item['cantidad'],
                ]);

                $producto->decrement('stock', $item['cantidad']);
            }

            DB::commit();

            event(new VentaCreada($venta));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Venta completada. Total: $' . number_format($data['total'], 2),
                    'folio'   => 'VNT-' . str_pad($venta->id, 3, '0', STR_PAD_LEFT),
                    'venta_id'=> $venta->id,
                ]);
            }

            return redirect()->route('ventas.show', $venta)
                             ->with('success', 'Venta registrada correctamente.');

        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /* ──────────────────────────── VER DETALLE ──────────────────────────── */

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'mascota', 'user', 'ventaProductos.producto']);

        if (request()->expectsJson()) {
            return response()->json([
                'venta'    => $venta,
                'cliente'  => $venta->cliente,
                'mascota'  => $venta->mascota,
                'user'     => $venta->user,
                'productos'=> $venta->ventaProductos->map(fn($vp) => [
                    'nombre'          => $vp->producto->nombre ?? '—',
                    'cantidad'        => $vp->cantidad,
                    'precio_unitario' => $vp->precio_unitario,
                    'subtotal'        => $vp->subtotal,
                ]),
                'folio'    => 'VNT-' . str_pad($venta->id, 3, '0', STR_PAD_LEFT),
            ]);
        }

        return view('ventas.show', compact('venta'));
    }

    /* ──────────────────────────── CANCELAR VENTA ────────────────────────── */

    public function cancelar(Venta $venta)
    {
        if ($venta->estado === 'cancelada') {
            return back()->withErrors(['error' => 'La venta ya está cancelada.']);
        }

        DB::beginTransaction();

        try {
            // Restaurar stock de cada producto
            foreach ($venta->ventaProductos()->with('producto')->get() as $vp) {
                if ($vp->producto) {
                    $vp->producto->increment('stock', $vp->cantidad);
                }
            }

            $venta->update(['estado' => 'cancelada']);

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Venta cancelada y stock restaurado.']);
            }

            return redirect()->route('ventas.index')
                             ->with('success', 'Venta cancelada y stock restaurado.');

        } catch (\Throwable $e) {
            DB::rollBack();

            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /* ─────────────────────────── API ENDPOINTS ─────────────────────────── */

    /**
     * GET /api/ventas/productos?buscar=&categoria=&page=
     */
    public function getProductos(Request $request)
    {
        $query = Producto::query();

        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");
            });
        }

        if ($categoria = $request->input('categoria')) {
            $query->where('categoria', $categoria);
        }

        $productos = $query->orderBy('nombre')
                           ->paginate(15)
                           ->through(fn ($p) => [
                               'id'        => $p->id,
                               'nombre'    => $p->nombre,
                               'codigo'    => $p->codigo,
                               'categoria' => $p->categoria,
                               'precio'    => (float) $p->precio,
                               'stock'     => $p->stock,
                           ]);

        return response()->json($productos);
    }

    /**
     * GET /api/ventas/{cliente}/mascotas
     */
    public function getMascotasCliente(Cliente $cliente)
    {
        $mascotas = $cliente->mascotas()
                            ->select('id', 'nombre', 'especie', 'raza')
                            ->orderBy('nombre')
                            ->get();

        return response()->json($mascotas);
    }

    /* ─────────────────────────── OTROS CRUD ────────────────────────────── */

    public function edit(Venta $venta)
    {
        return redirect()->route('ventas.show', $venta);
    }

    public function update(Request $request, Venta $venta)
    {
        $data = $request->validate([
            'estado'      => ['required', 'string', 'in:pendiente,pagada,cancelada'],
            'metodo_pago' => ['required', 'string', 'max:100'],
        ]);

        $venta->update($data);

        return redirect()->route('ventas.show', $venta)
                         ->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();

        event(new VentaEliminada($venta->id));

        return redirect()->route('ventas.index')
                         ->with('success', 'Venta eliminada correctamente.');
    }
}
