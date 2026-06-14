@extends('layouts.app')

@section('title', 'Réabonnement à venir')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Bandeau --}}
    <div class="page-hero mb-4 ax-fade-up">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-calendar-alt me-2"></i>Réabonnements à venir</h1>
                <p class="mb-0 opacity-75">Abonnements arrivant à échéance</p>
            </div>
            <span class="badge bg-white text-anyxtech fs-6 px-3 py-2">
                <i class="fas fa-clock me-2"></i>Prochains jours
            </span>
        </div>
    </div>

    {{-- Message d'erreur --}}
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                <div>{{ session('error') }}</div>
            </div>
        </div>
    @endif

    {{-- Cartes de statistiques --}}
    @if ($clients->count())
        @php
            $total = $clients->count();
            $payes = $clients->where('a_paye', 1)->count();
            $nonPayes = $total - $payes;
        @endphp

        <div class="row g-3 g-md-4 mb-4">
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="card card-hover h-100 ax-fade-up">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="stat-ico bg-success-light text-success-600"><i class="fas fa-check-circle"></i></div>
                            <div class="ms-3">
                                <div class="text-muted fw-semibold small">Clients payés</div>
                                <div class="h3 fw-bold mb-0 text-success-600">{{ $payes }}</div>
                                <div class="text-muted small">sur {{ $total }} clients</div>
                            </div>
                        </div>
                        <div class="progress mt-3">
                            <div class="progress-bar bg-success" style="width: {{ $total > 0 ? ($payes / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="card card-hover h-100 ax-fade-up">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="stat-ico bg-danger-light text-danger-600"><i class="fas fa-exclamation-triangle"></i></div>
                            <div class="ms-3">
                                <div class="text-muted fw-semibold small">Non payés</div>
                                <div class="h3 fw-bold mb-0 text-danger-600">{{ $nonPayes }}</div>
                                <div class="text-muted small">à relancer</div>
                            </div>
                        </div>
                        <div class="progress mt-3">
                            <div class="progress-bar bg-danger" style="width: {{ $total > 0 ? ($nonPayes / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Barre d'outils --}}
    <div class="card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-7">
                    <form method="GET" action="{{ route('clients.reabonnement') }}" class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="search" id="searchInput"
                               class="form-control form-control-lg ps-5"
                               placeholder="Rechercher par nom ou site relais…"
                               value="{{ request('search') }}">
                    </form>
                </div>
                <div class="col-lg-5 text-lg-end d-flex gap-2 justify-content-lg-end">
                    <a href="{{ route('clients.create') }}" class="btn btn-anyxtech">
                        <i class="fas fa-plus me-2"></i>Ajouter un client
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    @if ($clients->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="fas fa-check-circle fs-1 mb-3 opacity-50 text-anyxtech"></i>
                <h5 class="fw-semibold">Aucun réabonnement à venir</h5>
                <p class="mb-3">Aucun abonnement n'arrive à échéance dans les prochains jours.</p>
                <a href="{{ route('clients.index') }}" class="btn btn-anyxtech px-4">
                    <i class="fas fa-users me-2"></i>Voir tous les clients
                </a>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header bg-white border-0 p-3 p-md-4 d-flex align-items-center">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-table me-2 text-anyxtech"></i>Liste des réabonnements</h5>
                <span class="badge bg-anyxtech-light text-anyxtech ms-2">{{ $clients->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-anyxtech text-white">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Site relais</th>
                                <th>Paiement</th>
                                <th>Catégorie</th>
                                <th>Réabonnement</th>
                                <th>Montant</th>
                                <th class="pe-4 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                            <tr>
                                <td class="ps-4"><small class="text-muted">#{{ $client->id }}</small></td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $client->nom_client }}</div>
                                    <small class="text-muted">
                                        Depuis {{ $client->created_at ? $client->created_at->format('d/m/Y') : '-' }}
                                    </small>
                                </td>
                                <td>{{ $client->contact }}</td>
                                <td>{{ $client->sites_relais ?? 'Non renseigné' }}</td>
                                <td>
                                    @if($client->a_paye)
                                        <span class="badge bg-success-light text-success-600"><i class="fas fa-check-circle me-1"></i>Payé</span>
                                    @else
                                        <span class="badge bg-danger-light text-danger-600"><i class="fas fa-exclamation-circle me-1"></i>Non payé</span>
                                    @endif
                                </td>
                                <td>{{ $client->categorie ?? '-' }}</td>
                                <td>
                                    @if($client->date_reabonnement)
                                        <span class="badge bg-anyxtech-light text-anyxtech">
                                            {{ \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="fw-semibold text-anyxtech">{{ number_format($client->montant, 0, ',', ' ') }} F</td>
                                <td class="pe-4">
                                    @php
                                        // Nettoyage du numéro
                                        $numero_brut = preg_replace('/[^0-9]/', '', $client->contact);
                                        if (strlen($numero_brut) === 8) {
                                            $numero_brut = '229' . $numero_brut;
                                        }

                                        // Format de la date avec namespace complet
                                        $date = $client->date_reabonnement
                                            ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y')
                                            : 'bientôt';

                                        // Message clair
                                        $message_whatsapp = <<<MSG
                                Bonjour cher(e) client(e) {$client->nom_client},
                                Nous vous notifions que votre abonnement Internet arrive à échéance le {$date}.

                                Nous vous prions de bien vouloir procéder au réabonnement pour éviter une interruption de vos services.

                                ANYXTECH - Grandissons ensemble !

                                📱 MomoPay : *880*41*833398*{$client->montant}#
                                📞 Services clientèle : 0141421563 / 0152415241
                                MSG;

                                        // Encodage
                                        $encoded_message = rawurlencode($message_whatsapp);

                                        // Lien WhatsApp
                                        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                                        if (preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $user_agent)) {
                                            $whatsapp_link = "https://api.whatsapp.com/send?phone={$numero_brut}&text={$encoded_message}";
                                        } else {
                                            $whatsapp_link = "https://web.whatsapp.com/send?phone={$numero_brut}&text={$encoded_message}";
                                        }
                                    @endphp

                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ $whatsapp_link }}"
                                        target="_blank"
                                        class="btn btn-success btn-sm"
                                        data-bs-toggle="tooltip"
                                        data-bs-title="{{ $message_whatsapp }}">
                                            <i class="fab fa-whatsapp"></i>
                                            <span class="d-none d-md-inline ms-1">Relancer</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 p-3 d-flex justify-content-center">
                {{ $clients->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

{{-- Modale de succès --}}
@if(session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-anyxtech text-white border-0">
                    <h5 class="modal-title fw-semibold"><i class="fas fa-check-circle me-2"></i>Opération réussie</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-ico bg-success-light text-success-600 me-3"><i class="fas fa-check"></i></div>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-anyxtech px-4" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>Compris
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Recherche serveur (debounce 400ms)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let t;
        searchInput.addEventListener('input', function () {
            clearTimeout(t);
            t = setTimeout(() => this.closest('form').submit(), 400);
        });
    }

    // Modal succès auto hide
    @if(session('success'))
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        setTimeout(() => successModal.hide(), 4000);
    @endif

    // Activer les tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
