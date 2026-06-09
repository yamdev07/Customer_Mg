{{-- Sidebar latérale AnyxTech --}}
<aside class="app-sidebar" :class="{ 'is-open': sidebarOpen }">

    {{-- Logo --}}
    <div class="app-sidebar__brand">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
            <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="AnyxTech">
        </a>
        <button class="app-burger ms-auto" @click="sidebarOpen = false" aria-label="Fermer le menu">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="app-sidebar__nav" @click="sidebarOpen = false">
        <p class="app-sidebar__label">Pilotage</p>

        <a href="{{ route('clients.actifs') }}"
           class="side-link {{ request()->routeIs('clients.actifs') ? 'active' : '' }}">
            <i class="fas fa-wifi"></i> Clients actifs
        </a>
        <a href="{{ route('clients.index') }}"
           class="side-link {{ request()->routeIs('clients.index') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Tous les clients
        </a>

        <p class="app-sidebar__label mt-3">Paiements</p>

        <a href="{{ route('clients.payes') }}"
           class="side-link {{ request()->routeIs('clients.payes') ? 'active' : '' }}">
            <i class="fas fa-check-circle"></i> Payés
        </a>
        <a href="{{ route('clients.nonpayes') }}"
           class="side-link {{ request()->routeIs('clients.nonpayes') ? 'active' : '' }}">
            <i class="fas fa-exclamation-circle"></i> Non payés
        </a>

        <p class="app-sidebar__label mt-3">Abonnements</p>

        <a href="{{ route('clients.suspendus') }}"
           class="side-link {{ request()->routeIs('clients.suspendus') ? 'active' : '' }}">
            <i class="fas fa-pause-circle"></i> Suspendus
        </a>
        <a href="{{ route('clients.reabonnement') }}"
           class="side-link {{ request()->routeIs('clients.reabonnement') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> Réabonnement à venir
        </a>
        <a href="{{ route('clients.depasses') }}"
           class="side-link {{ request()->routeIs('clients.depasses') ? 'active' : '' }}">
            <i class="fas fa-triangle-exclamation"></i> Réabonnement dépassé
        </a>

        <p class="app-sidebar__label mt-3">Actions</p>
        <a href="{{ route('clients.create') }}"
           class="side-link {{ request()->routeIs('clients.create') ? 'active' : '' }}">
            <i class="fas fa-user-plus"></i> Nouveau client
        </a>
    </nav>

    {{-- Pied : utilisateur --}}
    <div class="app-sidebar__foot">
        <div class="side-user">
            <div class="side-user__avatar">
                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <div class="side-user__name text-truncate">{{ Auth::user()->name }}</div>
                <div class="side-user__mail text-truncate">{{ Auth::user()->email }}</div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-1">
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-anyxtech btn-sm flex-fill">
                <i class="fas fa-user-cog me-1"></i> Profil
            </a>
            <form method="POST" action="{{ route('logout') }}" class="flex-fill">
                @csrf
                <button type="submit" class="btn btn-sm w-100 text-danger-600"
                        style="border:1.5px solid rgba(220,38,38,.3);border-radius:11px;">
                    <i class="fas fa-sign-out-alt me-1"></i> Quitter
                </button>
            </form>
        </div>
    </div>
</aside>
