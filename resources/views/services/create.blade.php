@extends('layouts.app')

@section('title', 'Nouveau service')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    <div class="page-hero mb-4 ax-fade-up">
        <div class="position-relative" style="z-index:1">
            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="fas fa-arrow-left me-1"></i>Retour
            </a>
            <h1 class="h3 fw-bold mb-0"><i class="fas fa-plus-circle me-2"></i>Nouveau service</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('services.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nom du service *</label>
                        <input type="text" name="nom" class="form-control" required
                               value="{{ old('nom') }}" placeholder="Ex: CRM, ERP, Stock...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Prix par défaut (FCFA)</label>
                        <input type="number" name="prix_defaut" class="form-control" min="0"
                               value="{{ old('prix_defaut') }}" placeholder="Montant mensuel">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Couleur</label>
                        <input type="color" name="couleur" class="form-control form-control-color"
                               value="{{ old('couleur', '#3B82F6') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Description du service...">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
                    <button type="submit" class="btn btn-anyxtech px-4">
                        <i class="fas fa-save me-2"></i>Créer le service
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
