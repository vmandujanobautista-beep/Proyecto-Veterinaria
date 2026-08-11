<?php

namespace App\Http\Controllers;

use App\Mail\CitaConfirmacionMail;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\CitaConfirmacion;
use App\Services\WhatsappService;
use App\Services\EmailService;
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

        // Ya no enviamos automáticamente, la confirmación es explícita.

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Cita agendada correctamente.']);
        }

        return redirect()->route('citas.index')
                         ->with('success', 'Cita agendada correctamente. ¿Deseas enviar la confirmación ahora?')
                         ->with('abrir_detalle', $cita->id);
    }

    public function show(Cita $cita)
    {
        $cita->load(['cliente', 'mascota', 'confirmaciones']);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'html' => view('partials.modals.modal-ver-cita-body', compact('cita'))->render()
            ]);
        }

        return redirect()->route('citas.index')->with('abrir_detalle', $cita->id);
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
     * Procesa la solicitud explícita de enviar notificaciones.
     */
    public function notificar(Request $request, Cita $cita)
    {
        $request->validate([
            'canales' => ['required', 'array'],
            'canales.*' => ['in:whatsapp,email']
        ]);

        $canales = $request->input('canales', []);
        $cita->load('cliente');
        $mensajes = [];
        $hayError = false;

        if (in_array('email', $canales)) {
            $emailService = app(EmailService::class);
            $resultado = $emailService->enviarConfirmacionCita($cita);
            
            $estado = $resultado['success'] ? 'enviado' : 'error';
            if (!$resultado['success']) $hayError = true;
            
            $cita->confirmaciones()->create([
                'canal' => 'email',
                'destinatario' => $cita->cliente->email ?? '—',
                'estado' => $estado,
                'mensaje_error' => $resultado['error'],
                'provider_message_id' => $resultado['provider_id'],
                'fecha_envio' => now(),
            ]);

            $mensajes[] = $resultado['success'] ? 'Correo enviado.' : 'Error al enviar correo.';
        }

        if (in_array('whatsapp', $canales)) {
            $waService = app(WhatsappService::class);
            $resultado = $waService->enviarConfirmacionCita($cita);
            
            $estado = $resultado['success'] ? 'enviado' : 'error';
            if (!$resultado['success']) $hayError = true;
            
            $cita->confirmaciones()->create([
                'canal' => 'whatsapp',
                'destinatario' => $cita->cliente->telefono ?? '—',
                'estado' => $estado,
                'mensaje_error' => $resultado['error'],
                'provider_message_id' => $resultado['provider_id'],
                'fecha_envio' => now(),
            ]);

            $mensajes[] = $resultado['success'] ? 'WhatsApp enviado.' : 'Error al enviar WhatsApp.';
        }

        if ($hayError) {
            $redirect = redirect()->back()->with('error', implode(' ', $mensajes) . ' Revisa el historial de envíos para reintentar.');
            return $request->has('from_modal') ? $redirect->with('abrir_detalle', $cita->id) : $redirect;
        }

        $redirect = redirect()->back()->with('success', 'Confirmación enviada correctamente.');
        return $request->has('from_modal') ? $redirect->with('abrir_detalle', $cita->id) : $redirect;
    }

    /**
     * Reintenta enviar una confirmación específica.
     */
    public function reintentarConfirmacion(CitaConfirmacion $confirmacion)
    {
        $cita = $confirmacion->cita;
        $cita->load('cliente');

        if ($confirmacion->canal === 'email') {
            $resultado = app(EmailService::class)->enviarConfirmacionCita($cita);
        } else {
            $resultado = app(WhatsappService::class)->enviarConfirmacionCita($cita);
        }

        $confirmacion->update([
            'estado' => $resultado['success'] ? 'enviado' : 'error',
            'mensaje_error' => $resultado['error'],
            'provider_message_id' => $resultado['provider_id'],
            'fecha_envio' => now(),
        ]);

        if (!$resultado['success']) {
            $redirect = redirect()->back()->with('error', 'Error al reintentar: ' . $resultado['error']);
            return request()->has('from_modal') ? $redirect->with('abrir_detalle', $cita->id) : $redirect;
        }

        $redirect = redirect()->back()->with('success', 'Confirmación reenviada correctamente.');
        return request()->has('from_modal') ? $redirect->with('abrir_detalle', $cita->id) : $redirect;
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
     * Confirma la cita sin enviar notificaciones automáticamente.
     */
    public function confirmar(Cita $cita)
    {
        $cita->update(['estado' => 'confirmada']);
        return redirect()->back()->with('success', 'Cita confirmada.');
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
