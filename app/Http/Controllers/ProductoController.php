<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::latest();

        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre',      'like', "%{$buscar}%")
                  ->orWhere('codigo',     'like', "%{$buscar}%")
                  ->orWhere('descripcion','like', "%{$buscar}%");
            });
        }

        if ($categoria = $request->input('categoria')) {
            $query->where('categoria', $categoria);
        }

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
}
