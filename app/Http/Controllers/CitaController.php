<?php

namespace App\Http\Controllers;

use App\Mail\CitaConfirmacionMail;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Events\CitaCreada;
use App\Events\CitaEditada;
use App\Events\CitaEliminada;

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
                        ->orWhere('apellido_paterno','like', "%{$buscar}%")
                  )
                  ->orWhere('tipo_servicio', 'like', "%{$buscar}%");
            });
        }

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        if ($fechaFiltro = $request->input('fecha_filtro')) {
            $hoy = now()->toDateString();
            switch ($fechaFiltro) {
                case 'hoy':
                    $query->whereDate('fecha', $hoy);
                    break;
                case 'semana':
                    $query->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'mes':
                    $query->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year);
                    break;
            }
        }

        if ($fechaDesde = $request->input('fecha_desde')) {
            $query->whereDate('fecha', '>=', $fechaDesde);
        }
        if ($fechaHasta = $request->input('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $fechaHasta);
        }

        $citas = $query->paginate(10)->withQueryString();

        // Conteo agrupado en un solo query
        $conteoEstados = Cita::selectRaw('estado, count(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $conteoEstados = array_merge(
            ['pendiente' => 0, 'confirmada' => 0, 'completada' => 0, 'cancelada' => 0],
            $conteoEstados
        );

        return view('citas.index', compact('citas', 'conteoEstados'));
    }

    public function create(Request $request)
    {
        $clientes = Cliente::select('id', 'nombre', 'apellido')->orderBy('nombre')->get();
        $mascotas = Mascota::with('cliente:id,nombre,apellido')->select('id', 'nombre', 'especie', 'cliente_id')->orderBy('nombre')->get();
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
        $data['precio']           = Cita::SERVICIOS_PRECIOS[$data['tipo_servicio']] ?? 0;

        $cita = Cita::create($data);

        event(new CitaCreada($cita));

        // Enviar email si se marcó la opción
        try {
            if ($data['enviado_email'] && $cita->cliente?->email) {
                Mail::to($cita->cliente->email)->queue(new CitaConfirmacionMail($cita));
            }
        } catch (\Throwable $e) {
            Log::error('Error al enviar email de cita: ' . $e->getMessage());
        }

        // Enviar WhatsApp si se marcó la opción
        try {
            if ($data['enviado_whatsapp'] && $cita->cliente?->telefono) {
                app(WhatsappService::class)->enviarConfirmacionCita($cita);
            }
        } catch (\Throwable $e) {
            Log::error('Error al enviar WhatsApp de cita: ' . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Cita agendada correctamente.']);
        }

        return redirect()->route('citas.index')
                         ->with('success', 'Cita agendada correctamente.');
    }

    public function show(Cita $cita)
    {
        $cita->load(['cliente', 'mascota']);

        if (request()->expectsJson()) {
            return response()->json([
                'cita'    => $cita,
                'cliente' => $cita->cliente,
                'mascota' => $cita->mascota,
            ]);
        }

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
        $data['precio']           = Cita::SERVICIOS_PRECIOS[$data['tipo_servicio']] ?? 0;

        $cita->update($data);

        event(new CitaEditada($cita));

        return redirect()->route('citas.index')
                         ->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();

        event(new CitaEliminada($cita->id));

        return redirect()->route('citas.index')
                         ->with('success', 'Cita eliminada correctamente.');
    }

    /**
     * Confirma la cita y envía email de notificación.
     */
    public function confirmar(Cita $cita)
    {
        $cita->update(['estado' => 'confirmada']);

        try {
            if ($cita->cliente?->email) {
                Mail::to($cita->cliente->email)->queue(new CitaConfirmacionMail($cita));
            }
        } catch (\Throwable $e) {
            Log::error('Error al confirmar cita por email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Cita confirmada, pero hubo un error al enviar el correo.');
        }

        return redirect()->back()->with('success', 'Cita confirmada y correo enviado.');
    }

    /**
     * Envía confirmación por WhatsApp.
     */
    public function confirmarWhatsapp(Cita $cita)
    {
        try {
            app(WhatsappService::class)->enviarConfirmacionCita($cita);
            return redirect()->back()->with('success', 'Mensaje de WhatsApp enviado.');
        } catch (\Throwable $e) {
            Log::error('Error al confirmar cita por WhatsApp: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar enviar el mensaje de WhatsApp.');
        }
    }

    /**
     * Cancela la cita guardando el motivo en el campo `motivo`.
     */
    public function cancelar(Request $request, Cita $cita)
    {
        $request->validate([
            'motivo_cancelacion' => ['required', 'string', 'max:500'],
        ]);

        $cita->update([
            'estado' => 'cancelada',
            'motivo' => $request->input('motivo_cancelacion'),
        ]);

        return redirect()->back()->with('success', 'Cita cancelada correctamente.');
    }

    /**
     * Marca la cita como completada.
     */
    public function completar(Cita $cita)
    {
        $cita->update(['estado' => 'completada']);

        return redirect()->back()->with('success', 'Cita marcada como completada.');
    }

    /**
     * Envía recordatorio por email y marca enviado_email = true.
     */
    public function enviarEmail(Cita $cita)
    {
        $cita->load('cliente');

        if ($cita->cliente?->email) {
            try {
                Mail::to($cita->cliente->email)->queue(new CitaConfirmacionMail($cita));
                $cita->update(['enviado_email' => true]);
                return redirect()->back()->with('success', 'Recordatorio por email enviado a ' . $cita->cliente->email);
            } catch (\Throwable $e) {
                Log::error('Error al enviar recordatorio por email: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Ocurrió un error al intentar enviar el recordatorio por correo.');
            }
        }

        return redirect()->back()->with('error', 'El cliente no tiene email registrado.');
    }

    /**
     * Envía recordatorio por WhatsApp y marca enviado_whatsapp = true.
     */
    public function enviarWhatsapp(Cita $cita)
    {
        $cita->load('cliente');

        if ($cita->cliente?->telefono) {
            try {
                app(WhatsappService::class)->enviarConfirmacionCita($cita);
                $cita->update(['enviado_whatsapp' => true]);
                return redirect()->back()->with('success', 'Recordatorio por WhatsApp enviado.');
            } catch (\Throwable $e) {
                Log::error('Error al enviar recordatorio por WhatsApp: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Ocurrió un error al intentar enviar el recordatorio por WhatsApp.');
            }
        }

        return redirect()->back()->with('error', 'El cliente no tiene teléfono registrado.');
    }

    /**
     * Retorna las mascotas de un cliente en JSON (para AJAX en modal agendar).
     */
    public function mascotasPorCliente(Cliente $cliente)
    {
        $mascotas = $cliente->mascotas()
            ->select('id', 'nombre', 'especie', 'raza')
            ->orderBy('nombre')
            ->get();

        return response()->json($mascotas);
    }

    /**
     * Retorna todos los clientes en JSON (para AJAX en modal agendar).
     */
    public function listarClientes()
    {
        $clientes = Cliente::select('id', 'nombre', 'apellido', 'apellido_paterno', 'apellido_materno', 'email', 'telefono')
            ->orderBy('nombre')
            ->get()
            ->map(fn ($c) => [
                'id'       => $c->id,
                'nombre'   => trim($c->nombre . ' ' . ($c->apellido_paterno ?? $c->apellido) . ' ' . ($c->apellido_materno ?? '')),
                'email'    => $c->email,
                'telefono' => $c->telefono,
            ]);

        return response()->json($clientes);
    }
}
