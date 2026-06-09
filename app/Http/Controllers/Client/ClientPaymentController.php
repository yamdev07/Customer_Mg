<?php

namespace App\Http\Controllers\Client;

use App\Actions\Client\ProcessClientPaymentAction;
use App\Actions\Client\ReverseClientPaymentAction;
use App\Http\Controllers\Controller;
use App\Models\Client;

class ClientPaymentController extends Controller
{
    /**
     * Marquer un client comme payé.
     */
    public function markAsPaid(Client $client, ProcessClientPaymentAction $processPayment)
    {
        $processPayment->execute($client);

        return redirect()->back()
            ->with('success', 'Client marqué comme payé et date de réabonnement mise à jour.');
    }

    /**
     * Reconnecter un client (paiement + réabonnement).
     */
    public function reconnect(Client $client, ProcessClientPaymentAction $processPayment)
    {
        $processPayment->execute($client);

        return redirect()->back()
            ->with('success', 'Client reconnecté et date de réabonnement mise à jour.');
    }

    /**
     * Déconnecter un client : annuler son dernier paiement.
     *
     * Le mois réglé le plus récent redevient impayé et la date de réabonnement
     * recule en conséquence, de sorte que « marquer payé » puisse le régler à nouveau.
     */
    public function disconnect(Client $client, ReverseClientPaymentAction $reversePayment)
    {
        $reversePayment->execute($client);

        return redirect()->back()
            ->with('success', 'Client déconnecté : son dernier paiement a été annulé.');
    }
}
