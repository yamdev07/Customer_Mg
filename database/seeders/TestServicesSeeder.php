<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Service;
use App\Models\Connexion;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TestServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all();

        $clientsData = [
            ['nom_client' => 'Kofi Mensah', 'contact' => '+229 97 12 34 56', 'email' => 'kofi@test.com', 'lieu' => 'Cotonou', 'statut' => 'actif'],
            ['nom_client' => 'Amina Bello', 'contact' => '+229 96 23 45 67', 'email' => 'amina@test.com', 'lieu' => 'Porto-Novo', 'statut' => 'actif'],
            ['nom_client' => 'Yao Pierre', 'contact' => '+229 95 34 56 78', 'email' => 'yao@test.com', 'lieu' => 'Parakou', 'statut' => 'actif'],
            ['nom_client' => 'Fatima Sow', 'contact' => '+229 94 45 67 89', 'email' => 'fatima@test.com', 'lieu' => 'Abomey', 'statut' => 'actif'],
            ['nom_client' => 'Ibrahim Kone', 'contact' => '+229 93 56 78 90', 'email' => 'ibrahim@test.com', 'lieu' => 'Bohicon', 'statut' => 'actif'],
            ['nom_client' => 'Mariam Diallo', 'contact' => '+229 92 67 89 01', 'email' => 'mariam@test.com', 'lieu' => 'Cotonou', 'statut' => 'actif'],
            ['nom_client' => 'Oumarou Traore', 'contact' => '+229 91 78 90 12', 'email' => 'oumarou@test.com', 'lieu' => 'Natitingou', 'statut' => 'suspendu'],
            ['nom_client' => 'Adama Garba', 'contact' => '+229 90 89 01 23', 'email' => 'adama@test.com', 'lieu' => 'Lokossa', 'statut' => 'actif'],
        ];

        $clients = collect();
        foreach ($clientsData as $data) {
            $clients->push(Client::create($data));
        }

        $montants = [25000, 15000, 30000, 20000, 10000];

        foreach ($services as $index => $service) {
            $nbClients = match($index) {
                0 => 5,
                1 => 3,
                2 => 4,
                default => 2,
            };

            $selectedClients = $clients->random($nbClients);

            foreach ($selectedClients as $i => $client) {
                $service->clients()->attach($client->id, [
                    'montant' => $montants[array_rand($montants)],
                    'jour_reabonnement' => rand(1, 28),
                    'date_reabonnement' => Carbon::now()->addDays(rand(1, 30)),
                    'a_paye' => $i < ($nbClients - 1),
                ]);

                Connexion::create([
                    'client_id' => $client->id,
                    'service_id' => $service->id,
                    'date_connexion' => Carbon::now()->subDays(rand(0, 15)),
                    'ip_address' => '192.168.1.' . rand(1, 254),
                    'user_agent' => 'Mozilla/5.0 (Test)',
                ]);
            }
        }
    }
}
