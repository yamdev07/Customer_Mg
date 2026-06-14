@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Bandeau --}}
    <div class="page-hero mb-4 ax-fade-up">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index:1">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-bell me-2"></i>Journal d'activité</h1>
                <p class="mb-0 opacity-75">Historique des actions importantes de la plateforme</p>
            </div>
            <span class="badge bg-white text-anyxtech fs-6 px-3 py-2">
                <i class="fas fa-clock-rotate-left me-2"></i>{{ $activites->total() }} évènements
            </span>
        </div>
    </div>

    {{-- Filtres par type --}}
    <div class="card mb-4">
        <div class="card-body p-3 d-flex flex-wrap gap-2">
            <a href="{{ route('activites.index') }}"
               class="btn btn-sm {{ $actionFiltre ? 'btn-outline-anyxtech' : 'btn-anyxtech' }}">
                <i class="fas fa-layer-group me-1"></i>Tout
            </a>
            @php
                $libelles = [
                    'created' => 'Créations', 'updated' => 'Modifications', 'deleted' => 'Suppressions',
                    'paid' => 'Paiements', 'reconnected' => 'Reconnexions', 'disconnected' => 'Déconnexions',
                    'suspended' => 'Suspensions', 'reactivated' => 'Réactivations', 'notified' => 'Relances',
                ];
            @endphp
            @foreach($libelles as $key => $libelle)
                @php($m = $meta[$key] ?? ['icon' => 'fa-circle', 'color' => 'anyxtech'])
                <a href="{{ route('activites.index', ['action' => $key]) }}"
                   class="btn btn-sm {{ $actionFiltre === $key ? 'btn-anyxtech' : 'btn-outline-anyxtech' }}">
                    <i class="fas {{ $m['icon'] }} me-1"></i>{{ $libelle }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Timeline --}}
    <div class="card">
        <div class="card-body p-3 p-md-4">
            @forelse($activites as $a)
                @php($cls = $a->color === 'anyxtech' ? 'bg-anyxtech-light text-anyxtech' : 'bg-'.$a->color.'-light text-'.$a->color.'-600')
                <div class="activity-row">
                    <span class="activity-row__ico {{ $cls }}"><i class="fas {{ $a->icon }}"></i></span>
                    <div class="activity-row__body">
                        <div class="fw-semibold text-dark">
                            {{ $a->description }}
                            @if($a->client_id)
                                <a href="{{ route('clients.show', $a->client_id) }}"
                                   class="ms-1 small text-decoration-none text-anyxtech">
                                    <i class="fas fa-arrow-up-right-from-square"></i>
                                </a>
                            @endif
                        </div>
                        <small class="text-muted">
                            <i class="far fa-user me-1"></i>{{ $a->user->name }}
                            <span class="mx-1">·</span>
                            <i class="far fa-clock me-1"></i>{{ $a->created_at->translatedFormat('d M Y à H:i') }}
                            <span class="text-muted">({{ $a->created_at->diffForHumans() }})</span>
                        </small>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fs-1 mb-3 opacity-50"></i>
                    <p class="mb-0">Aucune activité enregistrée{{ $actionFiltre ? ' pour ce filtre' : '' }}.</p>
                </div>
            @endforelse
        </div>

        @if($activites->hasPages())
            <div class="card-footer bg-white border-0 p-3 d-flex justify-content-center">
                {{ $activites->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
