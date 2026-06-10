<?php

namespace App\Http\Controllers\Client;

use App\Actions\Client\SyncClientStatusAction;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ClientListController extends Controller
{
    /**
     * Afficher la liste de tous les clients.
     */
    public function __invoke(Request $request)
    {
        $this->syncClientsStatus();

        $today = Carbon::today();
        $moisCourant = $today->month;
        $anneeCourante = $today->year;

        $query = Client::query()->with('paiements');

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        $clients = $query->orderBy('id')->paginate(10)->appends($request->all());

        $stats = $this->getStats($moisCourant, $anneeCourante);

        return view('clients.index', array_merge(compact('clients'), $stats));
    }

    /**
     * Synchroniser le statut des clients.
     *
     * Throttle : au plus une fois toutes les 15 minutes, au lieu de recalculer
     * tous les clients à chaque affichage de la page. La synchro complète est
     * aussi planifiée (commande clients:synchroniser-statuts) pour la production.
     */
    private function syncClientsStatus(): void
    {
        if (Cache::add('clients:sync-lock', true, now()->addMinutes(15))) {
            app(SyncClientStatusAction::class)->execute();
        }
    }

    /**
     * Obtenir les statistiques.
     */
    private function getStats(int $moisCourant, int $anneeCourante): array
    {
        $today = Carbon::today();

        return [
            'totalClientsCount' => Client::count(),
            'payes' => Client::payesPourMois($moisCourant, $anneeCourante)->count(),
            'nonPayes' => Client::nonPayesPourMois($moisCourant, $anneeCourante)->count(),
            'actifs' => Client::actifs()->count(),
            'suspendus' => Client::suspendus()->count(),
            'clientsReabonnementProche' => Client::reabonnementProche(5)->count(),
            'clientsReabonnementDepasse' => Client::reabonnementDepasse()->count(),
        ];
    }
}
