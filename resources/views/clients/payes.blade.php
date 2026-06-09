@extends('layouts.app')

@section('title', 'Clients payés')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Bandeau --}}
    <div class="page-hero mb-4 ax-fade-up">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-check-circle me-2"></i>Clients payés</h1>
                <p class="mb-0 opacity-75">Abonnements réglés pour le mois courant</p>
            </div>
            <span class="badge bg-white text-anyxtech fs-6 px-3 py-2">
                <i class="fas fa-coins me-2"></i>{{ $payes }} payés
            </span>
        </div>
    </div>

    {{-- Barre d'outils --}}
    <div class="card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-7">
                    <form method="GET" action="{{ route('clients.payes') }}" class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="search" id="searchInput"
                               class="form-control form-control-lg ps-5"
                               placeholder="Rechercher par nom, contact ou site relais…"
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
                <i class="fas fa-info-circle fs-1 mb-3 opacity-50"></i>
                <h5 class="fw-semibold">Aucun client payé trouvé</h5>
                <p class="mb-0">Aucun client payé n'est enregistré pour le mois courant.</p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header bg-white border-0 p-3 p-md-4 d-flex align-items-center">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-check-circle me-2 text-success-600"></i>Liste des clients payés</h5>
                <span class="badge bg-success-light text-success-600 ms-2">{{ $clients->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-anyxtech text-white">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Site relais</th>
                                <th>Statut</th>
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
                                    <td class="fw-semibold text-dark">{{ $client->nom_client }}</td>
                                    <td>{{ $client->contact }}</td>
                                    <td>{{ $client->sites_relais ?? '-' }}</td>
                                    <td>
                                        @if ($client->statut)
                                            <span class="badge bg-anyxtech-light text-anyxtech">{{ strtoupper($client->statut) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success-light text-success-600"><i class="fas fa-check-circle me-1"></i>Payé</span>
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
                                        <div class="d-flex gap-2 justify-content-end">
                                            <form method="POST" action="{{ route('clients.deconnecter', $client->id) }}" onsubmit="return confirm('Confirmer la déconnexion (non paiement) de ce client ?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Déconnecter">
                                                    <i class="fas fa-power-off me-1"></i>Déconnecter
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let t;
            searchInput.addEventListener('input', function () {
                clearTimeout(t);
                t = setTimeout(() => this.closest('form').submit(), 400);
            });
        }

        @if(session('success'))
            const m = new bootstrap.Modal(document.getElementById('successModal'));
            m.show();
            setTimeout(() => m.hide(), 4000);
        @endif
    });
</script>
@endsection
