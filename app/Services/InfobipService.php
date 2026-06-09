<?php

namespace App\Services;

use App\Contracts\MessagingServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InfobipService implements MessagingServiceInterface
{
    protected string $baseUrl;

    protected string $token;

    protected string $sender;

    public function __construct()
    {
        $this->baseUrl = config('services.infobip.base_url', '');
        $this->token = config('services.infobip.token', '');
        $this->sender = config('services.infobip.sender', '');
    }

    /**
     * {@inheritdoc}
     */
    public function sendSMS(string $to, string $message): array
    {
        try {
            $to = $this->formatPhoneNumber($to);

            $response = Http::withHeaders([
                'Authorization' => 'App '.$this->token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post("{$this->baseUrl}/sms/2/text/advanced", [
                'messages' => [
                    [
                        'from' => $this->sender,
                        'destinations' => [['to' => $to]],
                        'text' => $message,
                    ],
                ],
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'sid' => data_get($response->json(), 'messages.0.messageId'),
                    'message' => 'SMS envoyé avec succès',
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['description'] ?? 'Erreur inconnue',
            ];
        } catch (\Exception $e) {
            Log::error('Infobip SMS error', ['to' => $to, 'error' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendWhatsApp(string $to, string $message): array
    {
        try {
            $to = $this->formatWhatsAppNumber($to);

            $response = Http::withHeaders([
                'Authorization' => 'App '.$this->token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post("{$this->baseUrl}/whatsapp/1/message/text", [
                'from' => $this->sender,
                'to' => $to,
                'text' => $message,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'sid' => data_get($response->json(), 'messages.0.messageId'),
                    'message' => 'WhatsApp envoyé avec succès',
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['description'] ?? 'Erreur inconnue',
            ];
        } catch (\Exception $e) {
            Log::error('Infobip WhatsApp error', ['to' => $to, 'error' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendWhatsAppTemplate(string $to, string $templateName, array $placeholders = []): bool
    {
        $payload = [
            'messages' => [
                [
                    'from' => $this->sender,
                    'to' => $this->formatWhatsAppNumber($to),
                    'messageId' => Str::uuid()->toString(),
                    'content' => [
                        'templateName' => $templateName,
                        'templateData' => [
                            'body' => [
                                'placeholders' => $placeholders,
                            ],
                        ],
                        'language' => 'en',
                    ],
                ],
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'App '.$this->token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post("{$this->baseUrl}/whatsapp/1/message/template", $payload);

        return $response->successful();
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageStatus(string $messageSid): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'App '.$this->token,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/whatsapp/1/message/{$messageSid}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => data_get($response->json(), 'results.0.status'),
                ];
            }

            return ['success' => false, 'error' => 'Impossible de récupérer le statut'];
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

    private function formatWhatsAppNumber(string $number): string
    {
        $formatted = $this->formatPhoneNumber($number);

        return str_starts_with($formatted, 'whatsapp:') ? $formatted : 'whatsapp:'.$formatted;
    }
}
