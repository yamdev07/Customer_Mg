@extends('layouts.app')

@section('title', 'Modifier le client')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            {{-- Bandeau --}}
            <div class="page-hero mb-4 ax-fade-up">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
                    <div>
                        <h1 class="h3 fw-bold mb-1"><i class="fas fa-user-edit me-2"></i>Modification client</h1>
                        <p class="mb-0 opacity-75">{{ $client->nom_client }}</p>
                    </div>
                    <a href="{{ route('clients.index') }}" class="btn btn-light fw-semibold">
                        <i class="fas fa-arrow-left me-2 text-anyxtech"></i>Retour
                    </a>
                </div>
            </div>

            {{-- Formulaire de modification --}}
            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('clients.update', $client->id) }}" method="POST" id="clientEditForm">
                        @csrf
                        @method('PUT')

                        {{-- Informations générales --}}
                        <div class="border-start border-4 border-anyxtech ps-3 mb-4">
                            <h6 class="text-anyxtech fw-semibold mb-1">Informations générales</h6>
                            <p class="text-muted mb-0 small">Données de base du client</p>
                        </div>

                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label for="nom_client" class="form-label fw-semibold">
                                    <i class="fas fa-user me-2 text-anyxtech"></i>Nom du client
                                </label>
                                <input type="text" name="nom_client" id="nom_client"
                                       class="form-control @error('nom_client') is-invalid @enderror"
                                       value="{{ old('nom_client', $client->nom_client) }}" required>
                                <div class="invalid-feedback">@error('nom_client'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-lg-6">
                                <label for="contact" class="form-label fw-semibold">
                                    <i class="fas fa-phone me-2 text-anyxtech"></i>Contact
                                </label>
                                <input type="text" name="contact" id="contact"
                                       class="form-control @error('contact') is-invalid @enderror"
                                       value="{{ old('contact', $client->contact) }}" required>
                                <div class="invalid-feedback">@error('contact'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-lg-6">
                                <label for="sites_relais" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt me-2 text-anyxtech"></i>Site relais
                                </label>
                                <input type="text" name="sites_relais" id="sites_relais"
                                       class="form-control @error('sites_relais') is-invalid @enderror"
                                       value="{{ old('sites_relais', $client->sites_relais) }}">
                                <div class="invalid-feedback">@error('sites_relais'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-lg-6">
                                <label for="categorie" class="form-label fw-semibold">
                                    <i class="fas fa-tags me-2 text-anyxtech"></i>Catégorie
                                </label>
                                <input type="text" name="categorie" id="categorie"
                                       class="form-control @error('categorie') is-invalid @enderror"
                                       value="{{ old('categorie', $client->categorie) }}">
                                <div class="invalid-feedback">@error('categorie'){{ $message }}@enderror</div>
                            </div>
                        </div>

                        {{-- Statut et paiement --}}
                        <div class="border-start border-4 border-info ps-3 mb-4 mt-5">
                            <h6 class="text-info fw-semibold mb-1">Statut et paiement</h6>
                            <p class="text-muted mb-0 small">État du compte et informations de paiement</p>
                        </div>

                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label for="statut" class="form-label fw-semibold">
                                    <i class="fas fa-info-circle me-2 text-info"></i>Statut du compte
                                </label>
                                <select name="statut" id="statut" class="form-select @error('statut') is-invalid @enderror" required>
                                    <option value="actif" {{ old('statut', $client->statut) === 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactif" {{ old('statut', $client->statut) === 'inactif' ? 'selected' : '' }}>Inactif</option>
                                    <option value="suspendu" {{ old('statut', $client->statut) === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                </select>
                                <div class="invalid-feedback">@error('statut'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-lg-6">
                                <label for="a_paye" class="form-label fw-semibold">
                                    <i class="fas fa-credit-card me-2 text-info"></i>Statut de paiement
                                </label>
                                <select name="a_paye" id="a_paye" class="form-select @error('a_paye') is-invalid @enderror" required>
                                    <option value="1" {{ old('a_paye', $client->a_paye) == 1 ? 'selected' : '' }}>Payé</option>
                                    <option value="0" {{ old('a_paye', $client->a_paye) == 0 ? 'selected' : '' }}>Non payé</option>
                                </select>
                                <div class="invalid-feedback">@error('a_paye'){{ $message }}@enderror</div>
                            </div>
                        </div>

                        {{-- Facturation et abonnement --}}
                        <div class="border-start border-4 border-success ps-3 mb-4 mt-5">
                            <h6 class="text-success fw-semibold mb-1">Facturation et abonnement</h6>
                            <p class="text-muted mb-0 small">Détails de l'abonnement et montants</p>
                        </div>

                        <div class="row g-3">
                            <div class="col-lg-4">
                                <label for="jour_reabonnement" class="form-label fw-semibold">
                                    <i class="fas fa-calendar-day me-2 text-success"></i>Jour de réabonnement
                                </label>
                                <input type="number" name="jour_reabonnement" id="jour_reabonnement"
                                       class="form-control @error('jour_reabonnement') is-invalid @enderror"
                                       min="1" max="31" value="{{ old('jour_reabonnement', $client->jour_reabonnement) }}" required>
                                <div class="invalid-feedback">@error('jour_reabonnement'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-lg-4">
                                <label for="date_reabonnement" class="form-label fw-semibold">
                                    <i class="fas fa-calendar me-2 text-success"></i>Date de réabonnement
                                </label>
                                <input type="date" name="date_reabonnement" id="date_reabonnement"
                                       class="form-control @error('date_reabonnement') is-invalid @enderror"
                                       value="{{ old('date_reabonnement', $client->date_reabonnement) }}" readonly required>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-info-circle me-1"></i>Calculée automatiquement
                                </small>
                                <div class="invalid-feedback">@error('date_reabonnement'){{ $message }}@enderror</div>
                            </div>

                            <div class="col-lg-4">
                                <label for="montant" class="form-label fw-semibold">
                                    <i class="fas fa-money-bill-wave me-2 text-success"></i>Montant (FCFA)
                                </label>
                                <input type="number" name="montant" id="montant"
                                       class="form-control @error('montant') is-invalid @enderror"
                                       value="{{ old('montant', $client->montant) }}" step="0.01" required>
                                <div class="invalid-feedback">@error('montant'){{ $message }}@enderror</div>
                            </div>
                        </div>

                        {{-- Message d'information --}}
                        <div class="alert alert-info border-0 mt-4 bg-anyxtech-light">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-3 fs-5 text-anyxtech"></i>
                                <div>
                                    <h6 class="alert-heading mb-1 text-anyxtech">Calcul automatique de la date</h6>
                                    <p class="mb-0 small text-dark">
                                        La date de réabonnement est calculée automatiquement en fonction du jour de réabonnement
                                        et du statut de paiement. Elle sera mise à jour lors de la sauvegarde.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-5 pt-4 border-top">
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-anyxtech">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-anyxtech btn-lg px-4">
                                <i class="fas fa-save me-2"></i>
                                <span class="btn-text">Mettre à jour</span>
                                <span class="spinner-border spinner-border-sm d-none ms-2" role="status"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal succès --}}
    @if(session('success'))
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-anyxtech text-white border-0">
                        <h5 class="modal-title fw-semibold" id="successModalLabel">
                            <i class="fas fa-check-circle me-2"></i>Client mis à jour
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="stat-ico bg-success-light text-success-600 me-3"><i class="fas fa-user-check"></i></div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark">Modification réussie !</h6>
                                <p class="mb-0 text-muted">{{ session('success') }}</p>
                            </div>
                        </div>

                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-anyxtech progress-countdown" style="width: 100%;"></div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-clock me-1"></i>
                            Redirection automatique dans <span id="countdown">3</span> secondes...
                        </small>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <a href="{{ route('clients.index') }}" class="btn btn-anyxtech px-4">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .progress-countdown { animation: countdown 3s linear; }
    @keyframes countdown { from { width: 100%; } to { width: 0%; } }
</style>

{{-- Scripts avec calcul automatique de la date de réabonnement --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('clientEditForm');
        const submitBtn = form.querySelector('button[type="submit"]');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = submitBtn.querySelector('.spinner-border');

        // === CALCUL AUTOMATIQUE DE LA DATE DE RÉABONNEMENT ===
        function calculerDateReabonnement() {
            const jourReabonnement = parseInt(document.getElementById('jour_reabonnement').value);
            const statutPaiement = document.getElementById('a_paye').value;
            const dateReabonnementInput = document.getElementById('date_reabonnement');

            if (jourReabonnement >= 1 && jourReabonnement <= 31) {
                const aujourdHui = new Date();
                let mois = aujourdHui.getMonth() + 1;
                let annee = aujourdHui.getFullYear();

                // Si le client est payé, on prend le mois suivant
                if (statutPaiement === '1') {
                    mois += 1;
                    if (mois > 12) {
                        mois = 1;
                        annee += 1;
                    }
                }

                // Ajuster le jour pour ne pas dépasser le nombre de jours du mois
                const dernierJourDuMois = new Date(annee, mois, 0).getDate();
                const jourAjuste = Math.min(jourReabonnement, dernierJourDuMois);

                // Formater la date au format YYYY-MM-DD
                const dateFormatee = `${annee}-${mois.toString().padStart(2, '0')}-${jourAjuste.toString().padStart(2, '0')}`;

                // Mettre à jour le champ date_reabonnement
                dateReabonnementInput.value = dateFormatee;

                console.log('Date de réabonnement calculée:', dateFormatee);
            }
        }

        // Écouter les changements sur les champs pertinents
        document.getElementById('jour_reabonnement').addEventListener('change', calculerDateReabonnement);
        document.getElementById('jour_reabonnement').addEventListener('input', calculerDateReabonnement);
        document.getElementById('a_paye').addEventListener('change', calculerDateReabonnement);

        // Calculer initialement au chargement
        calculerDateReabonnement();

        // === VALIDATION AVANT SOUMISSION ===
        form.addEventListener('submit', function(e) {
            // S'assurer que la date de réabonnement est calculée
            calculerDateReabonnement();

            const dateReabonnement = document.getElementById('date_reabonnement').value;
            if (!dateReabonnement) {
                e.preventDefault();
                alert('Erreur : La date de réabonnement n\'a pas pu être calculée. Veuillez vérifier le jour de réabonnement.');
                return;
            }

            // Animation de soumission
            submitBtn.disabled = true;
            btnText.textContent = 'Mise à jour...';
            spinner.classList.remove('d-none');

            // Réactiver le bouton après 5 secondes en cas de problème
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    btnText.textContent = 'Mettre à jour';
                    spinner.classList.add('d-none');
                }
            }, 5000);
        });

        // Validation en temps réel
        const inputs = form.querySelectorAll('input[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.value.trim() !== '') {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });

        // Modal de succès avec compte à rebours
        @if(session('success'))
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();

            const countdownElement = document.getElementById('countdown');
            let countdown = 3;

            const countdownInterval = setInterval(() => {
                countdown--;
                if (countdownElement) {
                    countdownElement.textContent = countdown;
                }

                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    successModal.hide();
                    window.location.href = "{{ route('clients.index') }}";
                }
            }, 1000);

            // Arrêter le compte à rebours si l'utilisateur ferme manuellement
            document.getElementById('successModal').addEventListener('hidden.bs.modal', function () {
                clearInterval(countdownInterval);
            });
        @endif

        // Indicateur de modifications non sauvegardées
        const originalValues = {};
        inputs.forEach(input => {
            originalValues[input.name] = input.value;
        });

        let hasChanges = false;
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                hasChanges = (this.value !== originalValues[this.name]);
                updateSaveButton();
            });
        });

        function updateSaveButton() {
            if (hasChanges) {
                submitBtn.classList.add('btn-warning');
                submitBtn.classList.remove('btn-anyxtech');
                btnText.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Sauvegarder les modifications';
            } else {
                submitBtn.classList.remove('btn-warning');
                submitBtn.classList.add('btn-anyxtech');
                btnText.innerHTML = '<i class="fas fa-save me-2"></i>Mettre à jour';
            }
        }

        // Confirmation avant de quitter avec des modifications non sauvegardées
        window.addEventListener('beforeunload', function(e) {
            if (hasChanges) {
                e.preventDefault();
                e.returnValue = 'Vous avez des modifications non sauvegardées. Êtes-vous sûr de vouloir quitter ?';
                return e.returnValue;
            }
        });
    });
</script>
@endsection
