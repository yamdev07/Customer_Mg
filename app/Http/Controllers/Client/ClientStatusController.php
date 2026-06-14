<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Activite;
use App\Models\Client;

class ClientStatusController extends Controller
{
    /**
     * Suspendre un client.
     */
    public function suspend(Client $client)
    {
        $client->update(['statut' => Client::STATUS_SUSPENDU]);

        Activite::log('suspended', "Client « {$client->nom_client} » suspendu", $client);

        return redirect()->back()
            ->with('success', 'Client suspendu avec succès.');
    }

    /**
     * Réactiver un client.
     */
    public function reactivate(Client $client)
    {
        $client->update(['statut' => Client::STATUS_ACTIF]);

        Activite::log('reactivated', "Client « {$client->nom_client} » réactivé", $client);

        return redirect()->back()
            ->with('success', 'Client réactivé avec succès.');
    }
}
