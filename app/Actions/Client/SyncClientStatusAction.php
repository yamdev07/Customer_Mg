<?php

namespace App\Actions\Client;

use App\Models\Client;
use Carbon\Carbon;

class SyncClientStatusAction
{
    /**
     * Synchroniser le statut de tous les clients selon leurs paiements et dates de réabonnement.
     */
    public function execute(): void
    {
        $today = Carbon::today();
        $moisCourant = $today->month;
        $anneeCourante = $today->year;

        Client::chunk(100, function ($clients) use ($today, $moisCourant, $anneeCourante) {
            foreach ($clients as $client) {
                $this->syncSingleClient($client, $today, $moisCourant, $anneeCourante);
            }
        });
    }

    /**
     * Synchroniser un seul client.
     */
    private function syncSingleClient(Client $client, Carbon $today, int $moisCourant, int $anneeCourante): void
    {
        // Vérifier si le client a payé ce mois
        $client->a_paye = $client->estPayePourMois($moisCourant, $anneeCourante);

        // Suspension automatique si la date limite est dépassée
        // On ne change pas le statut si le client a été suspendu manuellement
        if ($client->date_reabonnement && $client->statut !== Client::STATUS_SUSPENDU) {
            $dateLimite = $client->date_reabonnement->copy()->addMonths(2);

            if ($today->gt($dateLimite)) {
                $client->statut = Client::STATUS_SUSPENDU;
            }
        }

        // Note : « prochain_mois_du » n'est PAS une colonne, c'est un accesseur
        // calculé à la lecture (getProchainMoisDuAttribute). Ne rien y affecter ici,
        // sinon Eloquent tente d'écrire une colonne inexistante.

        // N'enregistrer que si quelque chose a réellement changé (évite des UPDATE inutiles).
        if ($client->isDirty()) {
            $client->saveQuietly();
        }
    }
}
