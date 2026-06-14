@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            {{-- Bandeau --}}
            <div class="page-hero mb-4 ax-fade-up">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
                    <div>
                        <h1 class="h3 fw-bold mb-1"><i class="fas fa-user-circle me-2"></i>Mon profil</h1>
                        <p class="mb-0 opacity-75">Gérez vos informations personnelles et votre sécurité</p>
                    </div>
                    <a href="{{ route('clients.index') }}" class="btn btn-light fw-semibold">
                        <i class="fas fa-arrow-left me-2 text-anyxtech"></i>Retour
                    </a>
                </div>
            </div>

            {{-- Résumé du profil --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3"><i class="fas fa-id-card me-2 text-anyxtech"></i>Résumé du profil</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2">Nom</dt>
                        <dd class="col-sm-8 fw-semibold text-dark py-2">{{ $user->name }}</dd>

                        <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Email</dt>
                        <dd class="col-sm-8 fw-semibold text-dark py-2 border-top">{{ $user->email }}</dd>

                        <dt class="col-sm-4 text-muted text-uppercase small fw-bold py-2 border-top">Date d'inscription</dt>
                        <dd class="col-sm-8 fw-semibold text-dark py-2 border-top">{{ $user->created_at->format('d/m/Y') }}</dd>
                    </dl>
                </div>
            </div>

            {{-- Mise à jour des informations --}}
            <div class="card mb-4">
                <div class="card-body p-4 p-md-5">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Mise à jour du mot de passe --}}
            <div class="card mb-4">
                <div class="card-body p-4 p-md-5">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Suppression du compte --}}
            <div class="card mb-4">
                <div class="card-body p-4 p-md-5">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
