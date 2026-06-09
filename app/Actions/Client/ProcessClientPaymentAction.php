<?php

namespace App\Actions\Client;

use App\Models\Client;
use App\Models\Paiement;
use Carbon\Carbon;

class ProcessClientPaymentAction
{
    /**
     * Traiter le paiement d'un client et mettre à jour son réabonnement.
     */
    public function execute(Client $client, ?Carbon $paymentDate = null): void
    {
        $paymentDate = $paymentDate ?? Carbon::today();

        // 1. Régler le mois impayé le plus ancien s'il existe (rattrapage de dette),
        //    sinon enregistrer le PROCHAIN mois dû (jamais le mois courant en aveugle).
        $moisImpaye = $client->paiements()
            ->where('statut', false)
            ->orderBy('annee')
            ->orderBy('mois')
            ->first();

        if ($moisImpaye) {
            $moisImpaye->update([
                'statut' => true,
                'date_paiement' => $paymentDate,
            ]);

            $mois = $moisImpaye->mois;
            $annee = $moisImpaye->annee;
        } else {
            // Aucun impayé : on règle le mois suivant le dernier payé.
            $prochain = $client->prochainMoisDu();
            $mois = $prochain->month;
            $annee = $prochain->year;

            // updateOrCreate = idempotent : pas de violation de la contrainte
            // unique (client_id, mois, annee) si le paiement existe déjà.
            $client->paiements()->updateOrCreate(
                ['mois' => $mois, 'annee' => $annee],
                [
                    'statut' => true,
                    'montant' => $client->montant,
                    'date_paiement' => $paymentDate,
                ]
            );
        }

        // 2. Mise à jour de la date de réabonnement
        $this->updateReabonnementDate($client, $annee, $mois);

        // 3. Statut actif et payé
        $client->update([
            'statut' => Client::STATUS_ACTIF,
            'a_paye' => true,
        ]);
    }

    /**
     * Mettre à jour la date de réabonnement selon le jour configuré.
     *
     * Un paiement couvre le mois réglé ($annee/$mois) ; la prochaine échéance
     * de réabonnement tombe donc le MOIS SUIVANT, au jour configuré
     * (borné au dernier jour du mois pour gérer les mois courts, ex. 31 → févr.).
     */
    private function updateReabonnementDate(Client $client, int $annee, int $mois): void
    {
        if (! $client->jour_reabonnement) {
            return;
        }

        $prochainMois = Carbon::create($annee, $mois, 1)->addMonth();
        $jour = min($client->jour_reabonnement, $prochainMois->copy()->endOfMonth()->day);

        $client->date_reabonnement = $prochainMois->setDay($jour);
    }
}
