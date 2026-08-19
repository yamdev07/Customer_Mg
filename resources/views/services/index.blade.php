@extends('layouts.app')

@section('title', 'Services SaaS')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    <div class="page-hero mb-4 ax-fade-up">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-cogs me-2"></i>Services SaaS</h1>
                <p class="mb-0 opacity-75">Tous les logiciels AnyxTech</p>
            </div>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('services.create') }}" class="btn btn-light fw-semibold px-4 py-2">
                <i class="fas fa-plus me-2 text-anyxtech"></i>Nouveau service
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats globales --}}
    <div class="row g-3 g-md-4 mb-4">
        @php
            $totalClients = $services->sum('clients_count');
            $totalCA = 0;
        @endphp
        <div class="col-xl-3 col-lg-6">
            <div class="card card-hover h-100 ax-fade-up">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-ico bg-anyxtech-light text-anyxtech">
                            <i class="fas fa-cube"></i>
                        </div>
                        <div class="ms-3">
                            <div class="text-muted fw-semibold small">Total SaaS</div>
                            <div class="h3 fw-bold mb-0 text-anyxtech">{{ $services->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card card-hover h-100 ax-fade-up">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-ico bg-success-light text-success-600">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="ms-3">
                            <div class="text-muted fw-semibold small">Clients totaux</div>
                            <div class="h3 fw-bold mb-0 text-success-600">{{ $totalClients }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des SaaS --}}
    <div class="row g-3 g-md-4">
        @forelse($services as $service)
            <div class="col-xl-4 col-lg-6">
                <a href="{{ route('services.show', $service) }}" class="text-decoration-none">
                    <div class="card card-hover h-100 ax-fade-up" style="border-left: 4px solid {{ $service->couleur }}">
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
                                <p class="text-muted small mb-3">{{ Str::limit($service->description, 80) }}</p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge" style="background: {{ $service->couleur }}20; color: {{ $service->couleur }}">
                                    {{ $service->prix_defaut ? number_format($service->prix_defaut, 0, ',', ' ') . ' FCFA/mois' : 'Prix non défini' }}
                                </span>
                                <span class="badge {{ $service->actif ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $service->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun service créé</h5>
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
