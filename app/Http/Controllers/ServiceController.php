<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Client;
use App\Models\Connexion;
use App\Models\SaaS\HotelUser;
use App\Models\SaaS\HotelCustomer;
use App\Models\SaaS\HotelSubscription;
use App\Models\SaaS\CuniAppUser;
use App\Models\SaaS\CuniAppSubscription;
use App\Models\SaaS\CuniAppFirm;
use App\Models\SaaS\ClicTableUser;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::withCount('clients')->get();

        return view('services.index', compact('services'));
    }

    public function show(Service $service)
    {
        $saasData = $this->getSaasData($service);

        return view('services.show', array_merge(['service' => $service], $saasData));
    }

    private function getSaasData(Service $service): array
    {
        $nom = strtolower($service->nom);

        if (str_contains($nom, 'hotel')) {
            return $this->getHotelData();
        }

        if (str_contains($nom, 'cuni')) {
            return $this->getCuniAppData();
        }

        if (str_contains($nom, 'clic') || str_contains($nom, 'table') || str_contains($nom, 'pos')) {
            return $this->getClicTableData();
        }

        return $this->getDefaultData($service);
    }

    private function getHotelData(): array
    {
        $users = HotelUser::latest('created_at')->limit(20)->get();
        $customers = HotelCustomer::count();
        $subscriptions = HotelSubscription::count();

        $dernieresConnexions = HotelUser::orderByDesc('last_login_at')
            ->limit(20)
            ->get()
            ->map(fn ($u) => (object) [
                'nom' => $u->name,
                'email' => $u->email,
                'date_connexion' => $u->last_login_at,
                'statut' => $u->is_active_session ? 'Actif' : 'Inactif',
            ]);

        $nouveauxClients = HotelUser::orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn ($u) => (object) [
                'nom' => $u->name,
                'email' => $u->email,
                'created_at' => $u->created_at,
                'role' => $u->role,
            ]);

        return compact('users', 'customers', 'subscriptions', 'dernieresConnexions', 'nouveauxClients');
    }

    private function getCuniAppData(): array
    {
        $users = CuniAppUser::latest('created_at')->limit(20)->get();
        $firms = CuniAppFirm::count();
        $subscriptions = CuniAppSubscription::count();

        $dernieresConnexions = CuniAppUser::orderByDesc('last_seen_at')
            ->limit(20)
            ->get()
            ->map(fn ($u) => (object) [
                'nom' => $u->name,
                'email' => $u->email,
                'date_connexion' => $u->last_seen_at,
                'statut' => $u->status === 'active' ? 'Actif' : 'Inactif',
            ]);

        $nouveauxClients = CuniAppUser::orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn ($u) => (object) [
                'nom' => $u->name,
                'email' => $u->email,
                'created_at' => $u->created_at,
                'role' => $u->role,
            ]);

        return compact('users', 'firms', 'subscriptions', 'dernieresConnexions', 'nouveauxClients');
    }

    private function getClicTableData(): array
    {
        $users = ClicTableUser::latest('created_at')->limit(20)->get();

        $dernieresConnexions = ClicTableUser::orderByDesc('updated_at')
            ->limit(20)
            ->get()
            ->map(fn ($u) => (object) [
                'nom' => $u->name ?? $u->email ?? 'User #' . $u->id,
                'email' => $u->email,
                'date_connexion' => $u->updated_at,
                'statut' => 'Actif',
            ]);

        $nouveauxClients = ClicTableUser::orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn ($u) => (object) [
                'nom' => $u->name ?? $u->email ?? 'User #' . $u->id,
                'email' => $u->email,
                'created_at' => $u->created_at,
                'role' => $u->role ?? 'user',
            ]);

        return compact('users', 'dernieresConnexions', 'nouveauxClients');
    }

    private function getDefaultData(Service $service): array
    {
        $service->load(['clients' => function ($q) {
            $q->orderBy('nom_client');
        }]);

        $totalClients = $service->clients->count();
        $clientsPayes = $service->clients->where('pivot.a_paye', true)->count();
        $chiffreAffaires = $service->clients->sum('pivot.montant');

        $dernieresConnexions = $service->connexions()
            ->with('client')
            ->latest('date_connexion')
            ->limit(20)
            ->get();

        $nouveauxClients = $service->clients()
            ->withPivot('created_at')
            ->orderByDesc('client_service.created_at')
            ->limit(10)
            ->get();

        return compact('totalClients', 'clientsPayes', 'chiffreAffaires', 'dernieresConnexions', 'nouveauxClients');
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:services',
            'description' => 'nullable|string',
            'prix_defaut' => 'nullable|numeric|min:0',
            'couleur' => 'nullable|string|max:7',
        ]);

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Service créé avec succès.');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:services,nom,' . $service->id,
            'description' => 'nullable|string',
            'prix_defaut' => 'nullable|numeric|min:0',
            'couleur' => 'nullable|string|max:7',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service supprimé.');
    }

    public function addClient(Request $request, Service $service)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'montant' => 'nullable|numeric|min:0',
            'jour_reabonnement' => 'nullable|integer|min:1|max:31',
        ]);

        $service->clients()->syncWithoutDetaching([
            $validated['client_id'] => [
                'montant' => $validated['montant'] ?? $service->prix_defaut,
                'jour_reabonnement' => $validated['jour_reabonnement'] ?? null,
                'date_reabonnement' => now()->addMonth(),
                'a_paye' => false,
            ],
        ]);

        return redirect()->route('services.show', $service)->with('success', 'Client ajouté au service.');
    }

    public function removeClient(Service $service, Client $client)
    {
        $service->clients()->detach($client->id);

        return redirect()->route('services.show', $service)->with('success', 'Client retiré du service.');
    }

    public function enregistrerConnexion(Request $request, Service $service)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
        ]);

        Connexion::create([
            'client_id' => $validated['client_id'],
            'service_id' => $service->id,
            'date_connexion' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('services.show', $service)->with('success', 'Connexion enregistrée.');
    }
}
