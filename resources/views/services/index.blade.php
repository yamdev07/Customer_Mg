@extends('layouts.app')

@section('title', 'Services')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    <div class="page-hero mb-4 ax-fade-up">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-cogs me-2"></i>Services SaaS</h1>
                <p class="mb-0 opacity-75">Gestion des logiciels et services AnyxTech</p>
            </div>
            <a href="{{ route('services.create') }}" class="btn btn-light fw-semibold px-4 py-2">
                <i class="fas fa-plus me-2 text-anyxtech"></i>Nouveau service
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 g-md-4">
        @forelse($services as $service)
            <div class="col-xl-4 col-lg-6">
                <div class="card card-hover h-100 ax-fade-up">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stat-ico" style="background: {{ $service->couleur }}20; color: {{ $service->couleur }}">
                                <i class="fas fa-cube"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="fw-bold mb-0">{{ $service->nom }}</h5>
                                <small class="text-muted">{{ $service->clients_count }} client(s)</small>
                            </div>
                        </div>
                        @if($service->description)
                            <p class="text-muted small mb-3">{{ Str::limit($service->description, 100) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge" style="background: {{ $service->couleur }}20; color: {{ $service->couleur }}">
                                {{ $service->prix_defaut ? number_format($service->prix_defaut, 0, ',', ' ') . ' FCFA/mois' : 'Prix non défini' }}
                            </span>
                            <span class="badge {{ $service->actif ? 'bg-success' : 'bg-secondary' }}">
                                {{ $service->actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-3 d-flex gap-2">
                        <a href="{{ route('services.show', $service) }}" class="btn btn-outline-anyxtech btn-sm flex-fill">
                            <i class="fas fa-eye me-1"></i>Voir
                        </a>
                        <a href="{{ route('services.edit', $service) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                            <i class="fas fa-pen me-1"></i>Modifier
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun service créé</h5>
                        <p class="text-muted">Commencez par ajouter votre premier service SaaS.</p>
                        <a href="{{ route('services.create') }}" class="btn btn-anyxtech">
                            <i class="fas fa-plus me-2"></i>Créer un service
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
