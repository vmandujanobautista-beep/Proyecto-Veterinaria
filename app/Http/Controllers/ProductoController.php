<?php

namespace App\Http\Controllers;

use App\Mail\SolicitudReabastecimiento;
use App\Models\Producto;
use App\Models\User;
use App\Notifications\SolicitudReabastecimientoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::latest();

        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre',      'like', "%{$buscar}%")
                  ->orWhere('codigo',     'like', "%{$buscar}%")
                  ->orWhere('categoria',  'like', "%{$buscar}%")
                  ->orWhere('descripcion','like', "%{$buscar}%");
            });
        }

        if ($categoria = $request->input('categoria')) {
            $query->where('categoria', $categoria);
        }

        // El filtro de stock fue removido a petición del usuario.

        $productos = $query->paginate(10)->withQueryString();

        // Productos con stock bajo para la alerta (solo columnas necesarias)
        $stockBajo = Producto::select(['id', 'nombre', 'codigo', 'stock'])
                             ->where('stock', '<=', 5)
                             ->orderBy('stock')
                             ->get();

        return view('productos.index', compact('productos', 'stockBajo'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => ['required', 'string', 'max:255'],
            'codigo'      => ['nullable', 'string', 'max:50', 'unique:productos,codigo'],
            'categoria'   => ['required', 'string', 'max:100'],
            'precio'      => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'descripcion' => ['nullable', 'string'],
        ]);

        Producto::create($data);

        return redirect()->route('productos.index')
                         ->with('success', 'Producto agregado al inventario.');
    }

    public function show(Producto $producto)
    {
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success'  => true,
                'producto' => [
                    'id'          => $producto->id,
                    'nombre'      => $producto->nombre,
                    'codigo'      => $producto->codigo,
                    'categoria'   => $producto->categoria,
                    'precio'      => $producto->precio,
                    'stock'       => $producto->stock,
                    'descripcion' => $producto->descripcion,
                    'created_at'  => $producto->created_at?->format('d/m/Y'),
                    'updated_at'  => $producto->updated_at?->format('d/m/Y H:i'),
                ],
            ]);
        }

        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre'      => ['required', 'string', 'max:255'],
            'codigo'      => ['nullable', 'string', 'max:50', 'unique:productos,codigo,' . $producto->id],
            'categoria'   => ['required', 'string', 'max:100'],
            'precio'      => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'descripcion' => ['nullable', 'string'],
        ]);

        $producto->update($data);

        return redirect()->route('productos.index')
                         ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
                         ->with('success', 'Producto eliminado del inventario.');
    }

    /**
     * Envía una solicitud de reabastecimiento por email a todos los admins.
     */
    public function solicitarReabastecimiento(Producto $producto)
    {
        // Solo permitir si el stock es bajo (< 10)
        if ($producto->stock >= 10) {
            return response()->json([
                'success' => false,
                'message' => 'Este producto no requiere reabastecimiento.',
            ], 422);
        }

        // Buscar todos los admins
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay administradores configurados para recibir notificaciones.',
            ], 422);
        }

        $solicitante = auth()->user();

        // Enviar notificación (Mail + Database) a todos los admins
        Notification::send($admins, new SolicitudReabastecimientoNotification($producto, $solicitante));

        $urgencia = $producto->stock === 0
            ? 'urgente (producto agotado)'
            : ($producto->stock <= 4 ? 'urgente' : '');

        return response()->json([
            'success' => true,
            'message' => "Solicitud {$urgencia} enviada a " . $admins->count() . " administrador(es) correctamente.",
            'admins'  => $admins->count(),
        ]);
    }

    public function getNextSku(Request $request)
    {
        $categoria = $request->query('categoria');
        if (!$categoria) {
            return response()->json(['sku' => '']);
        }

        $prefix = match($categoria) {
            'Medicamento' => 'MED',
            'Alimento'    => 'ALI',
            'Accesorio'   => 'ACC',
            'Higiene'     => 'HIG',
            'Vacuna'      => 'VAC',
            'Suplemento'  => 'SUP',
            default       => 'OTR',
        };

        $latestProduct = Producto::where('codigo', 'like', "{$prefix}-%")
            ->orderByRaw('CAST(SUBSTRING(codigo, 5) AS UNSIGNED) DESC')
            ->first();

        if ($latestProduct && preg_match("/{$prefix}-(\d+)/", $latestProduct->codigo, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        $nextSku = $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return response()->json(['sku' => $nextSku]);
    }
}
