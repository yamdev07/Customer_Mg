@extends('layouts.app')

@section('title', 'Clients Actifs')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Bandeau --}}
    <div class="page-hero mb-4 ax-fade-up">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-bolt me-2"></i>Clients actifs</h1>
                <p class="mb-0 opacity-75">Abonnements actuellement connectés</p>
            </div>
            <span class="badge bg-white text-anyxtech fs-6 px-3 py-2">
                <i class="fas fa-wifi me-2"></i>{{ $clients->total() }} actifs
            </span>
        </div>
    </div>

    {{-- Barre d'outils --}}
    <div class="card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-7">
                    <form method="GET" action="{{ route('clients.actifs') }}" class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="search" id="searchInput"
                               class="form-control form-control-lg ps-5"
                               placeholder="Rechercher un client…"
                               value="{{ request('search') }}">
                    </form>
                </div>
                <div class="col-lg-5 text-lg-end d-flex gap-2 justify-content-lg-end">
                    <form action="{{ route('clients.export') }}" method="POST">
                        @csrf
                        <input type="hidden" name="format" value="pdf">
                        <button type="submit" class="btn btn-outline-anyxtech">
                            <i class="fas fa-file-export me-2"></i>Exporter
                        </button>
                    </form>
                    <a href="{{ route('clients.create') }}" class="btn btn-anyxtech">
                        <i class="fas fa-plus me-2"></i>Nouveau client
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    @if($clients->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="fas fa-users-slash fs-1 mb-3 opacity-50"></i>
                <h5 class="fw-semibold">Aucun client actif</h5>
                <p class="mb-0">Aucun client n'est actuellement actif dans le système.</p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header bg-white border-0 p-3 p-md-4 d-flex align-items-center">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-table me-2 text-anyxtech"></i>Liste des clients actifs</h5>
                <span class="badge bg-anyxtech-light text-anyxtech ms-2">{{ $clients->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-anyxtech text-white">
                            <tr>
                                <th class="ps-4">Client</th>
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
                            @foreach($clients as $client)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $client->nom_client }}</div>
                                    <small class="text-muted">#{{ $client->id }}</small>
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
                                        <a href="{{ route('clients.edit', $client->id) }}"
                                           class="btn btn-outline-anyxtech btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('clients.suspendre', $client->id) }}" method="POST"
                                              onsubmit="return confirm('Suspendre ce client ?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-outline-warning btn-sm" title="Suspendre">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        </form>
                                        @if(!$client->a_paye)
                                        <form action="{{ route('clients.marquer-paye', $client->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Marquer payé">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                        @endif
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
    document.addEventListener('DOMContentLoaded', function () {
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
