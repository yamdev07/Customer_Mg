@extends('layouts.app')

@section('title', 'Liste des clients')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Bandeau --}}
    <div class="page-hero mb-4 ax-fade-up">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-users me-2"></i>Gestion des clients</h1>
                <p class="mb-0 opacity-75">Suivi des abonnements et des paiements</p>
            </div>
            <a href="{{ route('clients.create') }}" class="btn btn-light fw-semibold px-4 py-2">
                <i class="fas fa-plus me-2 text-anyxtech"></i>Ajouter un client
            </a>
        </div>
    </div>

    {{-- Statistiques --}}
    @if ($totalClientsCount > 0 || $clients->count() > 0)
        <div class="row g-3 g-md-4 mb-4">
            @php
                $stats = [
                    ['label' => 'Clients payés', 'value' => $payes,     'sub' => 'sur '.$totalClientsCount.' clients', 'icon' => 'fa-check-circle',        'color' => 'success'],
                    ['label' => 'Non payés',     'value' => $nonPayes,  'sub' => 'en attente',                         'icon' => 'fa-exclamation-triangle', 'color' => 'danger'],
                    ['label' => 'Clients actifs','value' => $actifs,    'sub' => 'connectés',                          'icon' => 'fa-wifi',                 'color' => 'anyxtech'],
                    ['label' => 'Suspendus',     'value' => $suspendus, 'sub' => 'temporairement',                     'icon' => 'fa-pause-circle',         'color' => 'warning'],
                ];
            @endphp

            @foreach ($stats as $s)
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card card-hover h-100 ax-fade-up">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="stat-ico bg-{{ $s['color'] }}-light text-{{ $s['color'] === 'anyxtech' ? 'anyxtech' : $s['color'].'-600' }}">
                                    <i class="fas {{ $s['icon'] }}"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted fw-semibold small">{{ $s['label'] }}</div>
                                    <div class="h3 fw-bold mb-0 text-{{ $s['color'] === 'anyxtech' ? 'anyxtech' : $s['color'].'-600' }}">{{ $s['value'] }}</div>
                                    <div class="text-muted small">{{ $s['sub'] }}</div>
                                </div>
                            </div>
                            <div class="progress mt-3">
                                <div class="progress-bar bg-{{ $s['color'] }}"
                                     style="width: {{ $totalClientsCount > 0 ? ($s['value'] / $totalClientsCount) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Recherche --}}
    <div class="card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <form action="{{ url()->current() }}" method="GET" class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text"
                               class="form-control form-control-lg ps-5"
                               id="searchInput"
                               name="search"
                               placeholder="Rechercher par nom, contact ou site relais…"
                               value="{{ request('search') }}">
                    </form>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <span class="badge bg-anyxtech-light text-anyxtech fs-6 px-3 py-2">
                        <i class="fas fa-database me-1"></i>{{ $totalClientsCount }} clients au total
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card">
        <div class="card-header bg-white border-0 p-3 p-md-4">
            <h5 class="mb-0 fw-semibold"><i class="fas fa-table me-2 text-anyxtech"></i>Liste des clients</h5>
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
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="clientTbody">
                        @include('clients.partials.client_list', ['clients' => $clients])
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 p-3 d-flex justify-content-center">
            {{ $clients->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>
    </div>

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
