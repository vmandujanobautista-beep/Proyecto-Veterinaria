<?php

namespace App\Services;

use App\Mail\CitaConfirmacionMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    protected bool $testMode;

    public function __construct()
    {
        $this->testMode = env('VETCARE_NOTIFICATIONS_TEST_MODE', false);
    }

    /**
     * Envía un correo de confirmación de cita.
     *
     * @param  \App\Models\Cita $cita
     * @return array ['success' => bool, 'provider_id' => string|null, 'error' => string|null]
     */
    public function enviarConfirmacionCita($cita): array
    {
        $email = $cita->cliente?->email;

        if (empty($email)) {
            return [
                'success' => false,
                'provider_id' => null,
                'error' => 'El cliente no tiene correo electrónico registrado.',
            ];
        }

        if ($this->testMode) {
            Log::info('Test Mode: Simulando envío de Email a ' . $email);
            sleep(1); // Simular latencia de red
            return [
                'success' => true,
                'provider_id' => 'test_email_' . uniqid(),
                'error' => null,
            ];
        }

        try {
            $sentMessage = Mail::to($email)->send(new CitaConfirmacionMail($cita));
            
            // Si el driver es log o array, $sentMessage puede ser null en versiones de Laravel
            $messageId = $sentMessage ? $sentMessage->getMessageId() : 'local_log_' . uniqid();

            return [
                'success' => true,
                'provider_id' => $messageId,
                'error' => null,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'provider_id' => null,
                'error' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }
}
