@extends('layouts.app')

@section('title', 'Ajouter un client')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            {{-- Bandeau --}}
            <div class="page-hero mb-4 ax-fade-up">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
                    <div>
                        <h1 class="h3 fw-bold mb-1"><i class="fas fa-user-plus me-2"></i>Ajouter un client</h1>
                        <p class="mb-0 opacity-75">Remplissez les informations requises</p>
                    </div>
                    <a href="{{ route('clients.index') }}" class="btn btn-light fw-semibold">
                        <i class="fas fa-arrow-left me-2 text-anyxtech"></i>Retour
                    </a>
                </div>
            </div>

            {{-- Formulaire --}}
            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nom_client" class="form-label fw-semibold">
                                    <i class="fas fa-user me-2 text-anyxtech"></i>Nom du client <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nom_client" id="nom_client"
                                       class="form-control @error('nom_client') is-invalid @enderror"
                                       value="{{ old('nom_client') }}" required>
                                <div class="invalid-feedback">@error('nom_client'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-md-6">
                                <label for="contact" class="form-label fw-semibold">
                                    <i class="fas fa-phone me-2 text-anyxtech"></i>Contact <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="contact" id="contact"
                                       class="form-control @error('contact') is-invalid @enderror"
                                       value="{{ old('contact') }}" required>
                                <div class="invalid-feedback">@error('contact'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-md-6">
                                <label for="sites_relais" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt me-2 text-anyxtech"></i>Site relais
                                </label>
                                <input type="text" name="sites_relais" id="sites_relais"
                                       class="form-control @error('sites_relais') is-invalid @enderror"
                                       value="{{ old('sites_relais') }}">
                                <div class="invalid-feedback">@error('sites_relais'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-md-6">
                                <label for="statut" class="form-label fw-semibold">
                                    <i class="fas fa-circle-notch me-2 text-anyxtech"></i>Statut
                                </label>
                                <select name="statut" id="statut" class="form-select @error('statut') is-invalid @enderror">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="actif" {{ old('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactif" {{ old('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                                    <option value="suspendu" {{ old('statut') === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                </select>
                                <div class="invalid-feedback">@error('statut'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-md-6">
                                <label for="categorie" class="form-label fw-semibold">
                                    <i class="fas fa-tag me-2 text-anyxtech"></i>Catégorie
                                </label>
                                <input type="text" name="categorie" id="categorie"
                                       class="form-control @error('categorie') is-invalid @enderror"
                                       value="{{ old('categorie') }}">
                                <div class="invalid-feedback">@error('categorie'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-md-6">
                                <label for="jour_reabonnement" class="form-label fw-semibold">
                                    <i class="fas fa-calendar-day me-2 text-anyxtech"></i>Jour de réabonnement <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="jour_reabonnement" id="jour_reabonnement"
                                       class="form-control @error('jour_reabonnement') is-invalid @enderror"
                                       min="1" max="31" value="{{ old('jour_reabonnement') }}" required>
                                <small class="form-text text-muted">Exemple : 5 => tous les 5 du mois</small>
                                <div class="invalid-feedback">@error('jour_reabonnement'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-md-6">
                                <label for="montant" class="form-label fw-semibold">
                                    <i class="fas fa-money-bill-wave me-2 text-anyxtech"></i>Montant <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">FCFA</span>
                                    <input type="number" name="montant" id="montant"
                                           class="form-control @error('montant') is-invalid @enderror"
                                           min="0" value="{{ old('montant') }}" required>
                                    <div class="invalid-feedback">@error('montant'){{ $message }}@enderror</div>
                                </div>
                            </div>

                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="a_paye"
                                           id="a_paye" value="1" {{ old('a_paye') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="a_paye">
                                        <i class="fas fa-check-circle me-2 text-anyxtech"></i>Le client a payé ?
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-5 pt-4 border-top">
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-anyxtech">
                                <i class="fas fa-arrow-left me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-anyxtech btn-lg px-4">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');

        // La validation HTML5 (champs « required ») bloque déjà l'envoi si un champ
        // obligatoire est vide, avec un message natif. Ici on empêche simplement le
        // double-clic et on indique que l'enregistrement est en cours (fluidité).
        form.addEventListener('submit', function () {
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement…';
            }
        });
    });
</script>
@endsection
