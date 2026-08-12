<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected string $sid;
    protected string $authToken;
    protected string $fromNumber;
    protected bool $testMode;

    public function __construct()
    {
        $this->sid        = config('services.twilio.sid', '');
        $this->authToken  = config('services.twilio.auth_token', '');
        $this->fromNumber = config('services.twilio.whatsapp_number', '');
        // config() es compatible con php artisan config:cache, env() no lo es
        $this->testMode   = config('app.vetcare_test_mode', env('VETCARE_NOTIFICATIONS_TEST_MODE', false));
    }

    /**
     * Envía un mensaje de WhatsApp usando la API de Twilio.
     *
     * @param  string $telefono  Número de destino
     * @param  string $mensaje   Texto del mensaje
     * @return array             ['success' => bool, 'provider_id' => string|null, 'error' => string|null]
     */
    public function enviarMensaje(string $telefono, string $mensaje): array
    {
        if ($this->testMode) {
            Log::info('Test Mode: Simulando envío de WhatsApp a ' . $telefono);
            // Sin sleep() — no bloqueamos el hilo en modo de prueba
            return [
                'success'     => true,
                'provider_id' => 'test_tw_' . uniqid(),
                'error'       => null,
            ];
        }

        if (empty($this->sid) || empty($this->authToken) || empty($this->fromNumber)) {
            return [
                'success' => false,
                'provider_id' => null,
                'error' => 'Twilio no está configurado correctamente (faltan credenciales).',
            ];
        }

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
                return [
                    'success' => true,
                    'provider_id' => $data['sid'] ?? null,
                    'error' => null,
                ];
            }

            return [
                'success' => false,
                'provider_id' => null,
                'error' => 'Error API Twilio: ' . $response->status() . ' - ' . $response->body(),
            ];

        } catch (\Throwable $e) {
            return [
                'success' => false,
                'provider_id' => null,
                'error' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Construye y envía el mensaje de confirmación de cita.
     *
     * @param  \App\Models\Cita $cita
     * @return array
     */
    public function enviarConfirmacionCita($cita): array
    {
        $telefono = $cita->cliente?->telefono;

        if (empty($telefono)) {
            return [
                'success' => false,
                'provider_id' => null,
                'error' => 'El cliente no tiene teléfono registrado.',
            ];
        }

        $fecha    = $cita->fecha?->format('d/m/Y') ?? '—';
        $hora     = $cita->hora ?? '—';
        $mascota  = $cita->mascota?->nombre ?? '—';
        $servicio = $cita->tipo_servicio ?? 'Consulta general';
        $cliente  = $cita->cliente?->nombre ?? 'Estimado cliente';
        $motivo   = $cita->motivo ? "📝 *Motivo:* {$cita->motivo}\n" : "";

        $config = \App\Models\Configuracion::instancia();
        $clinica_nombre = $config->clinica_nombre ?: 'VetCare';
        $clinica_direccion = $config->clinica_direccion ?: 'Dirección no registrada';
        $clinica_telefono = $config->clinica_telefono ?: 'Sin teléfono';

        $mensaje = "🐾 *{$clinica_nombre} – Confirmación de Cita*\n\n"
                 . "Hola, *{$cliente}*\n\n"
                 . "Tu cita ha sido confirmada:\n\n"
                 . "📅 *Fecha:* {$fecha}\n"
                 . "🕒 *Hora:* {$hora} hrs\n"
                 . "🐶 *Paciente:* {$mascota}\n"
                 . "🩺 *Servicio:* {$servicio}\n"
                 . $motivo
                 . "\nPor favor llega 10 minutos antes.\n"
                 . "Para cancelar o reprogramar llámanos al 📞 {$clinica_telefono}.\n\n"
                 . "📍 *Dirección:* {$clinica_direccion}\n\n"
                 . "_{$clinica_nombre} – Tu mascota en buenas manos_ 🐾";

        return $this->enviarMensaje($telefono, $mensaje);
    }
}
