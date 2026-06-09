@extends('layouts.app')

@section('title', 'Clients Suspendus')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Bandeau --}}
    <div class="page-hero mb-4 ax-fade-up">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-pause-circle me-2"></i>Clients suspendus</h1>
                <p class="mb-0 opacity-75">Abonnements temporairement suspendus</p>
            </div>
            <span class="badge bg-white text-anyxtech fs-6 px-3 py-2">
                <i class="fas fa-users me-2"></i>{{ $clients->total() }} suspendu(s)
            </span>
        </div>
    </div>

    {{-- Barre d'outils --}}
    <div class="card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <form method="GET" action="{{ route('clients.suspendus') }}" class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="search" id="searchInput"
                               class="form-control form-control-lg ps-5"
                               placeholder="Rechercher un client…"
                               value="{{ request('search') }}">
                    </form>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-anyxtech">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    @if ($clients->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="fas fa-check-circle fs-1 mb-3 opacity-50 text-success-600"></i>
                <h5 class="fw-semibold">Aucun client suspendu</h5>
                <p class="mb-0">Tous vos clients sont actuellement actifs.</p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header bg-white border-0 p-3 p-md-4 d-flex align-items-center">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-table me-2 text-warning-600"></i>Liste des suspensions</h5>
                <span class="badge bg-warning-light text-warning-600 ms-2">{{ $clients->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-anyxtech text-white">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Site</th>
                                <th>Catégorie</th>
                                <th>Réabonnement</th>
                                <th>Montant</th>
                                <th>Paiement</th>
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
                                        Depuis {{ $client->created_at ? $client->created_at->format('d/m/Y') : 'Date inconnue' }}
                                    </small>
                                </td>
                                <td>{{ $client->contact }}</td>
                                <td>{{ $client->sites_relais ?? '-' }}</td>
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
                                <td>
                                    @if($client->a_paye)
                                        <span class="badge bg-success-light text-success-600"><i class="fas fa-check-circle me-1"></i>Payé</span>
                                    @else
                                        <span class="badge bg-danger-light text-danger-600"><i class="fas fa-exclamation-circle me-1"></i>Non payé</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        @php
                                            $numero = preg_replace('/[^0-9]/', '', $client->contact);
                                            if (strlen($numero) === 8) {
                                                $numero = '229' . $numero;
                                            }
                                            $date = $client->date_reabonnement
                                                ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y')
                                                : 'bientôt';
                                            $message = "Bonjour {$client->nom_client}, votre réabonnement est arrivé à échéance le {$date}. Merci de penser à renouveler pour éviter toute interruption de service. - AnyxTech";
                                        @endphp

                                        <a href="{!! 'https://wa.me/' . $numero . '?text=' . urlencode($message) !!}"
                                           target="_blank"
                                           class="btn btn-success btn-sm"
                                           data-bs-toggle="tooltip"
                                           data-bs-title="Relancer par WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                            <span class="d-none d-md-inline ms-1">Relancer</span>
                                        </a>

                                        <form action="{{ route('clients.reactiver', $client->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-anyxtech btn-sm"
                                                    data-bs-toggle="tooltip" data-bs-title="Réactiver le client">
                                                <i class="fas fa-play"></i>
                                                <span class="d-none d-md-inline ms-1">Réactiver</span>
                                            </button>
                                        </form>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Activer les tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Recherche serveur (debounce 400ms)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let t;
        searchInput.addEventListener('input', function () {
            clearTimeout(t);
            t = setTimeout(() => this.closest('form').submit(), 400);
        });
    }
});
</script>
@endsection
