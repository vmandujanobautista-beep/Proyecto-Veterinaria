<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminUsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();

        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        if ($rol = $request->input('rol')) {
            $query->where('role', $rol);
        }

        if ($request->input('estado') === 'activo') {
            $query->where('activo', true);
        } elseif ($request->input('estado') === 'inactivo') {
            $query->where('activo', false);
        }

        $usuarios = $query->paginate(15)->withQueryString();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'     => ['required', 'in:admin,recepcionista'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'email.required'    => 'El correo es obligatorio.',
            'email.unique'      => 'Este correo ya está registrado.',
            'role.required'     => 'El rol es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'=> 'Las contraseñas no coinciden.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
            'activo'   => true,
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'  => ['required', 'in:admin,recepcionista'],
        ], [
            'name.required'  => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique'   => 'Este correo ya está registrado por otro usuario.',
            'role.required'  => 'El rol es obligatorio.',
        ]);

        // Protección: el admin no puede quitarse su propio rol de admin
        // si es el último administrador activo
        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            $otrosAdmins = User::where('role', 'admin')
                ->where('id', '!=', $user->id)
                ->where('activo', true)
                ->count();

            if ($otrosAdmins === 0) {
                return redirect()->back()
                    ->with('error', 'No puedes quitarte el rol de Administrador porque eres el único administrador activo del sistema.');
            }
        }

        $user->update($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggleActivo(Request $request, User $user)
    {
        // Protección: no puede desactivarse a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        // Si es el último admin activo, no se puede desactivar
        if ($user->role === 'admin' && $user->activo) {
            $otrosAdmins = User::where('role', 'admin')
                ->where('id', '!=', $user->id)
                ->where('activo', true)
                ->count();

            if ($otrosAdmins === 0) {
                return redirect()->back()
                    ->with('error', 'No puedes desactivar al único administrador activo del sistema.');
            }
        }

        $user->activo = ! $user->activo;
        $user->save();

        $accion = $user->activo ? 'activado' : 'desactivado';
        return redirect()->route('admin.usuarios.index')
            ->with('success', "Usuario {$accion} correctamente.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:8',
            'admin_password' => 'required|string',
        ], [
            'new_password.required' => 'La nueva contraseña es obligatoria.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'admin_password.required' => 'La contraseña de administrador es obligatoria.',
        ]);

        if ($request->admin_password !== 'PASSWORD') {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'La contraseña de administrador es incorrecta.'], 422);
            }
            return redirect()->back()->with('error', 'La contraseña de administrador es incorrecta.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        if ($request->wantsJson()) {
            session()->flash('success', "Contraseña restablecida correctamente para {$user->name}."); // Set flash so it appears after reload
            return response()->json(['success' => true, 'message' => "Contraseña restablecida correctamente para {$user->name}."]);
        }
        
        return redirect()->route('admin.usuarios.index')
            ->with('success', "Contraseña restablecida correctamente para {$user->name}.");
    }
}
