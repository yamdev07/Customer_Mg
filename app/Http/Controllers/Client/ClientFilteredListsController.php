<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientFilteredListsController extends Controller
{
    /**
     * Clients payés pour le mois courant.
     */
    public function payes(Request $request)
    {
        $today = Carbon::today();
        $moisCourant = $today->month;
        $anneeCourante = $today->year;

        $query = Client::payesPourMois($moisCourant, $anneeCourante);

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        $clients = $query->orderBy('id')->paginate(10)->appends($request->all());
        $stats = $this->getStats($moisCourant, $anneeCourante);

        return view('clients.payes', array_merge(compact('clients'), $stats));
    }

    /**
     * Clients non payés pour le mois courant.
     */
    public function nonPayes(Request $request)
    {
        $today = Carbon::today();
        $moisCourant = $today->month;
        $anneeCourante = $today->year;

        $query = Client::nonPayesPourMois($moisCourant, $anneeCourante);

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        $clients = $query->orderBy('id')->paginate(10)->appends($request->all());
        $stats = $this->getStats($moisCourant, $anneeCourante);

        return view('clients.nonpayes', array_merge(compact('clients'), $stats));
    }

    /**
     * Clients actifs.
     */
    public function actifs(Request $request)
    {
        $query = Client::actifs();

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        $clients = $query->orderBy('id')->paginate(10)->appends($request->all());
        $stats = $this->getStats();

        return view('clients.actifs', array_merge(compact('clients'), $stats));
    }

    /**
     * Clients suspendus.
     */
    public function suspendus(Request $request)
    {
        $query = Client::suspendus();

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        $clients = $query->orderBy('id')->paginate(10)->appends($request->all());
        $stats = $this->getStats();

        return view('clients.suspendus', array_merge(compact('clients'), $stats));
    }

    /**
     * Clients à réabonnement proche.
     */
    public function aReabonnement(Request $request)
    {
        $query = Client::reabonnementProche(3);

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        $clients = $query->paginate(10)->appends($request->all());
        $stats = $this->getStats();

        return view('clients.reabonnement', array_merge(compact('clients'), $stats));
    }

    /**
     * Clients dépassés.
     */
    public function depasses(Request $request)
    {
        $today = Carbon::today();
        $moisCourant = $today->month;
        $anneeCourante = $today->year;

        $query = Client::actifs()
            ->nonPayesPourMois($moisCourant, $anneeCourante)
            ->whereDate('date_reabonnement', '<', $today);

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        $clients = $query->paginate(10)->appends($request->all());
        $stats = $this->getStats($moisCourant, $anneeCourante);

        return view('clients.depasses', array_merge(compact('clients'), $stats));
    }

    /**
     * Obtenir les statistiques.
     */
    private function getStats(?int $moisCourant = null, ?int $anneeCourante = null): array
    {
        $moisCourant = $moisCourant ?? Carbon::today()->month;
        $anneeCourante = $anneeCourante ?? Carbon::today()->year;

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
