@extends('layouts.app')

@section('title', 'Détails du client: ' . ($client->nom_client ?? 'N/A'))

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Bandeau --}}
    <div class="page-hero mb-4 ax-fade-up">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-user-circle me-2"></i>{{ $client->nom_client ?? 'N/A' }}</h1>
                <p class="mb-0 opacity-75">Informations complètes sur l'abonnement</p>
            </div>
            <a href="{{ route('clients.index') }}" class="btn btn-light fw-semibold">
                <i class="fas fa-arrow-left me-2 text-anyxtech"></i>Retour
            </a>
        </div>
    </div>

    {{-- Informations générales --}}
    <div class="card mb-4">
        <div class="card-header bg-white border-0 p-3 p-md-4">
            <h5 class="mb-0 fw-semibold"><i class="fas fa-info-circle me-2 text-anyxtech"></i>Informations générales</h5>
        </div>
        <div class="card-body p-3 p-md-4">
            <dl class="row mb-0">
                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2">Nom du client</dt>
                <dd class="col-sm-8 fw-semibold text-dark py-2">{{ $client->nom_client ?? 'Non spécifié' }}</dd>

                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Contact</dt>
                <dd class="col-sm-8 fw-semibold text-dark py-2 border-top">{{ $client->contact ?? 'Non spécifié' }}</dd>

                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Email</dt>
                <dd class="col-sm-8 fw-semibold text-dark py-2 border-top">{{ $client->email ?? 'N/A' }}</dd>

                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Site relais</dt>
                <dd class="col-sm-8 fw-semibold text-dark py-2 border-top">{{ $client->sites_relais ?? 'N/A' }}</dd>

                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Catégorie</dt>
                <dd class="col-sm-8 fw-semibold text-dark py-2 border-top">{{ $client->categorie ?? 'Non classé' }}</dd>

                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Statut actuel</dt>
                <dd class="col-sm-8 py-2 border-top">
                    @if ($client->statut == 'actif')
                        <span class="badge bg-success-light text-success-600"><i class="fas fa-check-circle me-1"></i>Actif</span>
                    @elseif ($client->statut == 'suspendu')
                        <span class="badge bg-warning-light text-warning-600"><i class="fas fa-pause-circle me-1"></i>Suspendu</span>
                    @else
                        <span class="badge bg-anyxtech-light text-anyxtech">{{ $client->statut ?? 'Inconnu' }}</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    {{-- Abonnement et paiement --}}
    <div class="card mb-4">
        <div class="card-header bg-white border-0 p-3 p-md-4">
            <h5 class="mb-0 fw-semibold"><i class="fas fa-calendar-alt me-2 text-anyxtech"></i>Abonnement et paiement</h5>
        </div>
        <div class="card-body p-3 p-md-4">
            <dl class="row mb-0">
                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2">Jour de réabonnement</dt>
                <dd class="col-sm-8 fw-semibold text-dark py-2">{{ $client->jour_reabonnement ?? 'Non défini' }}</dd>

                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Date de réabonnement</dt>
                <dd class="col-sm-8 fw-semibold text-dark py-2 border-top">
                    @if ($client->date_reabonnement)
                        {{ \Carbon\Carbon::parse($client->date_reabonnement)->format('d F Y') }}
                        @if (\Carbon\Carbon::parse($client->date_reabonnement)->isPast() && !$client->a_paye)
                            <span class="badge bg-danger-light text-danger-600 ms-2">Expiré</span>
                        @elseif (\Carbon\Carbon::parse($client->date_reabonnement)->diffInDays(now()) <= 7 && !$client->a_paye)
                            <span class="badge bg-warning-light text-warning-600 ms-2">Bientôt</span>
                        @endif
                    @else
                        Non défini
                    @endif
                </dd>

                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Montant de l'abonnement</dt>
                <dd class="col-sm-8 text-anyxtech fw-semibold py-2 border-top">{{ number_format($client->montant ?? 0, 0, ',', ' ') }} FCFA</dd>

                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Statut de paiement</dt>
                <dd class="col-sm-8 py-2 border-top">
                    @if ($client->a_paye)
                        <span class="badge bg-success-light text-success-600"><i class="fas fa-check-circle me-1"></i>Payé</span>
                    @else
                        <span class="badge bg-danger-light text-danger-600"><i class="fas fa-exclamation-circle me-1"></i>Non payé</span>
                    @endif
                </dd>

                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Date d'ajout</dt>
                <dd class="col-sm-8 fw-semibold text-dark py-2 border-top">
                    {{ $client->created_at ? \Carbon\Carbon::parse($client->created_at)->format('d F Y H:i') : 'N/A' }}
                </dd>

                <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Dernière mise à jour</dt>
                <dd class="col-sm-8 fw-semibold text-dark py-2 border-top">
                    {{ $client->updated_at ? \Carbon\Carbon::parse($client->updated_at)->format('d F Y H:i') : 'N/A' }}
                </dd>
            </dl>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="card mb-4">
        <div class="card-header bg-white border-0 p-3 p-md-4">
            <h5 class="mb-0 fw-semibold"><i class="fas fa-cogs me-2 text-anyxtech"></i>Actions rapides</h5>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-wrap gap-3">
                @if ($client->a_paye == 0)
                    <form action="{{ route('clients.reconnecter', $client->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-plug me-2"></i> Reconnecter (Marquer payé)
                        </button>
                    </form>
                @else
                    <form action="{{ route('clients.deconnecter', $client->id) }}" method="POST">
                        @csrf
                        {{-- Laravel n'accepte pas PATCH/PUT avec un formulaire HTML simple si ce n'est pas _method, donc POST est ok pour deconnecter si c'est ce que vous aviez --}}
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-plug-circle-xmark me-2"></i> Déconnecter (Marquer non payé)
                        </button>
                    </form>
                @endif

                @if ($client->statut == 'actif')
                    <form action="{{ route('clients.suspendre', $client->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="fas fa-pause me-2"></i> Suspendre le client
                        </button>
                    </form>
                @endif

                @php
                    // Nettoyer le numéro et ajouter l'indicatif (ici +229 pour Bénin, adapte si besoin)
                    $numero = preg_replace('/[^0-9]/', '', $client->contact);
                    if (strlen($numero) === 8) {
                        $numero = '229' . $numero;
                    }

                    // Message prérempli (tu peux personnaliser)
                    $message = "Bonjour {$client->nom_client}, nous vous rappelons que votre abonnement est en attente de paiement. Merci de nous contacter rapidement.";
                    $messageEncoded = urlencode($message);
                @endphp

                <a href="https://wa.me/{{ $numero }}?text={{ $messageEncoded }}" target="_blank" class="btn btn-success px-4">
                    <i class="fab fa-whatsapp me-2"></i> Relancer par WhatsApp
                </a>
            </div>
        </div>
    </div>

    {{-- Boutons d'action principaux --}}
    <div class="d-flex flex-column flex-sm-row justify-content-end gap-3">
        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-anyxtech btn-lg px-4">
            <i class="fas fa-edit me-2"></i>Modifier le client
        </a>
        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ? Cette action est irréversible.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-lg px-4">
                <i class="fas fa-trash-alt me-2"></i>Supprimer le client
            </button>
        </form>
    </div>

</div>
@endsection
