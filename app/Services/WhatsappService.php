<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected string $sid;
    protected string $authToken;
    protected string $fromNumber;

    public function __construct()
    {
        $this->sid        = config('services.twilio.sid', env('TWILIO_SID', ''));
        $this->authToken  = config('services.twilio.auth_token', env('TWILIO_AUTH_TOKEN', ''));
        $this->fromNumber = config('services.twilio.whatsapp_number', env('TWILIO_WHATSAPP_NUMBER', ''));
    }

    /**
     * Envía un mensaje de WhatsApp usando la API de Twilio.
     *
     * @param  string $telefono  Número de destino (ej: +521234567890)
     * @param  string $mensaje   Texto del mensaje
     * @return bool              true si el envío fue exitoso, false en caso contrario
     */
    public function enviarMensaje(string $telefono, string $mensaje): bool
    {
        $to = 'whatsapp:' . $telefono;
        $from = 'whatsapp:' . $this->fromNumber;

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json";

        try {
            $response = Http::withBasicAuth($this->sid, $this->authToken)
                ->asForm()
                ->post($url, [
                    'From' => $from,
                    'To'   => $to,
                    'Body' => $mensaje,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('WhatsApp enviado correctamente', [
                    'sid'      => $data['sid'] ?? null,
                    'to'       => $to,
                    'status'   => $data['status'] ?? null,
                ]);
                return true;
            }

            Log::error('Error al enviar WhatsApp — respuesta no exitosa', [
                'to'     => $to,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return false;

        } catch (\Throwable $e) {
            Log::error('Excepción al enviar WhatsApp', [
                'to'      => $to,
                'mensaje' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Construye y envía el mensaje de confirmación de cita.
     *
     * @param  \App\Models\Cita $cita
     * @return bool
     */
    public function enviarConfirmacionCita($cita): bool
    {
        $telefono = $cita->cliente?->telefono;

        if (empty($telefono)) {
            Log::warning('WhatsApp: cliente sin teléfono registrado', ['cita_id' => $cita->id]);
            return false;
        }

        $fecha    = $cita->fecha?->format('d/m/Y') ?? '—';
        $hora     = $cita->hora ?? '—';
        $mascota  = $cita->mascota?->nombre ?? '—';
        $servicio = $cita->tipo_servicio ?? 'Consulta general';
        $cliente  = $cita->cliente?->nombre ?? 'Estimado cliente';

        $mensaje = "🐾 *VetCare — Confirmación de Cita*\n\n"
                 . "Hola, *{$cliente}*\n\n"
                 . "Tu cita ha sido confirmada:\n\n"
                 . "📅 *Fecha:* {$fecha}\n"
                 . "🕐 *Hora:* {$hora} hrs\n"
                 . "🐾 *Paciente:* {$mascota}\n"
                 . "🩺 *Servicio:* {$servicio}\n\n"
                 . "Por favor llega 10 minutos antes.\n"
                 . "Para cancelar o reprogramar llámanos al 📞 55 1234 5678.\n\n"
                 . "_VetCare — Tu mascota en buenas manos_ 🐾";

        return $this->enviarMensaje($telefono, $mensaje);
    }
}
