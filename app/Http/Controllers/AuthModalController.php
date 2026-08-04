<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\ValidEmailRule;
use App\Rules\ValidNameRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthModalController extends Controller
{
    /** Contraseña de administrador requerida para crear y modificar usuarios */
    private const ADMIN_PASSWORD = 'PASSWORD';

    /**
     * Registra un nuevo usuario con rol 'recepcionista'.
     * Requiere la contraseña de administrador para proceder.
     */
    public function register(Request $request)
    {
        // Validar campos básicos
        $request->validate([
            'name'                  => ['required', 'string', 'max:255', new ValidNameRule],
            'email'                 => ['required', 'string', 'max:255', new ValidEmailRule, 'unique:users'],
            'password'              => [
                'required',
                'confirmed',
                Password::min(8),
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('email')) {
                        $existingUser = User::where('email', $request->email)->first();
                        if ($existingUser && Hash::check($value, $existingUser->password)) {
                            $fail('La contraseña no puede ser igual a la contraseña anterior del sistema.');
                        }
                    }
                }
            ],
            'admin_password'        => ['required', 'string'],
        ], [
            'name.required'         => 'El nombre completo es obligatorio.',
            'email.required'        => 'El correo electrónico es obligatorio.',
            'email.unique'          => 'Este correo ya está registrado en el sistema.',
            'password.required'     => 'La contraseña es obligatoria.',
            'password.confirmed'    => 'Las contraseñas no coinciden.',
            'password.min'          => 'La contraseña debe tener al menos 8 caracteres.',
            'admin_password.required' => 'La contraseña de administrador es obligatoria.',
        ]);

        // Verificar contraseña de administrador
        if ($request->admin_password !== self::ADMIN_PASSWORD) {
            return back()
                ->withErrors(['admin_password' => 'La contraseña de administrador es incorrecta.'])
                ->withInput($request->except('password', 'password_confirmation', 'admin_password'))
                ->with('open_modal', 'register');
        }

        // Crear el usuario con rol 'recepcionista'
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'recepcionista',
        ]);

        return redirect('/')
            ->with('success_modal', 'register')
            ->with('success_message', '¡Usuario registrado con éxito! Ahora puedes iniciar sesión.');
    }

    /**
     * Verifica si un correo electrónico existe en el sistema mediante AJAX.
     * Fase 1 del flujo de Recuperar Contraseña.
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', new ValidEmailRule],
        ]);

        $exists = User::where('email', $request->email)->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Restablece la contraseña de un usuario existente.
     * Requiere el email del usuario y la contraseña de administrador.
     * Fase 2 del flujo de Recuperar Contraseña.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'             => ['required', 'string', new ValidEmailRule, 'exists:users,email'],
            'admin_password'    => ['required', 'string'],
            'new_password'      => [
                'required',
                'confirmed',
                Password::min(8),
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('email')) {
                        $user = User::where('email', $request->email)->first();
                        if ($user && Hash::check($value, $user->password)) {
                            $fail('La nueva contraseña no puede ser igual a la contraseña actual.');
                        }
                    }
                }
            ],
        ], [
            'email.exists'              => 'No existe un usuario con ese correo.',
            'new_password.required'     => 'La nueva contraseña es obligatoria.',
            'new_password.confirmed'    => 'Las contraseñas no coinciden.',
            'new_password.min'          => 'La contraseña debe tener al menos 8 caracteres.',
            'admin_password.required'   => 'La contraseña de administrador es obligatoria.',
        ]);

        if ($request->admin_password !== self::ADMIN_PASSWORD) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => ['admin_password' => ['La contraseña de administrador es incorrecta.']]], 422);
            }
            return back()
                ->withErrors(['admin_password' => 'La contraseña de administrador es incorrecta.'])
                ->withInput($request->except('admin_password', 'new_password', 'new_password_confirmation'))
                ->with('open_modal', 'reset');
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->new_password)]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect('/')
            ->with('success_modal', 'reset_success')
            ->with('success_message', '¡Contraseña actualizada con éxito! Ahora puedes iniciar sesión con tu nueva contraseña.');
    }
}
