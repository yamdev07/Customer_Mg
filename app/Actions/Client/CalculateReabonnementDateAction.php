<?php

namespace App\Actions\Client;

use App\Models\Client;
use Carbon\Carbon;

class CalculateReabonnementDateAction
{
    /**
     * Calculer la date de réabonnement d'un client.
     */
    public function execute(Client $client): Carbon
    {
        $today = Carbon::today();

        // Si le client a payé, on utilise le mois suivant
        if ($client->a_paye) {
            $mois = $today->month + 1;
            $annee = $today->year;
            if ($mois > 12) {
                $mois = 1;
                $annee++;
            }
        } else {
            $mois = $today->month;
            $annee = $today->year;
        }

        $jour = min($client->jour_reabonnement ?? 1, Carbon::create($annee, $mois, 1)->endOfMonth()->day);

        return Carbon::create($annee, $mois, $jour);
    }
}
