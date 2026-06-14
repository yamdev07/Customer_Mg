<?php

namespace App\Http\Controllers\Client;

use App\Actions\Client\CalculateReabonnementDateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Activite;
use App\Models\Client;

class ClientCrudController extends Controller
{
    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Stocker un nouveau client.
     */
    public function store(StoreClientRequest $request, CalculateReabonnementDateAction $calculateDate)
    {
        $validated = $request->validated();
        $validated['a_paye'] = $request->boolean('a_paye', false);
        $validated['date_reabonnement'] = $calculateDate->execute(
            new Client($validated)
        );

        $client = Client::create($validated);

        Activite::log('created', "Client « {$client->nom_client} » créé", $client);

        return redirect()->route('clients.index')
            ->with('success', 'Client ajouté avec succès !');
    }

    /**
     * Afficher un client.
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Afficher le formulaire d'édition.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Mettre à jour un client.
     */
    public function update(UpdateClientRequest $request, Client $client, CalculateReabonnementDateAction $calculateDate)
    {
        $validated = $request->validated();
        $validated['a_paye'] = (bool) $validated['a_paye'];

        $client->fill($validated);
        $client->date_reabonnement = $calculateDate->execute($client);
        $client->save();

        Activite::log('updated', "Client « {$client->nom_client} » modifié", $client);

        return redirect()->route('clients.index')
            ->with('success', 'Client modifié avec succès.');
    }

    /**
     * Supprimer un client.
     */
    public function destroy(Client $client)
    {
        $nom = $client->nom_client;
        $client->delete();

        Activite::log('deleted', "Client « {$nom} » supprimé");

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}
