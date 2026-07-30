<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Mascota;
use Illuminate\Http\Request;

class MascotaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mascota::with('cliente')->latest();

        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('raza',  'like', "%{$buscar}%");
            });
        }

        if ($especie = $request->input('especie')) {
            $query->where('especie', $especie);
        }

        $mascotas = $query->paginate(10)->withQueryString();

        return view('mascotas.index', compact('mascotas'));
    }

    public function create(Request $request)
    {
        $clientes = Cliente::orderBy('nombre')->get();
        return view('mascotas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'          => ['required', 'string', 'max:255'],
            'especie'         => ['nullable', 'string', 'max:100'],
            'raza'            => ['nullable', 'string', 'max:100'],
            'sexo'            => ['nullable', 'string', 'max:20'],
            'peso'            => ['nullable', 'numeric', 'min:0'],
            'fecha_nacimiento'=> ['nullable', 'date'],
            'nota_medica'     => ['nullable', 'string'],
            'cliente_id'      => ['required', 'exists:clientes,id'],
        ]);

        Mascota::create($data);

        return redirect()->route('mascotas.index')
                         ->with('success', 'Mascota registrada correctamente.');
    }

    public function show(Mascota $mascota)
    {
        $mascota->load(['cliente', 'citas']);
        return view('mascotas.show', compact('mascota'));
    }

    public function edit(Mascota $mascota)
    {
        $mascota->load('citas');
        $clientes = Cliente::orderBy('nombre')->get();
        return view('mascotas.edit', compact('mascota', 'clientes'));
    }

    public function update(Request $request, Mascota $mascota)
    {
        $data = $request->validate([
            'nombre'          => ['required', 'string', 'max:255'],
            'especie'         => ['nullable', 'string', 'max:100'],
            'raza'            => ['nullable', 'string', 'max:100'],
            'sexo'            => ['nullable', 'string', 'max:20'],
            'peso'            => ['nullable', 'numeric', 'min:0'],
            'fecha_nacimiento'=> ['nullable', 'date'],
            'nota_medica'     => ['nullable', 'string'],
            'cliente_id'      => ['required', 'exists:clientes,id'],
        ]);

        $mascota->update($data);

        return redirect()->route('mascotas.index')
                         ->with('success', 'Mascota actualizada correctamente.');
    }

    public function destroy(Mascota $mascota)
    {
        $mascota->delete();

        return redirect()->route('mascotas.index')
                         ->with('success', 'Mascota eliminada correctamente.');
    }
}
