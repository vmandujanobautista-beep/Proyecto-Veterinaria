<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Mascota;
use Illuminate\Http\Request;
use App\Events\MascotaCreada;
use App\Events\MascotaEditada;
use App\Events\MascotaEliminada;
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
        $clientes = Cliente::select('id', 'nombre', 'apellido', 'email')->orderBy('nombre')->get();
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

        $mascota = Mascota::create($data);

        event(new MascotaCreada($mascota));

        return redirect()->route('mascotas.index')
                         ->with('success', 'Mascota registrada correctamente.');
    }

    public function show(Mascota $mascota)
    {
        $mascota->load(['cliente', 'citas']);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'mascota' => [
                    'id'               => $mascota->id,
                    'nombre'           => $mascota->nombre,
                    'especie'          => $mascota->especie,
                    'raza'             => $mascota->raza,
                    'sexo'             => $mascota->sexo,
                    'color_pelaje'     => $mascota->color_pelaje,
                    'fecha_nacimiento' => $mascota->fecha_nacimiento ? $mascota->fecha_nacimiento->format('Y-m-d') : null,
                    'peso'             => $mascota->peso,
                    'nota_medica'      => $mascota->nota_medica,
                    'created_at'       => $mascota->created_at->format('d/m/Y'),
                    'cliente'          => $mascota->cliente ? [
                        'id'       => $mascota->cliente->id,
                        'nombre'   => $mascota->cliente->nombre,
                        'apellido' => $mascota->cliente->apellido,
                        'telefono' => $mascota->cliente->telefono,
                    ] : null,
                ]
            ]);
        }

        // Si la petición no es AJAX, redirigimos al index
        return redirect()->route('mascotas.index');
    }

    public function edit(Mascota $mascota)
    {
        $mascota->load('citas');
        $clientes = Cliente::select('id', 'nombre', 'apellido', 'email')->orderBy('nombre')->get();
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

        event(new MascotaEditada($mascota));

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mascota actualizada correctamente.'
            ]);
        }

        return redirect()->route('mascotas.index')
                         ->with('success', 'Mascota actualizada correctamente.');
    }

    public function destroy(Mascota $mascota)
    {
        $mascota->delete();

        event(new MascotaEliminada($mascota->id));

        return redirect()->route('mascotas.index')
                         ->with('success', 'Mascota eliminada correctamente.');
    }
}
