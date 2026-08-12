<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Mascota;
use App\Rules\ValidStrictEmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Events\ClienteCreado;
use App\Events\ClienteEditado;
use App\Events\ClienteEliminado;
class ClienteController extends Controller
{
    // ══════════════════════════════════════════════════════════
    //  CRUD ESTÁNDAR
    // ══════════════════════════════════════════════════════════

    public function index(Request $request)
    {
        $query = Cliente::withCount('mascotas')->latest();

        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre',           'like', "%{$buscar}%")
                  ->orWhere('apellido',        'like', "%{$buscar}%")
                  ->orWhere('apellido_paterno','like', "%{$buscar}%")
                  ->orWhere('apellido_materno','like', "%{$buscar}%")
                  ->orWhere('email',           'like', "%{$buscar}%")
                  ->orWhere('telefono',        'like', "%{$buscar}%");
            });
        }

        $clientes = $query->paginate(10)->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'           => ['required', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'apellido_paterno' => ['required', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'apellido_materno' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'apellido'         => ['nullable', 'string', 'max:255'],
            'email'            => ['nullable', 'string', 'max:255', 'unique:clientes,email', new ValidStrictEmailRule()],
            'telefono'         => ['required', 'string', 'max:20'],
            'codigo_pais'      => ['nullable', 'string', 'max:10'],
            'direccion'        => ['nullable', 'string', 'max:500'],
            'codigo_postal'    => ['nullable', 'string', 'max:10'],
            'estado'           => ['nullable', 'in:activo,inactivo'],
            'mascotas'         => ['nullable'],
        ], [
            'nombre.required'           => 'El nombre es obligatorio.',
            'nombre.regex'              => 'El nombre solo puede contener letras y espacios.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_paterno.regex'    => 'El apellido paterno solo puede contener letras y espacios.',
            'apellido_materno.regex'    => 'El apellido materno solo puede contener letras y espacios.',
            'email.unique'              => 'Este correo electrónico ya está registrado en el sistema por otro usuario o cliente.',
            'telefono.required'         => 'El teléfono es obligatorio.',
        ]);

        $cliente = DB::transaction(function () use ($validated, $request) {
            $cliente = Cliente::create([
                'nombre'           => $validated['nombre'],
                'apellido_paterno' => $validated['apellido_paterno'],
                'apellido_materno' => $validated['apellido_materno'] ?? null,
                'apellido'         => $validated['apellido_paterno'],
                'email'            => $validated['email'] ?? null,
                'telefono'         => $validated['telefono'],
                'codigo_pais'      => $validated['codigo_pais'] ?? '+52',
                'direccion'        => $validated['direccion'] ?? null,
                'codigo_postal'    => $validated['codigo_postal'] ?? null,
                'estado'           => $validated['estado'] ?? 'activo',
            ]);

            $mascotasData = $request->input('mascotas');
            if (is_string($mascotasData)) {
                $mascotasData = json_decode($mascotasData, true);
            }

            if (is_array($mascotasData) && count($mascotasData) > 0) {
                foreach ($mascotasData as $m) {
                    if (!empty($m['nombre']) && !empty($m['especie'])) {
                        // buildMascotaData() centraliza la construcción del array — DRY
                        $cliente->mascotas()->create($this->buildMascotaData($m));
                    }
                }
            }

            return $cliente;
        });

        event(new ClienteCreado($cliente));

        if ($request->expectsJson() || $request->ajax()) {
            $cliente->load('mascotas');
            return response()->json([
                'success'  => true,
                'message'  => 'Cliente agregado correctamente.',
                'cliente'  => [
                    'id'             => $cliente->id,
                    'nombre'         => $cliente->nombre,
                    'apellido_paterno' => $cliente->apellido_paterno,
                    'apellido_materno' => $cliente->apellido_materno,
                    'email'          => $cliente->email,
                    'telefono'       => $cliente->telefono,
                    'codigo_pais'    => $cliente->codigo_pais,
                    'estado'         => $cliente->estado,
                    'mascotas_count' => $cliente->mascotas->count(),
                    'created_at'     => $cliente->created_at->format('d/m/Y'),
                ],
            ], 201);
        }

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente registrado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        // Cargar solo las relaciones y columnas que se usan en el modal
        $cliente->load([
            'mascotas:id,nombre,especie,raza,sexo,peso,fecha_nacimiento,color_pelaje,nota_medica,cliente_id',
        ]);
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'cliente' => [
                    'id'               => $cliente->id,
                    'nombre'           => $cliente->nombre,
                    'apellido_paterno' => $cliente->apellido_paterno,
                    'apellido_materno' => $cliente->apellido_materno,
                    'apellido'         => $cliente->apellido,
                    'email'            => $cliente->email,
                    'telefono'         => $cliente->telefono,
                    'codigo_pais'      => $cliente->codigo_pais,
                    'direccion'        => $cliente->direccion,
                    'codigo_postal'    => $cliente->codigo_postal,
                    'estado'           => $cliente->estado,
                    'foto'             => $cliente->foto,
                    'created_at'       => $cliente->created_at->format('d/m/Y'),
                    'mascotas'         => $cliente->mascotas->map(function($m) {
                        return [
                            'id'               => $m->id,
                            'nombre'           => $m->nombre,
                            'especie'          => $m->especie,
                            'raza'             => $m->raza,
                            'sexo'             => $m->sexo,
                            'peso'             => $m->peso,
                            'fecha_nacimiento' => $m->fecha_nacimiento,
                            'color_pelaje'     => $m->color_pelaje,
                            'nota_medica'      => $m->nota_medica,
                        ];
                    })
                ]
            ]);
        }

        // Si la petición no es AJAX, redirigimos al index
        return redirect()->route('clientes.index');
    }

    public function edit(Cliente $cliente)
    {
        $cliente->load('mascotas');
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'apellido_paterno' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'apellido_materno' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'apellido'         => ['nullable', 'string', 'max:255'],
            'email'            => [
                'nullable', 
                'string', 
                'max:255', 
                \Illuminate\Validation\Rule::unique('clientes')->ignore($cliente->id), 
                new ValidStrictEmailRule()
            ],
            'telefono'         => ['nullable', 'string', 'max:20'],
            'codigo_pais'      => ['nullable', 'string', 'max:10'],
            'direccion'        => ['nullable', 'string', 'max:500'],
            'codigo_postal'    => ['nullable', 'string', 'max:10'],
            'estado'           => ['nullable', 'in:activo,inactivo'],
        ], [
            'nombre.required'           => 'El nombre es obligatorio.',
            'nombre.regex'              => 'El nombre solo puede contener letras y espacios.',
            'apellido_paterno.regex'    => 'El apellido paterno solo puede contener letras y espacios.',
            'apellido_materno.regex'    => 'El apellido materno solo puede contener letras y espacios.',
            'email.unique'              => 'Este correo electrónico ya está registrado en el sistema por otro usuario o cliente.',
        ]);

        if (!empty($data['apellido_paterno'])) {
            $data['apellido'] = $data['apellido_paterno'];
        }

        $cliente->update($data);

        // Actualización atómica de mascotas si vienen en el request
        $mascotasData = $request->input('mascotas');
        if (is_string($mascotasData)) {
            $mascotasData = json_decode($mascotasData, true);
        }

        if (is_array($mascotasData)) {
            $existingIds = $cliente->mascotas()->pluck('id')->toArray();
            $keptIds = [];

            foreach ($mascotasData as $m) {
                if (!empty($m['nombre']) && !empty($m['especie'])) {
                    if (!empty($m['id'])) {
                        // Actualizar existente
                        $mascota = Mascota::find($m['id']);
                        if ($mascota && $mascota->cliente_id === $cliente->id) {
                            $mascota->update($this->buildMascotaData($m));
                            $keptIds[] = $mascota->id;
                        }
                    } else {
                        // Crear nueva
                        $newMascota = $cliente->mascotas()->create($this->buildMascotaData($m));
                        $keptIds[] = $newMascota->id;
                    }
                }
            }

            // Eliminar las que ya no están en la lista (fueron borradas en el modal)
            $toDelete = array_diff($existingIds, $keptIds);
            if (!empty($toDelete)) {
                Mascota::whereIn('id', $toDelete)->delete();
            }
        }

        event(new ClienteEditado($cliente));

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cliente actualizado correctamente.',
            ]);
        }

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        // Eliminar foto si existe
        if ($cliente->foto && Storage::disk('public')->exists($cliente->foto)) {
            Storage::disk('public')->delete($cliente->foto);
        }

        $cliente->delete();

        event(new ClienteEliminado($cliente->id));

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente eliminado correctamente.');
    }

    // ══════════════════════════════════════════════════════════
    //  ENDPOINTS AJAX — MODAL
    // ══════════════════════════════════════════════════════════

    /**
     * POST /clientes/modal-store
     * Crea un nuevo cliente vía fetch (FormData) desde el modal Alpine.js.
     * Devuelve JSON.
     */
    public function storeModal(Request $request)
    {
        return $this->store($request);
    }

    /**
     * POST /clientes/{cliente}/mascotas-modal
     * Agrega una mascota al cliente vía fetch desde el sub-modal.
     * Devuelve JSON con la mascota creada.
     */
    public function storeMascotaModal(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre'           => ['required', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'especie'          => ['required', 'string', 'max:50'],
            'raza'             => ['nullable', 'string', 'max:100'],
            'sexo'             => ['nullable', 'in:macho,hembra'],
            'peso'             => ['nullable', 'numeric', 'min:0', 'max:999'],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
            'color_pelaje'     => ['nullable', 'string', 'max:100'],
            'nota_medica'      => ['nullable', 'string', 'max:2000'],
            'foto'             => ['nullable', 'image', 'max:2048'],
        ], [
            'nombre.required' => 'El nombre de la mascota es obligatorio.',
            'nombre.regex'    => 'El nombre solo puede contener letras y espacios.',
            'especie.required'=> 'La especie es obligatoria.',
            'sexo.in'         => 'El sexo debe ser Macho o Hembra.',
            'peso.numeric'    => 'El peso debe ser un número.',
            'foto.image'      => 'La foto debe ser una imagen.',
            'foto.max'        => 'La foto no debe superar 2 MB.',
        ]);

        // Guardar foto de mascota
        $fotoPath = null;
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $fotoPath = $request->file('foto')->store('mascotas/fotos', 'public');
        }

        $mascota = Mascota::create([
            'nombre'           => $validated['nombre'],
            'especie'          => $validated['especie'],
            'raza'             => $validated['raza'] ?? null,
            'sexo'             => $validated['sexo'] ?? null,
            'peso'             => $validated['peso'] ?? null,
            'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
            'color_pelaje'     => $validated['color_pelaje'] ?? null,
            'nota_medica'      => $validated['nota_medica'] ?? null,
            'foto'             => $fotoPath,
            'cliente_id'       => $cliente->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mascota agregada correctamente.',
            'mascota' => [
                'id'               => $mascota->id,
                'nombre'           => $mascota->nombre,
                'especie'          => $mascota->especie,
                'raza'             => $mascota->raza,
                'sexo'             => $mascota->sexo,
                'color_pelaje'     => $mascota->color_pelaje,
                'fecha_nacimiento' => $mascota->fecha_nacimiento?->format('d/m/Y'),
                'foto_url'         => $fotoPath ? asset('storage/' . $fotoPath) : null,
            ],
        ], 201);
    }

    // ══════════════════════════════════════════════════════════
    //  HELPERS PRIVADOS
    // ══════════════════════════════════════════════════════════

    /**
     * Construye el array de datos de mascota a partir del input del request.
     * Centraliza los 3 bloques idénticos que existían en store, update y storeMascotaModal.
     */
    private function buildMascotaData(array $m): array
    {
        return [
            'nombre'           => trim($m['nombre']),
            'especie'          => $m['especie'],
            'raza'             => $m['raza'] ?? null,
            'sexo'             => !empty($m['sexo']) ? strtolower($m['sexo']) : null,
            'peso'             => !empty($m['peso']) ? floatval($m['peso']) : null,
            'fecha_nacimiento' => !empty($m['fecha_nacimiento']) ? $m['fecha_nacimiento'] : null,
            'color_pelaje'     => $m['color_pelaje'] ?? null,
            'nota_medica'      => $m['nota_medica'] ?? null,
        ];
    }
}
