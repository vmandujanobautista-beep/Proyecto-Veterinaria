<?php

namespace App\Http\Controllers;

use App\Mail\CitaConfirmacionMail;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $query = Cita::with(['cliente', 'mascota'])->latest('fecha');

        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->whereHas('mascota', fn ($m) => $m->where('nombre', 'like', "%{$buscar}%"))
                  ->orWhereHas('cliente', fn ($c) =>
                      $c->where('nombre',   'like', "%{$buscar}%")
                        ->orWhere('apellido','like', "%{$buscar}%")
                  );
            });
        }

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        if ($fecha = $request->input('fecha')) {
            $query->whereDate('fecha', $fecha);
        }

        $citas = $query->paginate(10)->withQueryString();

        // Conteos por estado para el panel de tarjetas
        $conteoEstados = [
            'pendiente'  => Cita::where('estado', 'pendiente')->count(),
            'confirmada' => Cita::where('estado', 'confirmada')->count(),
            'completada' => Cita::where('estado', 'completada')->count(),
            'cancelada'  => Cita::where('estado', 'cancelada')->count(),
        ];

        return view('citas.index', compact('citas', 'conteoEstados'));
    }

    public function create(Request $request)
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $mascotas = Mascota::with('cliente')->orderBy('nombre')->get();
        return view('citas.create', compact('clientes', 'mascotas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha'            => ['required', 'date'],
            'hora'             => ['required', 'string', 'max:10'],
            'tipo_servicio'    => ['required', 'string', 'max:100'],
            'motivo'           => ['nullable', 'string', 'max:500'],
            'estado'           => ['nullable', 'string', 'in:pendiente,confirmada,completada,cancelada'],
            'enviado_email'    => ['nullable', 'boolean'],
            'enviado_whatsapp' => ['nullable', 'boolean'],
            'cliente_id'       => ['required', 'exists:clientes,id'],
            'mascota_id'       => ['required', 'exists:mascotas,id'],
        ]);

        $data['estado']           = $data['estado']           ?? 'pendiente';
        $data['enviado_email']    = $request->boolean('enviado_email');
        $data['enviado_whatsapp'] = $request->boolean('enviado_whatsapp');

        $cita = Cita::create($data);

        // Enviar email si se marcó la opción
        if ($data['enviado_email'] && $cita->cliente?->email) {
            Mail::to($cita->cliente->email)->queue(new CitaConfirmacionMail($cita));
        }

        // Enviar WhatsApp si se marcó la opción
        if ($data['enviado_whatsapp'] && $cita->cliente?->telefono) {
            app(WhatsappService::class)->enviarConfirmacionCita($cita);
        }

        return redirect()->route('citas.index')
                         ->with('success', 'Cita agendada correctamente.');
    }

    public function show(Cita $cita)
    {
        $cita->load(['cliente', 'mascota']);
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $mascotas = Mascota::with('cliente')->orderBy('nombre')->get();
        return view('citas.edit', compact('cita', 'clientes', 'mascotas'));
    }

    public function update(Request $request, Cita $cita)
    {
        $data = $request->validate([
            'fecha'            => ['required', 'date'],
            'hora'             => ['required', 'string', 'max:10'],
            'tipo_servicio'    => ['required', 'string', 'max:100'],
            'motivo'           => ['nullable', 'string', 'max:500'],
            'estado'           => ['nullable', 'string', 'in:pendiente,confirmada,completada,cancelada'],
            'enviado_email'    => ['nullable', 'boolean'],
            'enviado_whatsapp' => ['nullable', 'boolean'],
            'cliente_id'       => ['required', 'exists:clientes,id'],
            'mascota_id'       => ['required', 'exists:mascotas,id'],
        ]);

        $data['enviado_email']    = $request->boolean('enviado_email');
        $data['enviado_whatsapp'] = $request->boolean('enviado_whatsapp');

        $cita->update($data);

        return redirect()->route('citas.index')
                         ->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();

        return redirect()->route('citas.index')
                         ->with('success', 'Cita eliminada correctamente.');
    }

    /**
     * Confirma la cita y envía notificaciones.
     */
    public function confirmar(Cita $cita)
    {
        $cita->update(['estado' => 'confirmada']);

        if ($cita->cliente?->email) {
            Mail::to($cita->cliente->email)->queue(new CitaConfirmacionMail($cita));
        }

        return redirect()->back()->with('success', 'Cita confirmada y correo enviado.');
    }

    /**
     * Envía confirmación por WhatsApp.
     */
    public function confirmarWhatsapp(Cita $cita)
    {
        app(WhatsappService::class)->enviarConfirmacionCita($cita);

        return redirect()->back()->with('success', 'Mensaje de WhatsApp enviado.');
    }
}
