@extends('layouts.app')

@section('title', 'Détail du service — ' . $service->nom)

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    <div class="page-hero mb-4 ax-fade-up">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('services.index') }}" class="text-decoration-none">Services</a></li>
                        <li class="breadcrumb-item active">{{ $service->nom }}</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold mb-1">
                    <span class="me-2" style="display:inline-block;width:14px;height:14px;border-radius:50%;background:{{ $service->couleur }}"></span>
                    {{ $service->nom }}
                </h1>
                @if($service->description)
                    <p class="mb-0 opacity-75">{{ $service->description }}</p>
                @endif
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('services.edit', $service) }}" class="btn btn-outline-secondary fw-semibold px-4 py-2">
                    <i class="fas fa-pen me-2"></i>Modifier
                </a>
                <button class="btn btn-danger fw-semibold px-4 py-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card card-hover h-100 ax-fade-up">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-ico bg-anyxtech-light text-anyxtech">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="ms-3">
                            <div class="text-muted fw-semibold small">Clients</div>
                            <div class="h3 fw-bold mb-0 text-anyxtech">{{ $totalClients }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card card-hover h-100 ax-fade-up">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-ico bg-success-light text-success-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <div class="text-muted fw-semibold small">Payés ce mois</div>
                            <div class="h3 fw-bold mb-0 text-success-600">{{ $clientsPayes }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card card-hover h-100 ax-fade-up">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-ico bg-warning-light text-warning">
                            <i class="fas fa-money-bill"></i>
                        </div>
                        <div class="ms-3">
                            <div class="text-muted fw-semibold small">Chiffre d'affaires</div>
                            <div class="h3 fw-bold mb-0 text-warning">{{ number_format($chiffreAffaires, 0, ',', ' ') }} FCFA</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ajouter un client --}}
    @if(auth()->user()->role === 'admin')
    <div class="card mb-4">
        <div class="card-header bg-white border-0 p-3">
            <h5 class="mb-0 fw-semibold"><i class="fas fa-user-plus me-2 text-anyxtech"></i>Ajouter un client à ce service</h5>
        </div>
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('services.addClient', $service) }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Client</label>
                    <select name="client_id" class="form-select" required>
                        <option value="">-- Choisir un client --</option>
                        @foreach(\App\Models\Client::orderBy('nom_client')->get() as $c)
                            <option value="{{ $c->id }}">{{ $c->nom_client }} — {{ $c->contact }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Montant (FCFA)</label>
                    <input type="number" name="montant" class="form-control" value="{{ $service->prix_defaut }}" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Jour réab.</label>
                    <input type="number" name="jour_reabonnement" class="form-control" min="1" max="31" value="1">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-anyxtech w-100">
                        <i class="fas fa-plus me-1"></i>Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Liste des clients --}}
    <div class="card">
        <div class="card-header bg-white border-0 p-3 p-md-4">
            <h5 class="mb-0 fw-semibold"><i class="fas fa-table me-2 text-anyxtech"></i>Clients associés ({{ $totalClients }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-anyxtech text-white">
                        <tr>
                            <th class="ps-4">Client</th>
                            <th>Contact</th>
                            <th>Montant</th>
                            <th>Jour réab.</th>
                            <th>Date réab.</th>
                            <th>Statut paiement</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($service->clients as $client)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('clients.show', $client) }}" class="text-decoration-none fw-semibold">
                                        {{ $client->nom_client }}
                                    </a>
                                </td>
                                <td>{{ $client->contact }}</td>
                                <td>{{ $client->pivot->montant ? number_format($client->pivot->montant, 0, ',', ' ') . ' FCFA' : '—' }}</td>
                                <td>{{ $client->pivot->jour_reabonnement ?? '—' }}</td>
                                <td>{{ $client->pivot->date_reabonnement ? \Carbon\Carbon::parse($client->pivot->date_reabonnement)->format('d/m/Y') : '—' }}</td>
                                <td>
                                    @if($client->pivot->a_paye)
                                        <span class="badge bg-success">Payé</span>
                                    @else
                                        <span class="badge bg-danger">Impayé</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    <form action="{{ route('services.removeClient', [$service, $client]) }}" method="POST"
                                          onsubmit="return confirm('Retirer ce client du service ?')" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Aucun client associé à ce service.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal suppression --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Supprimer le service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Voulez-vous vraiment supprimer le service <strong>{{ $service->nom }}</strong> ?
                    <br><small class="text-muted">Les associations clients seront également supprimées.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
