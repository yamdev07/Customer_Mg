<?php

namespace App\Services;

use App\Contracts\MessagingServiceInterface;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client as TwilioClient;

class TwilioService implements MessagingServiceInterface
{
    protected TwilioClient $twilio;

    protected string $from;

    protected string $whatsappFrom;

    public function __construct()
    {
        $this->twilio = new TwilioClient(
            config('services.twilio.sid', ''),
            config('services.twilio.token', '')
        );
        $this->from = config('services.twilio.from', '');
        $this->whatsappFrom = config('services.twilio.whatsapp_from', '');
    }

    /**
     * {@inheritdoc}
     */
    public function sendSMS(string $to, string $message): array
    {
        try {
            $to = $this->formatPhoneNumber($to);

            $messageInstance = $this->twilio->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $message,
                ]
            );

            Log::info('SMS envoyé avec succès', ['to' => $to, 'sid' => $messageInstance->sid]);

            return [
                'success' => true,
                'sid' => $messageInstance->sid,
                'message' => 'SMS envoyé avec succès',
            ];
        } catch (\Exception $e) {
            Log::error('Erreur envoi SMS', ['to' => $to, 'error' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendWhatsApp(string $to, string $message): array
    {
        try {
            $to = 'whatsapp:'.$this->formatPhoneNumber($to);

            $messageInstance = $this->twilio->messages->create(
                $to,
                [
                    'from' => $this->whatsappFrom,
                    'body' => $message,
                ]
            );

            Log::info('WhatsApp envoyé avec succès', ['to' => $to, 'sid' => $messageInstance->sid]);

            return [
                'success' => true,
                'sid' => $messageInstance->sid,
                'message' => 'Message WhatsApp envoyé avec succès',
            ];
        } catch (\Exception $e) {
            Log::error('Erreur envoi WhatsApp', ['to' => $to, 'error' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendWhatsAppTemplate(string $to, string $templateName, array $placeholders = []): bool
    {
        // Twilio ne supporte pas directement les templates via l'API REST de la même manière
        // On compose le message manuellement avec les placeholders
        $message = $templateName;
        foreach ($placeholders as $placeholder) {
            $message .= ' '.$placeholder;
        }

        $result = $this->sendWhatsApp($to, $message);

        return $result['success'];
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageStatus(string $messageSid): array
    {
        try {
            $message = $this->twilio->messages($messageSid)->fetch();

            return [
                'success' => true,
                'status' => $message->status,
                'error_code' => $message->errorCode,
                'error_message' => $message->errorMessage,
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /* ──────────────────────────────────────────────
       Private helpers
    ────────────────────────────────────────────── */

    private function formatPhoneNumber(string $number): string
    {
        $number = preg_replace('/[^0-9]/', '', $number);

        if (strlen($number) === 8) {
            $number = '+229'.$number;
        } elseif (strlen($number) === 11 && str_starts_with($number, '229')) {
            $number = '+'.$number;
        } elseif (! str_starts_with($number, '+')) {
            $number = '+'.$number;
        }

        return $number;
    }
}
