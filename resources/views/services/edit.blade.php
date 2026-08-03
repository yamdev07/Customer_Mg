@extends('layouts.app')

@section('title', 'Modifier — ' . $service->nom)

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    <div class="page-hero mb-4 ax-fade-up">
        <div class="position-relative" style="z-index:1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('services.index') }}" class="text-decoration-none">Services</a></li>
                    <li class="breadcrumb-item active">{{ $service->nom }}</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-0"><i class="fas fa-pen me-2"></i>Modifier {{ $service->nom }}</h1>
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
            <form action="{{ route('services.update', $service) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nom du service *</label>
                        <input type="text" name="nom" class="form-control" required
                               value="{{ old('nom', $service->nom) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Prix par défaut (FCFA)</label>
                        <input type="number" name="prix_defaut" class="form-control" min="0"
                               value="{{ old('prix_defaut', $service->prix_defaut) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Couleur</label>
                        <input type="color" name="couleur" class="form-control form-control-color"
                               value="{{ old('couleur', $service->couleur) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $service->description) }}</textarea>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('services.show', $service) }}" class="btn btn-outline-secondary px-4">Annuler</a>
                    <button type="submit" class="btn btn-anyxtech px-4">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
