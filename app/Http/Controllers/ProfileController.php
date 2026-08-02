<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{

    /**
     * Actualiza el perfil completo del usuario autenticado (nombre, email,
     * datos personales y opcionalmente la contraseña) vía JSON.
     *
     * Reglas especiales:
     *   - La fecha de nacimiento solo se puede guardar si aún no está bloqueada.
     *   - Una vez guardada, se bloquea permanentemente (fecha_nacimiento_bloqueada = true).
     *   - La contraseña solo se procesa si se envía `current_password`.
     */
    public function actualizarPerfil(Request $request): JsonResponse
    {
        $user = $request->user();

        // ── Reglas base ────────────────────────────────────────────────────
        $rules = [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'telefono'  => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\+\-\(\)]+$/'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ];

        $messages = [
            'name.required'     => 'El nombre completo es obligatorio.',
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'El correo electrónico no es válido.',
            'email.unique'      => 'Este correo ya está en uso por otro usuario.',
            'telefono.regex'    => 'El teléfono solo puede contener números, espacios y los caracteres + - ( ).',
            'telefono.max'      => 'El teléfono no puede superar los 20 caracteres.',
            'direccion.max'     => 'La dirección no puede superar los 255 caracteres.',
        ];

        // ── Fecha de nacimiento: solo validar si aún no está bloqueada ─────
        if (! $user->fecha_nacimiento_bloqueada) {
            $rules['fecha_nacimiento'] = ['nullable', 'date', 'before_or_equal:today'];
            $messages['fecha_nacimiento.before_or_equal'] = 'La fecha de nacimiento no puede ser una fecha futura.';
            $messages['fecha_nacimiento.date']            = 'La fecha de nacimiento no es válida.';
        }



        // ── Validar ────────────────────────────────────────────────────────
        $validated = $request->validate($rules, $messages);

        // ── Aplicar cambios ────────────────────────────────────────────────
        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->telefono  = $validated['telefono']  ?? null;
        $user->direccion = $validated['direccion'] ?? null;

        // Fecha de nacimiento: guardar y bloquear en la primera vez
        if (! $user->fecha_nacimiento_bloqueada && isset($validated['fecha_nacimiento'])) {
            if ($validated['fecha_nacimiento']) {
                $user->fecha_nacimiento           = $validated['fecha_nacimiento'];
                $user->fecha_nacimiento_bloqueada = true;
            }
        }



        $user->save();

        return response()->json([
            'success' => true,
            'message' => '¡Perfil actualizado correctamente!',
        ]);
    }
}
