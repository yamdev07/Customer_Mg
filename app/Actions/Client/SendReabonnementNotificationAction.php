<?php

namespace App\Actions\Client;

use App\Models\Client;
use Illuminate\Support\Facades\Mail;

class SendReabonnementNotificationAction
{
    /**
     * Envoyer les notifications de réabonnement aux clients éligibles.
     *
     * @return int Nombre de notifications envoyées
     */
    public function execute(): int
    {
        $clients = Client::reabonnementProche(7)
            ->whereNotNull('email')
            ->get();

        $count = 0;

        foreach ($clients as $client) {
            if (! $client->email) {
                continue;
            }

            Mail::raw(
                "Bonjour {$client->nom_client}, votre date de réabonnement approche. Merci de renouveler via ce lien : https://anyxtech.com/reabonnement",
                function ($message) use ($client) {
                    $message->to($client->email)
                        ->subject('Réabonnement AnyxTech - Échéance proche');
                }
            );

            $count++;
        }

        return $count;
    }
}
