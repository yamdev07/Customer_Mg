<?php

namespace App\Actions\Client;

use App\Models\Client;
use Carbon\Carbon;

class ReverseClientPaymentAction
{
    /**
     * Annuler le dernier paiement d'un client (déconnexion).
     *
     * Le mois réglé le plus récent redevient impayé et la date de réabonnement
     * recule sur ce mois (qui redevient l'échéance due). Ainsi un clic ultérieur
     * sur « marquer payé » règle de nouveau ce même mois — opération réversible.
     */
    public function execute(Client $client): void
    {
        $dernierPaye = $client->paiements()
            ->where('statut', true)
            ->orderByDesc('annee')
            ->orderByDesc('mois')
            ->first();

        if ($dernierPaye) {
            // Le mois soi-disant payé ne compte plus.
            $dernierPaye->update(['statut' => false]);

            // La date de réabonnement revient sur ce mois (échéance redevenue due).
            if ($client->jour_reabonnement) {
                $jour = min(
                    $client->jour_reabonnement,
                    Carbon::create($dernierPaye->annee, $dernierPaye->mois, 1)->endOfMonth()->day
                );
                $client->date_reabonnement = Carbon::create($dernierPaye->annee, $dernierPaye->mois, $jour);
            }
        }

        $client->a_paye = false;
        $client->save();
    }
}
