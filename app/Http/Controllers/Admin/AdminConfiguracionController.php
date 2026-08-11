<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminConfiguracionController extends Controller
{
    public function index()
    {
        $config = Configuracion::instancia();
        return view('admin.configuracion.index', compact('config'));
    }

    public function update(Request $request)
    {
        $config = Configuracion::instancia();

        $validated = $request->validate([
            'clinica_nombre'       => ['required', 'string', 'max:10', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'clinica_direccion'    => ['nullable', 'string', 'max:255'],
            'clinica_telefono'     => ['nullable', 'string', 'max:15', 'regex:/^\+[0-9]{1,14}$/'],
            'clinica_email'        => ['nullable', 'string', new \App\Rules\ValidEmailRule],
            'clinica_logo'         => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'horarios'             => ['nullable', 'string'],   // JSON string
            'servicios'            => ['nullable', 'string'],   // JSON string
            'metodos_pago'         => ['nullable', 'array'],
            'metodos_pago.*'       => ['string', 'max:60'],
            'mensaje_confirmacion' => ['nullable', 'string', 'max:1000'],
            'mensaje_whatsapp'     => ['nullable', 'string', 'max:1000'],
        ], [
            'clinica_nombre.required' => 'El nombre de la clínica es obligatorio.',
            'clinica_nombre.max'      => 'El nombre no puede tener más de 10 caracteres.',
            'clinica_nombre.regex'    => 'El nombre solo puede contener letras y espacios.',
            'clinica_telefono.regex'  => 'El teléfono debe comenzar con el código de país (ej. +52) seguido de números.',
            'clinica_telefono.max'    => 'El teléfono no puede tener más de 15 caracteres en total.',
            'clinica_email.email'     => 'El correo de la clínica debe ser una dirección válida.',
            'clinica_logo.image'      => 'El logo debe ser una imagen válida.',
            'clinica_logo.max'        => 'El logo no puede superar 2 MB.',
        ]);

        // Manejo del logo
        // Convertir strings JSON a arrays para que el cast del modelo funcione correctamente
        if (isset($validated['horarios'])) {
            $validated['horarios'] = json_decode($validated['horarios'], true);
        }
        if (isset($validated['servicios'])) {
            $validated['servicios'] = json_decode($validated['servicios'], true);
        }

        if ($request->hasFile('clinica_logo')) {
            if ($config->clinica_logo) {
                Storage::disk('public')->delete($config->clinica_logo);
            }
            $validated['clinica_logo'] = $request->file('clinica_logo')->store('logos', 'public');
        }

        // Parsear JSON strings a arrays si vienen como texto desde JS
        foreach (['horarios', 'servicios'] as $campo) {
            if (isset($validated[$campo]) && is_string($validated[$campo])) {
                $decoded = json_decode($validated[$campo], true);
                $validated[$campo] = is_array($decoded) ? $decoded : null;
            }
        }

        $config->update($validated);

        return redirect()->route('admin.configuracion.index')
            ->with('success', 'Configuración guardada correctamente.');
    }
}
