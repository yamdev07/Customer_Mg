<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Client;
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
        $service->load(['clients' => function ($q) {
            $q->orderBy('nom_client');
        }]);

        $totalClients = $service->clients->count();
        $clientsPayes = $service->clients->where('pivot.a_paye', true)->count();
        $chiffreAffaires = $service->clients->sum('pivot.montant');

        return view('services.show', compact('service', 'totalClients', 'clientsPayes', 'chiffreAffaires'));
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
}
