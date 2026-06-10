<?php

namespace App\Http\Controllers\Client;

use App\Actions\Client\SendReabonnementNotificationAction;
use App\Contracts\MessagingServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Activite;
use App\Models\Client;
use Carbon\Carbon;

class ClientNotificationController extends Controller
{
    /**
     * Envoyer les notifications de réabonnement par email.
     */
    public function sendEmailNotifications(SendReabonnementNotificationAction $action)
    {
        $count = $action->execute();

        Activite::log('notified', "{$count} notification(s) de réabonnement envoyée(s) par e-mail");

        return redirect()->route('clients.index')
            ->with('success', "{$count} notification(s) envoyée(s) avec succès.");
    }

    /**
     * Relancer un client via WhatsApp (template Infobip).
     */
    public function sendWhatsApp(Client $client, MessagingServiceInterface $messaging)
    {
        $numero = $this->formatWhatsAppNumber($client->contact);

        $success = $messaging->sendWhatsAppTemplate(
            $numero,
            'test_whatsapp_template_en',
            [$client->nom_client]
        );

        if ($success) {
            Activite::log('notified', "Relance WhatsApp envoyée à « {$client->nom_client} »", $client);

            return back()->with('success', "Message WhatsApp envoyé à {$client->nom_client}.");
        }

        return back()->with('error', "Échec de l'envoi WhatsApp à {$client->nom_client}.");
    }

    /**
     * Relancer un client via SMS.
     */
    public function sendSms(Client $client, MessagingServiceInterface $messaging)
    {
        $numero = $this->formatPhoneNumber($client->contact);

        $date = $client->date_reabonnement
            ? Carbon::parse($client->date_reabonnement)->format('d/m/Y')
            : 'bientôt';

        $message = "Bonjour {$client->nom_client}, votre réabonnement arrive à échéance le {$date}. Merci de renouveler votre abonnement. - AnyxTech";

        $result = $messaging->sendSMS($numero, $message);

        if ($result['success']) {
            Activite::log('notified', "Relance SMS envoyée à « {$client->nom_client} »", $client);

            return redirect()->back()->with('success', "SMS envoyé à {$client->nom_client}.");
        }

        return redirect()->back()->with('error', "Erreur lors de l'envoi du SMS.");
    }

    /* ──────────────────────────────────────────────
       Private helpers
    ────────────────────────────────────────────── */

    private function formatWhatsAppNumber(string $number): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $number);

        if (strlen($cleaned) === 8) {
            $cleaned = '229'.$cleaned;
        }

        return '+'.$cleaned;
    }

    private function formatPhoneNumber(string $number): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $number);

        if (strlen($cleaned) === 8) {
            $cleaned = '229'.$cleaned;
        }

        return '+'.$cleaned;
    }
}
