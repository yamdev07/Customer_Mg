<?php

namespace App\Console\Commands;

use App\Actions\Client\SyncClientStatusAction;
use Illuminate\Console\Command;

class SynchroniserStatutsClients extends Command
{
    protected $signature = 'clients:synchroniser-statuts';

    protected $description = 'Recalcule le statut payé/suspendu des clients selon leurs paiements et dates de réabonnement';

    public function handle(SyncClientStatusAction $sync): int
    {
        $sync->execute();

        $this->info('Statuts des clients synchronisés.');

        return self::SUCCESS;
    }
}
